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
	<div class="well">
    <h4 >添加任务</h4>
</div>
<div class="row">
    <div class="col-md-12">

        <form class="form-horizontal registerform" role="form" action="{!! url('manage/taskAdd') !!}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 用户：</label>
                <div class="col-sm-4">
                	<select name="uids" class="col-xs-10 col-sm-5" >
                		<option  value="-1" >请选择用户</option>
                		@foreach($qiye as $key => $value)
                		<option  value="{{$value['id']}}" >{{$value['name']}}</option>
            			@endforeach
                	</select>
                </div>
            </div> 
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务主类型：</label>
                <div class="col-sm-4">
                	<select name="type_id" class="col-xs-10 col-sm-5" onchange="checkTaskType(this.value);">
                		<option  value="-1" >请选择主类型</option>
                		@foreach($taskType as $key => $value)
                		<option  value="{{$value['id']}}" >{{$value['name']}}</option>
                    	<!--<label><input name="lx" type="radio" value="{{$value['id']}}" onclick="radio_check(this)" />{{$value['name']}}<em></em><span class="iconfont icon-dagou"></span></label>-->        			
            			@endforeach
                	</select>
                </div>
            </div>  
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务子类型：</label>
                <div class="col-sm-4">
                	<select name="sub_type_id" class="col-xs-10 col-sm-5" id="zileixing">
                	</select>
                </div>
            </div>            
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 开始时间：</label>
                <div class="col-sm-4">
                	<input  type="datetime-local" name="begin_at" class="birthday"/>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 结束时间：</label>
                <div class="col-sm-4">
                	<input type="datetime-local" name="end_at" class="birthday"/>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务名称：</label>
                <div class="col-sm-4">
                	<input type="text" class="col-xs-10 col-sm-5" name="title" placeholder="请输入任务名称" />
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label  class="col-sm-1 control-label no-padding-left">任务地址：</label>
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
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 服务地址：</label>
                <div class="col-sm-4">
                	<input type="text" class="col-xs-10 col-sm-5" name="address" placeholder="请输入服务地址" />
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务预算：</label>
                <div class="col-sm-4">
                	<input type="number" class="col-xs-10 col-sm-5" name="bounty" />元
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务描述：</label>
                <div class="col-sm-4">
                	<textarea name="desc" class="col-xs-20 col-sm-20" id="desc" cols="60" rows="10" placeholder="请简单描述您的任务需求..." onkeydown="clearError(this)"></textarea>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务人数：</label>
                <div class="col-sm-4">
                	<input type="number" class="col-xs-10 col-sm-5" name="worker_num" value="1" min="1" />
                </div>
            </div>
            <!--<div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 密&nbsp;&nbsp;码：</label>
                <div class="col-sm-4">
                    <input type="password" id="form-field-1"  class="col-xs-10 col-sm-5" name="password" datatype="*6-16">
                    <span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i>（提示：此为用户初始密码）</span>
                </div>
            </div>-->
            
            <div class="form-group basic-form-bottom" style="display: none;">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 上传图片：</label>
                <div class="col-sm-4">
                    <input type="file" id="file" name="card_front_side"  onchange="img()">
                    	<input type="hidden" name="file_id" />
                </div>
            </div>
            
            
            <div class="jnbox">
                <div class="jn_check">
                    <div>技能要求：</div><div class="jn"></div>
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
<script>
	function img(){
            var reads = new FileReader();
            var f = document.getElementById('file').files[0];    
            console.log(f);        
            reads.readAsDataURL(f);
            reads.onload = function(e) {
                uploadFile();
            };
    }
     function uploadFile(){
//  	$("body").mLoading({text:"上传中"});//显示loading组件
    	var headers = { "_token": "{{ csrf_token() }}"};
    	// 开始上传
        $.ajaxFileUpload({
            secureuri: false,// 是否启用安全提交，默认为 false
            type: "POST",
            url: "/jz/task/fileUpload",
            fileElementId: "file",// input[type=file] 的 id
            dataType: "json",// 返回值类型，一般位 `json` 或者 `text`
            //data: data,// 添加参数，无参数时注释掉
            header: headers,
            success: function (ret, status) {
                // 成功
            	console.log(ret); 
            	if(ret&&ret.id){
            		var attachmentId = $("input[name='file_id']").val();
            		$("input[name='file_id']").val(attachmentId+(attachmentId?",":"")+ret.id);     
            		var imgs = $(".zs_img").children();	
            		if(imgs&&imgs.length>0){
            			imgs[imgs.length-1].id = 'img_'+ret.id;
                	}
                }
                $('#file').val('')
            },
            error: function (data, status, e) {
                // 失败
            	console.log(data);
            },
        });
    }
