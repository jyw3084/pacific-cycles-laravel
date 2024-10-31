<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Category;
use App\Models\BikeModel;
use App\Models\Products;
use App\Models\Store;
use App\Models\UserBike;
use App\Http\Controllers\APIController;
use Firebase\JWT\JWT;

class SyncController extends Controller
{
    public function area(Request $request)
    {
        Area::truncate();
        $response = APIController::getBuyArea();
        foreach(json_decode($response)->Entries as $k => $v)
        {
            $area = new Area;
            $area->AreaNo = $v->AreaNo;
            $area->AreaName = $v->AreaName;
            $area->AreaCnName = $v->AreaCnName;
            $area->AreaEngName = $v->AreaEngName;
            $area->AreaJpName = $v->AreaJpName;
            $area->AreaKrName = $v->AreaKrName;
            $area->created_at = date('Y-m-d H:i:s');
            $area->save();
        }
    }

    public function category(Request $request)
    {
        Category::truncate();
        $response = APIController::getBicycleType();
        foreach(json_decode($response)->Entries as $k => $v)
        {
            $category = new Category;
            $category->name = $v->BicycleTypeName;
            $category->created_at = date('Y-m-d H:i:s');
            $category->save();
        }
    }

    public function model(Request $request)
    {
        BikeModel::truncate();
        $categories = Category::all();
        foreach($categories as $category)
        {
            $response = APIController::getProductModel($category->id);
            foreach(json_decode($response)->Entries as $k => $v)
            {
                $model = new BikeModel;
                $model->category_id = $category->id;
                $model->bike_model = $v->ProductModelName;
                $model->code = $v->ProductItemCode;
                $model->created_at = date('Y-m-d H:i:s');
                $model->save();
            }
        }
    }

    public function prodcutByModel(Request $request)
    {
        Products::truncate();
        $models = BikeModel::all();
        foreach($models as $model)
        {
            $response = APIController::getProductModel($model->id);
            foreach(json_decode($response)->Entries as $k => $v)
            {
                $bike = new Products;
                $bike->category_id = $model->category->id;
                $bike->model = $model->id;
                $bike->product_name = $v->ProductModelName;
                $bike->product_code = $v->ProductItemCode;
                $bike->created_at = date('Y-m-d H:i:s');
                $bike->save();
            }
        }
    }

    public function store(Request $request)
    {
        Store::truncate();
        $areas = Area::all();
        foreach($areas as $area)
        {
            $response = APIController::GetCompanyLocationList($area->AreaNo);
            foreach(json_decode($response)->Entries as $k => $v)
            {
                $store = new Store;
                $store->NickName = $v->NickName;
                $store->CompanyNo = $v->CompanyNo;
                $store->name = $v->CompanyName;
                $store->country = $area->AreaEngName;
                $store->phone = $v->Tel;
                $store->address = $v->Address;
                $store->business_hour = $v->OpeningHours;
                $store->website = $v->WebSiteUrl;
                $store->Long = $v->Long;
                $store->Lat = $v->Lat;
                $store->BicycleTypeID = json_encode($v->BicycleTypeID);
                $store->FeaturesID = json_encode($v->FeaturesID);
                $store->created_at = date('Y-m-d H:i:s');
                $store->save();
            }
        }
    }

    public function MyBikeGetList(Request $request)
    {
        $user = auth()->user();
        UserBike::where('user_id', $user->id)->delete();
        $response = APIController::MyBikeGetList();
        if(json_decode($response))
        {
            foreach(json_decode($response)->Entries as $k => $v)
            {
                $bike = new UserBike;
                $bike->user_id = $user->id;
                $bike->MemberId = $user->MemberID;
                $bike->BikeImage = $v->BikeImage;
                $bike->IsTransfer = $v->IsTransfer;
                $bike->IsCanBuyWarranty = $v->IsCanBuyWarranty;
                $bike->ProductNo = $v->ProductNo;
                $bike->BBNo = $v->BBNo;
                $bike->BuyAreaNo = $v->BuyAreaNo;
                $bike->BuyAreaName = $v->BuyAreaName;
                $bike->BuyCompanyNo = $v->BuyCompanyNo;
                $bike->BuyCompanyName = $v->BuyCompanyName;
                $bike->BuyDate = $v->BuyDate;
                $bike->BicycleTypeID = $v->BicycleTypeID;
                $bike->BicycleTypeName = $v->BicycleTypeName;
                $bike->ModelsID = $v->ModelsID;
                $bike->Model = $v->Model;
                $bike->Color = $v->Color;
                $bike->WarrantyStartDT = $v->WarrantyStartDT;
                $bike->WarrantyEndDT = $v->WarrantyEndDT;
                $bike->WarrantyStartDT2 = $v->WarrantyStartDT2;
                $bike->WarrantyEndDT2 = $v->WarrantyEndDT2;
                $bike->BuyWarranty = $v->BuyWarranty;
                $bike->WarrantyCompanyNo = $v->WarrantyCompanyNo;
                $bike->WarrantyCompanyName = $v->WarrantyCompanyName;
                $bike->CustomerProductNo = $v->CustomerProductNo;
                $bike->CustomerProductName = $v->CustomerProductName;
                $bike->CMc = $v->CMc;
                $bike->status = 2;
                $bike->save();
            }
        }
        
        return redirect('login')->with(['code' => 200]);
    }
}
