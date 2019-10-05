<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('favicon.png') }}" rel="icon">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />


</head>
<body>
<div id="app">
    Loading...
</div>

<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
