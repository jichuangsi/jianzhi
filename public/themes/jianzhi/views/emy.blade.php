	<div class="top" onclick="window.location.href = '{!! url('jz/info') !!}'">
        <div class="user">
            <div>
                <div class="title">
                    企业名称：
                    @if(isset($company_name))
                    	{{ $company_name }}
                    @else
                    	未认证
                    @endif
                </div>
                <div class="text">
                    联系人：
                    @if(isset($contactor))
                    	{{ $contactor }}
                    @else
                    	未认证
                    @endif
                </div>
                <div class="text">
                    地址：
                    @if(isset($address))
                    	{{ $address }}
                    @else
                    	未认证
                    @endif
                </div>
            </div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>
    </div>
    <div class="center">
        <div class="list" onclick="window.location.href = '{!! url('jz/auth') !!}'">
            <span class="iconfont icon-dunpai1" style="color:#3399ff"></span>
            <div>企业认证</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>
        <div class="list" onclick="window.location.href = '{!! url('jz/help') !!}'">
            <span class="iconfont icon-bangzhuzhongxin" style="color:#3399ff"></span>
            <div>帮助中心</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>  
        <div class="list" onclick="window.location.href = '{!! url('jz/proposal') !!}'">
            <span class="iconfont icon-bijiben" style="color:#3399ff"></span>
            <div>建议反馈</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>
        <div class="list" onclick="window.location.href = '{!! url('jz/contact') !!}'">
            <span class="iconfont icon-tubiao-" style="color:#3399ff"></span>
            <div>客服中心</div>
            <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
        </div>
        <div class="btn" onclick="outlogin()">退出登录</div>
    </div>
    <div class="foot">
        <div onclick="window.location.href = '{!! url('jz/home') !!}'">
            <span class="iconfont icon-emizhifeiji"></span>
            发布任务
        </div>
        <div onclick="window.location.href = '{!! url('jz/task') !!}'">
            <span class="iconfont icon-thin-_notebook_p"></span>
            项目管理
        </div>
        <div class="foot_check">
            <span class="iconfont icon-dalou4"></span>
            企业中心
        </div>
    </div>
    <script>
        function outlogin(){
            window.location.href = '{!! url("jz/logout") !!}';
        }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('enterprise_my-style','style/enterprise_my.css') !!}