<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditorialProjectDestroyRequest;
use App\Http\Requests\EditorialProjectIndexRequest;
use App\Http\Requests\EditorialProjectShowRequest;
use App\Http\Requests\EditorialProjectStoreRequest;
use App\Http\Requests\EditorialProjectUpdateRequest;
use App\Http\Resources\EditorialProjectResource;
use App\Jobs\Store\EditorialProjectLogJob;
use App\Models\EditorialProject;
use App\Models\EditorialProjectLog;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditorialProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param EditorialProjectIndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(EditorialProjectIndexRequest $request): AnonymousResourceCollection
    {
        $per_page = $request->query('per_page') ?: 15;

        $editorial_projects = EditorialProject::query();

        // Filter by text
        if ($text = $request->query('text')) {
            $editorial_projects->where(function ($query) use ($text) {
                $query->where('title', 'like', '%' . $text . '%');
            });
        }

        // Filter by trashed
        if ($request->has('trashed')) {
            switch ($request->query('trashed')) {
                case 'with':
                    $editorial_projects->withTrashed();
                    break;
                case 'only':
                    $editorial_projects->onlyTrashed();
                    break;
                default:
                    $editorial_projects->withTrashed();
            }
        }

        $editorial_projects = $editorial_projects->paginate((int)$per_page);

        // Include relationship
        if ($request->has('with')) {
            $editorial_projects->load($request->query('with'));
        }

        return EditorialProjectResource::collection($editorial_projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EditorialProjectStoreRequest $request
     * @return EditorialProjectResource
     * @throws Exception
     */
    public function store(EditorialProjectStoreRequest $request): EditorialProjectResource
    {

        DB::beginTransaction();

        try {
            $editorial_project = new EditorialProject();
            $editorial_project->title = $request->title;
            $editorial_project->publication_date = $request->publication_date;
            $editorial_project->pages = $request->pages;
            $editorial_project->price = $request->price;
            $editorial_project->cost = $request->cost;
            $editorial_project->sector_id = $request->sector_id;
            $editorial_project->author_id = $request->has('author_id') ? $request->author_id : Auth::id();
            $editorial_project->save();

            DB::commit();
        } catch (Exception $exception) {

            DB::rollBack();
            throw $exception;
        }

        return new EditorialProjectResource($editorial_project);
    }

    /**
     * Display the specified resource.
     *
     * @param EditorialProjectShowRequest $request
     * @param EditorialProject $editorial_project
     * @return EditorialProjectResource
     */
    public function show(EditorialProjectShowRequest $request, EditorialProject $editorial_project): EditorialProjectResource
    {
        // Include relationship
        if ($request->query('with')) {
            $editorial_project->load($request->query('with'));
        }

        return new EditorialProjectResource($editorial_project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditorialProjectUpdateRequest $request
     * @param EditorialProject $editorial_project
     * @return EditorialProjectResource
     * @throws Exception
     */
    public function update(EditorialProjectUpdateRequest $request, EditorialProject $editorial_project): EditorialProjectResource
    {

        DB::beginTransaction();

        try {

            $editorial_project->update($request->only(['title', 'sector_id', 'is_approved_by_ceo']));

            //StoreEditorialProjectLogJob::dispatchAfterResponse(Auth::id(), $editorial_project->id, EditorialProjectLog::ACTION_UPDATE);

            DB::commit();
        } catch (Exception $exception) {

            DB::rollBack();
            throw $exception;
        }

        return new EditorialProjectResource($editorial_project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param EditorialProjectDestroyRequest $request
     * @param EditorialProject $editorial_project
     * @return Response
     */
    public function destroy(EditorialProjectDestroyRequest $request, EditorialProject $editorial_project): Response
    {
        $editorial_project->delete();

        //StoreEditorialProjectLogJob::dispatchAfterResponse(Auth::id(), $editorial_project->id, EditorialProjectLog::ACTION_DESTROY);

        return response(null, 204);
    }
}
