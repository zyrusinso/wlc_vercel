<div class="content-header d-sm-none d-md-block">
    <div class="container-fluid">
        <div class="row mb-2 ">
            <div class="col-sm-6 col-6">
                <h1 class="m-0">
                    {{ $headerTitle }}
                </h1>
            </div>
            <div class="col-sm-6 col-6 elevation-8">
                <div class="d-flex float-right" style="color: white">
                    <a href="#" class="nav-link">
                        <i class="fa fa-envelope fa-lg"></i>
                    </a>
                    <a href="#" class="nav-link">
                        <i class="fa fa-comment fa-lg"></i>
                    </a>
                    <a data-toggle="dropdown" href="#" aria-expanded="true" class="nav-link">
                        <i class="fa fa-user fa-lg"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                        @if (route('profile.show') != request()->url())
                            <a href="{{ route('profile.show') }}" class="dropdown-item">
                                Account Settings
                            </a>
                            <div class="dropdown-divider"></div>
                        @endif
                        
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"
                            class="dropdown-item dropdown-footer">Logout</a>
                        
                        <form method="POST" id="logout-form" action="{{ route('logout') }}">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            // Toaster
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: true,
            });
            
            @if (auth()->user()->role == 'product-endorsers' && request()->routeIs('profile.show'))
                $(function(){
                    $(document).Toasts('create', {
                        class: 'bg-info text-center',
                        title: "Become a Business Endorsers!",
                        position: 'topRight',
                        body: "<button class='btn btn-dark' onclick='upgradeEmit()'>Upgrade</button>",
                        subtitle: 'Activate now',
                        close: false,
                        delay: 2000,
                        autohide: true,
                    });
                })
            @endif
        });

        function upgradeEmit(){
            window.Livewire.emit('UpgradeProductEndorser');
        }
    </script>
</div>
