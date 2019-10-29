{{--<div class="well">
	<h4 >个人用户详情</h4>
</div>--}}
<style>
        .jnbox {
            width: 100%;
            font-size: 14px;
            padding-bottom: 20px;
        }
        .jn_check {
            width: 100%;
            display: flex;
            line-height: 36px;
            margin-bottom: 10px;
        }
        .jn_check div:first-child {
            padding-left: 20px;
        }
        .jn_check div:last-child {
            flex: 1;
        }
        .jn span {
            background-color: #3da7f4;
            display: inline-block;
            margin-right: 40px;
            position: relative;
            padding-left: 10px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .jn span em {
            font-style: normal;
            padding: 0px 10px;
        }
        .jn span em:hover {
            background-color: #0094ff;
        }
        .jn span i {
            display: inline-block;
            border: 18px solid #3da7f4;
            position: absolute;
            right: -36px;
            top: 0px;
            border-color:transparent transparent transparent #3da7f4;
        }
        .jn_box {
            width: 99%;
            margin: 0 auto;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #f3f3f3;
        }
        .jn_li {
            width: 100%;
        }
        .jn_li .title {
            width: 100%;
            background-color: #f3f3f3;
            line-height: 46px;
            padding: 0px 20px;
        }
        .jn_li .text {
            width: 100%;
            background-color: #fff;
            line-height: 36px;
            height: 0px;
            overflow: hidden;
        }
        .jn_li .text_check {
            height: auto;
            padding: 20px;
        }
        .jn_li .text span {
            background-color: #f3f3f3;
            display: inline-block;
            margin-right: 40px;
            position: relative;
            padding-left: 10px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .jn_li .text span:hover {
            background-color: #3da7f4;
            color: #fff;
        }
        .jn_li .text span:hover i {
            border-color:transparent transparent transparent #3da7f4;
        }
        .jn_li .text em {
            font-style: normal;
        }
        .jn_li .text i {
            display: inline-block;
            border: 18px solid #f3f3f3;
            position: absolute;
            right: -36px;
            top: 0px;
            border-color:transparent transparent transparent #f3f3f3;
        }
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
            z-index:9999;
        }
        .bigimg img {
            width: 60%;
            /* height: 80%;
            transform: scale(1.5); */
            /* 放大倍数 */
        }
    </style>
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">个人用户详情</h3>
    
	<div class="g-backrealdetails clearfix bor-border">
		<input type="hidden" name="uid" value="{!! $info['id'] !!}">
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 用户名：</p>
			<p class="col-sm-4">
				{{$info['name']}}				
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 注册手机：</p>
			<p class="col-sm-4">
				{{$info['mobile']}}				
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 姓名：</p>
			<p class="col-sm-4">
				{{$info['realname']}}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 身份证号码：</p>
			<p class="col-sm-4">
				{{$info['card_number']}}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 银行卡号：</p>
			<p class="col-sm-4">
				{{$info['account']}}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 认证状态：</p>
			<p class="col-sm-4">
				@if(isset($info['astatus']))
					@if($info['astatus']===0)
						待审核
					@elseif($info['astatus']===1)
						已认证
					@elseif($info['astatus']===2)
						已拒绝
					@elseif($info['astatus']===NULL)
						未认证
					@else
						未知状态
					@endif
				@endif
			</p>
		</div>
			<div class="bankAuth-bottom clearfix col-xs-12">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 身份证正面：</label>
                <div class="col-sm-4">
                    <img alt="身份证正面" src="{!! url($info['card_front_side']) !!}" onclick="bigimg(this)">
                </div>
                	
            </div>
            
            <div class="bankAuth-bottom clearfix col-xs-12">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 身份证反面：</label>
                <div class="col-sm-4">
                    <img alt="身份证反面" src="{!! url($info['card_back_dside']) !!}"  onclick="bigimg(this)">
                </div>
            </div>		
		
			<div class="bankAuth-bottom clearfix col-xs-12">
				<div class="jnbox">
                    <div class="jn_check">
                        <div class="col-sm-1 control-label no-padding-left">技能标签：</div>
                        <div class="jn col-sm-4">
                        	@if(isset($myTags)&&!empty($myTags))
                        		@foreach($myTags as $v1)
                        			<span>{{ $v1['tag_name'] }}<i></i></span>
                        		@endforeach
                        	@endif
                        
                        </div>
                    </div>                    
                </div>
			
			</div>
		
		<div class="col-xs-12">
			<div class="clearfix row bg-backf5 padding20 mg-margin12">
				<div class="col-xs-12">
					<div class="col-md-1 text-right"></div>
					<div class="col-md-10">
						<button class="btn btn-primary btn-sm" onclick="toEdit('{{$info['id']}}')">修改</button>
						<div class="btn btn-primary btn-sm" style="margin-left: 50px" onclick="window.location.href = '{!! url('manage/userList') !!}'">返回</div>
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
			window.location.href = "{!! url('manage/userEdit') !!}" + "/" + id;		
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
