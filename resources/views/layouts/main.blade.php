<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/assets/icon.ico" type="image/x-icon">
    <title>{{ $title ?? 'Aplikasi Monitoring' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    @stack('link')
    <style>
        input[readonly] {
            background-color: rgb(51, 48, 48);
            cursor: not-allowed;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <header class="max-w-7xl ">
            @include('layouts.sidebar')
        </header>

        {{-- <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif --}}

        <!-- Page Content -->
        <main>
            @yield('body')
        </main>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

{{-- Toaster Alert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    // Initialize toastr options
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };
</script>
@auth
    <script>
        $(document).ready(function() {
            $('th').addClass('border border-gray-400 ')
            $('td').addClass('border border-gray-400 ')
            const val_navbar = @json($navbar);
            $(`#nav-${val_navbar}`).removeClass('btn-light').addClass('btn-error');
            let id = '';



            function showAlertError() {
                toastr.error('Anda harus memilih data terlebih dahulu')
            }

            // Handle click table
            $(document).on('click', '.table-row', function() {
                id = $(this).data("id");
                if ($(this).hasClass('bg-blue-300')) {
                    $(this).removeClass('bg-blue-300')
                } else {
                    $('.table-row').removeClass('bg-blue-300')
                    $(this).toggleClass('bg-blue-300')
                }
            })

            $(document).on('click', '#btn-redeem-complete', function() {
                if (id === '') {
                    showAlertError()
                    return
                }
                window.location.href = `${id}/redeem-complete`
            })

            $(document).on('click', '#btn-edit', function() {
                if (id === '') {
                    showAlertError()
                    return
                }
                window.location.href = `${id}/edit`
            })
            $(document).on('click', '#btn-access', function() {
                if (id === '') {
                    showAlertError()
                    return
                }
                window.location.href = `${id}/permission`
            })

            $(document).on('click', '#btn-payment', function() {
                if (id === '') {
                    showAlertError()
                    return
                }
                window.location.href = `${id}/payment`
            })
            $(document).on('click', '#btn-refund', function() {
                if (id === '') {
                    showAlertError()
                    return
                }
                window.location.href = `${id}/refund`
            })

            $(document).on('click', '#btn-delete', function() {

                if (id === '') {
                    showAlertError()
                    return
                }
                let confirmation = confirm("Apakah anda yakin menghapus data?");
                if (confirmation) {
                    fetch(`${id}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'content-type': 'applications/json',
                            'X-CSRF-TOKEN': @json(csrf_token()),
                        }
                    }).then(res => {
                        if (!res.ok) {
                            return res.json().then((errorData) => {
                                throw errorData; // Pass error data to the catch block
                            });
                        }
                        return res.json()
                    }).then(d => {
                        location.reload()

                    }).catch(e => {
                        console.error({
                            e
                        });
                        toastr.error(e.errors[0]);

                    })
                }
            })
            $(document).on('click', '#btn-logout', function() {

                fetch(`/logout`, {
                    method: 'POST',
                    headers: {
                        'content-type': 'applications/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    }
                }).then(res => {
                    if (!res.ok) {
                        return res.json().then((errorData) => {
                            throw errorData; // Pass error data to the catch block
                        });
                    }
                    return res.json()
                }).then(d => {
                    location.reload()
                }).catch(e => {
                    console.log({
                        e
                    });

                })

            })
        })
    </script>
@endauth
@if (session('success-status'))
    <script>
        const message = @json(session('success-status'));
        toastr.success(message);
    </script>
@endif

@if (session('error-status'))
    <script>
        const error = @json(session('error-status'));
        toastr.error(error);
    </script>
@endif

@stack('script')

</html>
