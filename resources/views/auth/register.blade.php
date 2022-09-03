<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            
        </x-slot>

        <div class="card-body">
            
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('img/WLC_REGFORM_LOGO.png') }}" style="max-width: 100%">
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <x-jet-label value="{{ __('First Name') }}" />

                            <x-jet-input class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name"
                                        :value="old('first_name')" autofocus/>
                            <x-jet-input-error for="first_name"></x-jet-input-error>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <x-jet-label value="{{ __('Middle Name') }}" />

                            <x-jet-input class="{{ $errors->has('middle_name') ? 'is-invalid' : '' }}" type="text" name="middle_name"
                                        :value="old('middle_name')" />
                            <x-jet-input-error for="middle_name"></x-jet-input-error>
                        </div>
                    </div>
                    <div class="col-12 col-lg-12">
                        <div class="mb-3">
                            <x-jet-label value="{{ __('Last Name') }}" />

                            <x-jet-input class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name"
                                        :value="old('last_name')" />
                            <x-jet-input-error for="last_name"></x-jet-input-error>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <x-jet-label value="{{ __('Address') }}" />

                    <x-jet-input class="{{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address"
                                :value="old('address')" />
                    <x-jet-input-error for="address"></x-jet-input-error>
                </div>

                <div class="mb-3">
                    <x-jet-label value="{{ __('Email') }}" />

                    <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email"
                                 :value="old('email')" />
                    <x-jet-input-error for="email"></x-jet-input-error>
                </div>

                <div class="mb-3">
                    <x-jet-label value="{{ __('Cellphone Number') }}" />

                    <x-jet-input class="{{ $errors->has('cp_num') ? 'is-invalid' : '' }}" type="number" name="cp_num"
                                 :value="old('cp_num')" />
                    <x-jet-input-error for="cp_num"></x-jet-input-error>
                </div>

                <div class="mb-3">
                    @if (session()->has('referrer'))
                        <x-jet-label value="{{ __('Endorsers ID') }}" />

                        <x-jet-input class="{{ $errors->has('endorsers_id') ? 'is-invalid' : '' }}" type="text" name="endorsers_id"
                                    readonly="readonly" value="{{ session()->get('referrer') }}"/>
                        <x-jet-input-error for="endorsers_id"></x-jet-input-error>
                    @else
                        <x-jet-label value="{{ __('Endorsers ID') }}" />

                        <x-jet-input class="{{ $errors->has('endorsers_id') ? 'is-invalid' : '' }}" type="text" name="endorsers_id"
                                    :value="old('endorsers_id')" />
                        <x-jet-input-error for="endorsers_id"></x-jet-input-error>
                    @endif
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <x-jet-checkbox id="terms" name="terms" />
                            <label class="custom-control-label" for="terms">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                            </label>
                        </div>
                    </div>
                @endif

                <div class="mb-0">
                    <div class="d-flex justify-content-end align-items-baseline">
                        <a class="text-muted me-3 text-decoration-none" href="{{ route('login') }}">
                            {{ __('Already registered?') }}
                        </a>

                        <x-jet-button>
                            {{ __('Register') }}
                        </x-jet-button>
                    </div>
                </div>
            </form>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>