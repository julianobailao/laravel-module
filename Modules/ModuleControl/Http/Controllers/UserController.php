<?php

namespace Modules\ModuleControl\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ModuleControl\Entities\User;
use Modules\ModuleControl\Facades\FrontEnd;
use Modules\ModuleControl\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function frontEndTable()
    {
        $frontEnd = FrontEnd::create(
            'Usuários Cadastrados',
            'Gerencie os usários do sistema',
            function ($column) {
                $column->put('group.name', 'Grupo')->size('30%');
                $column->put('name', 'Nome')->size('40%');
                $column->put('email', 'E-mail')->size('40%');
            }
        );

        return response()->json($frontEnd->getData());
    }

    public function frontEndForm()
    {
        $frontEnd = FrontEnd::create(
            'Cadastro de Usuários',
            'Gerencie os usários do sistema',
            function ($field) {
                $field->put('user_group_id', 'Cargo')
                    ->component('select-field')
                    ->itemText('title')
                    ->itemValue('id')
                    ->uri('api/user-groups')
                    ->grid('xs12 md6');

                $field->put('name', 'Nome')->grid('xs12 md6');

                $field->put('email', 'E-mail')->grid('xs12 md6');

                $field->put('password', 'Senha')
                    ->component('password-field')
                    ->grid('xs12 md6');
            }
        );

        return response()->json($frontEnd->getData());
    }

    /**
     * Display a list of Users.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = User::search($request->get('search'))
            ->with('group')
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
