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
            z-index: 9999;
        }
        .bigimg img {
            width: 60%;
            /* height: 80%;
            transform: scale(1.5); */
            /* 放大倍数 */
        }        
        .zs_img img {
        	width: 60%;
        	/* height: 10rem; */
        	margin-right: 0.2rem;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button{
        	-webkit-appearance: none !important;
        	}
        input[type="number"]{-moz-appearance:textfield;}
    </style>
<div class="well">
    <h4 >新增个人用户资料</h4>
</div>
<div class="row">
    <div class="col-md-12">

        <form class="form-horizontal registerform" role="form" action="{!! url('manage/userAdd') !!}" method="post" enctype="multipart/form-data" onsubmit="return validate()">
            {!! csrf_field() !!}
            <!-- <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 用户名：</label>
                <div class="col-sm-4">
                    <input type="text" name="name" id="form-field-1" class="col-xs-10 col-sm-5"  ajaxurl="{!! url('manage/checkUserName') !!}" datatype="*4-15" nullmsg="请输入用户名" errormsg="用户名长度为4到15位字符">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>   -->          
            <!-- <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 注册邮箱：</label>
                <div class="col-sm-4">
                    <input type="text" name="email" id="form-field-1"  class="col-xs-10 col-sm-5"  ajaxurl="{!! url('manage/checkEmail') !!}" datatype="e" nullmsg="请输入邮箱帐号" errormsg="邮箱地址格式不对！">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div> -->
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 真实姓名：</label>
                <div class="col-sm-4">
                    <input type="text" name="realname" id="form-field-1"  class="col-xs-10 col-sm-5" datatype="*2-5" nullmsg="请输入真实姓名" errormsg="名字长度为2到5个中文" value="{!! old('realname') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 身份证号码：</label>
                <div class="col-sm-4">
                    <input type="number" name="card_number" id="form-field-1"  class="col-xs-10 col-sm-5" ajaxurl="{!! url('manage/checkIDCard') !!}" datatype="*15-18" nullmsg="请输入身份证号码" errormsg="身份证号码长度为15-18位字符"  value="{!! old('card_number') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 手机号码：</label>
                <div class="col-sm-4">
                    <input type="number" name="mobile" id="form-field-1"   class="col-xs-10 col-sm-5" ajaxurl="{!! url('manage/checkMobile') !!}"  datatype="m" nullmsg="请输入手机号码" errormsg="手机号码输入有误" value="{!! old('mobile') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 银行卡号：</label>
                <div class="col-sm-4">
                    <input type="number" name="account" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! old('account') !!}">
                </div>
            </div>
            <div class="form-group basic-form-bottom" style="display: none;">
                <label  class="col-sm-1 control-label no-padding-left">所在地：</label>
                <div class="col-sm-5">
                    <div class="row">
                        <div class="col-sm-6">
                            <select name="province" id="province" class="form-control validform-select Validform_error" onchange="getZone(this.value, 'city');">
                                <option value="">请选择省份</option>
                                @foreach($province as $item)
                                    <option value="{!! $item['id'] !!}">{!! $item['name'] !!}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control  validform-select" name="city" id="city" onchange="getZone(this.value, 'area');">
                                <option value="">请选择城市</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <select class="form-control  validform-select" name="area" id="area">
                        <option value="">请选择区域</option>
                    </select>
                </div>
            </div>
            <!-- <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 出生日期：</label>
                <div class="col-sm-4">
                    <div class="input-group input-group-sm col-xs-10 col-sm-5">
                        <input type="text" id="datepicker" class="form-control hasDatepicker">
				<span class="input-group-addon">
					<i class="ace-icon fa fa-calendar"></i>
				</span>
                    </div>
                </div>
            </div> -->
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 密&nbsp;&nbsp;码：</label>
                <div class="col-sm-4">
                    <input type="password" id="form-field-1"  class="col-xs-10 col-sm-5" name="password" datatype="*6-16">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i>（提示：此为用户初始密码）</span>
                </div>
            </div>
            
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 身份证正面：</label>
                <div class="col-sm-4 zs_img">
                	<img alt="身份证正面"  id="img" src="/themes/jianzhi/assets/images/fm.jpg"  onclick="bigimg(this)">
                    <input type="file" name="card_front_side" id="card_front_side" datatype="*"  onchange="imgfile(this)">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
                	@if($errors->first('card_front_side'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('card_front_side') !!}</p>
        			@endif
            </div>
            
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 身份证反面：</label>
                <div class="col-sm-4 zs_img">
                	<img alt="身份证反面"  id="img" src="/themes/jianzhi/assets/images/fm.jpg"  onclick="bigimg(this)">
                    <input type="file" name="card_back_dside" id="card_back_dside" datatype="*" onchange="imgfile(this)">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
                		@if($errors->first('card_back_dside'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('card_back_dside') !!}</p>
            			@endif
            </div>
            
            <div class="jnbox">
                <div class="jn_check">
                    <div>技能标签：</div><div class="jn"></div>
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
                    <!-- <div class="jn_li">
                        <div class="title">推广营销</div>
                        <div class="text text_check">
                            <span><em>+</em>线下宣传<i></i></span>
                            <span><em>+</em>软文写作<i></i></span>
                            <span><em>+</em>客户回访<i></i></span>
                            <span><em>+</em>新品促销<i></i></span>
                            <span><em>+</em>微信推广<i></i></span>
                            <span><em>+</em>微博推广<i></i></span>
                            <span><em>+</em>渠道推广<i></i></span>
                        </div>
                    </div> -->                    
                </div>  
                <input type="hidden" name="skill" id="skill"/>
            </div>           
            
            <div class="form-group text-center">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"></label>
                <div class="col-sm-3 text-center">
                    <button class="btn btn-primary btn-sm">提交</button>
                    <div class="btn btn-primary btn-sm" style="margin-left: 50px" onclick="window.location.href = '{!! url('manage/userList') !!}'">返回</div>
                </div>
            </div>
        </form>
    </div>
</div>	
	<div class="bigimg" onclick="$('.bigimg').css('display','none')">
        <img src="" alt="">
    </div>
	<script>
		$(function(){
			var skills = $(".jn_box").children();
			if(skills.length>0){
				$(skills[0]).find(".text").addClass('text_check');
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
        function imgfile(val){
			var reads = new FileReader();
            var f = $(val)[0].files[0];            
            reads.readAsDataURL(f);
            reads.onload = function(e) {
            	$(val).parent().find('img')[0].src = this.result;     
            };
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
