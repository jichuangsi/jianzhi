	<div class="top">
        个人认证
        <div class="out iconfont icon-fanhui" onclick="window.history.back(-1);"></div>
    </div>
    <div class="center">
        <div class="list">
            真实姓名<input type="text">
        </div>
        <div class="list">
            身份证号码<input type="text">
        </div>
        <div class="list">
            银行卡号<input type="text">
        </div>
        <div class="text">
            请将证件放在平面拍摄，避免歪斜以及反光，以免造成审核不通过，谢谢！建议选择JPG格式照片进行上传，照片小于5M。
        </div>
        <div class="img_box">
            <div class="left_img">
                <div class="img">
                    <img src="/themes/jianzhi/assets/images/zm.jpg" alt="">
                    <input type="file" name="" id="">
                </div>
                身份证正面照
            </div>
            <div class="left_img">
                <div class="img">
                    <img src="/themes/jianzhi/assets/images/fm.jpg" alt="">
                    <input type="file" name="" id="">
                </div>
                身份证反面照
            </div>
        </div>
    </div>
    <div class="btn">
        提交认证
    </div>
    <script>
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('my-style','style/Authentication.css') !!}