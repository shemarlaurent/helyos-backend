<?php

namespace App\Http\Controllers\Abyss;

use App\AbyssForum as Forum;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    /** return all active forums
     * @return JsonResponse
     */
    public function forums()
    {
        $forums = Forum::with('product')
            ->where('status', 1)
            ->latest('created_at')
            ->get();

        return response()->json($forums);
    }


    public function forum(Forum $forum)
    {
        return response()->json($forum->load(['winner', 'product.variations']));
    }


    public function claimPrice(Request $request, Forum $forum)
    {
        // create an order for the piece selected

        $product = Product::where('id', $request->input('product_id'))->first();

        $user = auth('abyss_user')->user();

        if (!$user->claimedPrize($forum->winner)) {
            auth('abyss_user')->user()->orders()->create([
                'store_id' => $product->store->id,
                'code' => Str::random(10),
                'product_variation_id' => $request->input('product_variation'),
                'amount' => 0,
                'type' => 'normal',
                'status' => true,
            ]);

            $product->sell($request->input('size'));

            $product->store->seller->credit($request->input('amount'));

            $forum->close();
            return response()->json('done');
        }

        else return response()->json('claimed');
    }

}
