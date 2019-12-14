<style>
        .form-group div{
            padding-top: 4px;
        }
        .jn span {
            background-color: #3da7f4;
            display: inline-block;
            margin-right: 40px;
            position: relative;
            padding-left: 10px;
            padding-right: 10px;
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
            border: 9px solid #3da7f4;
            position: absolute;
            right: -18px;
            top: 1px;
            border-color:transparent transparent transparent #3da7f4;
        }
        .table_box {
            width: 100%;
        }
        .table_box table {
            width: 100%;
            line-height: 36px;
            text-align: left;
            color: rgb(103, 106, 108);
        }
        .table_box table tr{
            width: 100%;
            height: 36px;
            display: block;
            border-bottom: 1px solid rgb(103, 106, 108);;
            display: flex;
        }
        .table_box table tr th,.table_box table tr td{
            flex: 1;
        }
        .users {
            width: 100%;
            position: relative;
            height:21px;
        }
        .users ul{
            width: 60%;
            height: 300px;
            position: absolute;
            bottom: 36px;
            right: 0px;
            background-color: #fff;
            border: 1px solid #999;
            padding: 20px;
            overflow-y: auto;
            display: none;
        }
        .users ul input {
            border: 1px solid #999;
            width: 100%;
        }
        .users ul li {
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .users ul li img {
            width: 24px;
            height: 24px;
            display: none;
        }
        .users ul .users_check img {
            width: 24px;
            height: 24px;
            display: block;
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
        .zs_img {
            flex-wrap: wrap;
        }
        .zs_img div {
            margin-right : 1rem;
            display: inline-block;
            position: relative;
        }
        .zs_img div em {            
            font-style: normal;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            color: #fff;
            background-color: red;
            position: absolute;
            right: -5px;
            top: -5px;
            text-align: center;
            line-height: 20px;
            cursor: pointer
        }
        
</style>
{{--<div class="well">
    <h4 >任务结算信息</h4>
</div>--}}
{{--<div class="row">
    <div class="col-xs-12 widget-container-col ui-sortable">
        <div class="widget-box transparent ui-sortable-handle">
            <div class="widget-header">--}}
                {{--<h3 class="widget-title lighter">任务详情/ <small>任务需求</small></h3>--}}

            
            <div class="widget-body">
                <div class="widget-main paddingTop no-padding-left no-padding-right">
                    <div class="tab-content padding-4">
                        <div id="need" class="tab-pane active">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="/manage/taskSettleUpload" method="post" id="taskform" class="form-horizontal" enctype="multipart/form-data">
                                        <div class="g-backrealdetails clearfix bor-border interface">
                                            <div class="space-8 col-xs-12"></div>
                                            <input type="hidden" name="w_id" value="{{$work['w_id']}}">
                                            <input type="hidden" name="w_task_id" value="{{$work['w_task_id']}}">                                            
                                            <input type="hidden" name="w_uid" value="{{$work['w_uid']}}">
                                            <div class="col-xs-12"><b><u>任务结算信息</u></b></div>
                                            <div class="interface-bottom col-xs-12">
                                            	<div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 任务名称： </label>
    
                                                    <div class="col-sm-9">
                                                    	{{ $work['title'] }}
                                                        
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 任务主类别： </label>
    
                                                    <div class="col-sm-9">
                                                    	{{ $work['mt_type_name'] }}
                                                        <!-- <label><input type="text" value="悬赏模式" readonly="true"/></label> -->
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 发布企业： </label>
    
                                                    <div class="col-sm-9">
                                                        {{ $work['company_name'] }}
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 接包人姓名： </label>
                                                    <div class="col-sm-9">
                                                       	{{$work['realname']}}
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-12">
                                                	<div class="col-xs-6">
                                                		<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 验收时间： </label>
                                                        <div class="col-sm-9">
                                                           	{{$work['w_checked_at']}}
                                                        </div>
                                                	</div>                                                    
                                                </div>
                                                <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 结算金额： </label>
                                                    <div class="col-sm-9">
                                                       	{{$work['w_payment']}}
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 结算时间： </label>
                                                    <div class="col-sm-9">
                                                       	{{$work['w_settle_at']}}
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                {{-- <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 服务时间： </label>
    
                                                    <div class="col-sm-9">
                                                    	{{ date('Y年m月d日 H:i',strtotime($work['begin_at'])) }}	至 {{ date('Y年m月d日 H:i',strtotime($work['end_at'])) }}
                                                        
                                                    </div>
                                                </div>                                                
                                                <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 任务预算： </label>
    
                                                    <div class="col-sm-9">
                                                        {{ $work['bounty'] }}(元)                                          
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 任务子类别： </label>
    
                                                    <div class="col-sm-9">
                                                    	{{ $work['st_type_name'] }}
                                                        <!-- <label><input type="text" value="悬赏模式" readonly="true"/></label> -->
                                                    </div>
                                                </div>   
                                                <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 任务标签： </label>
    
                                                    <div class="col-sm-9 jn">
                                                    	@foreach($t_tag as $v)                                                    	
                                                        	<span>{{ $v['tag_name'] }}<i></i></span>
                                                        @endforeach                                                	
                                                    </div>
                                                </div>                                           
                                                
                                                <div class="form-group interface-bottom col-xs-12">
                                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 服务地址： </label>
    
                                                    <div class="col-sm-9">
                                                        {{ $work['address'] }}
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-12">
                                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 任务详情： </label>
    
                                                    <div class="col-sm-9">
                                                        {!! htmlspecialchars_decode($work['desc']) !!}
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-12">
                                                	<label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 任务图片： </label>
                                                	<div class="col-sm-9">
                                                        @foreach($t_attachment as $k=>$v)
                                                            <img alt="150x150" src="{!! url($v['url']) !!}" style="width: 10rem;height: 10rem;" onclick="bigimg(this)">                                                   
                                                        @endforeach
                                                    </div>
                                                </div>                                            
                                                <div class="form-group interface-bottom col-xs-12">
                                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">  验收状态： </label>
    
                                                    <div class="col-sm-9">
                                                       @if($work['w_status']===2)
                                                       		已交付
                                                       @elseif($work['w_status']===3)
                                                       		已验收
                                                       @elseif($work['w_status']===4)
                                                       		被驳回
                                                       @elseif($work['w_status']===5)
                                                       		已结算
                                                       @elseif($work['w_status']===6)
                                                       		已终止
                                                       @endif
                                                    </div>
                                                </div>--}} 
                                            </div>
                                            
                                            
                                            <div class="col-xs-12"><b><u>已有验收材料</u></b></div>
                                            <div class="interface-bottom col-xs-12">
                                            	<div class="form-group interface-bottom col-xs-12">
                                                	<label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 提交图片： </label>
                                                	<div class="col-sm-9">
                                                        @foreach($w_attachment as $k=>$v)
	                                                        @if(@getimagesize(url($v['url'])))
	                                                        	 <img alt="150x150" src="{!! url($v['url']) !!}" style="width: 10rem;height: 10rem;" onclick="bigimg(this)">  
	                                                        @else
	                                                        	<a href="{!! url($v['url']) !!}" target="view_window" style="margin: 0 20px;"> 下载文件</a>
	                                                        @endif
                                                        @endforeach
                                                    </div>
                                                </div> 
                                            	
                                                {{-- <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 接包人手机号： </label>
                                                    <div class="col-sm-9">
                                                       	{{$work['w_mobile']}}
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-12">
                                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 擅长技能： </label>
    
                                                    <div class="col-sm-9 jn">
                                                    	@foreach($w_tag as $v)                                                    	
                                                        	<span>{{ $v['tag_name'] }}<i></i></span>
                                                        @endforeach                                                	
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-12">
                                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 身份证号码： </label>
                                                    <div class="col-sm-9">
                                                       	{{$work['w_card_number']}}
                                                    </div>
                                                </div>--}}
                                            </div>
                                            
                                            @if($action==='upload')
                                            <div class="col-xs-12"><b><u>上传验收材料</u></b></div>
                                            <div class="interface-bottom col-xs-12">
                                            	<div class="form-group interface-bottom col-xs-12">
                                                	<label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 文件上传： </label>
                                                	<div class="col-sm-9">
                                                        <div class="zs_img">
                                                            
                                                        </div>
                                                        <input type="file" name="file[]" id="file" onchange="imgfile(this)" multiple>
                                                        	@if($errors->first('uploadFiles'))
                                                				<p class="Validform_checktip Validform_wrong">{!! $errors->first('uploadFiles') !!}</p>
                                                			@endif
                                                    </div>
                                                </div> 
                                            
                                            	<input type="hidden" name="file_id">
                                            
                                            	{{--<div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 提交时间： </label>
                                                    <div class="col-sm-9">
                                                       	{{$work['w_delivered_at']}}
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-6">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 提交说明： </label>
                                                    <div class="col-sm-9">
                                                       	{{$work['w_desc']}}
                                                    </div>
                                                </div>
                                                <div class="form-group interface-bottom col-xs-12">
                                                	<label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 提交图片： </label>
                                                	<div class="col-sm-9">
                                                        @foreach($w_attachment as $k=>$v)
                                                            <img alt="150x150" src="{!! url($v['url']) !!}" style="width: 10rem;height: 10rem;" onclick="bigimg(this)">                                                  
                                                        @endforeach
                                                    </div>
                                                </div> --}}
                                            </div>
                                            @endif
                                            
                                            {{ csrf_field() }}                                            
                                            <div class="col-xs-12">
                                                <div class="clearfix row bg-backf5 padding20 mg-margin12">
                                                    <div class="col-xs-12">
                                                        <div class="col-sm-1 text-right"></div>
                                                        <div class="col-sm-10">      
                                                        @if($action==='upload')
                                                        <button class="btn btn-sm btn-primary" type="submit">提交</button>  
                                                         @endif                                   
                                                        <div class="btn btn-sm btn-primary" style="margin-left: 50px" onclick="toBack()">返回</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                           {{-- <div class="space-10"></div>
                                            <div class="clearfix form-actions">
                                                <div class="col-md-offset-3 col-md-9 ">
                                                    <div class="row">
                                                        <button class="btn btn-info" type="submit" form="seo-form" >
                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                            	提交
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-24"></div>--}}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.row -->
	<div class="bigimg" onclick="$('.bigimg').css('display','none')">
        <img src="" alt="">
    </div>
<script type="text/javascript">
var cnt = '{{count($w_attachment)}}';
var max = '{{$a_config["number"]}}';
//var arr = [];
function toBack(){
	window.location.href = '{!! url("manage/taskSettle") !!}'
}
function bigimg(val){
    $('.bigimg > img')[0].src = val.src
    $('.bigimg').css('display','flex')
}
function imgfile(val){

	var files = document.getElementById('file').files;

	if(files.length+parseInt(cnt)+$(".zs_img").children().length>parseInt(max)){
		popUpMessage('最多上传'+parseInt(max)+'张');
		$("#file").val('');
		return;
	}
	
    if($(".zs_img").children().length < parseInt(max)){			
		for(var i = 0; i < files.length; i++){
			var f = files[i];  
			//arr.push(f);
			var reads = new FileReader();
			reads.readAsDataURL(f);
			reads.onload = function(e) {
	            $(".zs_img").css("display", "flex");
	            var html = '<div><img src ="'+this.result+'" onclick="bigimg(this)"><em data-name="'+f.name+'" onclick="delimg(this)">X</em></div>'
				$(".zs_img").append(html)            
	        };
		}
		//console.log(arr);
		uploadFile();       
    }else{
    	popUpMessage('最多上传'+parseInt(max)+'张')
    }
}
function delimg(val){
	$(val).parent().remove()
	event.stopPropagation()
	if($(".zs_img").children().length == 0){
		$(".zs_img").css("display", "none");
	} 
	/* for(var i =0; i< arr.length;i++){
		if($(val).attr('data-name') == arr[i].name){
			arr.splice(i,1)
		}
	}
	console.log(arr); */
	deleteFile($(val).parent()[0].id);
}

/**
上传文件
*/
function uploadFile(){
    $("body").mLoading({text:"上传中"});//显示loading组件
    var headers = { "_token": "{{ csrf_token() }}"};
    var data = { "uid": "{{$work['w_uid']}}"};
    // 开始上传
    $.ajaxFileUpload({
        secureuri: false,// 是否启用安全提交，默认为 false
        type: "POST",
        url: "/manage/fileMultipleUpload",
        fileElementId: "file",// input[type=file] 的 id
        dataType: "json",// 返回值类型，一般位 `json` 或者 `text`
        data: data,// 添加参数，无参数时注释掉
        header: headers,
        success: function (ret, status) {
            // 成功
        	console.log(ret); 
        	if(ret&&ret.ids&&ret.ids.length>0){
        		var attachmentId = $("input[name='file_id']").val();
        		var files = $(".zs_img").children();
				for(var i = 0; i < ret.ids.length; i++){
					attachmentId += (attachmentId?",":"")+ret.ids[i];
					if(files&&files.length>0){
	        			files[files.length-ret.ids.length+i].id = 'file_'+ret.ids[i];
	            	}
				}
        		$("input[name='file_id']").val(attachmentId);
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
    $.get('/manage/fileSingleDelete',{'id':ids[1]},function(data){
        console.log(data);            
    });
}
</script>
{{-- {!! Theme::widget('editor')->render() !!}
{!! Theme::widget('ueditor')->render() !!} --}}
{!! Theme::asset()->container('custom-css')->usePath()->add('backstage', 'css/backstage/backstage.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('ajaxFileUpload-js', 'js/jquery.ajaxFileUpload.js') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('mloading-js', 'js/jquery.mloading.js') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('mloading-css', 'js/jquery.mloading.css') !!}