<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') | {{ Config::get('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ $assets_url }}/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ $assets_url }}/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{{ $assets_url }}/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="{{ $assets_url }}/plugins/font-awesome/css/font-awesome-4.5.0.min.css">
    <link rel="stylesheet" href="{{ $assets_url }}/bootstrap/css/bootstrap-datetimepicker.min.css">
    <link href="{{$assets_url}}/layui/css/layui.css" rel="stylesheet">
    @yield('css')
</head>
<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper">
@include("admin.header")
@include("admin.menu")
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('content_title')
                <small>@yield('content_title_small')</small>
            </h1>
        </section>
        @yield('content')
    </div>
    <!-- /.content-wrapper -->
    @include("admin.footer")
</div>
<!-- ./wrapper -->


<script src="{{ $assets_url }}/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="{{ $assets_url }}/plugins/jQueryUI/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="{{ $assets_url }}/bootstrap/js/bootstrap.min.js"></script>
<script src="{{ $assets_url }}/js/app.min.js"></script>
<script src="{{ $assets_url }}/js/common.js?t={{ time() }}"></script>
<script src="{{ $assets_url }}/bootstrap/js/bootstrap-datetimepicker.js"></script>
<script src="{{ $assets_url }}/bootstrap/js/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript" src="{{$assets_url}}/layui/layui.all.js"></script>
<link rel="stylesheet"  href="{{ $assets_url}}/plugins/upload-file/upload.css">
<script type="text/javascript" src="{{ $assets_url}}/plugins/upload-file/jQuery.upload.min.js"></script>
@yield('js')


</body>
</html>
