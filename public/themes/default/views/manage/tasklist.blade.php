
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">任务列表</h3>
<style>
    	.search-list{
    		padding-right:12px !important;
    	}
    </style>
<div class="row">
    <div class="col-xs-12">
        <div class="clearfix  well">
            <div class="form-inline search-group">
                <form  role="form" action="/manage/taskList" method="get">
                    <div class="form-group search-list">
                        <label for="name">任务名称</label>
                        <input type="text" class="form-control" id="task_title" name="task_title" placeholder="请输入任务名称" @if(isset($merge['task_title']))value="{!! $merge['task_title'] !!}"@endif>
                    </div>
                    <div class="form-group search-list">
                        <label for="namee">企业名称</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="请输入企业名称" @if(isset($merge['company_name']))value="{!! $merge['company_name'] !!}"@endif>
                    </div>
                    <div class="form-group search-list">
                            <select class="" name="time_type">
                                <option value="task.created_at" @if(isset($merge['time_type']) && $merge['time_type'] == 'task.created_at')selected="selected"@endif>发布时间</option>
                                <option value="task.verified_at" @if(isset($merge['time_type']) && $merge['time_type'] == 'task.verified_at')selected="selected"@endif>审核时间</option>
                            </select>
                            <div class="input-daterange input-group">
                                <input type="text" name="start" class="input-sm form-control" value="@if(isset($merge['start'])){!! $merge['start'] !!}@endif">
                                <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                                <input type="text" name="end" class="input-sm form-control" value="@if(isset($merge['end'])){!! $merge['end'] !!}@endif">
                            </div>
                     </div>
                     <div class="form-group search-list">
                            <label class="">状态　</label>
                            <select name="status">
                                <option value="0">全部</option>
                                <option value="1" @if(isset($merge['status']) && $merge['status'] == '1')selected="selected"@endif>已通过</option>
                                <option value="2" @if(isset($merge['status']) && $merge['status'] == '2')selected="selected"@endif>未审核</option>
                                <option value="3" @if(isset($merge['status']) && $merge['status'] == '3')selected="selected"@endif>未通过</option>
                                <!-- <option value="4" @if(isset($merge['status']) && $merge['status'] == '4')selected="selected"@endif>已结束</option>
                                <option value="5" @if(isset($merge['status']) && $merge['status'] == '5')selected="selected"@endif>失败</option>
                                <option value="6" @if(isset($merge['status']) && $merge['status'] == '6')selected="selected"@endif>维权</option> -->
                            </select>
                    </div>
                    <div class="form-group">
                    	<button type="submit" class="btn btn-primary btn-sm">搜索</button>
                    </div>
                    </form>
                    <div class="form-inline search-group" style="display: none;">
                        <div class="form-group search-list">
                            <select class="" name="time_type">
                                <option value="task.created_at" @if(isset($merge['time_type']) && $merge['time_type'] == 'task.created_at')selected="selected"@endif>发布时间</option>
                                <option value="task.verified_at" @if(isset($merge['time_type']) && $merge['time_type'] == 'task.verified_at')selected="selected"@endif>审核时间</option>
                            </select>
                            <div class="input-daterange input-group">
                                <input type="text" name="start" class="input-sm form-control" value="@if(isset($merge['start'])){!! $merge['start'] !!}@endif">
                                <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                                <input type="text" name="end" class="input-sm form-control" value="@if(isset($merge['end'])){!! $merge['end'] !!}@endif">
                            </div>
                        </div>
                        <div class="form-group search-list width285">
                            <label class="">状态　</label>
                            <select name="status">
                                <option value="0">全部</option>
                                <option value="1" @if(isset($merge['status']) && $merge['status'] == '1')selected="selected"@endif>已通过</option>
                                <option value="2" @if(isset($merge['status']) && $merge['status'] == '2')selected="selected"@endif>未审核</option>
                                <option value="3" @if(isset($merge['status']) && $merge['status'] == '3')selected="selected"@endif>未通过</option>
                                <!-- <option value="4" @if(isset($merge['status']) && $merge['status'] == '4')selected="selected"@endif>已结束</option>
                                <option value="5" @if(isset($merge['status']) && $merge['status'] == '5')selected="selected"@endif>失败</option>
                                <option value="6" @if(isset($merge['status']) && $merge['status'] == '6')selected="selected"@endif>维权</option> -->
                            </select>
                        </div>
                    </div>
                
            </div>
        </div>
        <div>
            <table id="sample-table" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th class="center">
                        <input type="checkbox" class="ace"/>
                        <span class="lbl"></span>
                    </th>
                    <th>编号</th>
                    <!-- <th>用户名</th> -->
                    <th>任务标题</th>
					 <th>任务主类别</th>
                    <th>
                        发布企业
                    </th>
                    <th>
                        任务预算
                    </th>
                    <th>任务状态</th>
                    <!-- <th>
                        审核时间
                    </th> -->
                    <th>操作</th>
                </tr>
                </thead>
                <form id="rms" action="/manage/taskMultiHandle" method="post">
                    {!! csrf_field() !!}
                    <tbody>
                    @foreach($task as $k=>$item)
                        <tr>
                            <td class="center">
                                <label class="pos-rel">
                                    <input type="checkbox" class="ace" name="ckb[]" value="{!! $item->id !!}"/>
                                    <span class="lbl"></span>
                                </label>
                            </td>

                            <td>
                                <!--<a href="#">{!! $k+1 !!}</a>-->
                                <a href="#">
                                	
                                	@if(intval($tasks['current_page'])>1)
		                            {{ $tasks['per_page']*($tasks['current_page']-1)+($k + 1)  }}
		                            @else
		                             {{ $k + 1 }}                            
		                            @endif
                           		</a>
                            </td>
                            
                            <td>{!! $item->title !!}</td>
                            <td>{!! $item->cname !!}</td>
                            <td>{!! $item->company_name !!}</td>
                            <td>{!! $item->bounty !!}</td>
                            
                            
                            <!-- <td class="hidden-480">
                                @if($item->status >=2)<a target="_blank" href="/task/{!! $item->id  !!}">{!! $item->title !!}</a>@else{!! $item->title !!} @endif
                            </td>
                            <td>{!! $item->cname !!}</td>
                            <td>{!! $item->created_at !!}</td> -->
							
                            <td class="hidden-480">
                                @if($item->status < 2)
                                    <span class="label label-sm label-warning">未通过</span>
                                @elseif($item->status == 2)
                                    <span class="label label-sm label-success">待审核</span>
                                @elseif($item->status == 8)
                                    <span class="label label-sm label-success">已验收</span>
                                @elseif($item->status == 9)
                                    <span class="label label-sm label-success">已结算</span>
                                @elseif($item->status >= 3)
                                    <span class="label label-sm label-danger ">已通过</span>
                                @endif
                            </td>

                            <!-- <td>
                                @if($item->bounty_status)已托管@else未托管@endif
                            </td>

                            <td>
                                @if(isset($item->verified_at)){!! $item->verified_at !!}@else N/A @endif
                            </td> -->

                            <td>
                                <div class="hidden-sm hidden-xs btn-group">
                                	@if($item->status==2)
                                        <a class="btn btn-xs btn-success" href="/manage/taskHandle/{!! $item->id !!}/pass" style="padding: 1px !important;">
                                            <i class="ace-icon fa fa-check bigger-120">通过</i>
                                        </a>
                                        <a class="btn btn-xs btn-danger" href="/manage/taskHandle/{!! $item->id !!}/deny" style="padding: 1px !important;">
                                            <i class="ace-icon fa fa-minus-circle bigger-120"> 拒绝</i>
                                        </a>
                                    @endif
										<a href="/manage/taskInfo/{{ $item->id }}" class="btn btn-xs btn-info" style="padding: 1px !important;">
	                                        <i class="fa fa-info bigger-120">详情</i>
	                                    </a>
	                                    <a href="/manage/taskUpdate/{{ $item->id }}" class="btn btn-xs btn-info" style="padding: 1px !important;">
	                                        <i class="ace-icon fa fa-edit bigger-120">编辑</i>
	                                    </a>
										 <a onclick="confdel(this)" data-data="/manage/taskHandle/{!! $item->id !!}/del" class="btn btn-xs btn-danger" href="javascript:void(0)" style="padding: 1px !important;">
	                                            <i class="ace-icon fa fa-trash-o bigger-120"> 删除</i>
	                                    </a>
	                                    <a class="btn btn-xs btn-danger" style="background-color: #23c6c8 !important;border: #23c6c8 !important;" href="/manage/taskVoucher/{{ $item->id }}" >
	                                            <i class="ace-icon fa fa-link bigger-120"> 上传凭证</i>
	                                    </a>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                
            </table>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="dataTables_info" id="sample-table-2_info" role="status" aria-live="polite">
                	<!--<label class="position-relative mg-right10">
                        <input type="checkbox" class="ace" />
                        <span class="lbl"> 全选</span>
                    </label>-->
                    <button type="submit" class="btn btn-primary btn-sm">批量审核</button>
                    <button type="button" onclick="refuse()" class="btn btn-primary btn-sm">批量拒绝</button>
                    <button type="button" onclick="refdel()" class="btn btn-primary btn-sm">批量删除</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='/manage/taskAdd'">添加</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='/attachment/sys/templates/template_task.xlsx'"  >模板下载</button>
                    <button type="button"  onclick="window.location.href='/manage/taskListImport'" class="btn btn-primary btn-sm" >批量导入</button>
                    <!--<button type="button"  onclick="fileipt()" class="btn btn-primary btn-sm" >批量导入</button>-->
                </div>
            </div>
            <div class="space-10 col-xs-12"></div>
            <div class="col-xs-12">
            	<div class="col-xs-4 " id="">
                	@if($tasks['current_page']!=$tasks['last_page'])
					    显示第 &nbsp;{{ $tasks['per_page']*($tasks['current_page']-1)+1 }}&nbsp;到第
					    &nbsp;{{ $tasks['per_page']*$tasks['current_page'] }}&nbsp;条记录
					@elseif($tasks['current_page']==$tasks['last_page'] && $tasks['per_page']*($tasks['current_page']-1)+1!=$tasks['total'])
					    显示第&nbsp;{{ $tasks['per_page']*($tasks['current_page']-1)+1 }}&nbsp;到第
					    &nbsp;{{ $tasks['total'] }}&nbsp;条记录
					@else
					    显示第&nbsp;{{ $tasks['total'] }}&nbsp;条记录
					@endif
					总共&nbsp; {{ $tasks['total'] }} &nbsp;条记录&nbsp;&nbsp;
					每页显示 
					<div class="btn-group dropup">
						<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button" style="background-color: #fff !important;color: #333 !important;border-color: #ccc !important;border: 1px solid;">{{ $tasks['per_page'] }}<span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="/manage/taskList?paginate=10">10</a></li>
							<li><a href="/manage/taskList?paginate=20">20</a></li>
							<li><a href="/manage/taskList?paginate=30">30</a></li>
							<li><a href="/manage/taskList?paginate=50">50</a></li>
						</ul>
					</div>
					条
                </div>
                <div class="col-xs-8 dataTables_paginate paging_simple_numbers row" id="dynamic-table_paginate">
                    {!! $task->appends($merge)->render() !!}
                </div>
            </div>
        </div>
        </form>
    </div>
    <form id="exceform" action="/manage/taskExcel" method="post" enctype="multipart/form-data">
    	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
    	<input type="file" id="exce" name="tasklistexcel" style="display: none;" accept="xls" onchange="ss()" />
    </form>
</div><!-- /.row -->

<script>
	console.log("{{$tasks['per_page']}}");
	function confdel(obj){
		if(confirm("确定要删除吗?"))
	     {
	     	window.location.href=$(obj).attr('data-data');
	     }else{
	     	console.log(22222);
		   return false;
		 }
	}
	function refuse(){
		$("#rms").append('<input type="hidden" name="action" value="deny"/>');
		$("#rms").submit();
	}
	function refdel(){
		$("#rms").append('<input type="hidden" name="action" value="del"/>');
		$("#rms").submit();
	}
	function fileipt(){
		$('#exce').click();
	}
	function ss(){
		$("#exceform").submit();
		console.log(document.getElementById("exce").files[0]);
		console.log(11111);
	}
</script>

{!! Theme::asset()->container('custom-css')->usepath()->add('backstage', 'css/backstage/backstage.css') !!}

{{--时间插件--}}
{!! Theme::asset()->container('specific-css')->usePath()->add('datepicker-css', 'plugins/ace/css/datepicker.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('datepicker-js', 'plugins/ace/js/date-time/bootstrap-datepicker.min.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('userfinance-js', 'js/userfinance.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('checked-js', 'js/checkedAll.js') !!}