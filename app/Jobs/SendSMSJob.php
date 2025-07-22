<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendSMSJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $message;
    protected $phone;
    public function __construct($phone,$message)
    {
        //
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        sendSMS($this->phone, $this->message);
    }
}
