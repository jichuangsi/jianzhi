	<div class="top" onclick="window.location.href = '{!! url('jz/info') !!}'">
        <div class="user">
        	@if(isset($avatar))
            <img src="{!! url($avatar) !!}" alt="">
            @else
            <img src="/themes/jianzhi/assets/images/default.jpg" alt="">
            @endif
            <div>{{ $nickname }}</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>
        <div class="jn">
        					@if(isset($skills))
                                @foreach($skills as $v1)
                                	<span>{{ $v1['tag_name'] }}</span>
                                @endforeach
                            @endif
            <!-- <span>app</span><span>小程序</span> -->
        </div>
    </div>
    <div class="center">
        <div class="list" onclick="window.location.href = '{!! url('jz/skill') !!}'">
            <span class="iconfont icon-zhuanyezhishijineng" style="color:#fd711a"></span>
            <div>技能管理</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>
        <div class="list" onclick="window.location.href = '{!! url('jz/auth') !!}'">
            <span class="iconfont icon-dunpai1" style="color:#3399ff"></span>
            <div>实名认证</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>
        <div class="list" onclick="window.location.href = '{!! url('jz/comment') !!}'">
            <span class="iconfont icon-hua" style="color:#e56b6c"></span>
            <div>我的评价</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>
        <div class="list" onclick="window.location.href = '{!! url('jz/help') !!}'">
            <span class="iconfont icon-bangzhuzhongxin" style="color:#369e07"></span>
            <div>帮助中心</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>  
        <div class="list" onclick="window.location.href = '{!! url('jz/proposal') !!}'">
            <span class="iconfont icon-bijiben" style="color:#3399ff"></span>
            <div>建议反馈</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>
        <div class="list" onclick="window.location.href = '{!! url('jz/contact') !!}'" style="display:none">
            <span class="iconfont icon-tubiao-" style="color:#3399ff"></span>
            <div>客服中心</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>
        <div class="btn" onclick="outlogin()">退出登录</div>
    </div>
    <div style="font-size: 15px;line-height: 15px; display: block;">
				 <span>
				 	Copyright 2020 广州网金创纪信息技术有限公司 All rights reserved <a style="color: blue;text-decoration: underline;" href="http://www.miitbeian.gov.cn/"> 粤ICP备19125254号-1</a>
				</span>
			</div>
    <div class="foot">
        <div onclick="window.location.href = '{!! url('jz/home') !!}'">
            <span class="iconfont icon-fangzi"></span>
            任务大厅
        </div>
        <div onclick="window.location.href = '{!! url('jz/task') !!}'">
            <span class="iconfont icon-thin-_notebook_p"></span>
            我的任务
        </div>
        <div class="foot_check">
            <span class="iconfont icon-yonghu-tianchong"></span>
            个人中心
        </div>
    </div>
    <script>
        function outlogin(){
            window.location.href = '{!! url("jz/logout") !!}';
        }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('my-style','style/my.css') !!}