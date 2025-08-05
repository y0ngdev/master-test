<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailConn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Send a test email to verify mail configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $to = $this->argument('email');
        try {
            Mail::raw('This is a test email to confirm your mail configuration is working.', function ($message) use ($to) {
                $message->to($to)
                    ->subject('Laravel Mail Config Test');
            });

            $this->info(" Test email sent successfully to $to");
        } catch (Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
        }
    }
}
