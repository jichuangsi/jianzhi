	<div class="top">
        帮助中心
        <div class="out iconfont icon-fanhui" onclick="backToMy();"></div>
    </div>
    <div class="center">
    	@if(!empty($list))
    		@foreach($list as $item)
    				<div class="list">
                        <div class="title">
                            	{{$item['title']}}
                        </div>
                        <span class="iconfont icon-jiantou9"></span>
                        <div class="text">
                            	{!! htmlspecialchars_decode($item['content']) !!}
                        </div>
                    </div>
    		
    		@endforeach
    	 @endif
    
        <!-- <div class="list">
            <div class="title">
                如何知道报名任务是否成功？
            </div>
            <span class="iconfont icon-jiantou9"></span>
            <div class="text">
                可以在我的任务---已报名查看
            </div>
        </div>
        <div class="list">
            <div class="title">
                如何知道报名任务是否成功？
            </div>
            <span class="iconfont icon-jiantou9"></span>
            <div class="text">
                可以在我的任务---已报名查看
            </div>
        </div> -->
    </div>
    <script>
        $('.list').click(function(){
            if($(this).find('span')[0].className.indexOf('icon-jiantou9') != -1){
                    $(this).find('.text').css('height','auto')
                    a =  Number($(this).find('.text').css('height').split('px')[0])+Number($(this).find('.title').css('height').split('px')[0])
                    console.log(a)
                    $(this).css('height',a+'px')
                $(this).find('span').removeClass('icon-jiantou9').addClass('icon-changyongtubiao-xianxingdaochu-zhuanqu-1')
            }else{
                $(this).css('height','0.8rem')
                $(this).find('span').addClass('icon-jiantou9').removeClass('icon-changyongtubiao-xianxingdaochu-zhuanqu-1')
            }
        })
        
        function backToMy(){
        	window.location.href = "{!! url('jz/my') !!}";
        }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('help-style','style/help.css') !!}