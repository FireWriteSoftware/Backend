<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\PostReport;
use App\Models\Announcement;
use App\Models\Badge;
use App\Models\Ban;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostVote;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use App\Observers\AnnouncementObserver;
use App\Observers\BadgeObserver;
use App\Observers\BanObserver;
use App\Observers\CategoryObserver;
use App\Observers\NotificationObserver;
use App\Observers\PermissionObserver;
use App\Observers\PostCommentObserver;
use App\Observers\PostObserver;
use App\Observers\PostReportObserver;
use App\Observers\PostVoteObserver;
use App\Observers\RoleObserver;
use App\Observers\TagObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();

        /**
         * Observers for activities
         */
        User::observe(UserObserver::class);
        Role::observe(RoleObserver::class);
        Permission::observe(PermissionObserver::class);
        Ban::observe(BanObserver::class);
        Badge::observe(BadgeObserver::class);

        Category::observe(CategoryObserver::class);
        Tag::observe(TagObserver::class);
        Post::observe(PostObserver::class);
        PostVote::observe(PostVoteObserver::class);
        PostComment::observe(PostCommentObserver::class);
        PostReport::observe(PostReportObserver::class);

        Announcement::observe(AnnouncementObserver::class);
        Notification::observe(NotificationObserver::class);

        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
