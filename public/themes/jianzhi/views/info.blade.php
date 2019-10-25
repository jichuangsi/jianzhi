	<div class="top">
        信息编辑
        <div class="out iconfont icon-fanhui" onclick="backToMy();"></div>
    </div>
    <form action="/jz/user/infoUpdate" method="post" id="uinfoform" enctype="multipart/form-data">
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
            <div class="ipt">银行卡号 <input type="text" placeholder="请输入银行卡号" value=""></div>
        </div>
        <div class="center_box">
            <div class="ipt">
            	<span>
            	手机号
            		@if($errors->first('mobile'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('mobile') !!}</p>
        			@endif
            	</span> 
            	
            	<input type="number" name="mobile" id="mobile" placeholder="请输入手机号" value="{{ $uinfo['mobile'] }}" ></div>
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
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('edit-style','style/edit.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}