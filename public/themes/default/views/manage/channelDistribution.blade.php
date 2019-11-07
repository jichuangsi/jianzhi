
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">渠道商分配</h3>
<div class="row">
    <div class="col-xs-12">
        <div class="clearfix  well">
            <div class="form-inline search-group">
                <form  role="form" action="/manage/channelDistribution" method="get">
                    <div class="form-group search-list" style="padding-right: 15px;">
                        <label for="namee" style="font-size: 24px;">渠道商分配</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="请输入关键字查询" value="{{$company_name}}">
                    </div>
                    <div class="form-group">
                    	<button type="submit" class="btn btn-primary btn-sm">搜索</button>
                    </div>
                    	<span style="margin-left: 20px;">时间筛选：</span>
                    <div  class="input-daterange input-group">
                                <input type="text" name="start" class="input-sm form-control" value="@if(isset($merge['start'])){!! $merge['start'] !!}@endif">
                                <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                                <input type="text" name="end" class="input-sm form-control" value="@if(isset($merge['end'])){!! $merge['end'] !!}@endif">
                    </div>
                    <div class="form-group search-list width285">
                            <label class="">状态筛选：　</label>
                            <select name="status">
                                <option value="-1">全部</option>
                                <option value="0">已分配</option>
                                <option value="1">未分配</option>
                            </select>
                        </div>
                    <div class="space"></div>
                </form>
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
                    <th>企业名称</th>
					 <th>联系人</th>
					 <th>联系电话</th>
					 <th>销售人员</th>
                    <th>
                        分配状态
                    </th>
                    <th>
                        分配时间
                    </th>
                     <th>
                        操作
                    </th>
                </tr>
                </thead>
                <form id="rms" action="/manage/taskMultiHandle" method="post">
                    {!! csrf_field() !!}
                    <tbody>
                    	 @foreach($chan as $k=>$item)
	                    	 	<tr>
	                    	 		<td class="center">
	                                <label class="pos-rel">
	                                    <input type="checkbox" class="ace" name="ckb[]" value="{!! $item->id !!}"/>
	                                    <span class="lbl"></span>
	                                </label>
	                            </td>
                    	 		<td>
                    	 			<a href="#">
	                                	@if(intval($chan['current_page'])>1)
			                            {{ $chan['per_page']*($chan['current_page']-1)+($k + 1)  }}
			                            @else
			                             {{ $k + 1 }}                            
			                            @endif
	                           		</a>
                    	 		</td>
                    	 		<td>{{$item['company_name']}}</td>
                    	 		<td>{{$item['contactor']}}</td>
                    	 		<td>{{$item['contactor_mobile']}}</td>
                    	 		<td>{{$item['musername']}}</td>
                    	 		<td>
                    	 			@if($item['musername'])
                    	 			<span style="color: green;">已分配</span>
                    	 			@else
                    	 			<span style="color: red;">未分配</span>
                    	 			@endif
                    	 			</td>
                    	 		<td>{{$item['cdtime']}}</td>
                    	 		<td>
	                                <div class="hidden-sm hidden-xs btn-group">
	                                	@if($item['musername'])
	                    	 				<a class="btn btn-xs btn-success" href="#">
	                                            <i class="ace-icon fa fa-check bigger-120">修改</i>
	                                        </a>
	                    	 			@else
	                    	 				<a class="btn btn-xs btn-success" href="/manage/channelDistributionInfo/{!! $item->id !!}">
	                                            <i class="ace-icon fa fa-check bigger-120">分配</i>
	                                        </a>
	                    	 			@endif
	                                        
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
                	 <button type="submit" class="btn btn-primary btn-sm">批量分配</button>
                </div>
            </div>
            <div class="space-10 col-xs-12"></div>
            <div class="col-xs-12">
                <div class="dataTables_paginate paging_simple_numbers row" id="dynamic-table_paginate">
                	{!! $chan->appends($merge)->render() !!}
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