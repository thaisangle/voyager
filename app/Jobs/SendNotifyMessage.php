<?php

namespace App\Jobs;

use App\Traits\Notify;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendNotifyMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filter;

    protected $content;

    protected $data;

    /**
     * SendNotifyCreateOrder constructor.
     * @param $userId
     * @param $orderId
     */
    public function __construct($filter, $content, $data = false)
    {
        $this->filter = $filter;
        $this->content = $content;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notify = new Notify();
        $notify->sendNotifyToUser($this->filter,$this->content, $this->data);
    }
}
