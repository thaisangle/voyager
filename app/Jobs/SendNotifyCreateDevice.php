<?php

namespace App\Jobs;

use App\Traits\Notify;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendNotifyCreateDevice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;

    protected $identifier;

    protected $device_type;

    /**
     * SendNotifyCreateOrder constructor.
     * @param $userId
     * @param $orderId
     */
    public function __construct($user_id, $identifier,$device_type)
    {
        $this->user_id = $user_id;
        $this->identifier = $identifier;
        $this->device_type = $device_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notify = new Notify();
        $notify->pushInfo($this->user_id,$this->identifier, $this->device_type);
    }
}
