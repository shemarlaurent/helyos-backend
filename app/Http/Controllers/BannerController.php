<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function addBanners(Request $request)
    {
        foreach ($request->all() as $image) {
            DB::table('banners')->insert([
                'image' => $image
            ]);
        }
    }

    /** return banner images for the homepage slider
     * @return JsonResponse
     */
    public function banners()
    {
        $banners = DB::table('banners')->latest('created_at')->limit(5)->get();

        return response()->json($banners);
    }
}
