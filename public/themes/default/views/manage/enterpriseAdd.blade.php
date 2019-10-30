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
        .zs_img img {
        	width: 60%;
        	/* height: 10rem; */
        	margin-right: 0.2rem;
        }
    </style>
<div class="well">
    <h4 >新增企业用户资料</h4>
</div>
<div class="row">
    <div class="col-md-12">

        <form class="form-horizontal registerform" role="form" action="{!! url('manage/enterpriseAdd') !!}" method="post" enctype="multipart/form-data" onsubmit="return validate()">
            {!! csrf_field() !!}
            <!-- <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 用户名：</label>
                <div class="col-sm-4">
                    <input type="text" name="name" id="form-field-1" class="col-xs-10 col-sm-5"  ajaxurl="{!! url('manage/checkUserName') !!}" datatype="*4-15" nullmsg="请输入用户名" errormsg="用户名长度为4到15位字符">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div> -->
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 注册手机：</label>
                <div class="col-sm-4">
                    <input type="number" name="mobile" id="form-field-1"  class="col-xs-10 col-sm-5"  ajaxurl="{!! url('manage/checkMobile') !!}" datatype="m" nullmsg="请输入注册手机号码" errormsg="注册手机输入有误" value="{!! old('mobile') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业名称：</label>
                <div class="col-sm-4">
                    <input type="text" name="company_name" id="form-field-1"  class="col-xs-10 col-sm-5" datatype="*" nullmsg="请输入企业名称"  value="{!! old('company_name') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
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
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1" style="padding-right:9px"> 纳税人识别号：</label>
                <div class="col-sm-4">
                    <input type="text" name="tax_code" id="form-field-1"  class="col-xs-10 col-sm-5" ajaxurl="{!! url('manage/checkTaxCode') !!}" datatype="*" nullmsg="请输入纳税人识别号"  value="{!! old('tax_code') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1">开户行：</label>
                <div class="col-sm-4">
                    <input type="text" name="bank" id="form-field-1"  class="col-xs-10 col-sm-5" datatype="*" nullmsg="请输入开户行" value="{!! old('bank') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1">企业账号：</label>
                <div class="col-sm-4">
                    <input type="number" name="account" id="form-field-1"  class="col-xs-10 col-sm-5" datatype="*" nullmsg="请输入企业账号"  value="{!! old('account') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1">企业法人：</label>
                <div class="col-sm-4">
                    <input type="text" name="owner" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! old('owner') !!}">
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1">企业电话：</label>
                <div class="col-sm-4">
                    <input type="number" name="phone" id="form-field-1"  class="col-xs-10 col-sm-5" value="{!! old('phone') !!}">
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1">联系人：</label>
                <div class="col-sm-4">
                    <input type="text" name="contactor" id="form-field-1"  class="col-xs-10 col-sm-5" datatype="*2-5" nullmsg="请输入真实姓名" errormsg="名字长度为2到5个中文" value="{!! old('contactor') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1">联系人电话：</label>
                <div class="col-sm-4">
                    <input type="number" name="contactor_mobile" id="form-field-1"  class="col-xs-10 col-sm-5" datatype="m" nullmsg="请输入联系人电话" errormsg="联系人电话输入有误" value="{!! old('contactor_mobile') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>            
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业邮箱：</label>
                <div class="col-sm-4">
                    <input type="text" name="company_email" id="form-field-1"   class="col-xs-10 col-sm-5"  value="{!! old('company_email') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><!-- <i class="light-red ace-icon fa fa-asterisk"></i> --></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业地址：</label>
                <div class="col-sm-4">
                    <input type="text" name="address" id="form-field-1"  class="col-xs-10 col-sm-5" datatype="*" nullmsg="请输入企业地址" value="{!! old('address') !!}">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i>（提示：此为营业执照地址）</span>
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
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"  style="padding-right:9px"> 营业执照副本：</label>                
                <div class="col-sm-4 zs_img">
                	<img alt="营业执副本"  id="img" src="/themes/jianzhi/assets/images/fm.jpg"  onclick="bigimg(this)">
                    <input type="file" name="business_license" id="business_license" datatype="*" onchange="imgfile()">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                    	@if($errors->first('business_license'))
            				<p class="Validform_checktip Validform_wrong">{!! $errors->first('business_license') !!}</p>
            			@endif
                </div>
            </div> 
            
            <div class="form-group text-center">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"></label>
                <div class="col-sm-3 text-center">
                    <button class="btn btn-primary btn-sm">提交</button>
                    <div class="btn btn-primary btn-sm" style="margin-left: 50px" onclick="window.location.href = '{!! url('manage/enterpriseList') !!}'">返回</div>
                </div>
            </div>
        </form>
    </div>
</div>
	<div class="bigimg" onclick="$('.bigimg').css('display','none')">
        <img src="" alt="">
    </div>
	<script>
		function validate(){
			/* if($("input[name='account']").val()){
				if(luhnCheck($("input[name='account']").val())){
					return true;
				}else{
					popUpMessage('银行卡号校验失败！');
					return false;
				}
			} */
			return true;
		}
		function imgfile(){
			var reads = new FileReader();
            var f = document.getElementById('business_license').files[0];            
            reads.readAsDataURL(f);
            reads.onload = function(e) {
                document.getElementById('img').src = this.result;        
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
