<?php

namespace App\Observers;

use App\Models\Uji;

class UjiObserver
{
    /**
     * Handle the Uji "created" event.
     */
    public function created(Uji $uji): void
    {
        //
    }

    /**
     * Handle the Uji "updated" event.
     */
    public function updated(Uji $uji): void
    {
        if ($uji->isDirty('cf_total')) {
            $uji->hitungCFTotal();
        }
    }

    /**
     * Handle the Uji "deleted" event.
     */
    public function deleted(Uji $uji): void
    {
        //
    }

    /**
     * Handle the Uji "restored" event.
     */
    public function restored(Uji $uji): void
    {
        //
    }

    /**
     * Handle the Uji "force deleted" event.
     */
    public function forceDeleted(Uji $uji): void
    {
        //
    }
}
