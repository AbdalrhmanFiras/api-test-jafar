<?php

namespace App\Observers;

use App\Models\post;

class PostObserver
{
    /**
     * Handle the post "creating" event.
     */
    public function creating(post $post): void
    {
        // $post->dec = 'is from creating';
    }
    /**
     * Handle the post "created" event.
     */
    // public function created(post $post): void
    // {
    //     $post->dec = 'is from created';
    //     $post->save();
    // }

    /**
     * Handle the post "updated" event.
     */
    public function updated(post $post): void
    {
        //
    }

    /**
     * Handle the post "deleted" event.
     */
    public function deleted(post $post): void
    {
        //
    }

    /**
     * Handle the post "restored" event.
     */
    public function restored(post $post): void
    {
        //
    }

    /**
     * Handle the post "force deleted" event.
     */
    public function forceDeleted(post $post): void
    {
        //
    }
}
