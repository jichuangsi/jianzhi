	<div class="top">
        个人认证
        <div class="out iconfont icon-fanhui" onclick="backToMy();"></div>
    </div>
    <form action="/jz/user/realnameAuth" method="post" id="authform" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="center">
        <div class="list">
            	<span>
            	真实姓名
            		@if($errors->first('realname'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('realname') !!}</p>
        			@endif
            	</span>            
            <input type="text" name="realname" id="realname" placeholder="请输入真实姓名" onkeydown='clearError(this)'>
        </div>
        <div class="list">
            <span>
            	身份证号码
            		@if($errors->first('card_number'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('card_number') !!}</p>
        			@endif
            </span>
            <input type="text" name="card_number" id="card_number" placeholder="请输入身份证号码" onkeydown='clearError(this)'>
        </div>
        <div class="list">
            银行卡号<input type="text">
        </div>
        <div class="text">
            请将证件放在平面拍摄，避免歪斜以及反光，以免造成审核不通过，谢谢！建议选择JPG格式照片进行上传，照片小于5M。
        </div>
        <div class="img_box">
            <div class="left_img">
                <div class="img">
                    <img src="/themes/jianzhi/assets/images/zm.jpg" alt="">
                    <input type="file" name="card_front_side" id="card_front_side" onchange="zmfile()">
                </div>
                <span>
                	身份证正面照
                	@if($errors->first('card_front_side'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('card_front_side') !!}</p>
        			@endif
                </span>
            </div>
            <div class="left_img">
                <div class="img">
                    <img src="/themes/jianzhi/assets/images/fm.jpg" alt="">
                    <input type="file" name="card_back_dside" id="card_back_dside" onchange="fmfile()">
                </div>
                	<span>
                		身份证反面照
                		@if($errors->first('card_back_dside'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('card_back_dside') !!}</p>
            			@endif
                	</span>
            </div>
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
    function zmfile(){
    	clearError($("#card_front_side").parent());
    	var reads = new FileReader();
    	var f = document.getElementById('card_front_side').files[0];
    	reads.readAsDataURL(f);
    	reads.onload = function(e) {
        	console.log($('#card_front_side').siblings()[0])
        	$('#card_front_side').siblings()[0].src = this.result;        	
    	};
    }
    function fmfile(){
    	clearError($("#card_back_dside").parent());
    	var reads = new FileReader();
    	var f = document.getElementById('card_back_dside').files[0];
    	reads.readAsDataURL(f);
    	reads.onload = function(e) {
        	$('#card_back_dside').siblings()[0].src = this.result;
    	};
    }
    function clearError(obj){
		//console.log(obj);
		$(obj).parent().find('p').remove();
    }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('Authentication-style','style/Authentication.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}