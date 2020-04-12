<?php

namespace App\Observers;

use App\Sos;

class SosObserver
{
    /**
     * Handle the sos "created" event.
     *
     * @param  \App\Sos  $sos
     * @return void
     */
    public function created(Sos $sos)
    {
        //
    }

    /**
     * Handle the sos "updated" event.
     *
     * @param  \App\Sos  $sos
     * @return void
     */
    public function updated(Sos $sos)
    {
        //
    }

    /**
     * Handle the sos "deleted" event.
     *
     * @param  \App\Sos  $sos
     * @return void
     */
    public function deleted(Sos $sos)
    {
        //
    }

    /**
     * Handle the sos "restored" event.
     *
     * @param  \App\Sos  $sos
     * @return void
     */
    public function restored(Sos $sos)
    {
        //
    }

    /**
     * Handle the sos "force deleted" event.
     *
     * @param  \App\Sos  $sos
     * @return void
     */
    public function forceDeleted(Sos $sos)
    {
        //
    }
}
