<?php

namespace App\Jobs;

use App\Models\Captain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MakeCaptainAvailableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $captainId;

    public function __construct($captainId)
    {
        $this->captainId = $captainId;
    }

    public function handle()
    {
        $captain = Captain::find($this->captainId);

        if ($captain) {
            // Set captain to available after the order duration is complete
            $captain->status = 'available';
            $captain->save();

            // Check for unassigned orders again
            dispatch(new AssignCaptainToOrder());
        }
    }
}
