<?php

namespace App\Http\Controllers\Abyss;

use App\AbyssUser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\PaymentController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login() : JsonResponse
    {
        $credentials = request(['email', 'password']);


        $abyssUser = AbyssUser::where('email', $credentials['email'])->first();
        if (! $token = auth()->login($abyssUser)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request) : JsonResponse
    {
        if ((new PaymentController)->payForForum($request)) {
            // create the new Seller
            $abyssUser = AbyssUser::create([
                'name' => $request->input('first_name'). ' ' . $request->input('last_name'),
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'password' => bcrypt($request->input('password')),
                'zip_code' => $request->input('zip_code'),
                'card' => $request->input('card'),
                'card_expire' => $request->input('card_expire'),
                'cvv2' => $request->input('cvv2'),
                'last_four' => implode('', array_slice(str_split($request->input('card')), -4, 4))
            ]);

            $abyssUser->joinForum(intval($request['forum_id']));

            // authenticate abyssUser
            $token = auth()->login($abyssUser);

            return $this->respondWithToken($token);
        }

        else {
            return response()->json('payment method declined', 403);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function abyssUser() : JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout() : JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh() : JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token) : JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'abyssUser' => auth()->user()
        ]);
    }
}

