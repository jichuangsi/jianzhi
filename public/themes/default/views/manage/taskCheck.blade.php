
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">任务验收</h3>
<div class="row">
    <div class="col-xs-12">
        <div class="clearfix  well">
            <div class="form-inline search-group">
                <form  role="form" action="/manage/taskCheck" method="get">
                    <div class="form-group search-list">
                        <label for="name">任务名称　</label>
                        <input type="text" class="form-control" id="task_title" name="task_title" placeholder="请输入任务名称" @if(isset($merge['task_title']))value="{!! $merge['task_title'] !!}"@endif>
                    </div>
                    <div class="form-group search-list">
                        <label for="namee">接单人　　</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="请输入接单人" @if(isset($merge['username']))value="{!! $merge['username'] !!}"@endif>
                    </div>
                    <div class="form-group search-list">
                        <label for="namee">发布企业　　</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="请输入发布企业" @if(isset($merge['company_name']))value="{!! $merge['company_name'] !!}"@endif>
                    </div>
                    <div class="form-group">
                    	<button type="submit" class="btn btn-primary btn-sm">搜索</button>
                    </div>
                    <div class="space"></div>
                    <div class="form-inline search-group" >
                        <div class="form-group search-list">
                            <select class="" name="time_type">
                                <option value="task.delivered_at" @if(isset($merge['time_type']) && $merge['time_type'] == 'task.created_at')selected="selected"@endif>验收提交时间</option>                                
                            </select>
                            <div class="input-daterange input-group">
                                <input type="text" name="start" class="input-sm form-control" value="@if(isset($merge['start'])){!! $merge['start'] !!}@endif">
                                <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                                <input type="text" name="end" class="input-sm form-control" value="@if(isset($merge['end'])){!! $merge['end'] !!}@endif">
                            </div>
                        </div>
                        <div class="form-group search-list width285">
                            <label class="">验收状态　</label>
                            <select name="status">
                                <option value="0">全部</option>
                                <option value="1" @if(isset($merge['status']) && $merge['status'] == '1')selected="selected"@endif>通过</option>
                                <option value="2" @if(isset($merge['status']) && $merge['status'] == '2')selected="selected"@endif>驳回</option>
                                <option value="3" @if(isset($merge['status']) && $merge['status'] == '3')selected="selected"@endif>待审核</option>
                                <option value="4" @if(isset($merge['status']) && $merge['status'] == '4')selected="selected"@endif>任务终止</option>
                            </select>
                        </div>
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
                        任务主类别
                    </th>
                    <th>
                        发布企业
                    </th>
                    <th>结算金额</th>
                    <th>
                        接单人
                    </th>
                    <th>验收状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <!-- <form action="/manage/taskMultiHandle" method="post"> -->
                    {!! csrf_field() !!}
                    <tbody>
                    
                    @foreach($work as $k => $item)
                        <tr>
                            <td class="center">
                                <label class="pos-rel">
                                    <input type="checkbox" class="ace" name="chk" value="{!! $item->w_id !!}"/>
                                    <span class="lbl"></span>
                                </label>
                            </td>
							<td>
                                {{ $listArr['per_page']*($listArr['current_page']-1)+($k + 1)  }}
                            </td>
                            <td>
                                {!! $item->title !!}
                            </td>
                            <td>{!! $item->t_type_name !!}</td>
                            <td>
                                {!! $item->company_name !!}
                            </td>
                            <td>{!! $item->w_payment !!}</td>
							<td>{!! $item->realname !!}</td>
                            <td>
                                @if($item->w_status===1)
                                	派单实施中
                                @elseif($item->w_status===2)
                                	交付待验收
                                @elseif($item->w_status===3)
                                	验收通过
                                @elseif($item->w_status===4)
                                	验收驳回
                                @elseif($item->w_status===5)
                                	任务结束
                                @elseif($item->w_status===6)
                                	任务终止
                                @else
                                	未知状态
                                @endif
                            </td>
                            <td>
                            	@if($item->w_status===1)
                                	待提交验收材料
                                @elseif($item->w_status===2)
                                		<a class="btn btn-xs btn-success" href="/manage/taskCheckHandle/{!! $item->w_id !!}/pass">
                                            <i class="ace-icon fa fa-check bigger-120">通过</i>
                                        </a>
                                        <a class="btn btn-xs btn-danger" href="/manage/taskCheckHandle/{!! $item->w_id !!}/reject">
                                            <i class="ace-icon fa fa-minus-circle bigger-120">驳回</i>
                                        </a>
                                        <a class="btn btn-xs btn-success" href="/manage/taskCheckHandle/{!! $item->w_id !!}/end">
                                            <i class="ace-icon fa fa-check bigger-120">终止任务</i>
                                        </a>
                                @elseif($item->w_status===3||$item->w_status===4||$item->w_status===5||$item->w_status===6)
                                		<a class="btn btn-xs btn-info" href="/manage/taskDetail3/{!! $item->w_id !!}">
                                            <i class="ace-icon fa fa-info bigger-120">详情</i>
                                        </a>                                
                                @else
                                	未知状态
                                @endif
                            
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
                    <button class="btn btn-primary btn-sm" onclick="batchAction('r')">批量驳回</button>
                    <button class="btn btn-primary btn-sm" onclick="batchAction('e')">批量终止</button>
                    <button class="btn btn-primary btn-sm" onclick="templateDownload()">模板下载</button>
                    <button class="btn btn-primary btn-sm" onclick="batchImport()">验收导入</button>
                </div>
            </div>
            <div class="space-10 col-xs-12"></div>
            <div class="col-xs-12">
            	<div class="col-xs-4 " id="">
                	@if($listArr['current_page']!=$listArr['last_page'])
					    显示第 &nbsp;{{ $listArr['per_page']*($listArr['current_page']-1)+1 }}&nbsp;到第
					    &nbsp;{{ $listArr['per_page']*$listArr['current_page'] }}&nbsp;条记录
					@elseif($listArr['current_page']==$listArr['last_page'] && $listArr['per_page']*($listArr['current_page']-1)+1!=$listArr['total'])
					    显示第&nbsp;{{ $listArr['per_page']*($listArr['current_page']-1)+1 }}&nbsp;到第
					    &nbsp;{{ $listArr['total'] }}&nbsp;条记录
					@else
					    显示第&nbsp;{{ $listArr['total'] }}&nbsp;条记录
					@endif
					总共&nbsp; {{ $listArr['total'] }} &nbsp;条记录&nbsp;&nbsp;
					每页显示 
					<div class="btn-group dropup">
						<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button" style="background-color: #fff !important;color: #333 !important;border-color: #ccc !important;border: 1px solid;">{{ $listArr['per_page'] }}<span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="/manage/taskCheck?paginate=10">10</a></li>
							<li><a href="/manage/taskCheck?paginate=20">20</a></li>
							<li><a href="/manage/taskCheck?paginate=30">30</a></li>
							<li><a href="/manage/taskCheck?paginate=50">50</a></li>
						</ul>
					</div>
					条
                </div>
                <div class="col-xs-8 dataTables_paginate paging_simple_numbers row" id="dynamic-table_paginate">
                    {!! $work->appends($merge)->render() !!}
                </div>
            </div>
        </div>
    </div>
