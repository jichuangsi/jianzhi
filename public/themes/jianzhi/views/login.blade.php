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
           </span><input type="text" name="username" placeholder="请输入用户名/手机" onkeydown='clearError(this)'>           		
        </div>
        <div class="ipt">
           <span class="iconfont icon-mima">
           </span>
           <input type="password" name="password" placeholder="请输入密码" onkeydown='clearError(this)'>  
           		
        </div>
        		@if($errors->first('username'))
        			<div style="padding-left:10%">
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('username') !!}</p>
        			</div>
        		@endif
        		@if($errors->first('password'))
        			<div style="padding-left:10%">
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('password') !!}</p>
        			</div>
        		@endif
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
        $(function(){
			if(isWeiXin()){				
				let code = window.location.href
				if(code.indexOf('?code=')>-1&&code.indexOf('&state=')>-1){
					//$("body").mLoading({text:"微信信息同步中。。。"});//显示loading组件
					code = code.split('?')[1].split('&')[0].split('=')[1]
					let appid = "{{Theme::get('weixin_config')['wx_appid']}}";
					let secret = "{{Theme::get('weixin_config')['wx_secret']}}";
					// 获取code
					$.ajax({
						url:'https://api.weixin.qq.com/sns/oauth2/access_token?appid='+appid+'&secret='+secret+'&code='+code+'&grant_type=authorization_code',
						dataType:'json',
						type:'get',
						success:function(res){
							console.log(res)
							$.ajax({
								url:'https://api.weixin.qq.com/sns/userinfo?access_token='+res.access_token+'&openid='+res.openid+'&lang=zh_CN',
								dataType:'json',
								type:'get',
								success:function(res){
									console.log(res)
									// res.headimgurl 头像
									if(res.openid){
										$("body").mLoading({text:"微信自动登录中。。。"});//显示loading组件
										var url = '/jz/ajaxCheckOpenid';
										var data = {'_token': '{{ csrf_token() }}','openid': res.openid};
										$.post(url,data,function(ret,status,xhr){
											console.log(ret);
							                console.log(status);
											if(status==='success'){
												if(ret&&!ret.errMsg){
													if(ret.mobile){
														var form = document.createElement('form');
														form.action = 'jz/wxlogin';
														form.method = 'POST';
														var chk = document.createElement('input');
														chk.type = 'hidden'; 
														chk.name = 'mobile';    
														chk.value = ret.mobile;    
														form.appendChild(chk);  
														var token = document.createElement('input');
														token.type = 'hidden'; 
														token.name = '_token';    
														token.value = "{{ csrf_token() }}";    
														form.appendChild(token); 
														$(document.body).append(form);    
														form.submit();
														document.body.removeChild(form);
													}else{
														var weixinUser = {'openid':res.openid,'nickname':res.nickname,'headimgurl':res.headimgurl};
														sessionStorage.setItem("wx_user",JSON.stringify(weixinUser));
													}
												}else{
													if(ret.errMsg) popUpMessage(ret.errMsg);
												}

											}

											$("body").mLoading("hide");//隐藏loading组件
										});
									}								
								},
					            error: function (data, status, e) {
					                // 失败
					            	popUpMessage('获取微信信息失败！');
					            },
					            complete: function(){
					            	//$("body").mLoading("hide");//隐藏loading组件
					            }
							})
						},
			            error: function (data, status, e) {
			                // 失败
			            	popUpMessage('获取微信信息失败！');
			            }
					})
				}				
			}
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
    {!! Theme::asset()->container('specific-js')->usePath()->add('mloading-js', 'libs/jquery.mloading.js') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('mloading-css', 'libs/jquery.mloading.css') !!}