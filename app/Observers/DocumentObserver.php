<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Document;

class DocumentObserver
{
    /**
     * Handle the Badge "created" event.
     *
     * @param Document $document
     * @return void
     */
    public function created(Document $document)
    {
        Activity::create([
            'issuer_type' => 14, // 14 => Document
            'issuer_id' => $document->id,
            'short' => 'Document has been uploaded.',
            'details' => "Document $document->title has been uploaded on $document->created_at.",
            'attributes' => $document->toJson()
        ]);
    }

    /**
     * Handle the Badge "updated" event.
     *
     * @param Document $document
     * @return void
     */
    public function updated(Document $document)
    {
        Activity::create([
            'issuer_type' => 14, // 14 => Document
            'issuer_id' => $document->id,
            'short' => 'Document has been updated.',
            'details' => "Document $document->title has been updated on $document->updated_at.",
            'attributes' => $document->toJson()
        ]);
    }

    /**
     * Handle the Badge "deleted" event.
     *
     * @param Document $document
     * @return void
     */
    public function deleted(Document $document)
    {
        Activity::create([
            'issuer_type' => 14, // 14 => Document
            'issuer_id' => $document->id,
            'short' => 'Document has been soft-deleted.',
            'details' => "Document $document->title has been soft-deleted on $document->deleted_at.",
            'attributes' => $document->toJson()
        ]);
    }

    /**
     * Handle the Badge "restored" event.
     *
     * @param Document $document
     * @return void
     */
    public function restored(Document $document)
    {
        Activity::create([
            'issuer_type' => 14, // 14 => Document
            'issuer_id' => $document->id,
            'short' => 'Document has been restored.',
            'details' => "Document $document->title has been restored on $document->updated_at.",
            'attributes' => $document->toJson()
        ]);
    }

    /**
     * Handle the Badge "force deleted" event.
     *
     * @param Document $document
     * @return void
     */
    public function forceDeleted(Document $document)
    {
        Activity::create([
            'issuer_type' => 14, // 14 => Document
            'issuer_id' => $document->id,
            'short' => 'Document has been force-deleted.',
            'details' => "Document $document->title has been force-deleted on $document->updated_at.",
            'attributes' => $document->toJson()
        ]);
    }
}
