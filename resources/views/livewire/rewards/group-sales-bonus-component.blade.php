<div>
    @include('content-header', ['headerTitle' => 'Group Sales Bonus'])
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="example1" class="table table-bordered table-striped dataTable dtr-inline table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>Buyer</th>
                                    <th>Role</th>
                                    <th>Profit</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @if ($data->count())
                                @foreach ($data as $item)
                                    <tr>
                                        <th>{{ $this->buyer($item->buyer_id)->full_name }}</th>
                                        <td>{{ \App\Models\User::userRoleList()[$this->buyer($item->buyer_id)->role] }}</td>
                                        <td>{{ $item->profit }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format("F j, Y, g:i a") }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="10">No Results Found</td>
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
                {{ __('Title') }}
            </x-slot>

            <x-slot name="content">
                <div class="mb-3">
                    <x-jet-label for="" value="{{ __('Example') }}" />
                    <x-jet-input id="" type="text" class="{{ $errors->has('') ? 'is-invalid' : '' }}"
                                 wire:model="" autofocus />
                    <x-jet-input-error for="" />
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
                {{ __('Delete Title') }}
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
