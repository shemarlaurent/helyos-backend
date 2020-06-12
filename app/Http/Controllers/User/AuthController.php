<?php

namespace App\Http\Controllers\User;

use App\Affiliate;
use App\Events\Seller\SellerCreated;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ReferralController;
use App\Invitation;
use App\Seller;
use App\SubscriptionPlan;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request): JsonResponse
    {


        // create the nuew user
        $user = User::create([
            'email' => $request->input('user')['email'],
            'password' => bcrypt($request->input('user')['password']),
            'name' => $request->input('user')['first_name'] . ' ' . $request->input('user')['last_name'],
            'code' => Str::random(8),
            'zip_code' => $request->input('user')['zip'],
        ]);


        // validate seller invitation token from the request
        if ($request->input('user')['token'] !== null) {
            $referrer = (new ReferralController)->getReferrer($request->input('user')['token']);
            // create the new Seller
            $referrer->referrals()->create([
                'user_id' => $user->id,
            ]);

        }

            if ($request->input('user')['plan'] > 0) {
                (new CardController)->addCard($user, $request->input('card'));
                (new SubscriptionPlanController)->createPlan($user, $request->input('user')['plan']);
            }




        // authenticate seller
        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    /** Update current user information
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        // find the current user

        $user = User::find($request->input('id'));

       $user->name = $request['name'];
        $user->phone = $request->input('phone');
        $user->country = $request->input('country');
        $user->city = $request->input('city');
        $user->postal_code = $request->input('postal_code');
        $user->address1 = $request->input('address1');

        if ($request->input('old_password')) {
            $user->password = bcrypt('new_password');
        }


        $user->save();


        return response()->json($user->load(['subscription', 'card']));
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        $user = User::with(['subscription', 'card'])->where('id', auth()->id())->first();
        return response()->json([
            'access_token' => $token,
            'user' => $user
        ]);
    }
}
