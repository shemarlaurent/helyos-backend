<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Pryo;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PryoController extends Controller
{
    /** create a pryo entry for the current month
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        Pryo::create([
            'product_id' => $request['product'],
            'images' => json_encode($request['images'])
        ]);

        return response()->json(true);
    }

    /** return all pro records
     * @return JsonResponse
     */
    public function details()
    {
        return \response()->json(
            Pryo::with('product')
            ->latest('created_at')
            ->get()
        );
    }

    /** get the active pryo data for the month
     * @return JsonResponse
     */
    public function active()
    {
        return response()->json(
            Pryo::with(['product', 'winner'])->latest('created_at')
                ->where('status', false)
            ->first()
        );
    }
}
