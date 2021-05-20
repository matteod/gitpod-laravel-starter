<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserShowRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param UserIndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(UserIndexRequest $request): AnonymousResourceCollection
    {
        $per_page = $request->query('per_page') ?: 15;

        $users = User::query();

        // Check if is filtered by text
        if ($text = $request->query('text')) {
            $users->where(function ($query) use ($text) {
                $query->where('name', 'like', '%' . $text . '%')
                    ->orWhere('email', 'like', '%' . $text . '%');
            });
        }

        // Filter by trashed
        if ($request->has('trashed')) {
            switch ($request->query('trashed')) {
                case 'with':
                    $users->withTrashed();
                    break;
                case 'only':
                    $users->onlyTrashed();
                    break;
                default:
                    $users->withTrashed();
            }
        }

        $users = $users->paginate((int)$per_page);

        // Include relationship
        if ($request->has('with')) {
            $users->load($request->query('with'));
        }

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRequest $request
     * @return UserResource
     * @throws Exception
     */
    public function store(UserStoreRequest $request): UserResource
    {
        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $user->roles()->attach(Role::find($request->role_id));

            DB::commit();
        } catch (Exception $exception) {

            DB::rollBack();
            throw $exception;
        }
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param UserShowRequest $request
     * @param User $user
     * @return UserResource
     */
    public function show(UserShowRequest $request, User $user): UserResource
    {
        // Include relationship
        if ($request->query('with')) {
            $user->load($request->query('with'));
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return UserResource
     * @throws Exception
     */
    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        DB::beginTransaction();

        try {

            $user->update($request->only(['name', 'email']));

            if ($request->has('role_id')) {
                $user->roles()->sync([$request->role_id]);
            }

            DB::commit();
        } catch (Exception $exception) {

            DB::rollBack();
            throw $exception;
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserDestroyRequest $request
     * @param User $user
     * @return Response
     */
    public function destroy(UserDestroyRequest $request, User $user): Response
    {
        $user->delete();

        return response(null, 204);
    }
}