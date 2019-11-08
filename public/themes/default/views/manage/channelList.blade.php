
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">渠道商查看</h3>
<div class="row">
    <div class="col-xs-12">
        <div class="clearfix  well">
            <div class="form-inline search-group">
                <form  role="form" action="/manage/channelList" method="get">
                    <div class="form-group search-list">
                        <label for="namee" style="font-size: 24px;">渠道商查看</label>
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
					 <th>企业城市</th>
                    <th>
                        月累计已结算
                    </th>
                    <th>
                        总计结算
                    </th>
                    <th>
                        分配时间
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
                    	 		<td>{{$item['dname']}}</td>
                    	 		<td>{{$item['musername']}}</td>
                    	 		<td>{{$item['sumbou']}}</td>
                    	 		<td>{{$item['cdtime']}}</td>
                    	 	</tr>
                    	 @endforeach
                    </tbody>
                
            </table>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="dataTables_info" id="sample-table-2_info" role="status" aria-live="polite">
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