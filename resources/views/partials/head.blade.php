<head>

<meta charset="utf-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>

@yield('title','Dashboard')

|

{{ config('app.name') }}

</title>

<meta name="description" content="Tradesmart Supplies Limited Portal">

<link rel="icon"
      href="{{ asset('assets/img/favicon/favicon.ico') }}">

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
      rel="stylesheet">

<link rel="stylesheet"
      href="{{ asset('assets/vendor/fonts/boxicons.css') }}">

<link rel="stylesheet"
      href="{{ asset('assets/vendor/css/core.css') }}">

<link rel="stylesheet"
      href="{{ asset('assets/vendor/css/theme-default.css') }}">

<link rel="stylesheet"
      href="{{ asset('assets/css/demo.css') }}">

<link rel="stylesheet"
      href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">

<link rel="stylesheet"
      href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">

<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

<script src="{{ asset('assets/js/config.js') }}"></script>

@stack('styles')

</head>