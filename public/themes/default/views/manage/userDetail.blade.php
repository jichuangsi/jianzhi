{{--<div class="well">
	<h4 >编辑个人资料</h4>
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
    </style>
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">编辑个人用户资料</h3>

<form class="form-horizontal registerform" role="form" action="{!! url('manage/userEdit') !!}" method="post" enctype="multipart/form-data">
    {!! csrf_field() !!}
	<div class="g-backrealdetails clearfix bor-border">
		<input type="hidden" name="uid" value="{!! $info['id'] !!}">
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 用户名：</p>
			<p class="col-sm-4">
				<input type="text" disabled="disabled" id="form-field-1" class="col-xs-10 col-sm-5" value="{!! $info['name'] !!}">				
			</p>
		</div>
		<!-- <div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 注册邮箱：</p>
			<p class="col-sm-4">
				<input type="text" disabled="disabled" name="email" id="form-field-1"  class="col-xs-10 col-sm-5" datatype="e" value="{!! $info['email'] !!}">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div> -->
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 真实姓名：</p>
			<p class="col-sm-4">
				<input type="text" name="realname" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['realname'] !!}" datatype="*2-5" nullmsg="请输入真实姓名" errormsg="用户名长度为2到5个中文">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 身份证号码：</p>
			<p class="col-sm-4">
				<input type="text" name="card_number" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['card_number'] !!}" datatype="*15-18" nullmsg="请输入身份证号码" errormsg="身份证号码长度为15-18位字符">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 注册手机：</p>
			<p class="col-sm-4">
				<input type="text" name="mobile" id="form-field-1"   class="col-xs-10 col-sm-5" value="{!! $info['mobile'] !!}" datatype="m" nullmsg="请输入手机号码" errormsg="手机号码长度为11位数字">
				<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>	
						@if($errors->first('mobile'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('mobile') !!}</p>
            			@endif					
			</p>
		</div>
		<!-- <div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> QQ号码：</p>
			<p class="col-sm-4">
				<input type="text" name="qq" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! $info['qq'] !!}">
			</p>
		</div> -->
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
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 身份证正面：</label>
                <div class="col-sm-4">
                    <input type="file" name="card_front_side" id="card_front_side">
                </div>
                	@if($errors->first('card_front_side'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('card_front_side') !!}</p>
        			@endif
            </div>
            
            <div class="bankAuth-bottom clearfix col-xs-12">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 身份证反面：</label>
                <div class="col-sm-4">
                    <input type="file" name="card_back_dside" id="card_back_dside">
                </div>
                		@if($errors->first('card_back_dside'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('card_back_dside') !!}</p>
            			@endif
            </div>
		
		
			<div class="bankAuth-bottom clearfix col-xs-12">
				<div class="jnbox">
                    <div class="jn_check">
                        <div>技能标签：</div>
                        <div class="jn">
                        	@if(isset($skill)&&!empty($skill))
                        		@foreach($skill as $v1)
                        			<span data-id="{{ $v1['cate_id'] }}">{{ $v1['cate_name'] }}<em onclick="del_jn(this)">x</em><i></i></span>
                        		@endforeach
                        	@endif
                        
                        </div>
                    </div>
                    <div class="jn_box">
                    	@foreach($taskCate as $v)
                        	<div class="jn_li">
                        	<div class="title" data-id="{{ $v['id'] }}">{{ $v['name'] }}</div>
                        	<div class="text">
                        	@if(isset($v['children_task']))
                            	@foreach($v['children_task'] as $sub)
                            		<span data-id="{{ $sub['id'] }}"><em>+</em>{{ $sub['name'] }}<i></i></span>
                            	@endforeach
                        	@endif
                        	</div>
                        	</div>
                        @endforeach              
                    </div>  
                    <input type="hidden" name="skill" id="skill"/>
                </div>
			
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
</form>
	<script>
		$(function(){
			var skills = $(".jn_box").children();
			if(skills.length>0){
				$(skills[0]).find(".text").addClass('text_check');
			}		
			var mySkills = $('.jn').children();
			if(mySkills.length>0){
				var skill = $("#skill").val();
				for(var i = 0; i < mySkills.length; i++){
					if(skill){
	                	skill += "," + $(mySkills[i]).attr('data-id');
	                }else{
	                	skill = $(mySkills[i]).attr('data-id');
	                }					
				}
				$("#skill").val(skill);
			}
		});
        $('.text>span').click(function(){
            if($('.jn>span').text().indexOf($(this).text().split('+')[1]) != -1){
                return;
            }else{
                var html = '<span data-id="'+$(this).attr('data-id')+'">'+$(this).text().split('+')[1]+'<em onclick="del_jn(this)">x</em><i></i></span>'
                $('.jn').append(html)
                var skill = $("#skill").val();
                if(skill){
                	skill += "," + $(this).attr('data-id');
                }else{
                	skill = $(this).attr('data-id');
                }
                $("#skill").val(skill);
            }
        })
        function del_jn(val){
            $(val).parent().remove()
			var skill = $("#skill").val();
            skill = skill.replace(','+$(val).parent().attr('data-id'),'').replace($(val).parent().attr('data-id')+',','').replace($(val).parent().attr('data-id'),'');
            $("#skill").val(skill);
        }
        $('.jn_li>.title').click(function(){
            if($(this).siblings()[0].className == 'text_check'){
                return;
            }else {
                $('.text_check').removeClass('text_check')
                $(this).siblings().addClass('text_check')
            }
        })
    </script>
{!! Theme::asset()->container('custom-css')->usePath()->add('back-stage-css', 'css/backstage/backstage.css') !!}
{!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}
{!! Theme::asset()->container('specific-css')->usePath()->add('datepicker-css', 'plugins/ace/css/datepicker.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('datepicker-js', 'plugins/ace/js/date-time/bootstrap-datepicker.min.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('userManage-js', 'js/userManage.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('main-js', 'js/main.js') !!}
