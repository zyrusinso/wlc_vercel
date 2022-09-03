<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Code;
use App\Models\Product;
use App\Models\Transaction;

use App\Http\Controllers\TransactionController;

use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class UpgradeProductEndorser extends Component
{
    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;

    public $code;
    public $networkRootData = [];

    protected $listeners = ['UpgradeProductEndorser' => 'createShowModal'];

    //Validation Rules
    public function rules(){
        return [
            'code' => 'required|exists:codes',
        ];
    }
    
    //The Data for the model mapped in this component
    public function activationBAModel(){
        return [
            'role' => 'business-endorsers',
        ];
    }

    // Model of Business Direct
    public function networkBADirect($networkUser){
        // initial Setup Amount
        $directReferralAmount = 1000;
        $currentAccountNotBACash = $networkUser->ba_direct_cash_not_ba;

        if($networkUser->role == 'business-endorsers'){
            return [
                'ba_direct_cash' => $networkUser->ba_direct_cash + $directReferralAmount,
            ];
        }elseif($networkUser->role == 'product-endorsers'){
            if($networkUser->business_account_counter-- <= 1){
                return [
                    'ba_direct_cash' =>  $currentAccountNotBACash + $directReferralAmount,
                    'ba_direct_cash_not_ba' =>  0,
                    'role' => 'business-endorsers'
                ];
            }
        }

        return [
            'ba_direct_cash_not_ba' =>  $networkUser->ba_direct_cash_not_ba + $directReferralAmount,
            'business_account_counter' => ($networkUser->business_account_counter > 1)? $networkUser->business_account_counter-1 : 3, 
        ];
    }

    public function transactionBAModel($user, $productSelected){
        return [
            'user_id' => auth()->user()->endorsers_id,
            'name' => auth()->user()->full_name,
            'amount' => $productSelected->srp,
            'transaction_id' => TransactionController::createUniqueTransactionCode(),
            'product_id' => $productSelected->id,
            'product_code' => $this->code
        ];
    }

    public function referralBAModel($user, $productSelected){
        return [
            'user_id' => $user->endorsers_id,
            'name' => $user->full_name,
            'amount' => 1000,
            'transaction_id' => TransactionController::createUniqueTransactionCode(),
            'product_id' => $productSelected->id,
            'product_code' => $this->code,
            'description' => 'Direct Referral',
            'status' => ($user->role == 'business-endorsers') ? 'Credited' : 'Hold',
        ];
    }

    public function create(){
        $this->validate();
        $initialBundleActivationProductAmountRequired = 4500;
        $codeSelected = Code::where('code', $this->code)->first();
        $productSelected = Product::where('id', $codeSelected->product_id)->first();
        $authUser = auth()->user();
        $networkListRootData = $this->networkListRootData();
        array_shift($networkListRootData);
        
        if($this->isCodeAlreadyUsed($this->code)){
            return $this->addError('code', 'The Code is Already Used!');
        }

        if($productSelected->srp < $initialBundleActivationProductAmountRequired){
            return $this->addError('code', 'The Code is not a Bundle Product!');
        }

        if($productSelected->srp >= $initialBundleActivationProductAmountRequired){
            foreach($networkListRootData as $item){
                User::where('endorsers_id', $item->endorsers_id)->update($this->networkBADirect($item));
                Transaction::create($this->referralBAModel($item, $productSelected));
            }

            User::where('endorsers_id', $authUser->endorsers_id)->update($this->activationBAModel());
            Transaction::create($this->transactionBAModel($authUser, $productSelected));
        }

        $this->modalFormVisible = false;
        $this->reset();
        return redirect(route('dashboard'));
    }
    
    public function read(){
        return User::paginate(5);
    }

    public function update(){
        $this->validate();
        User::where('id', $this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    public function delete(){
        User::where('id', $this->modelId)->delete();
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

    public function isCodeAlreadyUsed($code){
        $findCode = Transaction::where('product_code', $code)
                                ->where('user_id', auth()->user()->id)->first();

        return $findCode? true : false;
    }

    // Network List Root Data
    public function networkListRootData(){
        $this->networkRootData = [];
        $networkList = User::networkRootList(auth()->user()->endorsers_id);

        $this->networkListRootDataFormat($networkList);
        
        return $this->networkRootData;
    }

    public function networkListRootDataFormat($networkList){
        foreach($networkList as $item){
            array_push($this->networkRootData, $item);

            if($item->root->isNotEmpty()){
                $this->networkListRootDataFormat($item->root);
            }
        }
    }

    public function render()
    {
        // dd(User::networkRootList(auth()->user()->endorsers_id));
        return view('livewire.upgrade-product-endorser', [
            'data'=> $this->read()
        ])->layout('layouts.base');
    }
}