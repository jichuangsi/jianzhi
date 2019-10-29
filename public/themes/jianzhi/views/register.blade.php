	<div class="logo">
        <img src="/themes/jianzhi/assets/images/微信图片_20191009141254.png" alt="">
    </div>
    <form class="registerform" method="post" action="{!! url('jz/register') !!}" onsubmit="return validate()">
    	{!! csrf_field() !!}
        <div class="ipt">
           <span class="iconfont icon-yonghu"></span><input type="text" name="username" value="{{old('username')}}" placeholder="请输入用户名" ajaxurl="{!! url('checkUserName') !!}" datatype="*4-15" nullmsg="请输入用户名" errormsg="用户名长度为4到15位字符">
       	</div>
        <div class="ipt">
           <span class="iconfont icon-youxiang"></span><input type="number" name="mobile" value="{{old('mobile')}}"  placeholder="请输入手机号" ajaxurl="{!! url('checkMobile') !!}" datatype="m" nullmsg="请输入手机号" errormsg="手机号格式不对！">
        </div>
        <div class="ipt">
           <span class="iconfont icon-mima"></span><input type="password" name="password" placeholder="请输入密码" datatype="*6-16" nullmsg="请输入密码" errormsg="密码长度为6-16位字符">
        </div>
        <div class="ipt">
           <span class="iconfont icon-mima"></span><input type="password" name="confirmPassword" placeholder="请输入确认密码" datatype="*" recheck="password" nullmsg="请输入确认密码" errormsg="两次密码不一致">
        </div>
        <div class="bs">
            <div class="ipt" onclick="jn()">
                <div>技能选择<em class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></em></div>
            </div>
            <input type="hidden" name="skill">
        </div>
         <div class="text">
                为了确保您的账户提现功能,请使用银行卡预留手机号注册
         </div>
         <div class="radio">
         	    <input type="checkbox" name="agree" checked="checked" datatype="*" nullmsg="勾选即表示您已同意">
         		勾选即表示您已同意
         </div>
         <div class="xy">
             	<span onclick="xy_one()">《平台注册认证协议》</span>
                <span onclick="xy_two()">《平台合作伙伴协议协议》</span>
                <span onclick="xy_three()">《平台接包须知》</span>
                <span onclick="xy_four()">《平台任务承揽协议》</span>
         </div>
        <div class="radio">
            <!-- <div class="radio_check" onclick="radio_check(this)"><span><em class="iconfont icon-dagou"></em></span>个人注册</div>
            <div onclick="radio_check(this)"><span></span>企业注册</div> -->
            <label><input name="type" type="radio" value="1" onclick="radio_check(this)" checked /><span class="radio_check iconfont"></span>个人注册 </label> 
        	<label><input name="type" type="radio" value="2" onclick="radio_check(this)" /><span class="iconfont"></span>企业注册</label> 
        </div>
        <button class="btn" type="submit">注册</button>
    </form>
    <div class="tishi">
        已经有账号了？ <a href="{!! url('/') !!}">点击此登录</a>
    </div>
    <div class="xybj">
        <div class="xybox">
            <div class="none" onclick="xy_none()">x</div>
            <iframe id="zc_one" src="{!! url('jz/agreement') !!}" frameborder="0"></iframe>
            <iframe id="zc_two" src="{!! url('jz/partner') !!}" frameborder="0"></iframe>
            <iframe id="zc_three" src="{!! url('jz/notice') !!}" frameborder="0"></iframe>
            <iframe id="zc_four" src="{!! url('jz/taskContract') !!}" frameborder="0"></iframe>
        </div>
    </div>
    
    <div class="jnbj">
        <div class="jnbox">
            <div class="li">
                <div class="left">技能选择</div>
                <div class="right jnbtn iconfont icon-dagou" onclick="jn_btn()"></div>
            </div>
                @foreach($category_all as $v)
                	<div class="li">
                	<div class="left" data-id="{{ $v['id'] }}">{{ $v['name'] }}:</div>
                	<div class="right">
                	@if(isset($v['children_task']))
                    	@foreach($v['children_task'] as $sub)
                    		<span data-id="{{ $sub['id'] }}">{{ $sub['name'] }}</span>
                    	@endforeach
                	@endif
                	</div>
                	</div>
                @endforeach
            </div>
        </div>
    </div>
    <script>    
    var register = $(".registerform").Validform({
        tiptype:3,
        label:".label",
        showAllError:true,
        ajaxPost:false,
    });

    register.eq(0).config({
        ajaxurl:{
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }
    });
    
    function validate(){
        // window.location.href = './login.html'
        if($("input[name='password']").val() == $("input[name='confirmPassword']").val()){
            return true;
        }else{
        	popUpMessage("密码不一致");
            return false;
        }
    }
    function radio_check(val){
        if($(val).siblings()[0].className.indexOf('radio_check') != -1){
            return
        }else {
            $('label >span').removeClass('radio_check')
            $(val).siblings().addClass('radio_check')
            if($(val).val() == '2'){
                $('.bs').children().remove()
            }else{
                $('.bs').append('<div class="ipt" onclick="jn()"><div>技能选择<em class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></em></div></div>')
            }
        }
    }
    function radio_check_xy(val){
        if(val.className.indexOf('radio_check') != -1){
            $(val).removeClass('radio_check')
            $(val).find('em').remove()
        }else {
            $(val).addClass('radio_check')
            $(val).find('span').append('<em class="iconfont icon-dagou"></em>')
        }
    }
    function xy_one(){
    	$('.xybj').css('display','block');
    	$('.xybj').find('iframe').css('display','none');
    	$('#zc_one').css('display','block')
    	setTimeout(function(){
    		$('.xybox').css('bottom','10%');
    	},500)
    }
    function xy_two(){
    	$('.xybj').css('display','block');
    	$('.xybj').find('iframe').css('display','none');
    	$('#zc_two').css('display','block')
    	setTimeout(function(){
    		$('.xybox').css('bottom','10%');
    	},500)
    }
    function xy_three(){
    	$('.xybj').css('display','block');
    	$('.xybj').find('iframe').css('display','none');
    	$('#zc_three').css('display','block')
    	setTimeout(function(){
    		$('.xybox').css('bottom','10%');
    	},500)
    }
    function xy_four(){
    	$('.xybj').css('display','block');
    	$('.xybj').find('iframe').css('display','none');
    	$('#zc_four').css('display','block')
    	setTimeout(function(){
    		$('.xybox').css('bottom','10%');
    	},500)
    }
    function xy_none(){
        $('.xybox').css('bottom','100%');
        setTimeout(function(){
            $('.xybj').css('display','none');
        },500)
    }
    function jn() {
        $('.jnbj').css('display','block');
        setTimeout(function(){
            $('.jnbox').css('bottom','0%');
        },500)
    }
    function jn_btn(){
    	if($('.jn_check').length>15){
    		popUpMessage('一个用户最多只能有15个标签！');
       		return;
        }  
        
        $('.jnbox').css('bottom','100%');
        setTimeout(function(){
            $('.jnbj').css('display','none');
        },500)
        var a = ''   
        for(let i =0;i<$('.jn_check').length;i++){
            a+=$('.jn_check').eq(i).attr('data-id') + ','
        }
        a = a.substr(0, a.length-1);
        $('.bs').attr('data-id',a)
        $("input[name='skill']").val(a)
    }
    $('.jnbox').find('.li').find('.right>span').click(function(){
        if(this.className.indexOf('jn_check') != -1){
            $(this).removeClass('jn_check')
            $(this).find('em').remove()
            $(this).find('i').remove()
        }else {
            $(this).addClass('jn_check')
            $(this).append('<em></em>')
            $(this).append('<i class="iconfont icon-dagou"></i>')
        }
    })
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('register-style','style/register.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}