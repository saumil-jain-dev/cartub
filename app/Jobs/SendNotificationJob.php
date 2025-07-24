<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Traits\NotificationTrait;

class SendNotificationJob implements ShouldQueue
{
    use Queueable, NotificationTrait;

    /**
     * Create a new job instance.
     */
    protected $user_id;
    protected $notificationData;
    public function __construct($user_id,$notificationData)
    {
        //
        $this->user_id = $user_id;
        $this->notificationData = $notificationData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $user_id = $this->user_id;
        $notificationData = $this->notificationData;
        $this->save_notification($user_id,$notificationData);
    }
}
