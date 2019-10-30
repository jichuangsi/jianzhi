	<div class="top">
        我的评价
        <div class="out iconfont icon-fanhui" onclick="backToMy();"></div>
    </div>
    <div class="center">
            <div class="center_box">
                <div class="center_kong">
                    <img src="/themes/jianzhi/assets/images/developmenting.png" alt="">
                </div>
                <div class="center_list">
                
                	@foreach($works as $v)
                    <div class="list_box" onclick="details({{ $v['task']['id'] }})">
                        <div class="list_box_top">
                            <div class="img">
                                @if(isset($v['task']['type_icon']))
                                    <img src=" {!! url($v['task']['type_icon']) !!}  " alt="">
                                @else
                                	<img src="/themes/jianzhi/assets/images/xxjs.png" alt="">
                                @endif
                            </div>
                            <div class="text_box">
                                <div class="title">{{ $v['task']['title'] }}</div>
                                <div class="text">任务类型：
                                @if( $v['task']['type_name'] )
                                	{{ $v['task']['type_name'] }}/
                                @endif
                                @if( $v['task']['sub_type_name'] )
                                	{{ $v['task']['sub_type_name'] }}
                                @endif
                                </div>
                                <div class="money">预算：{{ $v['task']['bounty'] }}</div>
                            </div>
                        </div>
                        <div class="list_box_bottom">
                            <div>任务时间：{{ date('Y年m月d日 H:i',strtotime($v['task']['begin_at'])) }}—{{ date('Y年m月d日 H:i',strtotime($v['task']['end_at'])) }}</div>
                            <div>评价内容：</div>
                            @foreach($v['children_comment'] as $v1)
                            	<div>{{ $v1['nickname'] }}：{{ $v1['comment'] }}</div>                            
                            @endforeach
                            
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
    </div>
    <script>
    function details(task_id){
    	window.location.href = "/jz/task/"+task_id;
    }
    function backToMy(){
    	window.location.href = "{!! url('jz/my') !!}";
    }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('evaluate-style','style/evaluate.css') !!}