	<div class="top">
        	任务详情
        <div class="out iconfont icon-fanhui" onclick="backToList()"></div>
    </div>
    <div class="center">
        <div class="state">
            <div>任务单号：{{ $detail['id'] }}</div>
            <div>
            	@if($detail['status']==3)
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
        <div class="text_box">
            <div class="title">{{ $detail['title'] }}<span>人数：{{ $detail['delivery_count'] }}/{{ $detail['worker_num'] }}</span></div>
            <div class="text">任务类型：{{ $detail['type_name'] }}/{{ $detail['sub_type_name'] }}</div>
            <div class="money">预算：{{ $detail['bounty'] }} 元</div>
        </div>
        <div class="text_box">
            <div>任务地点：{{ $detail['province_name'] }} {{ $detail['city_name'] }} {{ $detail['area_name'] }} {{ $detail['address'] }}</div>
            <div>服务时间：{{ date('Y年m月d日 H:i',strtotime($detail['begin_at'])) }}—{{ date('Y年m月d日 H:i',strtotime($detail['end_at'])) }}</div>
        </div>
        <div class="text_box">
            <div class="jn">
                <div class="yq">技能要求：</div>
                <div class="jn_box">
                		@foreach($tags as $v)
                        	<span>{{ $v['tag_name'] }}</span>
                        @endforeach
                </div>
            </div>
        </div>
        <div class="text_box">
            <div class="text_title">
                任务描述：                
            </div>
            <div>
                {{ $detail['desc'] }}
            </div>
        </div>
        <div class="text_box">
            <div>图片展示：
            								@foreach($attatchment as $v)
                                                <li>
                                                        <!-- <a href="{{ URL('jz/task/fileDownload',['id'=>$v['id']]) }}" target="#"></a> -->
                                                        <img alt="{{$v['name']}}" src="{!! url($v['url']) !!}" style="width: 1.2rem;height: 1.2rem;" onclick="bigimg(this)">
                                                </li>
                                            @endforeach
            
            </div>
        </div>
        @if(isset($works))
         @foreach($works as $v)
         	
         					@if($v['status']>=2)                          		
                        		<div class="textbox">
									<div>完成时间：@if(isset($v['delivered_at'])) {{ date('Y年m月d日 H:i',strtotime($v['delivered_at'])) }} @endif</div>
									<div>验收说明：@if(isset($v['desc'])) {{ $v['desc'] }} @endif</div>
									<div>验收材料	({{ count($v['attachments']) }})：
									@if(isset($v['attachments']))                   	
												@foreach($v['attachments'] as $v1)
													<li><!-- <a href="{{ URL('jz/task/fileDownload',['id'=>$v1['id']]) }}" target="#">{{ $v1['name'] }}</a> -->
													<img alt="{{$v1['name']}}" src="{!! url($v1['url']) !!}" style="width: 1.2rem;height: 1.2rem;" onclick="bigimg(this)">
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
         	
         	
         	
         @endforeach
        @endif
    </div>
    @if($detail['status']==3)
    	@if(isset($works))
    		<div class="btn">
               	 已报名
            </div>
    	@else
    		<div class="btn" onclick="xy()">
               	 报名任务
            </div>    	
    	@endif    
    @endif
    <div class="xybj">
            <div class="xybox">
                <div class="none" onclick="$('.xybj').css('display','none');">x</div>
                <div class="xy_title">任务接受协议</div>
                <div class="xy_text">在报名任务前请先认证阅读《任务接受协议》并确定接受协议，如您暂时不接受协议，在您报名24小时后系统也会自动帮您确定该协议。</div>
                <div class="xy_text blue" onclick="readyxy()">点击阅读《任务接受协议》</div>
                <div class="xy_btn">
                        <span onclick="createWork(0)">暂不确定</span>
                        <span onclick="createWork(1)">立即确定</span>
                    </div>
                <iframe src="{!! url('jz/task/contract') !!}" frameborder="0"></iframe>
            </div>
        </div>
    	<div class="bigimg" onclick="$('.bigimg').css('display','none')">
            <img src="" alt="">
        </div>
        <script>
            function readyxy(){
                $('.xybox').find('iframe').css('display','block').siblings().css('display','none')
                $('.none').css('display','block')
            }
            function xy(){
                $('.xybj').css('display','block')
                $('.xybox').find('iframe').css('display','none').siblings().css('display','block')
                $('.xy_btn').css('display','flex')
            }
            function createWork(a){
            	var data = {'_token': '{{ csrf_token() }}', 'agreement': a, 'task_id': '{{ $detail["id"] }}'};
            	$.post('/jz/task/ajaxCreateNewWork',data,function(ret,status,xhr){
            		console.log(ret);
                    console.log(status);
    				if(status==='success'&&ret.data&&!ret.errMsg){
    					$('.xybj').css('display','none');
    					popUpMessage(ret.data);
    					backToList();
        			}else{
            			if(ret.errMsg) popUpMessage(ret.errMsg);
            		}
                });
            }
            function backToList(){
            	window.location.href = "{!! url('jz/task') !!}";
            }
    		function bigimg(val){
    	        $('.bigimg').css('display','flex')
    	        $('.bigimg > img')[0].src = $(val)[0].src
    	    }
        </script>
    
    {!! Theme::asset()->container('specific-css')->usepath()->add('details-style','style/details.css') !!}