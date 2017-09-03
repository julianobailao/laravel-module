<?php

namespace Modules\ModuleControl\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ModuleControl\Entities\UserGroup;
use Modules\ModuleControl\Http\Requests\UserGroupRequest;

class UserGroupController extends Controller
{
    /**
     * Display a list of UserGroups.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = UserGroup::search($request->get('search'))
            ->paginate($request->get('perPage') ?: 15);

        return response()->json($data);
    }

    /**
     * Display a specified UserGroup data.
     *
     * @param  UserGroup $userGroup
     * @return Response
     */
    public function show(UserGroup $userGroup, $httpStatusCode = 200)
    {
        $userGroup->load('permissions');

        return response()->json($userGroup, $httpStatusCode);
    }

    /**
     * Create a new UserGroup.
     *
     * @param  UserGroupRequest $request
     * @return Response
     */
    public function store(UserGroupRequest $request)
    {
        return $this->save(new UserGroup(), $request);
    }

    /**
     * Update a specified UserGroup.
     *
     * @param  UserGroup        $userGroup
     * @param  UserGroupRequest $request
     * @return Response
     */
    public function update(UserGroup $userGroup, UserGroupRequest $request)
    {
        return $this->save($userGroup, $request);
    }

    /**
     * Persist the request data on the database.
     *
     * @param  UserGroup        $userGroup
     * @param  UserGroupRequest $request
     * @return Response
     */
    private function save(UserGroup $userGroup, UserGroupRequest $request)
    {
        $update = $userGroup->id != null;
        $userGroup->fill($request->only('title', 'description'))->save();
        $userGroup->permissions()->detach();
        $userGroup->permissions()->attach($request->json('permissions'));

        return $this->show($userGroup, $update === true ? 200 : 201);
    }

    /**
     * Delete a specified UserGroup
     *
     * @param  UserGroup $userGroup
     * @return Response
     */
    public function destroy(UserGroup $userGroup)
    {
        $userGroup->delete();

        return response(null, 204);
    }
}
