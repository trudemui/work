<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 
<head>
    <meta charset="UTF-8">
    <title>Una JAS有機書類作成システム</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width" >
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>
 
<body class="">
    <div id="app">
        <header-component class="fixed w-full z-index-apex"></header-component>
        <div class="flex w-full">
            <sidebar-component></sidebar-component>
            <router-view class="relative w-full min-h-screen mt-12 ml-0 xl:ml-80 bg-white-light" />
        </div>
    </div>
    <script src="{{ mix('/js/app.js') }}" defer></script>
</body>
 
</html>