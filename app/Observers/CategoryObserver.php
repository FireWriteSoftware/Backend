<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Badge "created" event.
     *
     * @param Category $category
     * @return void
     */
    public function created(Category $category)
    {
        Activity::create([
            'issuer_type' => 6, // 6 => Category
            'issuer_id' => $category->id,
            'short' => 'Category has been created.',
            'details' => "Category $category->title has been created on $category->created_at.",
            'attributes' => $category->toJson()
        ]);
    }

    /**
     * Handle the Badge "updated" event.
     *
     * @param Category $category
     * @return void
     */
    public function updated(Category $category)
    {
        Activity::create([
            'issuer_type' => 6, // 6 => Category
            'issuer_id' => $category->id,
            'short' => 'Category has been updated.',
            'details' => "Category $category->title has been updated on $category->updated_at.",
            'attributes' => $category->toJson()
        ]);
    }

    /**
     * Handle the Badge "deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function deleted(Category $category)
    {
        Activity::create([
            'issuer_type' => 6, // 6 => Category
            'issuer_id' => $category->id,
            'short' => 'Category has been soft-deleted.',
            'details' => "Category $category->title has been soft-deleted on $category->deleted_at.",
            'attributes' => $category->toJson()
        ]);
    }

    /**
     * Handle the Badge "restored" event.
     *
     * @param Category $category
     * @return void
     */
    public function restored(Category $category)
    {
        Activity::create([
            'issuer_type' => 6, // 6 => Category
            'issuer_id' => $category->id,
            'short' => 'Category has been restored.',
            'details' => "Category $category->title has been restored on $category->updated_at.",
            'attributes' => $category->toJson()
        ]);
    }

    /**
     * Handle the Badge "force deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        Activity::create([
            'issuer_type' => 6, // 6 => Category
            'issuer_id' => $category->id,
            'short' => 'Category has been force-deleted.',
            'details' => "Category $category->title has been force-deleted on $category->updated_at.",
            'attributes' => $category->toJson()
        ]);
    }
}
