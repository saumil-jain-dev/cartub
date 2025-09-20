<?php

namespace App\Jobs\Customer;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;


class SendMailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $data;
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $event = $this->data;
        if (!isset($event['subject'])) {
            $event['subject'] = '';
        }
        Mail::send('email.' . $event['_blade'], $event, function ($message) use ($event) {
            $message->to($event['to_email']);
            if (isset($event['cc']) && $event['cc'] != '') {
                $message->cc($event['cc']);
            }
            if (isset($event['bcc']) && $event['bcc'] != '') {
                $message->bcc($event['bcc']);
            }
            $message->subject($event['subject']);
            // 👇 Attach file if exists
            $message->attachData(
                $event['attachment']['content'],
                $event['attachment']['name'],
                ['mime' => $event['attachment']['mime']]
            );
        });
    }
}
