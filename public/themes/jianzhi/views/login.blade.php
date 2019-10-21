	<div class="logo">
        <img src="/themes/jianzhi/assets/images/微信图片_20191009141254.png" alt="">
    </div>
    <form method="post" action="{!! url('jz/login') !!}" id="loginform">
    	{!! csrf_field() !!}
        <!-- <div class="ipt">
           <span class="iconfont icon-yonghu"></span><input type="text" placeholder="请输入用户名">
        </div>
        <div class="ipt">
           <span class="iconfont icon-shouji"></span><input type="text" placeholder="请输入手机号码">
        </div> -->
        <div class="ipt">
           <span class="iconfont icon-yonghu">           		           
           </span><input type="text" name="username" placeholder="请输入用户名/邮箱" onkeydown='clearError(this)'>
           		@if($errors->first('username'))
        			<p class="Validform_checktip Validform_wrong">{!! $errors->first('username') !!}</p>
        		@endif
        </div>
        <div class="ipt">
           <span class="iconfont icon-mima">
           </span><input type="password" name="password" placeholder="请输入密码" onkeydown='clearError(this)'>           		
           		@if($errors->first('password'))
        			<p class="Validform_checktip Validform_wrong">{!! $errors->first('password') !!}</p>
        		@endif            
        </div>
        <!-- <div class="btn" onclick="btn()">登录</div> -->
        <button class="btn" type="submit">登录</button>
    </form>
    <div class="tishi">
        还没有账号？ <a href="{!! url('jz/register') !!}">立即注册</a>
    </div>
    <script>
        var loginform=$("#loginform").Validform({
            tiptype:3,
            label:".label",
            showAllError:true,
            ajaxPost:false,
            dataType:{
                'positive':/^[1-9]\d*$/,
            },
        });
        function clearError(obj){
    		//console.log(obj);
    		$(obj).parent().find('p').remove();
        }
        function btn(){
            window.location.href = './index.html'
        }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('login-style','style/login.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}