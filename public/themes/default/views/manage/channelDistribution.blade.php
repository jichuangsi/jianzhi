
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
                            <select name="isstatus">
                                <option value="0" >全部</option>
                                <option value="1" @if(isset($merge['isstatus']))  @if($merge['isstatus']==1) selected="selected" @endif @endif>已分配</option>
                                <option value="2" @if(isset($merge['isstatus'])) @if($merge['isstatus']==2) selected="selected" @endif @endif>未分配</option>
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
                <form id="rms" action="/manage/channelDistributionInfoBatch" method="post">
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
	                                	@if(intval($listArr['current_page'])>1)
			                            {{ $listArr['per_page']*($listArr['current_page']-1)+($k + 1)  }}
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
	                    	 				<a class="btn btn-xs btn-success" href="/manage/channelDistributionInfo/{!! $item->id !!}">
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
							<li><a href="/manage/channelDistribution?paginate=10">10</a></li>
							<li><a href="/manage/channelDistribution?paginate=20">20</a></li>
							<li><a href="/manage/channelDistribution?paginate=30">30</a></li>
							<li><a href="/manage/channelDistribution?paginate=50">50</a></li>
						</ul>
					</div>
					条
                </div>
                <div class="col-xs-8 dataTables_paginate paging_simple_numbers row" id="dynamic-table_paginate">
                	{!! $chan->appends($merge)->render() !!}
                </div>
            </div>
        </div>
        </form>
    </div>
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