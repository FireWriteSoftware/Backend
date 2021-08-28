<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClearUnusedResourcesTask
{
    protected $ignoredFiles = ['.gitignore'];

    public function __invoke() {
        $files = array_map(function ($e) {
            return preg_replace('/public\//', '', $e, 1);
        }, Storage::allFiles('public'));

        $files = array_filter($files, function ($e) {
            if (!in_array($e, $this->ignoredFiles)) return $e;
            return null;
        });

        $deletedFiles = 0;
        foreach ($files as $file) {
            $used = false;

            if ($this->isUsedInProfile($file)) $used = true;
            if ($this->isUsedInCategoryThumbnail($file)) $used = true;
            if ($this->isUsedInPostThumbnail($file)) $used = true;
            if ($this->isUsedInPostContent($file)) $used = true;

            if (!$used) {
                Storage::delete('public/' . $file);
                $deletedFiles++;
            }
        }

        error_log($deletedFiles . ' files has been deleted.');
    }

    private function isUsedInProfile($name) {
        $url = config('app.url') . '/storage/' . $name;
        return User::where('profile_picture', $url)->exists();
    }

    private function isUsedInCategoryThumbnail($name) {
        $url = config('app.url') . '/storage/' . $name;
        return Category::where('thumbnail', $url)->exists();
    }

    private function isUsedInPostThumbnail($name) {
        $url = config('app.url') . '/storage/' . $name;
        return Post::where('thumbnail', $url)->exists();
    }

    private function isUsedInPostContent($name) {
        $url = config('app.url') . '/storage/' . $name;
        return Post::where('content', 'LIKE', '%' . $url . '%')->exists();
    }
}
