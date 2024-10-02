<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Palmcare') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/welcome_theme/bootstrap/css/bootstrap.min.css') }}">
    <script type="text/javascript" src="{{ asset('/welcome_theme/bootstrap/js/bootstrap.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('/welcome_theme/custom/style.css') }}" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/welcome_theme/custom/images/palmcare_logo.png') }}">
</head>

<body>
    <main class="area">
        <div class="container-fluid d-flex overflow-hidden p-0 position-relative vh-100">
            <img src="{{ asset('/welcome_theme/custom/images/palmcare_bg.jpeg') }}" class="w-100 background-image">
            <div class="card login-card">
                <div class="row align-items-center justify-content-center text-lg-center text-md-center">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div>

                            <img src="{{ asset('/welcome_theme/custom/images/palmcare_logo.png') }}"
                                class="mb-5 w-50 mx-auto" alt="logo">
                            {{-- input file --}}
                            <livewire:send-data />
                            {{-- input file --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
