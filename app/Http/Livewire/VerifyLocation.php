<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Userinfo;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Http;

class VerifyLocation extends Component
{
    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;

    public $region;
    public $province;
    public $city;
    public $baranggay;
    public $regionSelection = [];
    public $provinceSelection = [];
    public $citySelection = [];
    public $baranggaySelection = [];

    //Validation Rules
    public function rules(){
        return [
            'region' => 'required',
            'province' => 'required',
            'city' => 'required',
            'baranggay' => 'required',
        ];
    }

    public function loadModel(){
        $data = Userinfo::where('id', $this->modelId)->first();
        //Assign The Variable Here
    }
    
    //The Data for the model mapped in this component
    public function modelData(){
        return [
            'user_id' => auth()->user()->endorsers_id,
            'region' => $this->apiData('regions', $this->region)['name'],
            'region_code' => $this->region,
            'province' => $this->apiData('provinces', $this->province)['name'],
            'province_code' => $this->province,
            'city' => $this->apiData('cities', $this->city)['name'],
            'city_code' => $this->city,
            'barangay' => $this->apiData('barangays', $this->baranggay)['name'],
            'barangay_code' => $this->baranggay,
        ];
    }

    public function create(){
        $this->validate();
        Userinfo::create($this->modelData());
        return redirect(route('/'));
    }

    public function update(){
        $this->validate();
        Userinfo::where('id', $this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    public function delete(){
        Userinfo::where('id', $this->modelId)->delete();
        $this->modalConfirmDeleteVisible = false;
    }

    public function createShowModal(){
        $this->resetValidation();
        $this->reset();
        $this->modalFormVisible = true;
    }

    public function updateShowModal($id){
        $this->resetValidation();
        $this->reset();
        $this->modalFormVisible = true;
        $this->modelId = $id;
        $this->loadModel();
    }

    public function deleteShowModal($id){
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }

    public function api($category, $code = null){
        // https://ph-locations-api.buonzz.com/v1/provinces?filter[where][region_code]=02&filter[order]=name asc
        if($category == 'regions'){
            $response = Http::get('https://ph-locations-api.buonzz.com/v1/regions');
        }

        if($category == 'provinces'){
            $response = Http::get('https://ph-locations-api.buonzz.com/v1/provinces?filter[where][region_code]='. $code .'&filter[order]=name asc');
        }

        if($category == 'cities'){
            $response = Http::get('https://ph-locations-api.buonzz.com/v1/cities?filter[where][province_code]='. $code .'&filter[order]=name asc');
        }

        if($category == 'barangays'){
            $response = Http::get('https://ph-locations-api.buonzz.com/v1/barangays?filter[where][city_code]='. $code .'&filter[order]=name asc');
        }

        return json_decode($response->body(), true)['data'];
    }

    public function apiData($category, $id = null){
        if($category == 'regions'){
            $response = Http::get('https://ph-locations-api.buonzz.com/v1/regions/'. $id);
        }

        if($category == 'provinces'){
            $response = Http::get('https://ph-locations-api.buonzz.com/v1/provinces/'. $id);
        }

        if($category == 'cities'){
            $response = Http::get('https://ph-locations-api.buonzz.com/v1/cities/'. $id);
        }

        if($category == 'barangays'){
            $response = Http::get('https://ph-locations-api.buonzz.com/v1/barangays/'. $id);
        }

        return json_decode($response->body(), true);
    }

    public function mount(){
        $this->regionSelection = $this->api('regions');
    }

    public function updatedRegion(){
        $this->citySelection = [];
        $this->baranggaySelection = [];
        $this->provinceSelection = $this->api('provinces', $this->region);
    }

    public function updatedProvince(){
        $this->baranggaySelection = [];
        $this->citySelection = $this->api('cities', $this->province);
    }

    public function updatedCity(){
        $this->baranggaySelection = $this->api('barangays', $this->city);
    }

    public function render()
    {
        return view('verify.location')->layout('layouts.guest');
    }
}