	<div class="top">
        {{ $detail['title'] }}
        <div class="out iconfont icon-fanhui" onclick="backToList()"></div>
    </div>
    <div class="center">
        <div class="state">
            <div>任务单号：{{ $detail['id'] }}</div>
            <div>
            	@if($detail['status']==2)
            	审核中
            	@elseif($detail['status']==3)
            	招募中
            	@elseif($detail['status']==4)
            	工作中
            	@elseif($detail['status']==5||$detail['status']==6||$detail['status']==7)
            	待验收
            	@elseif($detail['status']==8)
            	已验收
            	@elseif($detail['status']==9||$detail['status']==10)
            	已结算
            	@elseif($detail['status']==11)
            	维权中
            	@else
            	未知状态
            	@endif
            </div>
        </div>
        <div class="center_box">
            <div class="center_list">
            	@if(isset($works))
            	 @foreach($works as $v)
                <div class="list_box">
                    <div class="list_box_title">
                        	报名时间：{{ date('Y年m月d日 H:i',strtotime($v['created_at'])) }} 
                        	<span>
                        	@if($v['status']==0)
                        	已报名待审核
                        	@elseif($v['status']==1)
                        	已派单进行中
                        	@elseif($v['status']==2)
                        	已提交待验收
                        	@elseif($v['status']==3)
                        	已验收待结算
                        	@elseif($v['status']==5)
                        	已结算
                        	@else
                        	未知状态
                        	@endif
                        	</span>
                    </div>
                    <div class="list_box_top">
                        <div class="img">
                            <img src="{!! url($v['avatar']) !!}" alt="">
                        </div>
                        <div class="text_box">
                            <div class="title">接包人姓名：{{ $v['nickname'] }}</div>
                            <div class="text">手机号码：{{ $v['mobile'] }}</div>
                            <div class="jn">擅长技能：<div>
                            @if(isset($v['skills']))
                                @foreach($v['skills'] as $v1)
                                	<span>{{ $v1['tag_name'] }}</span>
                                @endforeach
                            @endif
                            <!-- <span>美工</span><span>美工</span><span>美工</span><span>美工</span><span>美工</span><span>美工</span> -->
                            </div></div>
                        </div>
                        
                        	@if($v['status']==0)
                        	<div class="qxbtn" onclick="assignTask('{{ $detail['id'] }}', '{{ $v['id'] }}')">同意派单</div>                        	
                        	@elseif($v['status']==2)
                        	<div class="qxbtn" onclick="approveDelivery('{{ $detail['id'] }}', '{{ $v['id'] }}')">验收通过</div>
                        	@elseif($v['status']==3)
                        	<div class="qxbtn" onclick="settleTask('{{ $detail['id'] }}', '{{ $v['id'] }}')">结算</div>
                        	@endif
                        	
							@if($v['status']>=2)                          		
                        		<div class="textbox">
									<div>完成时间：@if(isset($v['delivered_at'])) {{ date('Y年m月d日 H:i',strtotime($v['delivered_at'])) }} @endif</div>
									<div>验收说明：@if(isset($v['desc'])) {{ $v['desc'] }} @endif</div>
									<div>验收材料	({{ count($v['attachments']) }})：
									@if(isset($v['attachments']))                   	
												@foreach($v['attachments'] as $v1)
													<li><a href="{{ URL('jz/task/fileDownload',['id'=>$v1['id']]) }}" target="#">{{ $v1['name'] }}</a>
													</li>
												@endforeach
									@endif
									</div>
                        		</div>
                        	@endif
                        	
                        		@if($v['status']>=3)
                            		@if(isset($v['comments']))
                            		<div class="textbox">
                            			<div>验收时间：@if(isset($v['checked_at'])) {{ date('Y年m月d日 H:i',strtotime($v['checked_at'])) }} @endif</div>
                            			雇主评价：
                            			@foreach($v['comments'] as $v2)                            				
                            					<div>{{ $v2['nickname'] }} ({{ date('Y年m月d日 H:i',strtotime($v['created_at'])) }})：{{ $v2['comment'] }}</div>
                            			@endforeach
                            			</div>
                            		@endif
                            	@endif
                            	
                            	@if($v['status']==5)
                            		@if(isset($v['settle_at']))
                            			<div class="textbox">
                            				<div>结算时间：{{ date('Y年m月d日 H:i',strtotime($v['settle_at'])) }} </div>
                            			</div>
                            		@endif
                            	@endif
                        		
                        	                               	                 
                    </div>
                </div>
                @endforeach
               @endif
            </div>
        </div>
        <div class="text_box">
            <div>任务预算：{{ $detail['bounty'] }} 元</div>
            <div>任务类型：{{ $detail['type_name'] }}/{{ $detail['sub_type_name'] }}</div>
            <div>所需技能：
            @foreach($tags as $v)
            	<span class='skill'>{{ $v['tag_name'] }}</span>
            @endforeach
            </div>
            <div>服务时间：{{ date('Y年m月d日 H:i',strtotime($detail['begin_at'])) }}—{{ date('Y年m月d日 H:i',strtotime($detail['end_at'])) }}</div>
            <div>任务地点：{{ $detail['province_name'] }} {{ $detail['city_name'] }} {{ $detail['area_name'] }} {{ $detail['address'] }}</div>
            <div>任务附件({{ count($attatchment) }})：
            							@foreach($attatchment as $v)
                                                <li>
                                                	<a href="{{ URL('jz/task/fileDownload',['id'=>$v['id']]) }}" target="#">
                                                        <img alt="150x150" src="{!! url($v['url']) !!}" style="width: 1.2rem;height: 1.2rem;"></a>
                                                </li>
                                            @endforeach
            
            </div>
        </div>
    </div>
    <script type="text/javascript">
		function assignTask(task_id, work_id){
			var data = {'_token': '{{ csrf_token() }}', 'task_id': task_id, 'work_id': work_id};
        	$.post('/jz/task/ajaxAssignTask',data,function(ret,status,xhr){
        		console.log(ret);
                console.log(status);
				if(status==='success'&&ret&&!ret.errMsg){
					window.location.href = "/jz/task/"+task_id;
    			}else{
        			if(ret.errMsg) popUpMessage(ret.errMsg);
        		}
            });
		}
		function approveDelivery(task_id, work_id){
			window.location.href = "/jz/task/approveDelivery/"+work_id;
		}
		function settleTask(task_id, work_id){
			var data = {'_token': '{{ csrf_token() }}', 'task_id': task_id, 'work_id': work_id};
			$.post('/jz/task/ajaxSettleTask',data,function(ret,status,xhr){
        		console.log(ret);
                console.log(status);
				if(status==='success'&&ret&&!ret.errMsg){
					window.location.href = "/jz/task/"+task_id;
    			}else{
        			if(ret.errMsg) popUpMessage(ret.errMsg);
        		}
            });
		}
		function backToList(){
        	window.location.href = "{!! url('jz/task') !!}";
        }
	</script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('enterprise_details-style','style/enterprise_details.css') !!}