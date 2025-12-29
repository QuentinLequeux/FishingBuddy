<?php

namespace App\Observers;

use App\Models\ActivityView;

class ActivityViewObserver
{
    /**
     * Handle the ActivityView "created" event.
     */
    public function created(ActivityView $view): void
    {
        $view->activity->increment('views');
    }
}
