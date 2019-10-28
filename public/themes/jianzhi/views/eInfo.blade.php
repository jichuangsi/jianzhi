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
            </span><input type="text" placeholder="请输入法人" name="owner" id="owner" value="{{ $einfo['owner'] }}" onkeydown='clearError(this)'></div>
            <div class="ipt">            
            <span>
            	联系人
            		@if($errors->first('contactor'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('contactor') !!}</p>
        			@endif
            </span><input type="text" placeholder="请输入联系人" name="contactor" id="contactor" value="{{ $einfo['contactor'] }}" onkeydown='clearError(this)'></div>
            <div class="ipt">
            <span>联系电话
            		@if($errors->first('contactor_mobile'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('contactor_mobile') !!}</p>
        			@endif            
            </span><input type="number" placeholder="请输入联系电话" name="contactor_mobile" id="contactor_mobile" value="{{ $einfo['contactor_mobile'] }}" onkeydown='clearError(this)'></div>
            <div class="ipt">
            <span>企业电话
            			@if($errors->first('phone'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('phone') !!}</p>
            			@endif            		
            	</span><input type="number" placeholder="请输入企业电话"  name="phone" id="phone" value="{{ $einfo['phone'] }}" onkeydown='clearError(this)'></div>
            <div class="img">
               	 营业执照副本
                <div class="img_file">
                    <img src="/themes/jianzhi/assets/images/fm.jpg" alt="">
                    <input type="file" name="business_license" id="business_license"  onchange="zzfile()">
                </div>
            </div>
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
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('enterprise_edit-style','style/enterprise_edit.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}