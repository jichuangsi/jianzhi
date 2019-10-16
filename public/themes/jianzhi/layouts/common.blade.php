<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{!! Theme::get('title') !!}</title>
    <meta name="keywords" content="{!! Theme::get('keywords') !!}">
    <meta name="description" content="{!! Theme::get('description') !!}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">--}}
    @if(Theme::get('basis_config')['css_adaptive'] == 1)
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
    @else
        <meta name="viewport" content="initial-scale=0.1">
    @endif
    <meta property="qc:admins" content="232452016063535256654" />
    <meta property="wb:webmaster" content="19a842dd7cc33de3" />
    <link rel="shortcut icon" href="{{ Theme::asset()->url('images/favicon.ico') }}" />
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="/themes/jianzhi/assets/style/reset.css">
    <link rel="stylesheet" href="/themes/jianzhi/assets/font_pns0nx4ud0l/iconfont.css">
    {!! Theme::asset()->container('specific-css')->styles() !!}
    {!! Theme::asset()->container('custom-css')->styles() !!}
    <script src="/themes/jianzhi/assets/libs/jquery.min.js"></script>
    <script src="/themes/jianzhi/assets/js/reset.js"></script>
    <script src="/themes/jianzhi/assets/js/tools.js"></script>
    {!! Theme::asset()->container('specific-js')->scripts() !!}
	{!! Theme::asset()->container('custom-js')->scripts() !!}
</head>
<body>

{!! Theme::content() !!}

</body>