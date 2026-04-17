<?php

namespace App\Console\Commands\AbandonedCart;

use App\Actions\AbandonedCart\SendMailAction;
use Illuminate\Console\Command;

class SendMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'abandoned-cart:send-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send abandoned cart email notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sendMailAction = new SendMailAction($this);
        $sendMailAction->handle();

        $this->info('Abandoned cart email notifications processing started.');

        return Command::SUCCESS;
    }
}
