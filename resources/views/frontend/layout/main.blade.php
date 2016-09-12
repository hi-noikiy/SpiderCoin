<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>{{ $page_title or " DingTou" }}</title>
    @yield('before.css')
    {{--<link rel="stylesheet" type="text/css" href="/assets/frontend/plugins/pace/pace.min.css">--}}
    <link rel="stylesheet" type="text/css" href="{{ elixir('assets/frontend/css/app.min.css') }}">
    @yield('after.css')
</head>

<body>
@include('frontend.layout.header')

<div class="container-fluid">
    <div class="content-wrapper">
        <section class="content-header">
            @include('frontend.layout.errors')
            @include('frontend.layout.success')
        </section>
        <section class="content">
            @yield('content')
        </section>
    </div>
    @include('frontend.layout.footer')
</div>

@yield('before.js')
<script type="text/javascript" src="{{ elixir('assets/frontend/js/app.min.js') }}"></script>
{{--<script type="text/javascript" src="/assets/frontend/plugins/pace/pace.min.js"></script>--}}
{{--<script type="text/javascript" src="/assets/frontend/plugins/slimScroll/jquery.slimscroll.min.js"></script>--}}
<script type="text/javascript">
    $(function () {
        $('.select2').select2();
        $('#created_at').daterangepicker({timePickerIncrement: 30, format: 'YYYY/MM/DD HH:mm:ss'});
    });
</script>
@yield('after.js')
</body>
</html>
