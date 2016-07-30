<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta id="token" name="token" value="{{ csrf_token() }}" >
    <title>
        @yield('page_title', 'ANIDB.IO')
    </title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my.css') }}">
</head>
<body>