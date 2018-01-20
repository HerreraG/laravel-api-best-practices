<?php

namespace App\Http\Controllers\Api\Auth;

use App\Entities\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use App\Core\BaseController;
use App\Contracts\Services\Logic\IUserAppService;

class AuthController extends BaseController
{


    public function __construct(IUserAppService $userAppService)
    {        
        parent::__construct($userAppService);
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {    
        $credentials = $request->only('email', 'password');

        try {        
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->authResponse(401, true, 'invalid_credentials');
            }
        } catch (JWTException $e) {            
            return $this->authResponse(500, 'could_not_create_token');
        }

        $user = JWTAuth::authenticate($token);

        if (!$user->active) {
            return $this->authResponse(401, true, 'invalid_credentials');
        }
        
        return $this->authResponse(200, false, $token);
    }

    /**
     * API Change password to user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $user = currentUser();

        // $this->userRepository->validateData($request->all(), 'Revise los datos', [
        //     'current_password' => 'required',
        //     'password' => 'required|min:6|confirmed',
        //     'password_confirmation' => 'min:6',
        // ]);

        $credentials = ['email' => $user->email, 'password' => $request->get('current_password')];

        if (! JWTAuth::attempt($credentials)) {
            return $this->errorResponse('La contraseña ingresada es incorrecta.', 401, 'invalid_credentials');
        }

        $user->fill($request->all());

        $change = $user->save();

        return $this->successResponse('La contraseña ha sido modificada.', compact('user', 'change'));
    }

    /**
     * Sign Up
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request)
    {
        $user = User::where(['email' => $request["email"]])->exists();

        if($user) {
            return $this->authResponse(400, "El usuario con email {$request->email} ya existe");
        }

        $user = new User();

        $user->create($request->input());

        return $this->jsonResponse(['message' => 'Usuario registrado correctamente']);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to re-login to get a new token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $token = JWTAuth::getToken();

        // checks if the token exist and is a valid one
        if($token /*&& JWTAuth::check()*/) {
            JWTAuth::invalidate($token);
        }

        // maybe redirect?
        return $this->jsonResponse(['error' => false, 'redirect' => true]);
    }

    /**
     * Get the current auth user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }
}
