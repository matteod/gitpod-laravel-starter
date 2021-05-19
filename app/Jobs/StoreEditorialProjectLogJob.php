<?php

namespace App\Jobs;

use App\Models\EditorialProjectLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class StoreEditorialProjectLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $editorial_project_id;
    protected $action;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $editorial_project_id, $action)
    {
        $this->user_id = $user_id;
        $this->editorial_project_id = $editorial_project_id;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $editorial_project_log = new EditorialProjectLog();
        $editorial_project_log->editorial_project_id = $this->editorial_project_id;
        $editorial_project_log->user_id = $this->user_id;
        $editorial_project_log->action = $this->action;
        $editorial_project_log->save();
    }
}
