<?php

namespace AppConsoleCommands;

use App\Jobs\StoreEditorialProjectLogJob;
use App\Models\EditorialProjectLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class StoreEditorialProjectLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:store-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store editorial project log';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        StoreEditorialProjectLogJob::dispatchAfterResponse(1, 1, EditorialProjectLog::ACTION_CREATE);
        return 0;
    }
}
