<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPayrollMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $data2;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $data2)
    {
        $this->data = $data;
        $this->data2 = $data2;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.sendPayrollMail')
            ->with([
                'data', $this->data,
                'data2', $this->data2
            ]);
    }
}
