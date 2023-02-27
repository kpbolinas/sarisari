<?php

namespace App\Jobs;

use App\Mail\ForgotPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The data to use.
     *
     * @var array
     */
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $to = (object) [
            'email' => $this->data['email'],
            'name' => $this->data['name'],
        ];
        $mailData = [
            'email' => $this->data['email'],
            'username' => $this->data['username'],
            'temporary_password' => $this->data['temporary_password'],
        ];
        Mail::to($to)->send(new ForgotPasswordMail($mailData));
    }
}
