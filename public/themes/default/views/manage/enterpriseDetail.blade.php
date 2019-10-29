	<style>
        .bigimg {
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: none;
            position: fixed;
            left: 0px;
            top: 0px;
            justify-content: center;
            align-items: center;
            overflow: auto;
            z-index: 9999;
        }
        .bigimg img {
            width: 60%;
            /* height: 80%;
            transform: scale(1.5); */
            /* 放大倍数 */
        }
    </style>
{{--<div class="well">
	<h4 >编辑企业资料</h4>
</div>--}}
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">编辑企业用户资料</h3>

<form class="form-horizontal registerform" role="form" action="{!! url('manage/enterpriseEdit') !!}" method="post" enctype="multipart/form-data" onsubmit="return validate()">
    {!! csrf_field() !!}
	<div class="g-backrealdetails clearfix bor-border">
		<input type="hidden" name="uid" value="{!! $info['id'] !!}">
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 用户名：</p>
			<p class="col-sm-4">
				<input type="text" disabled="disabled" id="form-field-1" class="col-xs-10 col-sm-5" value="{!! $info['name'] !!}">				
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 注册手机：</p>
			<p class="col-sm-4">
				<input type="number" name="mobile" id="form-field-1"  class="col-xs-10 col-sm-5" datatype="m" nullmsg="请输入手机号码" errormsg="手机号码长度为11位数字" value="{!! $info['mobile'] !!}">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
						@if($errors->first('mobile'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('mobile') !!}</p>
            			@endif	
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业名称：</p>
			<p class="col-sm-4">
				<input type="text" name="company_name" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['company_name'] !!}"  datatype="*" nullmsg="请输入企业名称">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p  class="col-sm-1 control-label no-padding-left">所在地：</p>
			<div class="col-sm-5">
				<div class="row">
					<p class="col-sm-6">
						<select name="province" id="province" class="form-control validform-select Validform_error" onchange="getZone(this.value, 'city');">
							<option value="">请选择省份</option>
							@foreach($province as $item)
								<option @if($info['province'] == $item['id'])selected="selected"@endif value="{!! $item['id'] !!}">{!! $item['name'] !!}</option>
							@endforeach
						</select>
					</p>
					<p class="col-sm-6">
						<select class="form-control  validform-select" name="city" id="city" onchange="getZone(this.value, 'area');">
							@if(empty($city))
							<option value="">请选择城市</option>
							@else
							<option selected="selected" value="{!! $info['city'] !!}">{!! $city !!}</option>
							@endif
						</select>
					</p>
				</div>
			</div>

			<div class="col-sm-3">
				<select class="form-control  validform-select" name="area" id="area">
					@if(empty($area))
						<option value="">请选择区域</option>
					@else
						<option selected="selected" value="{!! $info['area'] !!}">{!! $area !!}</option>
					@endif
				</select>
			</div>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 纳税人识别号：</p>
			<p class="col-sm-4">
				<input type="text" name="tax_code" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['tax_code'] !!}" datatype="*" nullmsg="请输入纳税人识别号">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 开户行：</p>
			<p class="col-sm-4">
				<input type="text" name="bank" id="form-field-1"   class="col-xs-10 col-sm-5" value="{!! $info['bank'] !!}" datatype="*" nullmsg="请输入开户行">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业账号：</p>
			<p class="col-sm-4">
				<input type="number" name="account" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['account'] !!}"  datatype="*" nullmsg="请输入企业账号">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业法人：</p>
			<p class="col-sm-4">
				<input type="text" name="owner" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['owner'] !!}">
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业电话：</p>
			<p class="col-sm-4">
				<input type="number" name="phone" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['phone'] !!}">
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 联系人：</p>
			<p class="col-sm-4">
				<input type="text" name="contactor" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['contactor'] !!}" datatype="*2-5" nullmsg="请输入真实姓名" errormsg="用户名长度为2到5个中文">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 联系人电话：</p>
			<p class="col-sm-4">
				<input type="number" name="contactor_mobile" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['contactor_mobile'] !!}" datatype="m" nullmsg="请输入联系人电话" errormsg="联系人电话输入有误">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业邮箱：</p>
			<p class="col-sm-4">
				<input type="text" name="company_email" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['company_email'] !!}" datatype="e" nullmsg="请输入企业邮箱" errormsg="邮箱地址格式不对！">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1">企业地址：</p>
			<p class="col-sm-4">
				<input type="text" name="address" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['address'] !!}" datatype="*" nullmsg="请输入企业地址">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1">认证状态：</p>
			<p class="col-sm-4">
				@if(isset($info['status']))
					@if($info['status']===0)
						待审核
					@elseif($info['status']===1)
						已认证
					@elseif($info['status']===2)
						已拒绝
					@elseif($info['status']===NULL)
						未认证
					@else
						未知状态
					@endif
				@endif
			</p>
		</div>
		
		<!-- <div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 出生日期：</p>
			<div class="col-sm-4">
				<p class="input-group input-group-sm col-xs-10 col-sm-5">
					<input type="text" id="datepicker" class="form-control hasDatepicker">
					<span class="input-group-addon">
						<i class="ace-icon fa fa-calendar"></i>
					</span>
				</p>
			</div>
		</div> -->
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 密&nbsp;&nbsp;码：</p>
			<p class="col-sm-5">
				<input type="password" id="form-field-1"  class="col-xs-10 col-sm-5" name="password">
				（提示：若填写则重置用户密码）
				<!-- <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i>（提示：更改此密码不会修改用户的支付密码）</span> -->
			</p>
		</div>
		
			<div class="bankAuth-bottom clearfix col-xs-12">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 营业执副本：</label>
                <div class="col-sm-5">
                	<img alt="营业执副本" src="{!! url($info['business_license']) !!}"  onclick="bigimg(this)">                    
                </div>
                <div class="col-sm-5">
                	<input type="file" name="business_license" id="business_license">                    
                </div>
                <div class="col-sm-5"></div>
                		@if($errors->first('business_license'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('business_license') !!}</p>
            			@endif
            </div> 
			          
		
		{{--<div class="form-group text-center">
				<label class="col-sm-1 control-label no-padding-left" for="form-field-1"></label>
				<div class="col-sm-3 text-left">
					　<button class="btn btn-primary btn-sm">提交</button>
				</div>
		</div>--}}
		<div class="col-xs-12">
			<div class="clearfix row bg-backf5 padding20 mg-margin12">
				<div class="col-xs-12">
					<div class="col-md-1 text-right"></div>
					<div class="col-md-10">
						<button class="btn btn-primary btn-sm" type="submit">提交</button>
						@if(isset($info['status']))
							@if($info['status']===0)
								<a class="btn btn-sm btn-success" style="margin-left: 30px" href="{!! url('manage/enterpriseAuthPass/'.$info["id"]) !!}">
                                    <!-- <i class="fa fa-check bigger-120"></i> -->通过
                                </a>
                                <a class="btn btn-sm btn-danger" style="margin-left: 30px" href="{!! url('manage/enterpriseAuthReject/' . $info["id"]) !!}">
                                    <!-- <i class="fa fa-minus-circle bigger-120"></i> -->拒绝
                                </a>
							@endif							
						@endif
						<div class="btn btn-primary btn-sm" style="margin-left: 30px" onclick="window.location.href = '{!! url('manage/enterpriseList') !!}'">返回</div>
					</div>
				</div>
			</div>
		</div>
		<div class="space col-xs-12"></div>
		<!-- <div class="col-xs-12">
			<div class="col-md-1 text-right"></div>
			<div class="col-md-10"><a href="">上一项</a>　　<a href="">下一项</a></div>
		</div> -->
		<div class="col-xs-12 space">

		</div>
	</div>
</form>
	<div class="bigimg" onclick="$('.bigimg').css('display','none')">
        <img src="" alt="">
    </div>
	<script>
	function validate(){
		if($("input[name='account']").val()){
			if(luhnCheck($("input[name='account']").val())){
				return true;
			}else{
				popUpMessage('银行卡号校验失败！');
				return false;
			}
		}
		return true;
	}
	function bigimg(val){
        $('.bigimg > img')[0].src = val.src
        $('.bigimg').css('display','flex')
    }
    </script>
{!! Theme::asset()->container('custom-css')->usePath()->add('back-stage-css', 'css/backstage/backstage.css') !!}
{!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}
{!! Theme::asset()->container('specific-css')->usePath()->add('datepicker-css', 'plugins/ace/css/datepicker.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('datepicker-js', 'plugins/ace/js/date-time/bootstrap-datepicker.min.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('userManage-js', 'js/userManage.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('main-js', 'js/main.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('tools-js', 'js/tools.js') !!}
