	<div class="top">
        企业认证
        <div class="out iconfont icon-fanhui" onclick="backToMy();"></div>
    </div>
    <form action="/jz/user/enterpriseAuth" method="post" id="authform" enctype="multipart/form-data" onsubmit='return validate()'>
    {!! csrf_field() !!}
    <div class="center">
        <div class="list">
            <span>
            		企业名称
            		@if($errors->first('company_name'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('company_name') !!}</p>
        			@endif
            </span><input type="text" placeholder="请输入企业名称" name="company_name" id="company_name" onkeydown='clearError(this)'>
        </div>
        <div class="list">
            <span>
            	法人
            		@if($errors->first('owner'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('owner') !!}</p>
        			@endif
            </span><input type="text" placeholder="请输入法人"  name="owner" id="owner" onkeydown='clearError(this)'>
        </div>
        <div class="list">
            <span>
            	联系人
            		@if($errors->first('contactor'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('contactor') !!}</p>
        			@endif
            </span><input type="text" placeholder="请输入联系人"  name="contactor" id="contactor" onkeydown='clearError(this)'>
        </div>
        <div class="list">
            <span>联系电话
            		@if($errors->first('contactor_mobile'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('contactor_mobile') !!}</p>
        			@endif            
            </span><input type="number" placeholder="请输入联系人电话" name="contactor_mobile" id="contactor_mobile" onkeydown='clearError(this)'>
        </div>
        <div class="title">开票信息</div>
        <div class="list">
            <span>
            	开户行
            		@if($errors->first('bank'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('bank') !!}</p>
        			@endif
            </span><input type="text" placeholder="请输入企业开户行" name="bank" id="bank" onkeydown='clearError(this)'>
        </div>
        <div class="list">
            <span>
            	账户
            		@if($errors->first('account'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('account') !!}</p>
        			@endif
            </span><input type="number" placeholder="请输入企业账户" name="account" id="account" onkeydown='clearError(this)'>
        </div>
        <div class="list">
            	<span>
            		纳税人识别码
            			@if($errors->first('tax_code'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('tax_code') !!}</p>
            			@endif
            	</span><input type="text" placeholder="请输入纳税人识别码" name="tax_code" id="tax_code" onkeydown='clearError(this)'>
        </div>
        <div class="list">
            	<span>
            		电话
            			@if($errors->first('phone'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('phone') !!}</p>
            			@endif            		
            	</span><input type="number" placeholder="请输入企业电话" name="phone" id="phone" onkeydown='clearError(this)'>
        </div>
        <div class="list">
            <span>
            	地址
            			@if($errors->first('address'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('address') !!}</p>
            			@endif 
            </span><input type="text" placeholder="请输入营业执照地址" name="address" id="address" onkeydown='clearError(this)'>
        </div>
        <div class="img">
                <span>
                	营业执照副本
                	@if($errors->first('business_license'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('business_license') !!}</p>
        			@endif
                </span>
                <div class="img_file">
                    <img src="/themes/jianzhi/assets/images/fm.jpg" alt="">
                    <input type="file" name="business_license" id="business_license"  onchange="zzfile()">
                </div>
            </div>
            <div class="text">
                    请将营业照副本放在平面拍摄，避免歪斜以及反光，以免造成审核不通过，谢谢！请上传不小于1M得不大于5M的照片
            </div>
    </div>
    <button class="btn" type="submit">提交认证</button>
    </form>
    <script>
    var authform=$("#authform").Validform({
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
    	clearError($(".img_file"));
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
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('enterprise_Authentication-style','style/enterprise_Authentication.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}