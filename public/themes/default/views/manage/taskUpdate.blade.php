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
            /*justify-content: center;
            align-items: center;*/
            overflow: auto;
            z-index:9999;
        }
        .bigimg img {
            width: 60%;
			position: absolute;
            top: 10%;
            left: 25%;
            /* height: 80%;
            transform: scale(1.5); */
            /* 放大倍数 */
        }
    </style>
	<div class="well">
    <h4 >编辑任务</h4>
</div>
<div class="row g-backrealdetails" >
    <div class="col-md-12">

        <form class="form-horizontal registerform" role="form" action="{!! url('manage/taskUpdate') !!}" method="post" enctype="multipart/form-data" onsubmit="return validate()">
            {!! csrf_field() !!}
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 发布企业：</label>
                <div class="col-sm-4">
                	<select name="uids" class="uids col-xs-10 col-sm-5" >
                		<option  value="-1" >请选择发布企业</option>
                		@foreach($qiye as $key => $value)
                		<option  value="{{$value['uid']}}" @if($value['uid']==$task['uid']) selected="selected" @endif>{{$value['company_name']}}</option>
            			@endforeach
                	</select>
                	<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务名称：</label>
                <div class="col-sm-4">
                	<input type="text" class="col-xs-10 col-sm-5" name="title" value="{{$task['title']}}" placeholder="请输入任务名称" />
                	<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div> 
            <input type="hidden" name="task_id" value="{{$task['id']}}" />
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务主类型：</label>
                <div class="col-sm-4">
                	<select name="type_id" class="type_id col-xs-10 col-sm-5" onchange="checkTaskType(this.value);">
                		<option  value="-1" >请选择主类型</option>
                		@foreach($taskType as $key => $value)
                		<option  value="{{$value['id']}}" @if($value['id']==$task['type_id'])selected="selected" @endif>{{$value['name']}}</option>
                    	<!--<label><input name="lx" type="radio" value="{{$value['id']}}" onclick="radio_check(this)" />{{$value['name']}}<em></em><span class="iconfont icon-dagou"></span></label>-->        			
            			@endforeach
                	</select>
                	<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务子类型：</label>
                <div class="col-sm-4">
                	<select name="sub_type_id" class="sub_type_id col-xs-10 col-sm-5" id="zileixing">
                	</select>
                	<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>  
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 开始时间：</label>
                <div class="col-sm-4">
                	<!--<input  type="datetime-local" name="begin_at" class="birthday"/>-->
                	<input  type="date" name="begin_at" style="line-height: inherit;" value="{{ date('Y-m-d',strtotime($task['begin_at'])) }}" class="col-xs-10 col-sm-5 birthday"/>
                	<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 结束时间：</label>
                <div class="col-sm-4">
                	<input type="date" name="end_at" style="line-height: inherit;" value="{{ date('Y-m-d',strtotime($task['end_at'])) }}" class="col-xs-10 col-sm-5 birthday"/>
                	<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 服务地址：</label>
                <div class="col-sm-4">
                	<input type="text" class="col-xs-10 col-sm-5" name="address" value="{{$task['address']}}" placeholder="请输入服务地址" />
                	<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务描述：</label>
                <div class="col-sm-4">
                	<textarea name="desc" class="col-xs-20 col-sm-20" id="desc" cols="60" rows="4" placeholder="请简单描述您的任务需求..." onkeydown="clearError(this)">{{$task['desc']}}</textarea>
                	<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
            </div>
            <div class="form-group basic-form-bottom">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务预算：</label>
                <div class="col-sm-4">
                	<input type="number" class="col-xs-10 col-sm-5" name="bounty" value="{{$task['bounty']}}"/>元
                	<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
                </div>
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务人数：</label>
                <div class="col-sm-4">
                	<input type="number" class="col-xs-10 col-sm-5" name="worker_num"  min="1" value="{{$task['worker_num']}}"/>
                	<span class="help-inline col-xs-12 col-sm-7"><i class="light-red ace-icon fa fa-asterisk"></i></span>
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
                                    <option @if($task['province'] == $item['id']) selected="selected" @endif value="{!! $item['id'] !!}">{!! $item['name'] !!}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control  validform-select" name="city" id="city" onchange="getZone(this.value, 'area');">
                               @if(empty($taskcity))
								<option value="">请选择城市</option>
								@else
								<option selected="selected" value="{!! $task['city'] !!}">{!! $taskcity !!}</option>
								@endif
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <select class="form-control  validform-select" name="area" id="area">
                        @if(empty($taskarea))
							<option value="">请选择区域</option>
						@else
							<option selected="selected" value="{!! $task['area'] !!}">{!! $taskarea !!}</option>
						@endif
                    </select>
                </div>
            </div>
            <div class="form-group basic-form-bottom" >
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 上传图片：</label>
                <?php $num=0;?>
                @foreach($taskAttachment as $k=>$v)
                	<?php $num=$k+1;?>
                	<!--<img alt="任务图片" src="{!! url($v['url']) !!}"  onclick="bigimg(this)">{{$k}}-->
                	<div class="bankAuth-bottom clearfix col-xs-12">
		                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"></label>
		                <div class="col-sm-4">
		                    <img alt="任务图片" class="img" src="{!! url($v['url']) !!}" onclick="bigimg(this)">
		                </div>
		                <div class="col-sm-4">
		                    <input type="file" name="file{{$num}}" id="card_front_side" onchange="imgfile(this)">
		                    <input type="hidden" name="aid{{$num}}" value="{{$v['id']}}"/>	
		                    <button type="button" style="background-color: red !important;border-color: red;margin: 10px;" class="btn btn-primary btn-sm" onclick="delatt({{$v['id']}},{{$task['id']}},this)" >删除</button>
		                </div>
		            </div>
                @endforeach
                @if($num<3)
                    	@for($i=1;$i<=3-$num;$i++)
                    		<div class="bankAuth-bottom clearfix col-xs-12">
				                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"></label>
				                <div class="col-sm-4">
				                    <img alt="任务图片" src="" onclick="bigimg(this)">
				                </div>
				                <div class="col-sm-4">
				                    <input type="file" name="file{{$num+$i}}" id="card_front_side" onchange="imgfile(this)">
				                    <input type="hidden" name="aid{{$num+$i}}" value="0"/>
				                </div>
				            </div>
                    	@endfor
                @endif
            </div>
            
            <div class="jnbox">
                <div class="jn_check">
                    <div>技能要求：</div><div class="jn">
                    	@foreach($tags as $v)         
                    		<span data-id="{{ $v['cate_id'] }}">{{ $v['cate_name'] }}<em onclick="del_jn(this)">x</em><i></i></span>                                           	
                        @endforeach   
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
            
            <div class="form-group text-center">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"></label>
                <div class="col-sm-3 text-center">
                    <button class="btn btn-primary btn-sm">提交</button>
                    <div class="btn btn-primary btn-sm" style="margin-left: 50px" onclick="window.location.href = '{!! url('manage/taskList') !!}'">返回</div>
                </div>
            </div>
        </form>
    </div>
   
