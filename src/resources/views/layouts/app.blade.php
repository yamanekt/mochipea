<!DOCTYPE html>
<html lang="ja"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>@yield('title') | もちぺあ</title>@vite(['resources/css/common.css'])@stack('css')<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">@stack('head')</head><body>@include('partials.common-header')@yield('content')@include('partials.common-footer')@stack('scripts')</body></html>

