<div>
    @include('content-header', ['headerTitle' => "Transactions"])

    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="d-flex justify-content-end mb-2">
                        <!-- <button class="btn btn-dark mr-3" wire:click="createShowModal">Create</button> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-striped dataTable dtr-inline table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Amount</th>
                                    <th>Transaction ID</th>
                                    <th>Product ID</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="tableList">
                            @if ($data->count())
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ \App\Models\Product::productList()[$item->product_id] ?? ''}}</td>
                                        <td>{{ $item->amount }}</td>
                                        <td>{{ $item->transaction_id }}</td>
                                        <td>{{ $item->product_id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format("F j, Y, g:i a") }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="10">No Transaction Found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->

        <div class="d-flex justify-content-center">
        {{ $data->links('vendor.livewire.bootstrap') }}
        </div>

        <!-- Create & Update Modal -->
        <x-jet-dialog-modal wire:model="modalFormVisible">
            <x-slot name="title">
                {{ __('Transaction') }}
            </x-slot>

            <x-slot name="content">
                <div class="mb-3">
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <x-jet-input id="name" type="text" class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                 wire:model="name" autofocus />
                    <x-jet-input-error for="name" />
                </div>
                <div class="mb-3">
                    <x-jet-label for="amount" value="{{ __('Amount') }}" />
                    <x-jet-input id="amount" type="text" class="{{ $errors->has('amount') ? 'is-invalid' : '' }}"
                                 wire:model="amount" autofocus />
                    <x-jet-input-error for="amount" />
                </div>
                <div class="mb-3">
                    <x-jet-label for="product_id" value="{{ __('Product ID') }}" />
                    <x-jet-input id="product_id" type="text" class="{{ $errors->has('product_id') ? 'is-invalid' : '' }}"
                                 wire:model="product_id" autofocus />
                    <x-jet-input-error for="product_id" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                @if ($modelId)
                    <x-jet-button class="ms-2" wire:click="update" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-jet-button>
                @else
                    <x-jet-button class="ms-2" wire:click="create" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-jet-button>
                @endif
            </x-slot>
        </x-jet-dialog-modal>

        <!-- Delete User Confirmation Modal -->
        <x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">
            <x-slot name="title">
                {{ __('Delete Transaction') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this transanction? Once the transaction is deleted, all of its resources and data will be permanently deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')"
                                        wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button wire:click="delete" wire:loading.attr="disabled">
                    <div wire:loading wire:target="delete" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    {{ __('Delete Account') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-dialog-modal>
    </div>
</div>
