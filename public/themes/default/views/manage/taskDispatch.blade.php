
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">任务派单</h3>
<div class="row">
    <div class="col-xs-12">
        <div class="clearfix  well">
            <div class="form-inline search-group">
                <form  role="form" action="/manage/taskDispatch" method="get">
                    <div class="form-group search-list">
                        <label for="name">任务名称　</label>
                        <input type="text" class="form-control" id="task_title" name="task_title" placeholder="请输入任务标题" @if(isset($merge['task_title']))value="{!! $merge['task_title'] !!}"@endif>
                    </div>
                    <div class="form-group search-list">
                        <label for="namee">用户名　　</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="请输入用户名" @if(isset($merge['username']))value="{!! $merge['username'] !!}"@endif>
                    </div>
                    <div class="form-group search-list">
                        <label for="namee">企业名称　　</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="请输入企业名称" @if(isset($merge['company_name']))value="{!! $merge['company_name'] !!}"@endif>
                    </div>
                    <div class="form-group">
                    	<button type="submit" class="btn btn-primary btn-sm">搜索</button>
                    </div>
                    <div class="space"></div>
                    <div class="form-inline search-group" >
                        <div class="form-group search-list">
                            <select class="" name="time_type">
                                <option value="task.created_at" @if(isset($merge['time_type']) && $merge['time_type'] == 'task.created_at')selected="selected"@endif>发布时间</option>
                                <option value="task.verified_at" @if(isset($merge['time_type']) && $merge['time_type'] == 'task.verified_at')selected="selected"@endif>审核时间</option>
                                <option value="task.begin_at" @if(isset($merge['time_type']) && $merge['time_type'] == 'task.begin_at')selected="selected"@endif>服务开始时间</option>
                                <option value="task.end_at" @if(isset($merge['time_type']) && $merge['time_type'] == 'task.end_at')selected="selected"@endif>服务结束时间</option>
                            </select>
                            <div class="input-daterange input-group">
                                <input type="text" name="start" class="input-sm form-control" value="@if(isset($merge['start'])){!! $merge['start'] !!}@endif">
                                <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                                <input type="text" name="end" class="input-sm form-control" value="@if(isset($merge['end'])){!! $merge['end'] !!}@endif">
                            </div>
                        </div>
                        <!-- <div class="form-group search-list width285">
                            <label class="">状态　</label>
                            <select name="status">
                                <option value="0">全部</option>
                                <option value="1" @if(isset($merge['status']) && $merge['status'] == '1')selected="selected"@endif>未发布</option>
                                <option value="2" @if(isset($merge['status']) && $merge['status'] == '2')selected="selected"@endif>待审核</option>
                                <option value="3" @if(isset($merge['status']) && $merge['status'] == '3')selected="selected"@endif>进行中</option>
                                <option value="4" @if(isset($merge['status']) && $merge['status'] == '4')selected="selected"@endif>已结束</option>
                                <option value="5" @if(isset($merge['status']) && $merge['status'] == '5')selected="selected"@endif>失败</option>
                                <option value="6" @if(isset($merge['status']) && $merge['status'] == '6')selected="selected"@endif>维权</option>
                            </select>
                        </div> -->
                    </div>

                </form>
            </div>
        </div>
        <div>
            <table id="sample-table" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th class="center">
                        
                    </th>
                    <th>序号</th>
                    <!-- <th>用户名</th> -->
                    <th>任务名称</th>

                    <th>
                        发布企业
                    </th>
                    <th>
                        任务人数
                    </th>
                    <th>已报名人数</th>
                    <th>
                        服务时间
                    </th>
                    <!-- <th>处理</th> -->
                </tr>
                </thead>
                <!-- <form action="/manage/taskMultiHandle" method="post"> -->
                    {!! csrf_field() !!}
                    <tbody>
                    @foreach($task as $k => $item)
                        <tr>
                            <td class="center">
                                <label class="pos-rel">
                                    <input type="checkbox" class="ace" name="chk" value="{!! $item->id !!}"/>
                                    <span class="lbl"></span>
                                </label>
                            </td>

                            <td>
                                {{ $listArr['per_page']*($listArr['current_page']-1)+($k + 1)  }}
                            </td>
                            <td><a href="/manage/taskDetail2/{{ $item->id }}" target="_Self">{!! $item->title !!}</a></td>
                            <td>
                                {!! $item->company_name !!}
                            </td>
                            <td>{!! $item->worker_num !!}</td>
							<td>{!! $item->delivery_count !!}</td>
                            <td>
                                {{ date('Y年m月d日 H:i',strtotime($item->begin_at)) }}—{{ date('Y年m月d日 H:i',strtotime($item->end_at)) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                <!-- </form> -->
            </table>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="dataTables_info" id="sample-table-2_info" role="status" aria-live="polite">
                	<!-- <label class="position-relative mg-right10">
                        <input type="checkbox" class="ace" />
                        <span class="lbl"> 全选</span>
                    </label> -->
                    <button class="btn btn-primary btn-sm" onclick="dispatchTask()">新增派单</button>
                    <button class="btn btn-primary btn-sm" onclick="templateDownload()">模板下载</button>
                    <button class="btn btn-primary btn-sm" onclick="batchImport()">批量派单</button>
                </div>
            </div>
            <div class="space-10 col-xs-12"></div>
            <div class="col-xs-12">
                <div class="dataTables_paginate paging_simple_numbers row" id="dynamic-table_paginate">
                    {!! $task->appends($merge)->render() !!}
                </div>
            </div>
        </div>
    </div>
</div><!-- /.row -->
<script type="text/javascript">
	function dispatchTask(){
		var value = '';
		var checkedArr = $("input[name='chk']:checked");
		if(checkedArr.length!=1){
			popUpMessage('请选择一个任务进行派单');	
			return;
		}
		for(var i = 0; i < checkedArr.length; i++){
			value += $(checkedArr[i]).val() + ',';
		}

		window.location.href = "/manage/createTaskDispatch/"+value.substr(0, value.length-1);
	}

	function templateDownload(){
		var value = '';
		var checkedArr = $("input[name='chk']:checked");
		if(checkedArr.length===0){
			popUpMessage('请选择至少一个任务再进行模板下载');
			return;
		}	
		for(var i = 0; i < checkedArr.length; i++){
			value += $(checkedArr[i]).val() + ',';
		}
		console.log(value);
		createForm('/manage/taskDispatchDownload', value.substr(0, value.length-1))
	}
	
	function batchImport(){
		window.location.href = "{!! url('manage/taskDispatchImport') !!}";
	}

	function createForm(action, value){
		var form = document.createElement('form');
		form.action = action;
		form.method = 'POST';
		var chk = document.createElement('input');
		chk.type = 'hidden'; 
		chk.name = 'chk';    
		chk.value = value?value:'';    
		form.appendChild(chk);  
		var token = document.createElement('input');
		token.type = 'hidden'; 
		token.name = '_token';    
		token.value = "{{ csrf_token() }}";    
		form.appendChild(token); 
		$(document.body).append(form);    
		form.submit();
		document.body.removeChild(form);
	
	}
</script>

{!! Theme::asset()->container('custom-css')->usepath()->add('backstage', 'css/backstage/backstage.css') !!}

{{--时间插件--}}
{!! Theme::asset()->container('specific-css')->usePath()->add('datepicker-css', 'plugins/ace/css/datepicker.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('datepicker-js', 'plugins/ace/js/date-time/bootstrap-datepicker.min.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('userfinance-js', 'js/userfinance.js') !!}