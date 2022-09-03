<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\Code;
use App\Models\User;
use App\Models\Product;

use App\Http\Controllers\TransactionController;

use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ActivateUser extends Component
{
    public $ModalFormVisible = false;

    public $code;
    public $networkRootData = [];
    
    protected $listeners = ['activateUser' => 'createShowModal'];

    //Validation Rules
    public function rules(){
        return [
            'code' => 'required|exists:codes',
        ];
    }

    //The Data for the model mapped in this component
    public function modelData(){
        return [
            'code' => $this->code,
        ];
    }

    public function userActivateModelData(){
        return [
            'role' => 'product-endorsers'
        ];
    }

    public function productEndorsersUpgradeModelData(){
        return [
            'role' => 'business-endorsers'
        ];
    }

    public function userEndorserModelData($userEndorser){
        return [
            'cbb' => ($userEndorser->cbb > 1)? $userEndorser->cbb-1 : 3,
        ];
    }

    public function userNetworkRootModelData($networkRootUser){
        return [
            'available_cash_bal' => ($networkRootUser->cbb > 1)? $networkRootUser->available_cash_bal : $networkRootUser->available_cash_bal+1500,
            'total_cash_bonus' => ($networkRootUser->cbb > 1)? $networkRootUser->total_cash_bonus : $networkRootUser->total_cash_bonus+1500,
            'cbb' => ($networkRootUser->cbb > 1)? $networkRootUser->cbb-1 : 3,
        ];
    }

    public function userPurchasedModelData($priceOfCode = 0){
        return [
            'tpp' => auth()->user()->tpp + $priceOfCode,
        ];
    }

    public function transactionModelData($productSelected){
        return [
            'user_id' => auth()->user()->endorsers_id,
            'name' => auth()->user()->full_name,
            'amount' => $productSelected->srp,
            'transaction_id' => TransactionController::createUniqueTransactionCode(),
            'product_id' => $productSelected->id,
            'product_code' => $this->code
        ];
    }

    public function create(){
        $this->validate();
        $codeSelected = Code::where('code', $this->code)->first();
        $productSelected = Product::where('id', $codeSelected->product_id)->first();
        $userTotalPurchaseWithCode = auth()->user()->tpp + $productSelected->srp ?? 0;
        $userEndorser = User::where('endorsers_id', auth()->user()->referred_by)->first();
        $networkListRootData = $this->networkListRootData();
        array_shift($networkListRootData);
        
        if($this->isCodeAlreadyUsed($this->code)){
            return $this->addError('code', 'The Code is Already Used!');
        }
 
        try {
            DB::beginTransaction();
            
            if($userTotalPurchaseWithCode >= 1500){
                User::where('id', auth()->user()->id)->update($this->userActivateModelData());
                foreach($networkListRootData as $item){
                    $networkRootUser = User::where('endorsers_id', $item->endorsers_id)->first();
                    $networkRootUser->update($this->userNetworkRootModelData($networkRootUser));
                }
                // $userEndorser->update($this->userEndorserModelData($userEndorser));
                User::where('id', auth()->user()->id)->update($this->userPurchasedModelData($productSelected->srp));
            }else{
                User::where('id', auth()->user()->id)->update($this->userPurchasedModelData($productSelected->srp));
            }
            Transaction::create($this->transactionModelData($productSelected));
        } catch (Throwable $ex) {

            DB::rollBack();
            Log::critical($ex);
            return response()->json([
                'success' => false,
                'message' => 'System Failed! please contact the admin or developer to fix this problem!', // Activation failed.
            ], 500);
        }
        DB::commit();

        return redirect(route(User::userRoleRedirect(auth()->user()->role)));
    }

    public function createShowModal(){
        $this->resetValidation();
        $this->reset();
        $this->ModalFormVisible = true;
    }

    public function isCodeAlreadyUsed($code){
        $findCode = Transaction::where('product_code', $code)
                                ->where('user_id', auth()->user()->id)->first();

        return $findCode? true : false;
    }

    public function networkListRoot(){
        return User::networkList(auth()->user()->endorsers_id);
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
        return view('livewire.activate-user');
    }
}
