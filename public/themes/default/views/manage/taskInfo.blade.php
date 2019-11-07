{{--<div class="well">
	<h4 >任务详情</h4>
</div>--}}
<style>
        .jnbox {
            width: 41%;
            font-size: 14px;
            padding-bottom: 20px;
        }
        .jn_check {
            width: 100%;
            display: flex;
            line-height: 36px;
            margin-bottom: 10px;
        }
        .jn_check div:first-child {
            padding-left: 20px;
        }
        .jn_check div:last-child {
            flex: 1;
        }
        .jn span {
            background-color: #3da7f4;
            display: inline-block;
            margin-right: 40px;
            position: relative;
            padding-left: 10px;
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
            border: 18px solid #3da7f4;
            position: absolute;
            right: -36px;
            top: 0px;
            border-color:transparent transparent transparent #3da7f4;
        }
        .jn_box {
            width: 99%;
            margin: 0 auto;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #f3f3f3;
        }
        .jn_li {
            width: 100%;
        }
        .jn_li .title {
            width: 100%;
            background-color: #f3f3f3;
            line-height: 46px;
            padding: 0px 20px;
        }
        .jn_li .text {
            width: 100%;
            background-color: #fff;
            line-height: 36px;
            height: 0px;
            overflow: hidden;
        }
        .jn_li .text_check {
            height: auto;
            padding: 20px;
        }
        .jn_li .text span {
            background-color: #f3f3f3;
            display: inline-block;
            margin-right: 40px;
            position: relative;
            padding-left: 10px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .jn_li .text span:hover {
            background-color: #3da7f4;
            color: #fff;
        }
        .jn_li .text span:hover i {
            border-color:transparent transparent transparent #3da7f4;
        }
        .jn_li .text em {
            font-style: normal;
        }
        .jn_li .text i {
            display: inline-block;
            border: 18px solid #f3f3f3;
            position: absolute;
            right: -36px;
            top: 0px;
            border-color:transparent transparent transparent #f3f3f3;
        }
        .bigimg {
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: none;
            position: fixed;
            left: 0px;
            top: 0px;
            /*justify-content: center;
            align-items: center;*/
            overflow: auto;
            z-index:9999;
        }
        .bigimg img {
            width: 60%;
            position: absolute;
            top: 10%;
            left: 25%;
            /* height: 80%;
            transform: scale(1.5); */
            /* 放大倍数 */
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
    </style>
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">任务详情</h3>
    
	<div class="g-backrealdetails clearfix bor-border">
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务名称：</p>
			<p class="col-sm-4">
				{{$task['title']}}
			</p>
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 服务时间：</p>
			<p class="col-sm-4">
				{{ date('Y年m月d日 H:i',strtotime($task['begin_at'])) }}	至 {{ date('Y年m月d日 H:i',strtotime($task['end_at'])) }}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务主类别：</p>
			<p class="col-sm-4">
				{{$task['typename']}}
			</p>
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务子类别：</p>
			<p class="col-sm-4">
				{{$task['subtypename']}}
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务预算：</p>
			<p class="col-sm-4">
				{{$task['bounty']}}(元)
			</p>
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务人数：</p>
			<p class="col-sm-4">
				{{$task['worker_num']}}(人)
			</p>
		</div>
		<div class="bankAuth-bottom clearfix col-xs-12">
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任发布企业：</p>
			<p class="col-sm-4">
				{{$task['nickname']}}
			</p>
			<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 服务地址：</p>
			<p class="col-sm-4">
				{{$task['address']}}
			</p>
		</div>	
		<div class="bankAuth-bottom clearfix col-xs-12">
				<div class="jnbox col-sm-6" >
                    <div class="jn_check">
                        <div class="col-sm-2 control-label no-padding-left">任务标签：</div>
                        <div class="jn col-sm-9">
	                        @if(isset($myTags)&&!empty($myTags))
	                        		@foreach($myTags as $v1)
	                        			<span>{{ $v1['tag_name'] }}<i></i></span>
	                        		@endforeach
                        	@endif
                        </div>
                    </div>                    
                </div>
                <div class="col-sm-6">
                	<p class="col-sm-2 control-label no-padding-left" for="form-field-1"> 任务详情：</p>
					<p class="col-sm-9">
						{{$task['desc']}}
					</p>
                </div>
		</div>
			<div class="bankAuth-bottom clearfix col-xs-12" style="padding-bottom: 20px;">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 任务图片：</label>
                <div class="col-sm-11" >
                	@foreach($taskAttachment as $k=>$v)
	                	@if($v['atstatus']!=3)
	                	<img class="col-sm-3" alt="任务图片" src="{!! url($v['url']) !!}"  onclick="bigimg(this)">
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
											                	@if($v['atstatus']==3)
											                	<tr>
											                		<td>{{$v['atcreated']}}</td>
											                		<td><a href="{!! url($v['url']) !!}" download="">{!! $v['atname'] !!}</a> </td>
											                    </tr>
											                    @endif
										                    @endforeach
                                                        </table>
                                                    </div>
                                                </div>
             </div>
            <!--<div class="bankAuth-bottom clearfix col-xs-12">
                <label class="col-sm-1 control-label no-padding-left" for="form-field-1"> 身份证反面：</label>
                <div class="col-sm-4">
                </div>
            </div>		-->
		
		<div class="col-xs-12">
			<div class="clearfix row bg-backf5 padding20 mg-margin12">
				<div class="col-xs-12">
					<div class="col-md-1 text-right"></div>
					<div class="col-md-10">
						<div class="btn btn-primary btn-sm" style="margin-left: 50px" onclick="window.location.href = '{!! url('manage/taskList') !!}'">返回</div>
					</div>
				</div>
			</div>
		</div>
		<div class="space col-xs-12"></div>
		<div class="col-xs-12 space">

		</div>
	</div>
	<div class="bigimg" onclick="$('.bigimg').css('display','none')">
        <img src="" alt="">
    </div>
	<script>
		function toEdit(id){
			window.location.href = "{!! url('manage/userEdit') !!}" + "/" + id;		
		}
		function bigimg(val){
            $('.bigimg > img')[0].src = val.src
            $('.bigimg').css('display','flex')
        }
    </script>
{!! Theme::asset()->container('custom-css')->usePath()->add('back-stage-css', 'css/backstage/backstage.css') !!}
{!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}
{!! Theme::asset()->container('specific-css')->usePath()->add('datepicker-css', 'plugins/ace/css/datepicker.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('datepicker-js', 'plugins/ace/js/date-time/bootstrap-datepicker.min.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('userManage-js', 'js/userManage.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('main-js', 'js/main.js') !!}
