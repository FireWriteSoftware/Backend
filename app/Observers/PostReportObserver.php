<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\PostReport;

class PostReportObserver
{
    /**
     * Handle the Post Report "created" event.
     *
     * @param PostReport $report
     * @return void
     */
    public function created(PostReport $report)
    {
        Activity::create([
            'issuer_type' => 12, // 12 => Report
            'issuer_id' => $report->id,
            'short' => 'Post Report has been created.',
            'details' => "Post Report $report->name has been created on $report->created_at.",
            'attributes' => $report->toJson()
        ]);
    }

    /**
     * Handle the Post Report "updated" event.
     *
     * @param PostReport $report
     * @return void
     */
    public function updated(PostReport $report)
    {
        Activity::create([
            'issuer_type' => 12, // 12 => Report
            'issuer_id' => $report->id,
            'short' => 'Post Report has been updated.',
            'details' => "Post Report $report->name has been updated on $report->updated_at.",
            'attributes' => $report->toJson()
        ]);
    }

    /**
     * Handle the Post Report "deleted" event.
     *
     * @param PostReport $report
     * @return void
     */
    public function deleted(PostReport $report)
    {
        Activity::create([
            'issuer_type' => 12, // 12 => Report
            'issuer_id' => $report->id,
            'short' => 'Post Report has been soft-deleted.',
            'details' => "Post Report $report->name has been soft-deleted on $report->deleted_at.",
            'attributes' => $report->toJson()
        ]);
    }

    /**
     * Handle the Post Report "restored" event.
     *
     * @param PostReport $report
     * @return void
     */
    public function restored(PostReport $report)
    {
        Activity::create([
            'issuer_type' => 12, // 12 => Report
            'issuer_id' => $report->id,
            'short' => 'Post Report has been restored.',
            'details' => "Post Report $report->name has been restored on $report->updated_at.",
            'attributes' => $report->toJson()
        ]);
    }

    /**
     * Handle the Post Report "force deleted" event.
     *
     * @param PostReport $report
     * @return void
     */
    public function forceDeleted(PostReport $report)
    {
        Activity::create([
            'issuer_type' => 12, // 12 => Report
            'issuer_id' => $report->id,
            'short' => 'Post Report has been force-deleted.',
            'details' => "Post Report $report->name has been force-deleted on $report->created_at.",
            'attributes' => $report->toJson()
        ]);
    }
}