</div> 
<div class="bigimg" onclick="$('.bigimg').css('display','none')">
        <img src="" alt="">
</div>

<script>
	function delatt(aid,tid,obj){
		popUpMessage("删除中！");
		$.ajax({
			type:"get",
			url:"/manage/ajaxDelAtt/"+aid+"/"+tid,
			async:true,
			success:function(data){
				console.log(data);
				if(data==1){
					$(obj).parent().parent().find('img')[0].src = "";
					$(obj).hide();
					popUpMessage("删除成功！");
				}else if(data==0){
					popUpMessage("删除失败！");
				}
			},
			error:function(e){
				console.log(e);
			}
		});
			
	}
		function imgfile(val){
			var reads = new FileReader();
            var f = $(val)[0].files[0];            
            reads.readAsDataURL(f);
            reads.onload = function(e) {
            	$(val).parent().parent().find('img')[0].src = this.result;     
            };
	    }
		function bigimg(val){
            $('.bigimg > img')[0].src = val.src;
            $('.bigimg').css('display','flex')
        }
		$(function(){
			checkTaskType({{$task['type_id']}},1);
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
    <script>
    var today=new Date();
    var date = new Array(today.getFullYear(), today.getMonth() + 1, today.getDate(), today.getHours(), today.getMinutes());
//  var taskform=$("#taskform").Validform({
//      tiptype:3,
//      label:".label",
//      showAllError:true,
//      ajaxPost:false,
//      dataType:{
//          'positive':/^[1-9]\d*$/,
//      },
//  });
    function validate(){
		var begintime = Date.parse($('input[name="begin_at"]').val());
		var endtime = Date.parse($('input[name="end_at"]').val());
		var today = new Date()
		if($(".uids option:selected").val()==-1){
			popUpMessage("请选择发布企业");
			return false;
		}
		if($(".type_id option:selected").val()==-1){
			popUpMessage("请选择任务主类型");
			return false;
		}
		if($(".sub_type_id option:selected").val()==undefined ||$(".sub_type_id option:selected").val()==null){
			popUpMessage("请选择任务子类型");
			return false;
		}
		if($('input[name="begin_at"]').val()  == '' ||$('input[name="begin_at"]').val()==null){
			popUpMessage("请选择开始时间");
			return false;
		}
		if($('input[name="end_at"]').val()  == '' ||$('input[name="end_at"]').val()==null){
			popUpMessage("请选择结束时间");
			return false;
		}
		if($('input[name="title"]').val()==null || $('input[name="title"]').val()==""){
			popUpMessage("请输入任务名称");
			return false;
		}
		if($('input[name="address"]').val()==null || $('input[name="address"]').val()==""){
			popUpMessage("请输入服务地址");
			return false;
		}
		if($('input[name="bounty"]').val()==null || $('input[name="bounty"]').val()==""){
			popUpMessage("请输入任务预算");
			return false;
		}
		if($('#desc').val()==null || $('#desc').val()==""){
			popUpMessage("请输入任务描述");
			return false;
		}
		if($('input[name="worker_num"]').val()==null || $('input[name="worker_num"]').val()==""){
			popUpMessage("请输入任务人数");
			return false;
		}
		if(begintime>=endtime){
			popUpMessage("结束时间应迟于开始时间！");
			return false;
		}else if(begintime<datenum){
			popUpMessage("开始时间不能在今天之前！");
			return false;
		}else if(endtime<datenum){
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
    function checkTaskType(id,isload){
    	console.log(id);
        if(!id) return;
        $('#zileixing').empty();
        $.get('/manage/ajaxSubTaskType',{'id':id},function(data){
            console.log(data);
            if(data&&data.subTaskType&&data.subTaskType.length>0){
            	var options = '';    
            	for(var i in data.subTaskType) {
            		if(isload==1&&data.subTaskType[i].id=={{$task['sub_type_id']}}){
            			options += '<option selected="selected" value="'+data.subTaskType[i].id+'">'+data.subTaskType[i].name+'</option>';
            		}else{
            			options += '<option  value="'+data.subTaskType[i].id+'">'+data.subTaskType[i].name+'</option>';
            		}
            		
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
	