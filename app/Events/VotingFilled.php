<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Voting;

class VotingFilled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Voting $voting */
    private $voting;

    /**
     * Create a new event instance.
     *
     * @param Voting $voting
     * @return void
     */
    public function __construct(Voting $voting)
    {
        $this->voting = $voting;
    }

    /**
     * Get the voting
     * 
     * @return Voting
     */
    public function getVoting(): Voting
    {
        return $this->voting;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
