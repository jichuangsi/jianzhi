	<div class="logo">
        <img src="/themes/jianzhi/assets/images/微信图片_20191009141254.png" alt="">
    </div>
    <form method="post" action="{!! url('jz/login') !!}" >
    	{!! csrf_field() !!}
        <!-- <div class="ipt">
           <span class="iconfont icon-yonghu"></span><input type="text" placeholder="请输入用户名">
        </div>
        <div class="ipt">
           <span class="iconfont icon-shouji"></span><input type="text" placeholder="请输入手机号码">
        </div> -->
        <div class="ipt">
           <span class="iconfont icon-yonghu"></span><input type="text" name="username" placeholder="请输入用户名/邮箱">
        </div>
        <div class="ipt">
           <span class="iconfont icon-mima"></span><input type="password" name="password" placeholder="请输入密码">
        </div>
        <!-- <div class="btn" onclick="btn()">登录</div> -->
        <button class="btn" type="submit">登录</button>
    </form>
    <div class="tishi">
        还没有账号？ <a href="{!! url('jz/register') !!}">立即注册</a>
    </div>
    <script>
        function btn(){
            window.location.href = './index.html'
        }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('login-style','style/login.css') !!}