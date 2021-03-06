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
            border-bottom:1px solid #999;
        }
        .users ul{
            /* width: 60%;
            height: 300px;
            position: absolute;
            bottom: 36px;
            right: 0px;
            background-color: #fff;
            border: 1px solid #999;
            padding: 20px;
            overflow-y: auto;
            display: none; */
            width: 100%;
            height: 0px;
            position: absolute;
            bottom: 36px;
            right: 0px;
            background-color: #fff;
            padding: 0px;
            overflow-x: hidden;
            overflow-y: hidden;
            box-sizing: border-box;
            transition: height 0.5s linear;
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
            line-height: 30px;
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
</style>
{{-- <div class="well">
    <h4 >任务详情ss</h4>
</div> --}}
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
                                    <form action="
                                    @if(isset($type))
                                    	@if($type==='dispatch')
                                    		/manage/createTaskDispatch
                                    	@endif
                                    @endif
                                    " method="post" id="taskform" class="form-horizontal">
                                        <div class="g-backrealdetails clearfix bor-border interface">
                                            <div class="space-8 col-xs-12"></div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 任务名称： </label>

                                                <div class="col-sm-9">
                                                	{{ $task['title'] }}
                                                    <!-- <input type="text" class="col-sm-5" name="title" value="{{ $task['title'] }}"> -->
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 服务时间： </label>

                                                <div class="col-sm-9">
                                                	{{ date('Y年m月d日 H:i',strtotime($task['begin_at'])) }}	至 {{ date('Y年m月d日 H:i',strtotime($task['end_at'])) }}
                                                    <!-- <input type="text" class="col-sm-5" name="title" value="{{ $task['title'] }}"> -->
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 任务主类别： </label>

                                                <div class="col-sm-9">
                                                	{{ $task['type_name'] }}
                                                    <!-- <label><input type="text" value="悬赏模式" readonly="true"/></label> -->
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 任务子类别： </label>

                                                <div class="col-sm-9">
                                                	{{ $task['sub_type_name'] }}
                                                    <!-- <label><input type="text" value="悬赏模式" readonly="true"/></label> -->
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 任务预算： </label>

                                                <div class="col-sm-9">
                                                    {{ $task['bounty'] }}(元)                                          
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 任务人数： </label>

                                                <div class="col-sm-9">
                                                    {{ $task['worker_num'] }}(人)
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 任务标签： </label>

                                                <div class="col-sm-9 jn">
                                                	@foreach($tags as $v)                                                    	
                                                    	<span>{{ $v['tag_name'] }}<i></i></span>
                                                    @endforeach                                                	
                                                </div>
                                            </div>                                            
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 发布企业： </label>

                                                <div class="col-sm-9">
                                                    {{ $task['company_name'] }}
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 服务地址： </label>

                                                <div class="col-sm-9">
                                                    {{ $task['address'] }}
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 任务详情： </label>

                                                <div class="col-sm-9">
                                                    {!! htmlspecialchars_decode($task['desc']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1">  任务图片： </label>

                                                <div class="col-sm-9">
                                                    @foreach($taskAttachment as $k=>$v)
                                                    	@if($v['atstatus']!=3)
                                                        <!-- <button>附件{{ ($k+1) }}</button><a href="{{ URL('task/download',['id'=>$v['attachment_id']]) }}" target="_blank">下载</a>&nbsp;&nbsp; -->
                                                    	<img alt="150x150" src="{!! url($v['url']) !!}" style="width: 10rem;height: 10rem;" onclick="bigimg(this)">                                                   
                                                    	@endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 任务进程： </label>
                                                <div class="col-sm-9">
                                                    <div class="table_box">
                                                        <table style="table-layout: fixed;">
                                                            <tr>
                                                                <th>接单人</th>
                                                                <th>身份证</th>
                                                                <th>状态</th>
                                                                <th>操作</th>
                                                            </tr>
                                                            @if(isset($works))
                                                            	@foreach($works as $v)
                                                            	<tr>
                                                            		<td>{{ $v['nickname'] }}</td>
                                                            		<td>{{ $v['card_number'] }}</td>
                                                            		<td>
                                                            		@if($v['status']==0)
                                                            			已报名待审核
                                                            		@elseif($v['status']==1)
                                                            			已派单待交付
                                                            		@elseif($v['status']==2)
                                                            			已交付待验收
                                                            		@elseif($v['status']==3)
                                                            			已验收待结算
                                                            		@elseif($v['status']==5)
                                                            			已结算
                                                            		@else
                                                            			未知状态
                                                            		@endif
                                                            		</td>
                                                            		<td>
                                                            		
                                                            		</td>
                                                            	</tr>
                                                            	@endforeach
                                                            @endif
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group interface-bottom col-xs-12">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 凭证： </label>
                                                <div class="col-sm-9">
                                                    <div class="table_box">
                                                        <table style="table-layout: fixed;">
                                                            <tr>
                                                                <th>上传时间</th>
                                                                <th>上传文件</th>
                                                            </tr>
                                                            @foreach($taskAttachment as $k=>$v)
											                	@if($v['status']==3)
											                	<tr>
											                		<td>{{$v['created_at']}}</td>
											                		<td><a href="{!! url($v['url']) !!}" download="">{!! $v['name'] !!}</a> </td>
											                    </tr>
											                    @endif
										                    @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if(isset($type)&&$type==='dispatch')
                                            <div class="form-group interface-bottom col-xs-12">
                                            	<label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 收单人(限选{{$rest_worker}}人)： </label>
                                            	<div class="col-sm-9">
                                            	<div class="users" onclick="usersli()">
                                                    <span></span>
                                                    <ul>
                                                        <input type="text" onclick="event.stopPropagation()" oninput="iptuser(this)">
                                                        <div class="star_user" onclick="event.stopPropagation()">
                                                        @if(isset($users))
                                                        	@foreach($users as $item)
                                                        	<li data-id="{{$item['uid']}}" onclick="userscheck(this)">{{$item['realname']}} {{$item['card_number']}}<img src="/themes/default/assets/images/tick.png"></li>
                                                        	@endforeach
                                                        @endif    
                                                        </div>        
                                                        <div class="end_user" onclick="event.stopPropagation()">

														</div>                                            
                                                    </ul>
                                                </div>
                                                </div>
                                                <input type="hidden" name="users_id" />
                                            </div>
                                            @endif
                                            
                                            
                                            <input type="hidden" name="task_id" value='{{ $task['id'] }}' />
                                            {{ csrf_field() }}                                            
                                            <div class="col-xs-12">
                                                <div class="clearfix row bg-backf5 padding20 mg-margin12">
                                                    <div class="col-xs-12">
                                                        <div class="col-sm-1 text-right"></div>
                                                        <div class="col-sm-10">
                                                        	@if(isset($type))
						                                    	@if($type==='dispatch')
						                                    		<button class="btn btn-sm btn-primary" type="submit">提交</button>     
						                                    	@endif
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
function toBack(){
	window.location.href = '{!! url("manage/taskDispatch") !!}'
}

function usersli(){
    /* if($('.users').find('ul').css('display') == 'none'){
        $('.users').find('ul').css('display','block')
    }else{
        $('.users').find('ul').css('display','none')
    } */
	if($('.users').find('ul').css('height').split('px')[0] < 10){
		$('.users').find('ul').css('padding','20px')
		$('.users').find('ul').css('overflow-y','auto')
		$('.users').find('ul').css('height','300px')
		$('.users').find('ul').css('border','1px solid #999')
	}else{
		$('.users').find('ul').css('overflow-y','hidden')
		$('.users').find('ul').css('height','0px')
		setTimeout(function(){
    		$('.users').find('ul').css('padding','0px')
    		$('.users').find('ul').css('border','none')
		},500)
	}
}

var text = ''
@if(isset($type)&&$type==='dispatch')
var rest_worker = '{{$rest_worker}}';
@else
var rest_worker = 0;
@endif
/* $('.users').find('li').click(function(event){
    	event.stopPropagation()
        if($(this)[0].className == 'users_check'){
        	var userId = $(this).attr('data-id');
        	var userIds = $("input[name='users_id']").val();
        	userIds = userIds.replace(','+userId,'').replace(userId+',','').replace(userId,'')
        	$("input[name='users_id']").val(userIds);            
            text = text.split($(this).text())[0]+' '+text.split($(this).text()+'，')[1]
            $(this).parent().parent().find('span').text(text)
            $(this).removeClass('users_check')
        }else if($('.users_check').length < parseInt(rest_worker)){
            var userId = $(this).attr('data-id');
            var userIds = $("input[name='users_id']").val();
            text+=$(this).text() + '，'
    		$("input[name='users_id']").val(userIds+(userIds?",":"")+userId);
            $(this).parent().parent().find('span').text(text.substr(0, text.length-1))
            $(this).addClass('users_check')
        }
}) */
function userscheck(val){
	event.stopPropagation()
    if($(val)[0].className == 'users_check'){
    	var userId = $(val).attr('data-id');
    	var userIds = $("input[name='users_id']").val();
    	userIds = userIds.replace(','+userId,'').replace(userId+',','').replace(userId,'')
    	$("input[name='users_id']").val(userIds);            
        text = text.split($(val).text())[0]+' '+text.split($(val).text()+'，')[1]
        $(val).parent().parent().find('span').text(text)
        $(val).removeClass('users_check')
    }else if($('.users_check').length < parseInt(rest_worker)){
        var userId = $(val).attr('data-id');
        var userIds = $("input[name='users_id']").val();
        text+=$(val).text() + '，'
		$("input[name='users_id']").val(userIds+(userIds?",":"")+userId);
        $(val).parent().parent().parent().find('span').text(text.substr(0, text.length-1))
        $(val).addClass('users_check')
    }
}
var timer = null
function iptuser(val){
    var arr = $('.star_user').find('li') //筛选数组
    var arr1 = ''
    clearTimeout(timer)
    timer = setTimeout(() => {
    timer = null
    if(val.value.trim() != ''){
        for(let i = 0; i < arr.length; i++){
            if(arr[i].innerText.indexOf(val.value.trim()) != -1){
            	arr1+='<li onclick="userscheck(this)">'+arr[i].innerText+'<img src="/themes/default/assets/images/tick.png"></li>'
            }
        }
        $('.star_user').css('display','none')
        $('.end_user').css('display','block')
        $('.end_user').find('li').remove()
        $('.end_user').append(arr1)
    }else{
        $('.star_user').css('display','block')
        $('.end_user').css('display','none')
    }
    }, 500);
}
function bigimg(val){
    $('.bigimg > img')[0].src = val.src
    $('.bigimg').css('display','flex')
}
</script>
{!! Theme::widget('editor')->render() !!}
{!! Theme::widget('ueditor')->render() !!}
{!! Theme::asset()->container('custom-css')->usePath()->add('backstage', 'css/backstage/backstage.css') !!}