	<div class="top">
        技能管理
        <div class="out iconfont icon-fanhui" onclick="backToMy();"></div>
    </div>
    <div class="center">
        <div class="title">
            我的技能
        </div>
        	@foreach($taskCate as $v)
                	<div class="li">
                	<div class="left" data-id="{{ $v['id'] }}">{{ $v['name'] }}:</div>
                	<div class="right">
                	@if(isset($v['children_task']))
                    	@foreach($v['children_task'] as $sub)
                    		@if(isset($sub['checked']))
                    		<span data-id="{{ $sub['id'] }}" class="jn_check"><em></em><i class="iconfont icon-dagou"></i>{{ $sub['name'] }}</span>
                    		@else
                    		<span data-id="{{ $sub['id'] }}">{{ $sub['name'] }}</span>
                    		@endif
                    	@endforeach
                	@endif
                	</div>
                	</div>
                @endforeach
        <!-- <div class="li">
            <div class="left">推广营销:</div>
            <div class="right">
                <span class="jn_check"><em></em><i class="iconfont icon-dagou"></i>线下宣传</span>
                <span>线下宣传</span>
                <span>线下宣传</span>
                <span>宣传</span>
                <span>线下宣传</span>
                <span>线下宣传</span>
                <span>线下宣传</span>
                <span>线下宣传</span>
            </div>
        </div> -->
    </div>
    <div class="btn">
        确认修改
    </div>
    <script>
        $('.li').find('.right>span').click(function(){
            if(this.className.indexOf('jn_check') != -1){
                $(this).removeClass('jn_check')
                $(this).find('em').remove()
                $(this).find('i').remove()
            }else {
                $(this).addClass('jn_check')
                $(this).append('<em></em>')
                $(this).append('<i class="iconfont icon-dagou"></i>')
            }
        })
        $(".btn").click(function(){
        	if($('.jn_check').length>15){
        		popUpMessage('一个用户最多只能有15个标签！');
           		return;
            }   
        	if($('.jn_check').length==0) {
        		popUpMessage('请选择标签后提交！');
            	return;
            } 
            var a = '';
        	for(let i =0;i<$('.jn_check').length;i++){
                a+=$('.jn_check').eq(i).attr('data-id') + ','
            }            
            a = a.substr(0, a.length-1);
            var data = {'_token': '{{ csrf_token() }}', 'skill': a};
            console.log(data);
        	$.post('/jz/user/ajaxSaveSkills',data,function(ret,status,xhr){
                console.log(ret);
                console.log(status);
				if(status==='success'){
					if(ret&&!ret.errMsg){
						popUpMessage(ret);
					}else{
						popUpMessage(ret.errMsg);
					}
				}else{
					popUpMessage('网络异常，请稍后再试！');
				}
            });
        });
        function backToMy(){
        	window.location.href = "{!! url('jz/my') !!}";
        }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('jn-style','style/jn.css') !!}