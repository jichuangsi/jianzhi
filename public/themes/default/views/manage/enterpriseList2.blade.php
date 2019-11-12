   {{-- <div class="page-header">
        <h3>
              搜索
        </h3>
    </div><!-- /.page-header -->--}}
    <h3 class="header smaller lighter blue mg-top12 mg-bottom20">企业管理</h3>
<div class="row">
    <div class="col-xs-12">
        <div class="clearfix  well">
        <form  role="form" class="form-inline search-group" action="{!! url('manage/enterpriseList') !!}" method="get">
            <div class="">
                <div class="form-group search-list">
                    <label for="">企业名称　　</label>
                    <input type="text" name="company_name" @if(isset($company_name)) value="{!! $company_name !!}" @endif/>
                </div>
                <div class="form-group search-list">
                    <label for="">联系人　</label>
                    <input type="text" name="contactor" @if(isset($contactor))value="{!! $contactor !!}"@endif/>
                </div>
                <!-- <div class="form-group search-list">
                    <label for="">联系电话　</label>
                    <input type="text" name="contactor_mobile" @if(isset($contactor_mobile))value="{!! $contactor_mobile !!}" @endif/>
                </div> -->
                <div class="form-group search-list">
                    <label for="">注册手机　</label>
                    <input type="text" name="mobile" @if(isset($mobile))value="{!! $mobile !!}" @endif/>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-sm">搜索</button>
                </div>
            </div>
            <div class="space"></div>
            <div class="">
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
        </form>
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
                        	序号
                    </label>
                </th>
                <th>企业名称</th>
                <th>所在城市</th>
                <th>联系人</th>
                <th>联系电话</th>
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
                        <a href="#">@if(!empty($item->company_name)){!! $item->company_name !!}@else - @endif</a>
                    </td>
                    <td>@if(!empty($item->city_name)){!! $item->city_name !!}@else - @endif</td>
                    <td>@if(!empty($item->contactor)){!! $item->contactor !!}@else - @endif</td>
                    <td>@if(!empty($item->contactor_mobile)){!! $item->contactor_mobile !!}@else - @endif</td>
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
                        		<a class="btn btn-xs btn-success" href="{!! url('manage/enterpriseAuthPass/' . $item->id) !!}">
                                    <i class="fa fa-check bigger-120"></i>通过
                                </a>
                        	@endif
                            <a class="btn btn-xs btn-info" target="_Self" href="{!! url('manage/enterpriseEdit/' . $item->id) !!}">
                                <i class="fa fa-edit bigger-120"></i>编辑
                            </a>
                            @if($item->astatus === 0)
                        		<a class="btn btn-xs btn-danger" href="{!! url('manage/enterpriseAuthReject/' . $item->id) !!}">
                                    <i class="fa fa-minus-circle bigger-120"></i>拒绝
                                </a>
                        	@endif
                        	<a class="btn btn-xs btn-info" target="_Self" href="{!! url('manage/enterpriseInfo/' . $item->id) !!}">
                                <i class="fa fa-info bigger-120"></i>详情
                            </a>
                            @if($item->astatus === 0||$item->astatus === NULL)
                            <a title="删除" onclick="confdel(this)" data-data="{!! url('manage/enterpriseDelete/' . $item->id) !!}" class="btn btn-xs btn-danger" href="javascript:void(0)" >
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
                        <a href="/manage/enterpriseAdd" target="_Self">添加</a>
                        <button class="btn btn-primary btn-sm" onclick="batchAction('p')">批量通过

                        </button>
                        <button class="btn btn-primary btn-sm" onclick="batchAction('r')">批量拒绝

                        </button>
                        <button class="btn btn-primary btn-sm" onclick="batchAction('d')">批量删除

                        </button>
                        
                        <button class="btn btn-primary btn-sm" onclick="batchImport()">企业导入

                        </button>
                    </div>
                </div>
            <!-- <div class="col-md-2">
                <button class="btn btn-white btn-default btn-round">批量删除
                </button>
            </div>
            <a href="/manage/userAdd" target="_blank">添加</a> -->
            <div class="col-md-12">
                <div class="dataTables_paginate paging_bootstrap text-right row">
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
		case 'p': action = '/manage/enterpriseAuthPassAll'; break;
		case 'r': action = '/manage/enterpriseAuthRejectAll'; break;
		case 'd': action = '/manage/enterpriseDeleteAll'; break;
		}
		var value = '';
		var checkedArr = $("input[name='chk']:checked");
		if(checkedArr.length===0){
			popUpMessage('请选择至少一个企业');
			return;		
		}
		for(var i = 0; i < checkedArr.length; i++){
			value += $(checkedArr[i]).val() + ',';
		}
		console.log(value);
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
		window.location.href = "{!! url('manage/enterpriseImport') !!}";
	}
	</script>
{!! Theme::asset()->container('custom-css')->usePath()->add('backstage', 'css/backstage/backstage.css') !!}

{{--时间插件--}}
{!! Theme::asset()->container('specific-css')->usePath()->add('datepicker-css', 'plugins/ace/css/datepicker.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('datepicker-js', 'plugins/ace/js/date-time/bootstrap-datepicker.min.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('userfinance-js', 'js/userfinance.js') !!}

{!! Theme::asset()->container('custom-js')->usePath()->add('checked-js', 'js/checkedAll.js') !!}
