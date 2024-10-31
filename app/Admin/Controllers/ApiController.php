<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use DB;
use App\Models;
use App\Handlers\ImageUploadHandler;
use Auth;
use Log;
use App\Models\BikeModel;
use App\Models\Products;

class ApiController extends Controller
{

    public function uploadImg(Request $request, ImageUploadHandler $uploader)
    {
        $data = [
            'success'   => false,
            'msg'       => 'Upload Fail!',
            'file_path' => ''
        ];
        
        if ($file = $request->upload_file) {

            $result = $uploader->save($request->upload_file, 'simditor', \Auth::guard('admin')->user()->id, 600);
        }
        return $result['path'];
    }

    public function bike_model(Request $request)
    {
        $provinceId = $request->get('q');

        return BikeModel::where('category_id', $provinceId)->get(['id', DB::raw('bike_model as text')]);
    }

    public function product(Request $request)
    {
        $provinceId = $request->get('q');

        return Products::where('model', $provinceId)->get(['id', DB::raw('CONCAT(product_name, " - ", COALESCE(color,"")) as text')]);
    }

    public function bikes(Request $request)
    {
        $provinceId = $request->get('q');

        return Products::where([['model', $provinceId], ['type', 1]])->get(['id', DB::raw('CONCAT(product_name, " - ", COALESCE(color,""), " - ", locale) as text')]);
    }

    public function package_products(Request $request)
    {
        $locale = $request->get('q');

        return Products::where([['locale', $locale], ['type', 1]])->get(['id', DB::raw('CONCAT(product_name, " - ", COALESCE(color,"")) as text')]);
    }
}
