<?php

namespace App\Http\Controllers\Affiliate;

use App\Affiliate;
use App\Events\Seller\SellerCreated;
use App\Http\Controllers\Controller;
use App\Invitation;
use App\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Str;

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


        $affiliate = Affiliate::where('email', $credentials['email'])->first();
        if (! $token = auth()->login($affiliate)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register() : JsonResponse
    {
        $request = request(['token', 'first_name', 'last_name', 'email', 'password']);

        // validate seller invitation token from the request

        if(Invitation::validateToken($request['token'])) {

            // create the new Seller

        $code = \Illuminate\Support\Str::random(8);

            $affiliate = Affiliate::create([
                'code' => $code,
                'name' => $request['first_name'] . ' ' . $request['last_name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password'])
            ]);


            // authenticate seller
            $token = auth('affiliate')->login($affiliate);

            return $this->respondWithToken($token);

        }

        else {
            return response()->json([
                'token' => false,
                'message' => 'Invalid invitation token'
            ], 401);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function affiliate() : JsonResponse
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
        auth('affiliate')->logout();

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
            'affiliate' => auth()->user()
        ]);
    }
}
