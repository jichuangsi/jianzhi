	<div class="top">
        企业资料
        <div class="out iconfont icon-fanhui" onclick="backToMy()"></div>
    </div>
    <form action="/jz/user/einfoUpdate" method="post" id="einfoform" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="center">
        <div class="center_box">
            <div class="title">
                认证信息
            </div>
            <div>企业名称：{{ $einfo['company_name'] }}</div>
            <div>纳税人识别号：{{ $einfo['tax_code'] }}</div>
        </div>
        <div class="center_box">
            <div class="title">
                账户信息
            </div>
            <div class="ipt">
            <span>
            	企业法人
            		@if($errors->first('owner'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('owner') !!}</p>
        			@endif
            </span><input type="text" placeholder="请输入法人" name="owner" id="owner" @if(old('owner')) value="{{ old('owner') }}" @else value="{{ $einfo['owner'] }}" @endif onkeydown='clearError(this)'></div>
            <div class="ipt">            
            <span>
            	联系人
            		@if($errors->first('contactor'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('contactor') !!}</p>
        			@endif
            </span><input type="text" placeholder="请输入联系人" name="contactor" id="contactor" @if(old('contactor')) value="{{ old('contactor') }}" @else value="{{ $einfo['contactor'] }}" @endif onkeydown='clearError(this)'></div>
            <div class="ipt">
            <span>联系电话
            		@if($errors->first('contactor_mobile'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('contactor_mobile') !!}</p>
        			@endif            
            </span><input type="number" placeholder="请输入联系电话" name="contactor_mobile" id="contactor_mobile" @if(old('contactor_mobile')) value="{{ old('contactor_mobile') }}" @else value="{{ $einfo['contactor_mobile'] }}" @endif onkeydown='clearError(this)'></div>
            <div class="ipt">
            <span>企业电话
            			@if($errors->first('phone'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('phone') !!}</p>
            			@endif            		
            	</span><input type="number" placeholder="请输入企业电话"  name="phone" id="phone" @if(old('phone')) value="{{ old('phone') }}" @else value="{{ $einfo['phone'] }}" @endif onkeydown='clearError(this)'></div>
            <div class="ipt">
            <span style="width: 30%;">验证码
            		@if($errors->first('code'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('code') !!}</p>
        			@endif
            </span><input style="text-align:left;" type="number" name="code" placeholder="请输入验证码" onkeydown='clearError(this)'><em onclick="yzm(this)">获取验证码</em>
            </div>
            <div class="img">
               	 营业执照副本
                <div class="img_file">
                	@if($einfo['business_license'])
                		<img src="{!! url($einfo['business_license']) !!}" alt="">
                	@else
                		<img src="/themes/jianzhi/assets/images/fm.jpg" alt="">
                	@endif                    
                    <input type="file" name="business_license" id="business_license"  onchange="zzfile()">
                </div>
            </div>
            <input type="hidden" name="mobile" value="{{$mobile}}"/>
        </div>
    </div>
    <button class="btn" type="submit">保存</button>
    </form>
    <script type="text/javascript">
    var einfoform=$("#einfoform").Validform({
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
    function zzfile(){
    	var reads = new FileReader();
    	var f = document.getElementById('business_license').files[0];
    	reads.readAsDataURL(f);
    	reads.onload = function(e) {
        	console.log($('#business_license').siblings()[0])
        	$('#business_license').siblings()[0].src = this.result;        	
    	};
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
    {!! Theme::asset()->container('specific-css')->usepath()->add('enterprise_edit-style','style/enterprise_edit.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}