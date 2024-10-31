<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;
use App\Models\UserBike;
use App\Models\Area;
use App\Models\BikeModel;
use App\Http\Controllers\APIController;
use Livewire\WithFileUploads;
use Storage;

class RegisterBikeWithoutQr extends Component
{
    use WithFileUploads;

    public $lang, $color, $models, $BBNo, $ProductNo, $BuyCompanyName, $BuyDate, $Color, $certificate, $bike, $frame, $Model;
    public $areas = [];
    public $categroy = [];
    public $colors = [];
    public $area = 'TW-台灣';
    public $BicycleTypeID = '1-BIRDY';


    public function mount(){
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        
        $user = auth()->user();
        $this->areas = Area::all();
        $this->lang = $lang;
        list($BicycleTypeID, $BicycleTypeName) = explode('-', $this->BicycleTypeID);
        list($AreaNo, $AreaCnName) = explode('-', $this->area);
        $this->models = json_decode(APIController::getProductModel($BicycleTypeID))->Entries;
        $this->store = json_decode(APIController::GetCompanyLocationList($AreaNo))->Entries;
    }
    
    public function render()
    {
        list($BicycleTypeID, $BicycleTypeName) = explode('-', $this->BicycleTypeID);
        list($AreaNo, $AreaCnName) = explode('-', $this->area);
        $this->models = json_decode(APIController::getProductModel($BicycleTypeID))->Entries ?? null;
        $this->store = json_decode(APIController::GetCompanyLocationList($AreaNo))->Entries ?? null;
        $this->colors = json_decode(APIController::getProductColor($this->Model))->Entries ?? null;
        return view('livewire.back.register-bike-without-qr',[
            'types' => json_decode(APIController::getBicycleType())->Entries,
            'models' => $this->models,
            'store' => $this->store,
        ])
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }

    public function changeType()
    {
        $this->mount();
    }

    public function changeColor()
    {
        $this->colors = json_decode(APIController::getProductColor($this->Model))->Entries;
        $this->mount();
    }

    public function changeArea()
    {
        $this->mount();
    }

    public function changeProductNo($value)
    {
        if($value)
        {
            $this->mount();
        }
    }

    public function saveBike()
    {
        $user = auth()->user();
        $this->validate([
            //'ProductNo' => 'required|min:6',
            'BBNo' => 'required',
            'BuyDate' => 'required',
            'area' => 'required',
            'BuyCompanyName' => 'required',
            'Model' => 'required',
            'certificate' => 'required|image|max:10240', // 1MB Max
            'bike' => 'required|image|max:10240', // 1MB Max
            'frame' => 'required|image|max:10240', // 1MB Max
        ]);

        list($AreaNo, $AreaCnName) = explode('-', $this->area);
        list($BicycleTypeID, $BicycleTypeName) = explode('-', $this->BicycleTypeID);
 
        $images[] = $CertificatePic = $this->certificate->store('images/'.$user->id, 'public');
        $images[] = $FullPic = $this->bike->store('images/'.$user->id, 'public');
        $images[] = $BBNoPic = $this->frame->store('images/'.$user->id, 'public');
        $models = [];
        foreach($this->models as $k => $v)
        {
            $models[$v['No']] = $v['ProductModelName'];
        }
        
        $data = [
            //'ProductNo' => $this->ProductNo,
            'BBNo' => $this->BBNo,
            'BuyAreaNo' => $AreaNo,
            'BuyCompanyNo' => $this->BuyCompanyName,
            'BuyDate' => $this->BuyDate,
            'BicycleType' => $BicycleTypeName,
            'Models' => $models[$this->Model],
            'Color' => $this->Color,
            'CertificatePic' => 'data:image/' . pathinfo(Storage::path('public/'.$CertificatePic), PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents(Storage::path('public/'.$CertificatePic))),
            'FullPic' => 'data:image/' . pathinfo(Storage::path('public/'.$FullPic), PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents(Storage::path('public/'.$FullPic))),
            'BBNoPic' => 'data:image/' . pathinfo(Storage::path('public/'.$BBNoPic), PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents(Storage::path('public/'.$BBNoPic))),
            'Type' => 2,
        ];
        $data = (object)$data;
        $response = APIController::saveCerProductData($data);

        if(empty(json_decode($response)->StatusCode))
        {
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
            $response = APIController::getQrcodeList();
            if(json_decode($response))
            {
                foreach(json_decode($response)->Entries as $k => $v)
                {
                    if($v->QrCodeStatus == 0)
                    {
                        $bike = new UserBike;
                        $bike->user_id = $user->id;
                        $bike->MemberId = $user->MemberID;
                        $bike->memo = $v->Memo;
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
                        $bike->No = $v->No;
                        $bike->status = 1;
                        $bike->save();
                    }
                }
            }

            return redirect('my-bike')->with(['code' => 200, 'message' => __('frontend.dashboard.add_success')]);
        }

        return redirect('my-bike')->with(['message' => json_decode($response)->Message]);
    }
}
