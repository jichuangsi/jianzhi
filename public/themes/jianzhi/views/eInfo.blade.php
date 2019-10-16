	<div class="top">
        企业资料
        <div class="out iconfont icon-fanhui" onclick="window.history.back(-1);"></div>
    </div>
    <div class="center">
        <div class="center_box">
            <div class="title">
                认证信息
            </div>
            <div>企业名称：</div>
            <div>纳税人识别号：</div>
        </div>
        <div class="center_box">
            <div class="title">
                账户信息
            </div>
            <div class="ipt">企业法人 <input type="text" placeholder="请输入联系人"></div>
            <div class="ipt">联系人 <input type="text" placeholder="请输入联系人"></div>
            <div class="ipt">联系电话 <input type="text" placeholder="请输入企业电话"></div>
            <div class="ipt">企业邮箱 <input type="text" placeholder="请输入企业邮箱"></div>
            <div class="img">
                营业执照副本
                <div class="img_file">
                    <img src="/themes/jianzhi/assets/images/fm.jpg" alt="">
                    <input type="file">
                </div>
            </div>
        </div>
    </div>
    <div class="btn">
        保存
    </div>
    {!! Theme::asset()->container('specific-css')->usepath()->add('my-style','style/enterprise_edit.css') !!}