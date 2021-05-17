<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\EditorialProject;
use App\Models\EditorialProjectLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EditorialProjectStoreRequest;
use App\Http\Requests\EditorialProjectShowRequest;
use App\Http\Resources\EditorialProjectResource;

class EditorialProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EditorialProjectStoreRequest $request)
    {
        //
        DB::beginTransaction();
        try{
          $editorial_project = new EditorialProject();
          $editorial_project->title = $request->title;
          $editorial_project->publication_date = $request->publication_date;
          $editorial_project->price = $request->price;
          $editorial_project->author_id = $request->author_id;
          $editorial_project->sector_id = $request->sector_id;
          $editorial_project->cost = $request->cost;
          $editorial_project->pages = $request->pages;
          $editorial_project->save();

          $editorial_project_log = new EditorialProjectLog();
          $editorial_project_log->user_id = $editorial_project->author_id;
          $editorial_project_log->action = EditorialProjectLog::ACTION_CREATE;
          $editorial_project_log->editorial_project_id = $editorial_project->id;
          $editorial_project_log->created_at = $editorial_project->created_at;
          $editorial_project_log->save();
          DB::commit();
        }
        catch ( \Exception $e){
          DB::rollback();
          throw $e;
        }
        return new EditorialProjectResource($editorial_project);

        
    }

    /**
     * Display the specified resource.
     *
     * @param  $EditorialProjectShowRequest
     * @param  $EditorialProject
     * @return \Illuminate\Http\Response
     */
    public function show(EditorialProjectShowRequest $request, EditorialProject $editorial_project)
    {
        //
        return new EditorialProjectResource($editorial_project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
