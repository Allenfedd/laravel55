<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\UserLoginRequest;
use App\Http\Requests\API\UserRegisterRequest;
use App\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class AuthController extends ApiController
{
    private $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = $this->guard()->attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not create token'], 500);
        }

        return $this->respondWithToken($token);
    }

    public function register(UserRegisterRequest $request)
    {
        $user = $this->user->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        return $this->respondWithToken($this->guard()->login($user));
    }

    public function me()
    {
        $user = $this->guard()->user();

        return response()->json(compact('user'));
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refreshToken()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }
}
