	<div class="top">
        实名认证
        <div class="out iconfont icon-fanhui" onclick="backToMy();"></div>
    </div>
    <div class="center">
        <img src="/themes/jianzhi/assets/images/微信图片_20191009141254.png" alt="">
        @if($authStatus===0)
        <div class="text">
            	实名认证审核中，请用心等待
        </div>
        @elseif($authStatus===1)
        <div class="text">
            	实名认证成功
        </div>
        @elseif($authStatus===2)
        <div class="text">
            	很遗憾您没有实名认证成功，请重新提交信息，或者与我们客服联系
        </div>
        <div class="btn">
            	重新提交认证
        </div>
        @endif
    </div>    
    <script>
    function backToMy(){
    	window.location.href = "{!! url('jz/my') !!}";
    }
    $(".btn").click(function(){
    	window.location.href = "{!! url('jz/user/reAuth') !!}";
    })
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('Result-style','style/Result.css') !!}