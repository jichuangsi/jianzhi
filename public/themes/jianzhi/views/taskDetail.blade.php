	<div class="top">
        	任务详情
        <div class="out iconfont icon-fanhui" onclick="window.history.back(-1);"></div>
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
                                                    <div>
                                                        <a href="{{ URL('jz/task/fileDownload',['id'=>$v['id']]) }}" target="#">
                                                        <img alt="150x150" src="{!! url($v['url']) !!}" style="width: 1.2rem;height: 1.2rem;"></a>
                                                    </div>
                                                </li>
                                            @endforeach
            
            </div>
        </div>
    </div>
    <div class="btn" onclick="xy()">
        报名任务
    </div>
    
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
                <iframe src="{!! url('jz/task/agreement') !!}" frameborder="0"></iframe>
            </div>
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
    					window.location.href = '{!! url("jz/task") !!}';
        			}else{
            			if(ret.errMsg) alert(ret.errMsg);
            		}
                });
            }
        </script>
    
    {!! Theme::asset()->container('specific-css')->usepath()->add('index-style','style/details.css') !!}