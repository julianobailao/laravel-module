<?php

namespace Modules\ModuleControl\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ModuleControl\Entities\User;
use Modules\ModuleControl\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display a list of Users.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = User::search($request->get('search'))
            ->paginate($request->get('perPage') ?: 15);

        return response()->json($data);
    }

    /**
     * Display a specified User data.
     *
     * @param  User $user
     * @return Response
     */
    public function show(User $user, $httpStatusCode = 200)
    {
        return response()->json($user, $httpStatusCode);
    }

    /**
     * Create a new User.
     *
     * @param  UserRequest $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
        return $this->save(new User(), $request);
    }

    /**
     * Update a specified User.
     *
     * @param  User        $user
     * @param  UserRequest $request
     * @return Response
     */
    public function update(User $user, UserRequest $request)
    {
        return $this->save($user, $request);
    }

    /**
     * Persist the request data on the database.
     *
     * @param  User        $user
     * @param  UserRequest $request
     * @return Response
     */
    private function save(User $user, UserRequest $request)
    {
        $update = $user->id != null;
        $user->fill($request->all())->save();

        return $this->show($user, $update === true ? 200 : 201);
    }

    /**
     * Delete a specified User
     *
     * @param  User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response(null, 204);
    }
}
