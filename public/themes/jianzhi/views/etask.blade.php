	<div class="top">
        发布任务
    </div>
    <div class="center">
    	<form action="/jz/task/createTask" method="post" id="taskform" enctype="multipart/form-data" onsubmit="return validate()">
    		{!! csrf_field() !!}
    		
            <div class="xian"></div>
            <div class="list zhu" onclick="leixing('main')">
                <span>任务主类型
                @if($errors->first('mainType'))
        			<p class="Validform_checktip Validform_wrong">{!! $errors->first('mainType') !!}</p>
        		@endif
        		</span>
                <span class="main_span"></span>                
                <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>		
                <input type="hidden" name="mainType">
            </div>
            <div class="list zi" onclick="leixing('sub')">
                <span>任务子类型
                @if($errors->first('subType'))
        			<p class="Validform_checktip Validform_wrong">{!! $errors->first('subType') !!}</p>
        		@endif
        		</span>
                <span class="sub_span"></span><span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
                <input type="hidden" name="subType">
            </div>
            <div class="list Time">
            <span>开始时间
            	@if($errors->first('begin_at'))
        			<p class="Validform_checktip Validform_wrong">{!! $errors->first('begin_at') !!}</p>
        		@endif
            </span>
            {{-- <span class="starTime_span">{{old('begin_at')}}</span> --}}
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
            {{-- <div class="time" id="startDate"></div> --}}
            {{-- <input type="hidden" name="begin_at" value="{{old('begin_at')}}"> --}}
            <input type="text" class="time" name="begin_at" id="begin_at" value="{{old('begin_at')}}">
            </div>
            <div class="list Time">
            <span>结束时间
            	@if($errors->first('end_at'))
        			<p class="Validform_checktip Validform_wrong">{!! $errors->first('end_at') !!}</p>
        		@endif
            </span>
            {{-- <span class="endTime_span">{{old('end_at')}}</span>  --}}
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
            {{-- <div class="time" id="endDate"></div>  --}}
            {{-- <input type="hidden" name="end_at" value="{{old('end_at')}}"> --}}
            <input type="text" class="time" name="end_at" id="end_at" value="{{old('end_at')}}">
            </div>
            <div class="list">
                <span>任务名称
                	@if($errors->first('title'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('title') !!}</p>
        			@endif
                </span>                
                <input type="text" name="title" id="title" placeholder="请输入任务名称" onkeydown='clearError(this)' value="{{old('title')}}">
            </div>
            <div class="list" onclick="$('.dzbj').css('display','block')">
            	<span>任务城市 
            		@if($errors->first('district'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('district') !!}</p>
        			@endif
            	</span>
            	<span class="dz_span"></span><span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
            	<input type="hidden" name="district" value="{{old('district')}}">
        	</div>
            <div class="list">
                <span>服务地址
                	@if($errors->first('address'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('address') !!}</p>
        			@endif
                </span>
                <input type="text" name="address" id="address" placeholder="请输入服务地址" onkeydown='clearError(this)' value="{{old('address')}}">
            </div>
            <div class="xian"></div>
            <div class="list" onclick="jn()">
                <span>技能要求
                	@if($errors->first('skill'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('skill') !!}</p>
        			@endif
                </span>                
                <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
                <input type="hidden" name="skill" value="{{old('skill')}}">
            </div>
            <div class="xian"></div>
            <div class="list">
                <span>任务预算
                	@if($errors->first('bounty'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('bounty') !!}</p>
        			@endif
                </span>                
                <input type="number" name="bounty" id="bounty" placeholder="请输入任务预算" onkeydown='clearError(this)'  value="{{old('bounty')}}">元
            </div>
            <div class="list">
                <span>任务人数
                	@if($errors->first('worker_num'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('worker_num') !!}</p>
        			@endif
                </span> 
                <div class="number_btn">
                    <div onclick="jian()">-</div>
                    <div class="number">1</div>
                    <div onclick="jia()">+</div>
                </div>
                <input type="hidden" name="worker_num" @if(old('worker_num')) value="{{old('worker_num')}}" @else value="1" @endif>
            </div>
            <div class="list">
                <span>任务描述
                	@if($errors->first('desc'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('desc') !!}</p>
        			@endif
                </span>
                <textarea name="desc" id="desc" cols="30" rows="10" placeholder="请简单描述您的任务需求..." onkeydown='clearError(this)'></textarea>
            </div>
            <div class="img_box">
                上传图片
                <div class="img">
                    <div class="zs_img">
                        <!-- <img src="" id="img" alt=""> -->
                    </div>
                    <div class="add" url="{{ url('task/fileUpload')}}" deleteurl="{{ url('task/fileDelet') }}">
                        +
                        <input type="file" name="file" id="file" onchange="imgfile()">
                    </div>
                </div>
                <input type="hidden" name="file_id">
            </div>
            <button class="btn" type="submit">任务提交</button>
    	</form>
    	<div style="font-size: 15px;line-height: 15px;">
    		 <span>
    		 	Copyright 2020 广州网金创纪信息技术有限公司 All rights reserved <a style="color: blue;text-decoration: underline;" href="http://www.miitbeian.gov.cn/"> 粤ICP备19125254号-1</a>
			</span>
		</div>
    </div>
    <div class="foot">
        <div class="foot_check">
            <span class="iconfont icon-emizhifeiji"></span>
            发布任务
        </div>
        <div onclick="window.location.href = '{!! url('jz/task') !!}'">
            <span class="iconfont icon-thin-_notebook_p"></span>
            项目管理
        </div>
        <div onclick="window.location.href = '{!! url('jz/my') !!}'">
            <span class="iconfont icon-dalou4"></span>
            企业中心
        </div>
    </div>
    <div class="jnbj">
            <div class="jnbox">
                <div class="li">
                    <div class="left">技能选择</div>
                    <div class="right jnbtn iconfont icon-dagou" onclick="jn_btn()"></div>
                </div>
                @foreach($taskCate as $v)
                	<div class="li">
                	<div class="left" data-id="{{ $v['id'] }}">{{ $v['name'] }}:</div>
                	<div class="right">
                	@if(isset($v['children_task']))
                    	@foreach($v['children_task'] as $sub)
                    		<span data-id="{{ $sub['id'] }}">{{ $sub['name'] }}</span>
                    	@endforeach
                	@endif
                	</div>
                	</div>
                @endforeach
            </div>
        </div>
        <div class="lxbj">
            <div class="lxbox">
                <div class="main">
                    <!-- 
                    <label><input name="lx" type="radio" value="0" onclick="radio_check(this)" checked />个人注册 <span class="radio_check iconfont icon-dagou"></span></label> 
                    <label><input name="lx" type="radio" value="1" onclick="radio_check(this)" />企业注册<span class="iconfont icon-dagou"></span></label> -->
                    
                    @foreach($taskType as $key => $value)
                    	<label><input name="lx" type="radio" value="{{$value['id']}}" onclick="radio_check(this)" />{{$value['name']}}<em></em><span class="iconfont icon-dagou"></span></label>        			
            		@endforeach
                </div>
                <div class="sub">
                	
                </div>
                <div class="lxbtn" onclick="lxbtn(this)">
                    确定
                </div>
            </div>
        </div>
    
    <div class="dzbj">
        <div class="dzbox">
            <div data-toggle="distpicker">
                    <div class="dz">
                        <!-- <select name="province" data-province="浙江省"></select> -->
                        
                        					<select name="province" onchange="checkprovince(this)">
                                                @foreach($province as $v)
                                                    <option value={{ $v['id'] }}>{{ $v['name'] }}</option>
                                                @endforeach
                                            </select>                       
                        <span class="iconfont icon-jiantou9">
                        </span>
                    </div>
                    <div class="dz">
                        <!-- <select name="city" data-city="杭州市"></select> -->
                        
                        					<select name="city" id="province_check" onchange="checkcity(this)">
                                                @foreach($city as $v)
                                                    <option value={{ $v['id'] }}>{{ $v['name'] }}</option>
                                                @endforeach
                                            </select>
                        <span class="iconfont icon-jiantou9">
                        </span>
                    </div>
                    <div class="dz">
                        <!-- <select name="district" data-district="西湖区"></select> -->
                        
                        					<select name="area" id="area_check" onchange="arealimit(this)">
                                                @foreach($area as $v)
                                                    <option value={{ $v['id'] }}>{{ $v['name'] }}</option>
                                                @endforeach
                                            </select>                                             
                        <span class="iconfont icon-jiantou9">
                        </span>
                    </div>
            </div>
            <div class="dzbtn" onclick="dzbtn()">
                确定
            </div>
        </div>
    </div>
    	<div class="bigimg" onclick="$('.bigimg').css('display','none')">
            <img src="" alt="">
        </div>
    <script>
    var today=new Date()
    //var date = new Array(today.getFullYear(), today.getMonth() + 1, today.getDate(), today.getHours()<10?("0"+today.getHours()):today.getHours(), today.getMinutes()<10?('0'+today.getMinutes()):today.getMinutes());
    
        
     var taskform=$("#taskform").Validform({
        tiptype:3,
        label:".label",
        showAllError:true,
        ajaxPost:false,
        dataType:{
            'positive':/^[1-9]\d*$/,
        },
    });
    $(function(){
        //初始任务主类型
		var old_mainType = "{{old('mainType')}}";
		if(old_mainType){
			var lx = $(".main").find("input[name='lx']");
			for(var i = 0; i<lx.length; i++){
				if($(lx[i]).val()===old_mainType){
					//$(lx[i]).checked = true;
					$('.main_span').text($(lx[i]).parent().text());  
					$("input[name='mainType']").val(old_mainType);
					checkTaskType(old_mainType, true);
					break;
				}
			}
		}
		//初始任务开始时间
		var begin_at = "{{old('begin_at')}}";
		if(begin_at){
			var d = new Date()
			d.setTime(Date.parse(begin_at));
			console.log(d);
		}else{
		    begin_at = today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate() + " " + (today.getHours()<10?("0"+today.getHours()):today.getHours()) + ":" + (today.getMinutes()<10?('0'+today.getMinutes()):today.getMinutes()) + ":" + (today.getSeconds()<10?('0'+today.getSeconds()):today.getSeconds());
		}
	    laydate.render({ 
	    	elem: '#begin_at',
	    	type: 'datetime',
	    	value: begin_at
	    });
	  	//初始任务结束时间
	    var end_at = "{{old('end_at')}}";
	    if(end_at){
			var d = new Date()
			d.setTime(Date.parse(end_at));
			console.log(d);
		}else{
			end_at = today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate() + " " + (today.getHours()<10?("0"+today.getHours()):today.getHours()) + ":" + (today.getMinutes()<10?('0'+today.getMinutes()):today.getMinutes()) + ":" + (today.getSeconds()<10?('0'+today.getSeconds()):today.getSeconds());
		}
	    laydate.render({ 
	    	elem: '#end_at',
	    	type: 'datetime',
	    	value: end_at
	    });
		
		//初始任务城市
		var old_district = "{{old('district')}}";
		if(old_district){
			var district_arr = old_district.split('-',3);
			if(district_arr[0]){
				$("select[name='province']").val(district_arr[0]);
				if(district_arr[1]){
					checkprovince($("select[name='province']")[0],district_arr);
				}
			}			
		}
		//初始技能选择
		var old_skill = "{{old('skill')}}";
		if(old_skill){
			var jn_arr = $('.jnbox').find('.li').find('.right>span');
			var skill_arr = old_skill.split(',')
			
			for(var i = 0; i < jn_arr.length; i++){
				for(var j = 0; j < skill_arr.length; j++){
					if($(jn_arr[i]).attr('data-id')===skill_arr[j]){
						$(jn_arr[i]).addClass('jn_check')
			            $(jn_arr[i]).append('<em></em>')
			            $(jn_arr[i]).append('<i class="iconfont icon-dagou"></i>')
					}
				}
			}
		}
		//初始任务人数
		var old_worker_num = "{{old('worker_num')}}";
		if(old_worker_num){
			$('.number').text(old_worker_num);
		}
		//初始任务描述
		var old_desc =  "{{old('desc')}}";
		if(old_desc){
			$("textarea[name='desc']").val(old_desc);
		}
    });

	function initTaskSubType(){
		var old_subType = "{{old('subType')}}";
		if(old_subType){
			var lx = $(".sub").find("input[name='lx']");
			for(var i = 0; i<lx.length; i++){
				if($(lx[i]).val()===old_subType){
					//$(lx[i]).checked = true;
					$('.sub_span').text($(lx[i]).parent().text());  
					$("input[name='subType']").val(old_subType);
					break;
				}
			}
		}
	}
    
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
    function jn_btn(){
        $('.jnbox').css('bottom','100%');
        setTimeout(function(){
            $('.jnbj').css('display','none');
        },500)
        var a = ''
        for(let i =0;i<$('.jn_check').length;i++){
            a+=$('.jn_check').eq(i).attr('data-id') + ','
        }
        a = a.substr(0, a.length-1);
        //$('.bs').attr('data-id',a)
        $("input[name='skill']").val(a)
        clearError($("input[name='skill']"));
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
    function imgfile(){
        if($(".zs_img").children().length < 3){
            var reads = new FileReader();
            var f = document.getElementById('file').files[0];            
            reads.readAsDataURL(f);
            reads.onload = function(e) {
                // document.getElementById('img').src = this.result;
                $(".zs_img").css("display", "flex");
                /* var img = document.createElement('img')
                img.src = this.result;
                $(".zs_img").append(img) */
                var html = '<div onclick="bigimg(this)" class="box_img"><img src ="'+this.result+'"><em onclick="delimg(this)">-</em></div>'
				$(".zs_img").append(html)
                uploadFile();
            };
            
        }else{
        	popUpMessage('最多上传三张')
        }
    }
    function delimg(val){
    	$(val).parent().remove()
        event.stopPropagation()
    	if($(".zs_img").children().length == 0){
    		$(".zs_img").css("display", "none");
    	}
    	deleteFile($(val).parent()[0].id);
    }
    function bigimg(val){
        $('.bigimg').css('display','flex')
        $('.bigimg > img')[0].src = $(val).find('img')[0].src
    }
    function leixing(type){
    	$('.lxbj').css('display','block')
    	if(type==='main'){
    		$('.lxbj').find('.main').css('display','block')
    		$('.lxbj').find('.sub').css('display','none')    		
        }else{
        	$('.lxbj').find('.sub').css('display','block')
    		$('.lxbj').find('.main').css('display','none')
        }
    	$('label >span').removeClass('radio_check')
    	var selectedOne = $("input[name='lx']:checked");
    	if(selectedOne.length>0) selectedOne[0].checked = false
		var options = $('.lxbj').find('.'+type).find("input[type='radio']");
		for(var i = 0; i < options.length; i++){
			if($(options[i]).val()===$("input[name='"+type+"Type']").val()){				
				$(options[i]).parent().find('span').addClass('radio_check')
				options[i].checked = true;
				break;
			}
    	}
    	$('.lxbj').find('.lxbtn').attr('data-type', type)
    }
    function radio_check(val){
        if($(val).parent().find('span')[0].className.indexOf('radio_check') != -1){
            return
        }else {
            $('label >span').removeClass('radio_check')
            $(val).parent().find('span').addClass('radio_check')
        }        
    }
    function lxbtn(obj){
        var type = $(obj).attr('data-type');
        $('.lxbj').css('display','none')
        if(($("input[name='lx']:checked")).length > 0 &&
        		$('input[name="'+type+'Type"]').val()!=$("input[name='lx']:checked").val()){
            $('input[name="'+type+'Type"]').val($("input[name='lx']:checked").val())
            $('.'+type+'_span').text($("input[name='lx']:checked").parent().text())               
            //$('.'+type+'_span').parent().find('p').remove();
            clearError($('.'+type+'_span'));
            if(type==='main'){
            	$('input[name="subType"]').val('')
                $('.sub_span').text('') 
            	checkTaskType($("input[name='lx']:checked").val());
            }        	
        }
    }
    /* $("#startDate").datetime({
        type: "datetime",
        value: date,
        success: function (res) {
            $('.starTime_span').text(res[0]+'-'+res[1]+'-'+res[2]+' '+res[3]+':'+res[4])
            $('input[name="begin_at"]').val(res[0]+'-'+res[1]+'-'+res[2]+' '+res[3]+':'+res[4])
            //$('.starTime_span').parent().find('p').remove();
            clearError($('.starTime_span'));
        }
    })
    $("#endDate").datetime({
        type: "datetime",
        value: date,
        success: function (res) {
            $('.endTime_span').text(res[0]+'-'+res[1]+'-'+res[2]+' '+res[3]+':'+res[4])
            $('input[name="end_at"]').val(res[0]+'-'+res[1]+'-'+res[2]+' '+res[3]+':'+res[4])
            //$('.endTime_span').parent().find('p').remove();
            clearError($('.endTime_span'));
        }
    }) */
    function dzbtn() {
        $('.dzbj').css('display','none')
        $('.dz_span').text($("select[name='province'] option:selected").text() + '-' + $("select[name='city'] option:selected").text() + '-' + $("select[name='area'] option:selected").text())
        $("input[name='district']").val($("select[name='province'] option:selected").val() + '-' + $("select[name='city'] option:selected").val() + '-' + $("select[name='area'] option:selected").val())
        //$('.dz_span').parent().find('p').remove();
        clearError($('.dz_span'));
    }
    /**
     * 省级切换
     * @param obj
     */
    function checkprovince(obj, init){
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

            if(init){
            	$("select[name='city']").val(init[1]);
            	if(init[2]){
                	checkcity($("select[name='city']")[0], init);
                }
            }
        });
    }
    /**
     * 市级切换
     * @param obj
     */
    function checkcity(obj, init){
        var id = obj.value;
        $.get('/jz/task/ajaxarea',{'id':id},function(data){
            var html = '';
            for(var i in data){
                html += "<option value=\""+data[i].id+"\">"+data[i].name+"<\/option>";
            }
            $('#area_check').html(html);
            //$('#region-limit').attr('value',data[0].id);

            if(init){
            	$("select[name='area']").val(init[2]);
            	$('.dz_span').text($("select[name='province'] option:selected").text() + '-' + $("select[name='city'] option:selected").text() + '-' + $("select[name='area'] option:selected").text())
            }
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
    function checkTaskType(id, init){
        if(!id) return;
        $('.lxbj').find('.sub').empty();
        $.get('/jz/task/ajaxSubTaskType',{'id':id},function(data){
            console.log(data);
            if(data&&data.subTaskType&&data.subTaskType.length>0){
            	var options = '';            	
            	for(var i in data.subTaskType) {
            		options += '<label><input name="lx" type="radio" value="'+data.subTaskType[i].id+'" onclick="radio_check(this)" /><em>'+data.subTaskType[i].name+'</em><span class="iconfont icon-dagou"></span></label>';
               	}
            	$('.lxbj').find('.sub').append(options);

            	if(init){
					initTaskSubType();
                }
            }
        });
    }
    /**
    	上传文件
    */
    function uploadFile(){
    	$("body").mLoading({text:"上传中"});//显示loading组件
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
            	console.log(e);
            },
            complete: function(){
            	$("body").mLoading("hide");//隐藏loading组件
            }
        });
    }
    function deleteFile(id){
    	if(!id) return;
    	var ids = id.split('_', 2);
    	if(!ids[1]) return;
    	var attachmentId = $("input[name='file_id']").val();
    	attachmentId = attachmentId.replace(','+ids[1],'').replace(ids[1]+',','').replace(ids[1],'')
    	console.log(attachmentId);
    	$("input[name='file_id']").val(attachmentId);
        $.get('/jz/task/fileDelet',{'id':ids[1]},function(data){
            console.log(data);            
        });
    }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('enterprise_index-style','style/enterprise_index.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}
    {{-- {!! Theme::asset()->container('specific-css')->usePath()->add('dateTime-css', 'libs/dateTime.css') !!} --}}
    {{-- {!! Theme::asset()->container('specific-js')->usePath()->add('dateTime-js', 'libs/dateTime.min.js') !!} --}}
    {!! Theme::asset()->container('specific-js')->usePath()->add('laydate-js', 'libs/layDate-v5.0.9/laydate/laydate.js') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('ajaxFileUpload-js', 'libs/jquery.ajaxFileUpload.js') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('mloading-js', 'libs/jquery.mloading.js') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('mloading-css', 'libs/jquery.mloading.css') !!}