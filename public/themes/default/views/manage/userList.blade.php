   {{-- <div class="page-header">
        <h3>
              搜索
        </h3>
    </div><!-- /.page-header -->--}}
    <h3 class="header smaller lighter blue mg-top12 mg-bottom20">个人管理</h3>
    <style>
    	.search-list{
    		padding-right:10px !important;
    	}
    </style>
<div class="row">
    <div class="col-xs-12">
        <div class="clearfix  well">
        <form  role="form" class="form-inline search-group" action="{!! url('manage/userList') !!}" method="get">
            <div class="">
                <div class="form-group search-list">
                    <label for="">姓名　　</label>
                    <input type="text" name="username" @if(isset($username)) value="{!! $username !!}" @endif/>
                </div>
                <!-- <div class="form-group search-list">
                    <label for="">注册邮箱　</label>
                    <input type="text" name="email" @if(isset($email))value="{!! $email !!}"@endif/>
                </div> -->
                <div class="form-group search-list">
                    <label for="">注册手机　</label>
                    <input type="text" name="mobile" @if(isset($mobile))value="{!! $mobile !!}" @endif/>
                </div>
                <div class="form-group search-list">
                    <label class="">注册时间　</label>
                    <div class="input-daterange input-group">
                        <input type="text" name="start" class="input-sm form-control" @if(isset($search['start']))value="{!! $search['start'] !!}" @endif>
                        <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                        <input type="text" name="end" class="input-sm form-control" @if(isset($search['end']))value="{!! $search['end'] !!}" @endif>
                    </div>
                </div>
                <div class="form-group search-list">
                    <label>认证状态　</label>
                    <select class="" name="status">
                        <option value="-1">全部</option>
                        <option @if(isset($status) && $status == 1)selected="selected"@endif value="1">未认证</option>
                        <option @if(isset($status) && $status == 2)selected="selected"@endif value="2">已认证</option>
                        <option @if(isset($status) && $status == 3)selected="selected"@endif value="3">待审核</option>
                        <option @if(isset($status) && $status == 4)selected="selected"@endif value="4">已拒绝</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-sm">搜索</button>
                </div>
            </div>
            </form>
            <div class="space"></div>
            <div class="" style="display: none;">
                <div class="form-group search-list width285">
                    <label>认证状态　</label>
                    <select class="" name="status">
                        <option value="-1">全部</option>
                        <option @if(isset($status) && $status == 1)selected="selected"@endif value="1">未认证</option>
                        <option @if(isset($status) && $status == 2)selected="selected"@endif value="2">已认证</option>
                        <option @if(isset($status) && $status == 3)selected="selected"@endif value="3">待审核</option>
                        <option @if(isset($status) && $status == 4)selected="selected"@endif value="4">已拒绝</option>
                    </select>
                </div>
                <div class="form-group search-list">
                    <label class="">注册时间　</label>
                    <div class="input-daterange input-group">
                        <input type="text" name="start" class="input-sm form-control" @if(isset($search['start']))value="{!! $search['start'] !!}" @endif>
                        <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                        <input type="text" name="end" class="input-sm form-control" @if(isset($search['end']))value="{!! $search['end'] !!}" @endif>
                    </div>
                </div>
            </div>
        
    </div>

    <!-- <div class="table-responsive"> -->

    <!-- <div class="dataTables_borderWrap"> -->
    <div>
        <table id="sample-table" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th class="center">
                    <label class="position-relative">
                        <input type="checkbox" class="ace"/>
                        <span class="lbl"></span>
                       	 编号
                    </label>
                </th>
                <th>姓名</th>
                <th>身份证号码</th>
                <th>手机号</th>
                <th>认证状态</th>
                <!-- <th>用户状态</th>
                <th>注册时间</th>
                <th>上次登录时间</th>
                <th>余额</th> -->
                <th>操作</th>
            </tr>
            </thead>
            
            <tbody>
                @if(!empty($list))
                @foreach($list as $k => $item)
                <tr>
                    <td class="center">
                        <label class="position-relative">
                            <input type="checkbox" class="ace"  value="{{ $item->id }}" name="chk"/>
                            <span class="lbl"></span>{{ $listArr['per_page']*($listArr['current_page']-1)+($k + 1)  }}
                        </label>
                    </td>
                    <td>
                        <a href="#">
                        	@if(!empty($item->realname))
                        		{!! $item->realname !!}
                        	@else
                        		@if(!empty($item->rname))
                        	 		{!! $item->rname !!}
                        	 	@else
                        	 		-
                        	 	@endif
                        	@endif
                       	</a>
                    </td>
                    <td>
                    	@if(!empty($item->card_number))
                    		{!! $item->card_number !!}
                    	@else
                    		@if(!empty($item->dcard_number))
                    	 		{!! $item->dcard_number !!}
                    	 	@else
                    	 		-
                    	 	@endif
                    	@endif
                    </td>
                    <td>@if(!empty($item->mobile)){!! $item->mobile !!}@else - @endif</td>
                    <td>
                    @if($item->astatus === 0)
                    	待审核
                    @elseif($item->astatus === 1)
                    	已认证
                    @elseif($item->astatus === 2)
                    	已拒绝
                    @elseif($item->astatus === NULL)
                    	未认证
                    @endif</td>
                    <!-- <td>@if($item->status == 0)未激活@elseif($item->status == 1)正常@elseif($item->status == 3)已禁用@endif</td>
                    <td>{!! $item->created_at !!}</td>
                    <td>
                        {!! $item->last_login_time !!}
                    </td>
                    <td>
                       {!! $item->balance !!}
                    </td> -->
                    <td>
                        <div class="btn-group">
                        	@if($item->astatus === 0)
                        		<a class="btn btn-xs btn-success" href="{!! url('manage/userAuthPass/' . $item->id) !!}">
                                    <i class="fa fa-check bigger-120"></i>通过
                                </a>
                        	@endif
                            <a class="btn btn-xs btn-info" target="_Self" href="{!! url('manage/userEdit/' . $item->id) !!}">
                                <i class="fa fa-edit bigger-120"></i>编辑
                            </a>
                            @if($item->astatus === 0)
                        		<a class="btn btn-xs btn-danger" href="{!! url('manage/userAuthReject/' . $item->id) !!}">
                                    <i class="fa fa-minus-circle bigger-120"></i>拒绝
                                </a>
                        	@endif
                        	<a class="btn btn-xs btn-info" target="_Self" href="{!! url('manage/userInfo/' . $item->id) !!}">
                                <i class="fa fa-info bigger-120"></i>详情
                            </a>
                        	@if($item->astatus === 0||$item->astatus === NULL)
                            <a title="删除" onclick="confdel(this)" data-data="{!! url('manage/userDelete/' . $item->id) !!}" class="btn btn-xs btn-danger" href="javascript:void(0)" >
                                <i class="ace-icon fa fa-trash-o bigger-120"></i>删除
                            </a>
                            @endif
                            <!-- @if($item->status == 1)
                            <a class="btn btn-xs btn-danger" href="{!! url('manage/handleUser/' . $item->id . '/disable') !!}">
                                <i class="fa fa-ban"></i>禁用
                            </a>
                            @elseif($item->status == 2)
                            <a class="btn btn-xs btn-success" href="{!! url('manage/handleUser/' . $item->id . '/enable') !!}">
                                <i class="fa fa-check"></i>启用
                            </a>
                            @endif -->
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
            
        </table>
        <div class="row">
        		<div class="col-xs-12">
                    <div class="dataTables_info" id="sample-table-2_info" role="status" aria-live="polite">
                        <a href="/manage/userAdd"  target="_Self">添加</a>
                        <button class="btn btn-primary btn-sm" onclick="batchAction('p')">批量通过

                        </button>
                        <button class="btn btn-primary btn-sm" onclick="batchAction('r')">批量拒绝

                        </button>
                        <button class="btn btn-primary btn-sm" onclick="batchAction('d')">批量删除

                        </button>
                        
                        <button class="btn btn-primary btn-sm" onclick="batchImport()">个人导入

                        </button>
                    </div>
                </div>
            <!-- <div class="col-md-2">
                <button class="btn btn-white btn-default btn-round">批量删除
                </button>
            </div>
            <a href="/manage/userAdd" target="_blank">添加</a> -->
            <div class="space-10 col-xs-12"></div>
            <div class="col-md-12">
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
							<li><a href="/manage/userList?paginate=10">10</a></li>
							<li><a href="/manage/userList?paginate=20">20</a></li>
							<li><a href="/manage/userList?paginate=30">30</a></li>
							<li><a href="/manage/userList?paginate=50">50</a></li>
						</ul>
					</div>
					条
                </div>
                <div class="col-xs-8 dataTables_paginate paging_bootstrap text-right row">
                    <!-- 分页列表 -->
                    {!! $list->appends($search)->render() !!}
                </div>
            </div>
        </div>
        </div>
    </div>
    </div>
	<script>
		function confdel(obj){
			if(confirm("确定要删除吗?"))
		     {
		     	window.location.href=$(obj).attr('data-data');
		     }else{
		     	console.log(22222);
			   return false;
			 }
		}
	function batchAction(param){
		var action = '';
		switch(param){
		case 'p': action = '/manage/userAuthPassAll'; break;
		case 'r': action = '/manage/userAuthRejectAll'; break;
		case 'd': action = '/manage/userDeleteAll'; break;
		}
		var value = '';
		var checkedArr = $("input[name='chk']:checked");
		if(checkedArr.length===0){
			 popUpMessage('请选择至少一个用户');
			 return;
		}
		for(var i = 0; i < checkedArr.length; i++){
			value += $(checkedArr[i]).val() + ',';
		}
		var form = document.createElement('form');
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
		if(param == 'd'){
			if(confirm("确定要删除吗?"))
		     {
		     	form.submit();
		     }else{
			  	 return false;
			 }	
		}   
		form.submit();
		document.body.removeChild(form);
	}
	function batchImport(){
		window.location.href = "{!! url('manage/userImport') !!}";
	}
	</script>
{!! Theme::asset()->container('custom-css')->usePath()->add('backstage', 'css/backstage/backstage.css') !!}

{{--时间插件--}}
{!! Theme::asset()->container('specific-css')->usePath()->add('datepicker-css', 'plugins/ace/css/datepicker.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('datepicker-js', 'plugins/ace/js/date-time/bootstrap-datepicker.min.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('userfinance-js', 'js/userfinance.js') !!}

{!! Theme::asset()->container('custom-js')->usePath()->add('checked-js', 'js/checkedAll.js') !!}
