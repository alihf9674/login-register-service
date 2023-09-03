<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/bootstraprtl-v4.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    @yield('links')
    <script src="{{asset('js/app.js')}}"></script>
    <title>@yield('title')</title>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')
    @if(session("mustVerifyEmail"))
        <div class="alert alert-danger text-center small">
            @lang('auth.you must verify your email')
        </div>
@endif
<!-- Page Heading -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{--                    {{ $header }}--}}
        </div>
    </header>
    <!-- Page Content -->
    <div class="container">
        @yield('content')
    </div>
</div>
</body>
</html>