$(function(){
       //得到当前时间
	var date_now = new Date();
	//得到当前年份
	var year = date_now.getFullYear();
	//得到当前月份
	//注：
	//  1：js中获取Date中的month时，会比当前月份少一个月，所以这里需要先加一
	//  2: 判断当前月份是否小于10，如果小于，那么就在月份的前面加一个 '0' ， 如果大于，就显示当前月份
	var month = date_now.getMonth()+1 < 10 ? "0"+(date_now.getMonth()+1) : (date_now.getMonth()+1);
	//得到当前日子（多少号）
	var date = date_now.getDate() < 10 ? "0"+date_now.getDate() : date_now.getDate();
	//设置input标签的max属性
	$(".birthday").attr("min",year+"-"+month+"-"+date+" 00:00");
})
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
    </script>
    <script>
    var today=new Date()
    var date = new Array(today.getFullYear(), today.getMonth() + 1, today.getDate(), today.getHours(), today.getMinutes());
    var taskform=$("#taskform").Validform({
        tiptype:3,
        label:".label",
        showAllError:true,
        ajaxPost:false,
        dataType:{
            'positive':/^[1-9]\d*$/,
        },
    });
    function validate(){
		var begintime = Date.parse($('input[name="begin_at"]').val());
		var endtime = Date.parse($('input[name="end_at"]').val());
		var today = new Date()
		console.log(today.getTime());
		console.log(begintime);
		console.log(endtime);
		if(begintime>=endtime){
			popUpMessage("结束时间应迟于开始时间！");
			return false;
		}else if(begintime<today.getTime()){
			popUpMessage("开始时间不能在今天之前！");
			return false;
		}else if(endtime<today.getTime()){
			popUpMessage("结束时间不能在今天之前！");
			return false;
		}else{
			return true;
		}
    }
    function clearError(obj){
		//console.log(obj);
		$(obj).parent().find('p').remove();
    }
    function jian(){
        if($('.number').text() == 1){
            return
        }else{
            var a = Number($('.number').text())
            a--
            $('.number').text(a)
            $('input[name="worker_num"]').val(a);
        }
    }
    function jia(){
            var a = Number($('.number').text())
            a++
            $('.number').text(a)
            $('input[name="worker_num"]').val(a);
    }
    function jn() {
        $('.jnbj').css('display','block');
        setTimeout(function(){
            $('.jnbox').css('bottom','0%');
        },500)
    }
    $('.jnbox').find('.li').find('.right>span').click(function(){
        if(this.className.indexOf('jn_check') != -1){
            $(this).removeClass('jn_check')
            $(this).find('em').remove()
            $(this).find('i').remove()
        }else {
            $(this).addClass('jn_check')
            $(this).append('<em></em>')
            $(this).append('<i class="iconfont icon-dagou"></i>')
        }
    })
    /**
     * 省级切换
     * @param obj
     */
    function checkprovince(obj){
        var id = obj.value;
        $.get('/jz/task/ajaxcity',{'id':id},function(data){
            var html = '';
            var area = '';
            for(var i in data.province){
                html+= "<option value=\""+data.province[i].id+"\">"+data.province[i].name+"<\/option>";
            }
            for(var s in data.area){
                area+= "<option value=\""+data.area[s].id+"\">"+data.area[s].name+"<\/option>";
            }
            $('#province_check').html(html);
            $('#area_check').html(area);
            //$('#region-limit').attr('value',data.area[0].id);
        });
    }
    /**
     * 市级切换
     * @param obj
     */
    function checkcity(obj){
        var id = obj.value;
        $.get('/jz/task/ajaxarea',{'id':id},function(data){
            var html = '';
            for(var i in data){
                html += "<option value=\""+data[i].id+"\">"+data[i].name+"<\/option>";
            }
            $('#area_check').html(html);
            //$('#region-limit').attr('value',data[0].id);
        });
    }

    /**
     * 地区限制数据替换
     * @param obj
     */
    function arealimit(obj){
        //$('#region-limit').attr('value',obj.value);
    }
    /**
     * 任务主类型切换
     * @param obj
     */
    function checkTaskType(id){
    	console.log(id);
        if(!id) return;
        $('#zileixing').find('.sub').empty();
        $.get('/jz/task/ajaxSubTaskType',{'id':id},function(data){
            console.log(data);
            if(data&&data.subTaskType&&data.subTaskType.length>0){
            	var options = '';    
            	for(var i in data.subTaskType) {
            		options += '<option  value="'+data.subTaskType[i].id+'">'+data.subTaskType[i].name+'</option>';
               	}
            	$('#zileixing').append(options);
            }
        });
    }
    </script>
    <script src="/themes/jianzhi/assets/libs/jquery.mloading.js"></script>
    
    {!! Theme::asset()->container('custom-css')->usePath()->add('back-stage-css', 'css/backstage/backstage.css') !!}
	{!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
	{!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}
	{!! Theme::asset()->container('specific-css')->usePath()->add('datepicker-css', 'plugins/ace/css/datepicker.css') !!}
	{!! Theme::asset()->container('specific-js')->usePath()->add('datepicker-js', 'plugins/ace/js/date-time/bootstrap-datepicker.min.js') !!}
	{!! Theme::asset()->container('custom-js')->usePath()->add('userManage-js', 'js/userManage.js') !!}
	<script src="/themes/jianzhi/assets/libs/jquery.ajaxFileUpload.js"></script>
	{!! Theme::asset()->container('custom-js')->usePath()->add('main-js', 'js/main.js') !!}
	