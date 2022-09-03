<?php

namespace App\Http\Livewire\Rewards;

use Livewire\Component;
use App\Models\TeamSalesBonus;
use App\Models\User;

use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Http\Controllers\PaginateController;


class TeamSalesBonusComponent extends Component
{
    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;

    //Validation Rules
    public function rules(){
        return [
            
        ];
    }

    public function loadModel(){
        $data = TeamSalesBonus::where('id', $this->modelId)->first();
        //Assign The Variable Here
    }
    
    //The Data for the model mapped in this component
    public function modelData(){
        return [
            
        ];
    }

    public function create(){
        $this->validate();
        TeamSalesBonus::create($this->modelData());
        $this->modalFormVisible = false;
        $this->reset();
    }
    
    public function read(){
        $data = [];
        $teamSales = TeamSalesBonus::where('user_id', auth()->user()->endorsers_id)->get();
        
        foreach($teamSales as $item){
            if(User::where('endorsers_id', $item->buyer_id)->first()->role == 'business-endorsers'){
                array_push($data, $item);
            }
        }

        return PaginateController::arrayPaginator($data);
    }

    public function update(){
        $this->validate();
        TeamSalesBonus::where('id', $this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    public function delete(){
        TeamSalesBonus::where('id', $this->modelId)->delete();
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

    public function buyer($buyerId){
        $buyerInfo = User::where('endorsers_id', $buyerId)->first();

        return $buyerInfo;
    }


    public function render()
    {
        return view('livewire.rewards.team-sales-bonus-component', [
            'data'=> $this->read()
        ])->layout('layouts.base');
    }
}