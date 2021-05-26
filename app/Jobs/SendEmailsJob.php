<?php

namespace App\Jobs;

use App\Mail\EditorialProjectErrorsMail;
use App\Models\EditorialProject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailsJob implements ShouldQueue
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

        // Logica calcoli
        $editorial_projects = EditorialProject::haveErrors()->get();

        $data = [];
        foreach ($editorial_projects as $editorial_project){
            array_push($data,[
                'title' => $editorial_project->title,
                'errors_count' => $editorial_project->countErrors()
            ]);
        }

        Mail::to('info@pe.com')->send(new EditorialProjectErrorsMail($data));
    }
}