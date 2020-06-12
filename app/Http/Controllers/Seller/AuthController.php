<?php

namespace App\Http\Controllers\Seller;

use App\Events\Seller\SellerCreated;
use App\Http\Controllers\Controller;
use App\Invitation;
use App\Seller;
use Illuminate\Http\JsonResponse;

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


        $seller = Seller::where('email', $credentials['email'])->first();
        if (! $token = auth()->login($seller)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register() : JsonResponse
    {
        $request = request(['token', 'name', 'email', 'password', 'store']);

//        dd($request);
        // validate seller invitation token from the request

        if(Invitation::validateToken($request['token'])) {

            // create the new Seller
            $seller = Seller::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password'])
            ]);

            $seller->store()->create(['name' => $request['store'], 'description' => 'Helyos Store']);

            event(new SellerCreated($seller));

            // authenticate seller
            $token = auth('seller')->login($seller);

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
    public function seller() : JsonResponse
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
        $seller = Seller::with('store')->where('id', auth()->id())->first();
        return response()->json([
            'access_token' => $token,
            'seller' => $seller
        ]);
    }
}
