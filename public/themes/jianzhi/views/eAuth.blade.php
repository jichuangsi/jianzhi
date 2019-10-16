	<div class="top">
        企业认证
        <div class="out iconfont icon-fanhui" onclick="window.history.back(-1);"></div>
    </div>
    <div class="center">
        <div class="list">
            企业名称<input type="text" placeholder="请输入企业名称">
        </div>
        <div class="list">
            联系人<input type="text" placeholder="请输入联系人">
        </div>
        <div class="list">
            联系电话<input type="text" placeholder="请输入联系人电话">
        </div>
        <div class="title">开票信息</div>
        <div class="list">
            开户行<input type="text" placeholder="请输入企业开户行">
        </div>
        <div class="list">
            账户<input type="text" placeholder="请输入企业账户">
        </div>
        <div class="list">
            纳税人识别码<input type="text" placeholder="请输入纳税人识别码">
        </div>
        <div class="list">
            电话<input type="text" placeholder="请输入企业电话">
        </div>
        <div class="list">
            地址<input type="text" placeholder="请输入营业执照地址">
        </div>
        <div class="img">
                营业执照副本
                <div class="img_file">
                    <img src="/themes/jianzhi/assets/images/fm.jpg" alt="">
                    <input type="file">
                </div>
            </div>
            <div class="text">
                    请将营业照副本放在平面拍摄，避免歪斜以及反光，以免造成审核不通过，谢谢！请上传不小于多少M得不大于多少M得照片
            </div>
    </div>
    <div class="btn">
        提交认证
    </div>
    <script>
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('my-style','style/enterprise_Authentication.css') !!}