</div><!-- /.row -->
<script type="text/javascript">
	function batchAction(param){
		var action = '';
		switch(param){
		case 'e': action = '/manage/taskCheckEndAll'; break;
		case 'r': action = '/manage/taskCheckRejectAll'; break;
		}
		var value = '';
		var checkedArr = $("input[name='chk']:checked");
		if(checkedArr.length===0){
			 popUpMessage('请选择至少一个任务');
			 return;
		}
		for(var i = 0; i < checkedArr.length; i++){
			value += $(checkedArr[i]).val() + ',';
		}
		console.log(value);
		createForm(action, value.substr(0, value.length-1))
		/* var form = document.createElement('form');
		form.action = action;
		form.method = 'POST';
		var chk = document.createElement('input');
		chk.type = 'hidden'; 
		chk.name = 'chk';    
		chk.value = value?value.substr(0, value.length-1):'';    
		form.appendChild(chk);  
		var token = document.createElement('input');
		token.type = 'hidden'; 
		token.name = '_token';    
		token.value = "{{ csrf_token() }}";    
		form.appendChild(token); 
		$(document.body).append(form);    
		form.submit();
		document.body.removeChild(form); */
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
		createForm('/manage/taskCheckDownload', value.substr(0, value.length-1))
	}
	
	function batchImport(){
		window.location.href = "{!! url('manage/taskCheckImport') !!}";
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