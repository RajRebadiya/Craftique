<?php

namespace App\Console\Commands\AbandonedCart;

use App\Actions\AbandonedCart\ProcessAction;
use Illuminate\Console\Command;

class HandleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'abandoned-cart:handle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start process abandoned cart for email notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Pass the command instance to the process action
        $processAction = new ProcessAction($this);
        $processAction->handle();

        return Command::SUCCESS;
    }
}
