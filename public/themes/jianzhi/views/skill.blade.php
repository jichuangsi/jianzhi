	<div class="top">
        技能管理
        <div class="out iconfont icon-fanhui" onclick="window.history.back(-1);"></div>
    </div>
    <div class="center">
        <div class="title">
            我的技能
        </div>
        <div class="li">
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
        </div>
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
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('my-style','style/jn.css') !!}