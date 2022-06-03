<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logger('TestQueue handle function hit for app ' . config('app.name'));
        if(file_exists(base_path('email'))) {
            logger('Sending an email to the admin for app ' . config('app.name'));
            User::whereEmail(config('app.admin_email'))->first()->sendEmailVerificationNotification();
        }
    }
}
