	<div class="top">
        建议反馈
        <div class="out iconfont icon-fanhui" onclick="window.history.back(-1);"></div>
    </div>
    <div class="center">
        <div class="title">
            标题 <input type="text" placeholder="请输入标题">
        </div>
        <div class="text">
            <div>内容描述</div>
            <textarea name="" id="" maxlength="200" cols="30" rows="10" placeholder="请输入内容" oninput="text(this)"></textarea>
            <em><span>0</span>/200</em>
        </div>
        <div class="img_box">
            上传图片
            <div class="img">
                <div class="zs_img">
                    <img src="" id="img" alt="">
                </div>
                <div class="add">
                    +
                    <input type="file" name="" id="img_file" onchange="imgfile()">
                </div>
            </div>
        </div>
    </div>
    <div class="btn">
        提交反馈
    </div>
    <script>
        function text(val){
            $(val).next().find('span').text(val.value.length)
        }
        function imgfile(){
            var reads = new FileReader();
            f = document.getElementById('img_file').files[0];
            reads.readAsDataURL(f);
            reads.onload = function(e) {
            document.getElementById('img').src = this.result;
            $(".zs_img").css("display", "block");
            $('#img_file').val('')
            };
        }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('my-style','style/proposal.css') !!}