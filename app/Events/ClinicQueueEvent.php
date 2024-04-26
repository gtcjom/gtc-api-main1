<?php

namespace App\Events;

use App\Http\Resources\PatientQueueResource;
use App\Models\PatientQueue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClinicQueueEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $clinicId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $clinicId)
    {
        //
        $this->clinicId = $clinicId;
    }


    public function broadcastAs()
    {
        return 'clinic.queue';
    }

    public function broadcastWith()
    {


        return [
            'msg' => 'ok'
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new PrivateChannel('clinic.'.$this->clinicId);
    }
}
