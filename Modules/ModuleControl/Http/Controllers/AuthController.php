<?php

namespace Modules\ModuleControl\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ModuleControl\Entities\User;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Authenticate the posted user.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        if (! $token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        return response()->json(compact('token'));
    }
}
