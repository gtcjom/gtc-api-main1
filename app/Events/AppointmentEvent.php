<?php

namespace App\Events;

use App\Models\AppointmentData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public int $id;
    public int $rhu_id;
    public  int $referred_to;
    public function __construct(int $id,  int $rhu_id, int $referred_to)
    {
        //
        $this->id = $id;
        $this->rhu_id = $rhu_id;
        $this->referred_to = $referred_to;
    }
    public function broadcastAs()
    {
        return 'appointment';
    }
    public function broadcastWith()
    {
        return AppointmentData::find($this->id)->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

        return [
            new PrivateChannel('clinic.'. $this->rhu_id),
            new PrivateChannel('user.'. $this->referred_to)
        ];
    }
}
