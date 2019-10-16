	<div class="top">
        信息编辑
        <div class="out iconfont icon-fanhui" onclick="window.history.back(-1);"></div>
    </div>
    <div class="center">
        <div class="center_box">
            <div class="title">
                认证信息
            </div>
            <div>真实姓名：</div>
            <div>身份证号：</div>
        </div>
        <div class="center_box">
            <div class="title">
                账户信息
            </div>
            <div class="ipt">银行卡号 <input type="text" placeholder="请输入银行卡号"></div>
        </div>
        <div class="center_box">
            <div class="ipt">手机号 <input type="text" placeholder="请输入手机号"></div>
        </div>
    </div>
    <div class="btn">
        确认修改
    </div>
    {!! Theme::asset()->container('specific-css')->usepath()->add('my-style','style/edit.css') !!}