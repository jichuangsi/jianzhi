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
	<h4 >企业用户详情</h4>
</div>--}}
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">企业用户详情</h3>

	<div class="g-backrealdetails clearfix bor-border">
		<input type="hidden" name="uid" value="{!! $info['id'] !!}">
		<!-- <div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 用户名：</p>
			<p class="col-sm-4">
				{!! $info['name'] !!}				
			</p>
		</div> -->
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 注册手机：</p>
			<p class="col-sm-4">
				{!! $info['mobile'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业名称：</p>
			<p class="col-sm-4">
				{!! $info['company_name'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p  class="col-sm-1 control-label no-padding-left">所在城市：</p>
			<div class="col-sm-4">
				{!! $info['province_name'] !!}{!! $info['city_name'] !!}
			</div>			
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 纳税人识别号：</p>
			<p class="col-sm-4">
				{!! $info['tax_code'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 开户行：</p>
			<p class="col-sm-4">
				{!! $info['bank'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业账号：</p>
			<p class="col-sm-4">
				{!! $info['account'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业法人：</p>
			<p class="col-sm-4">
				{!! $info['owner'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业电话：</p>
			<p class="col-sm-4">
				{!! $info['phone'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 联系人：</p>
			<p class="col-sm-4">
				{!! $info['contactor'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 联系人电话：</p>
			<p class="col-sm-4">
				{!! $info['contactor_mobile'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业邮箱：</p>
			<p class="col-sm-4">
				{!! $info['company_email'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1">企业地址：</p>
			<p class="col-sm-4">
				{!! $info['address'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1">注册时间：</p>
			<p class="col-sm-4">
				{!! $info['u_created_at'] !!}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1">提交认证时间：</p>
			<p class="col-sm-4">
				{!! $info['created_at'] !!}
			</p>
		</div>
			<div class="bankAuth-bottom clearfix col-xs-12">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 营业执副本：</label>
                <div class="col-sm-4">
                    <img alt="营业执副本" src="{!! url($info['business_license']) !!}"  onclick="bigimg(this)">                 
                </div>
            </div> 
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1">审核：</p>
			<p class="col-sm-4">
				@if(isset($info['status']))
    				@if($info['status']!=0)
    					{!! Theme::get('manager') !!}({!! $info['auth_time'] !!})		
    				@endif		
				@endif
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
		
		
		<div class="col-xs-12">
			<div class="clearfix row bg-backf5 padding20 mg-margin12">
				<div class="col-xs-12">
					<div class="col-md-1 text-right"></div>
					<div class="col-md-10">
						<button class="btn btn-primary btn-sm" onclick="toEdit('{{$info['uid']}}')">修改</button>
						<div class="btn btn-primary btn-sm" style="margin-left: 50px" onclick="window.location.href = '{!! url('manage/enterpriseList') !!}'">返回</div>
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
	
	<div class="bigimg" onclick="$('.bigimg').css('display','none')">
        <img src="" alt="">
    </div>
	<script>
	function toEdit(id){
		window.location.href = "{!! url('manage/enterpriseEdit') !!}" + "/" + id;		
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
