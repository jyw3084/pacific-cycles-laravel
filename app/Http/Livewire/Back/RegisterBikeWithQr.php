<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;
use App\Models\UserBike;
use App\Models\Area;
use App\Http\Controllers\APIController;
use Livewire\WithFileUploads;
use Session;
use Storage;

class RegisterBikeWithQr extends Component
{
    use WithFileUploads;

    public $lang, $color, $models, $ProductNo, $BBNo, $BuyCompanyName, $BuyDate, $Color, $certificate, $bike, $frame, $Model, $add = false;
    public $areas = [];
    public $categroy = [];
    public $colors = [];
    public $area = 'TW-台灣';
    public $BicycleTypeID = '1-BIRDY';

    public function mount(){
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->areas = Area::all();
        $this->lang = $lang;
        
        $user = auth()->user();
    }
    
    public function render()
    {
        $area = $this->area ?? 'TW-台灣';
        $BicycleType = $this->BicycleTypeID ?? '1-BIRDY';
        list($AreaNo, $AreaCnName) = explode('-', $area);
        list($BicycleTypeID, $BicycleTypeName) = explode('-', $BicycleType);
        $this->models = json_decode(APIController::getProductModel($BicycleTypeID))->Entries;
        $this->store = json_decode(APIController::GetCompanyLocationList($AreaNo))->Entries;

        return view('livewire.back.register-bike-with-qr',[
            'types' => json_decode(APIController::getBicycleType())->Entries,
            'models' => json_decode(APIController::getProductModel($BicycleTypeID))->Entries,
            'store' => json_decode(APIController::GetCompanyLocationList($AreaNo))->Entries,
        ])
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }
    
    public function addBike()
    {
        $this->rules = [
            'ProductNo' => 'required|min:6',
            'BBNo' => 'required',
        ];
        $data = $this->validate();
        $data = (object)$data;
        $response = APIController::GetProductQRCodeInfo($data);
        if(empty(json_decode($response)->StatusCode))
        {
            $data = json_decode($response)->Entries;
            Session::put('bike', $data);
            
            $this->add = true;
            $this->BuyDate = date('Y-m-d', strtotime($data->BuyDate));
            $this->area = $data->BuyAreaNo.'-'.$data->BuyAreaName;
            $this->BuyCompanyName = $data->BuyCompanyNo;
            //return redirect('my-bike');
        }
        else
            return redirect('my-bike')->with(['message' => trans('frontend.dashboard.add_bike_fail')]);
    }

    public function changeType()
    {
        $this->mount();
    }

    public function changeProductNo($value)
    {
        if($value)
        {
            $this->colors = json_decode(APIController::GetProductColor($value))->Entries;
            $this->mount();
        }
    }

    public function changeArea()
    {
        $this->mount();
    }

    public function saveBike()
    {
        $user = auth()->user();
        $input = $this->validate([
            'ProductNo' => 'required|min:6',
            'BBNo' => 'required',
            'BuyDate' => 'required',
            'area' => 'required',
            'BuyCompanyName' => 'required',
            'certificate' => 'required|image|max:10240', // 1MB Max
            'bike' => 'required|image|max:10240', // 1MB Max
            'frame' => 'required|image|max:10240', // 1MB Max
        ]);
 
        $images[] = $CertificatePic = $this->certificate->store('images/'.$user->id, 'public');
        $images[] = $FullPic = $this->bike->store('images/'.$user->id, 'public');
        $images[] = $BBNoPic = $this->frame->store('images/'.$user->id, 'public');

        $bike = Session::get('bike');
        
        list($AreaNo, $AreaCnName) = explode('-', $this->area);
        list($BicycleTypeID, $BicycleTypeName) = explode('-', $this->BicycleTypeID);
        $data = [
            'ProductNo' => $bike->ProductNo,
            'BBNo' => $bike->BBNo,
            'BuyAreaNo' => $AreaNo,
            'BuyCompanyNo' => $this->BuyCompanyName,
            'BuyDate' => $this->BuyDate,
            'CertificatePic' => 'data:image/' . pathinfo(Storage::path('public/'.$CertificatePic), PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents(Storage::path('public/'.$CertificatePic))),
            'FullPic' => 'data:image/' . pathinfo(Storage::path('public/'.$FullPic), PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents(Storage::path('public/'.$FullPic))),
            'BBNoPic' => 'data:image/' . pathinfo(Storage::path('public/'.$BBNoPic), PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents(Storage::path('public/'.$BBNoPic))),
            'Type' => 1,
        ];
        $data = (object)$data;
        $response = APIController::saveCerProductData($data);

        if(empty(json_decode($response)->StatusCode))
        {
            $data = Session::get('bike');

            $user = auth()->user();
            $bike = new UserBike;
            $bike->user_id = $user->id;
            $bike->MemberId = $user->MemberID;
            $bike->IsCanBuyWarranty = 0;
            $bike->ProductNo = $data->ProductNo;
            $bike->BBNo = $data->BBNo;
            $bike->BuyAreaNo = $AreaNo;
            $bike->BuyAreaName = $AreaCnName;
            $bike->BuyCompanyNo = $data->BuyCompanyNo;
            $bike->BuyCompanyName = $this->BuyCompanyName;
            $bike->BuyDate = $this->BuyDate;
            $bike->BicycleTypeID = $data->BicycleTypeID;
            $bike->BicycleTypeName = $data->BicycleTypeName;
            $bike->TypeName = $data->BicycleTypeName;
            $bike->ModelsID = $data->ModelsID;
            $bike->Model = $data->Model;
            $bike->Color = $data->Color;
            $bike->WarrantyStartDT = $data->WarrantyStartDT;
            $bike->WarrantyEndDT = $data->WarrantyEndDT;
            $bike->WarrantyStartDT2 = $data->WarrantyStartDT2;
            $bike->WarrantyEndDT2 = $data->WarrantyEndDT2;
            $bike->BuyWarranty = $data->BuyWarranty;
            $bike->WarrantyCompanyNo = $data->WarrantyCompanyNo;
            $bike->WarrantyCompanyName = $data->WarrantyCompanyName;
            $bike->CustomerProductNo = $data->CustomerProductNo;
            $bike->CustomerProductName = $data->CustomerProductName;
            $bike->CMc = $data->CMc;
            $bike->status = 1;
            $bike->BikeImages = json_encode($images);
            $bike->save();

            return redirect('my-bike')->with(['code' => 200, 'message' => __('frontend.dashboard.add_success')]);
        }
        
        return redirect('my-bike')->with(['message' => json_decode($response)->Message]);
    }
}
