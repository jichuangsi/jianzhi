	<div class="top">
        信息编辑
        <div class="out iconfont icon-fanhui" onclick="backToMy();"></div>
    </div>
    <form action="/jz/user/infoUpdate" method="post" id="uinfoform" enctype="multipart/form-data" onsubmit='return validate()'>
    {!! csrf_field() !!}
    <div class="center">
        <div class="center_box">
            <div class="title">
                认证信息
            </div>
            <div>真实姓名：{{ $uinfo['realname'] }}</div>
            <div>身份证号：{{ $uinfo['card_number'] }}</div>
        </div>
        <div class="center_box">
            <div class="title">
                账户信息
            </div>
            <div class="ipt">银行卡号 <input type="number" name="account" id="account" placeholder="请输入银行卡号" @if(old('account')) value="{{ old('account') }}" @else value="{{ $uinfo['account'] }}" @endif></div>
        </div>
        <div class="center_box">
            <div class="ipt">
            	<span>
            	手机号
            		@if($errors->first('mobile'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('mobile') !!}</p>
        			@endif
            	</span> 
            	
            	<input type="number" name="mobile" id="mobile" placeholder="请输入手机号" @if(old('mobile')) value="{{ old('mobile') }}" @else value="{{ $uinfo['mobile'] }}" @endif onkeydown='clearError(this)'></div>
        </div>
        <div class="center_box">
        	<div class="ipt">
        		<span style="width: 30%">
        		验证码
        			@if($errors->first('code'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('code') !!}</p>
        			@endif
        		</span>
        		
        		<input type="number" name="code" placeholder="请输入验证码" onkeydown='clearError(this)'><em onclick="yzm(this)">获取验证码</em>
        	</div>
        </div>
    </div>
    <button class="btn" type="submit">
        确认修改
    </button>
    </form>
    <script type="text/javascript">
    var uinfoform=$("#uinfoform").Validform({
        tiptype:3,
        label:".label",
        showAllError:true,
        ajaxPost:false,
        dataType:{
            'positive':/^[1-9]\d*$/,
        },
    });
    function backToMy(){
    	window.location.href = "{!! url('jz/my') !!}";
    }
    function validate(){
		if($("#account").val()){
			if(luhnCheck($("#account").val())){
				return true;
			}else{
				popUpMessage('银行卡号校验失败！');
				return false;
			}
		}
		return true;
    }
    function clearError(obj){
		//console.log(obj);
		$(obj).parent().find('p').remove();
    }
    function yzm(val){ 
    	if(val.className != 'yzm'){
        	let m = 60;
        	$(val).addClass('yzm')
        	$(val).text('60s后重新获取')
        	sendCode();
    		var timer = setInterval(function(){
            	m--;
            	$(val).text(m+'s后重新获取')
        		if(m == 0){
        			$(val).removeClass('yzm')
        			$(val).text('获取验证码')
        			clearInterval(timer)
        		}
    		},1000)
    	}
    }
    function sendCode(){
        if(!$("input[name='mobile']").val()){
			popUpMessage('请输入手机号！');
			return;
        }
    	var url = '/jz/user/ajaxSendCode';
		var data = {'_token': '{{ csrf_token() }}','mobile': $("input[name='mobile']").val()};
		$.post(url,data,function(ret,status,xhr){
			console.log(ret);
            console.log(status);
            if(ret&&ret.Code==="OK"){
            	popUpMessage('验证码发送成功！');
            }else{
            	popUpMessage('验证码发送失败！');
            }
        });
    }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('edit-style','style/edit.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}