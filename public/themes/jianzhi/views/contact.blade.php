	<div class="top">
        客服中心
        <div class="out iconfont icon-fanhui" onclick="backToMy();"></div>
    </div>
    <div class="center">
        <img src="/themes/jianzhi/assets/images/serviceCenter2.jpg" alt="">
        <div>客服电话 {!! Theme::get('site_config')['phone'] !!}</div>
         @if(Theme::get('site_config')['site_logo_1'])
            <img src="{!! url(Theme::get('site_config')['site_logo_1'])!!}">
         @else
            <img src="/themes/jianzhi/assets/images/serviceCenter.jpg" alt="">
         @endif   
        <div>长按识别二维码，添加客服微信</div>
        <div>工作时间：09:00~18:00</div>
    </div>
    <script>
    function backToMy(){
    	window.location.href = "{!! url('jz/my') !!}";
    }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('contact-style','style/contact.css') !!}