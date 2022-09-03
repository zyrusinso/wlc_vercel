<div>
<x-guest-layout>
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <!-- AdminLTE App-->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

    <script src="{{ asset('assets\js\js_ph_location.js') }}"></script>

    <x-jet-authentication-card>
        <x-slot name="logo">
        </x-slot>

        <div class="card-body">
            <div class="mb-3 small text-muted">
                {{ __('Thanks for signing up! Before we continue to take the next step, provide the needed information below!') }}
            </div>

            <x-jet-validation-errors class="mb-3" />

            <form>
                @csrf

                <select wire:model="region" class="form-control" name="region" id="region" style="margin-top: 20px">
                    <option>-- Select a Region --</option>
                    @if(count($regionSelection) > 0)
                        @foreach($regionSelection as $key => $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    @endif
                </select>
                @error('region') <span class="error" style="color: red"">{{ $message }}</span> @enderror
                
                <select wire:model="province" class="form-control" name="province" id="province" style="margin-top: 20px">
                    <option>-- Select a Province --</option>
                    @if(count($provinceSelection) > 0)
                        @foreach($provinceSelection as $key => $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    @endif
                </select>
                @error('province') <span class="error" style="color: red"">{{ $message }}</span> @enderror
                
                <select wire:model="city" class="form-control" name="city" id="city" style="margin-top: 20px">
                    <option>-- Select a City --</option>
                    @if(count($citySelection) > 0)
                        @foreach($citySelection as $key => $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    @endif
                </select>
                @error('city') <span class="error" style="color: red"">{{ $message }}</span> @enderror
                
                <select wire:model="baranggay" class="form-control" name="baranggay" id="barangay" style="margin-top: 20px">
                    <option>-- Select a Baranggay --</option>
                    @if(count($baranggaySelection) > 0)
                        @foreach($baranggaySelection as $key => $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    @endif
                </select>
                @error('baranggay') <span class="error" style="color: red"">{{ $message }}</span> @enderror

                <div>
                    <x-jet-button wire:click.prevent="create" wire:loading.attr="disabled" style="margin-top: 20px">
                        {{ __('Submit') }}
                    </x-jet-button>
                </div>
            </form>
        </div>
    </x-jet-authentication-card>

    <script>

    var my_handlers = {

        // fill_provinces:  function(){

        //     var region_code = $(this).val();
        //     $('#province').ph_locations('fetch_list', [{"region_code": region_code}]);
            
        // },

        // fill_cities: function(){

        //     var province_code = $(this).val();
        //     $('#city').ph_locations( 'fetch_list', [{"province_code": province_code}]);
        // },


        // fill_barangays: function(){

        //     var city_code = $(this).val();
        //         $('#barangay').ph_locations('fetch_list', [{"city_code": city_code}]);
        //     }
        // };

        // $(function(){
        //     $('#region').on('change', my_handlers.fill_provinces);
        //     $('#province').on('change', my_handlers.fill_cities);
        //     $('#city').on('change', my_handlers.fill_barangays);

        //     $('#region').ph_locations({'location_type': 'regions'});
        //     $('#province').ph_locations({'location_type': 'provinces'});
        //     $('#city').ph_locations({'location_type': 'cities'});
        //     $('#barangay').ph_locations({'location_type': 'barangays'});

        //     $('#region').ph_locations('fetch_list');
        // });
    

    </script>
</x-guest-layout>
</div>
