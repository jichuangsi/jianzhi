	<div class="top">
        建议反馈
        <div class="out iconfont icon-fanhui" onclick="backToMy();"></div>
    </div>
    <form action="/jz/user/createFeedback" method="post" id="feedbackform" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="center">    	
        <div class="title">
            	<span>标题
            	@if($errors->first('title'))
        			<p class="Validform_checktip Validform_wrong">{!! $errors->first('title') !!}</p>
        		@endif
            	</span> <input type="text" placeholder="请输入标题" name="title" id="title" onkeydown='clearError(this)'>
        </div>
        <div class="text">
            <div>内容描述
            	@if($errors->first('desc'))
        			<p class="Validform_checktip Validform_wrong">{!! $errors->first('desc') !!}</p>
        		@endif
            </div>
            <textarea name="desc" id="desc" maxlength="200" cols="30" rows="10" placeholder="请输入内容" oninput="text(this)" onkeydown='clearError(this)'></textarea>
            <em><span>0</span>/200</em>
        </div>
        <div class="img_box">
            上传图片
            <div class="img">
                <div class="zs_img">
                    <!-- <img src="" id="img" alt=""> -->
                </div>
                <div class="add">
                    +
                    <input type="file" name="file" id="file" onchange="imgfile()">
                </div>
            </div>
            <input type="hidden" name="file_id">
        </div>
    </div>
    <button class="btn" type="submit">
        提交反馈
    </button>
    </form>
    <script>
        function text(val){
            $(val).next().find('span').text(val.value.length)
        }
        function imgfile(){
        	if($(".zs_img").children().length < 3){
                var reads = new FileReader();
                var f = document.getElementById('file').files[0];            
                reads.readAsDataURL(f);
                reads.onload = function(e) {
                    // document.getElementById('img').src = this.result;
                    $(".zs_img").css("display", "flex");
                    /* var img = document.createElement('img')
                    img.src = this.result;
                    $(".zs_img").append(img) */
                    var html = '<div onclick="delimg(this)" class="box_img"><img src ="'+this.result+'"><em>-</em></div>'
    				$(".zs_img").append(html)
                    uploadFile();
                };
                
            }else{
            	popUpMessage('最多上传三张')
            }
        }
        function delimg(val){
        	$(val).remove()
        	if($(".zs_img").children().length == 0){
        		$(".zs_img").css("display", "none");
        	}
        	deleteFile(val.id);
        }
        /**
        	上传文件
        */
        function uploadFile(){
        	$("body").mLoading({text:"上传中"});
        	//$("body").mLoading("show");//显示loading组件
        	var headers = { "_token": "{{ csrf_token() }}"};
        	// 开始上传
            $.ajaxFileUpload({
                secureuri: false,// 是否启用安全提交，默认为 false
                type: "POST",
                url: "/jz/task/fileUpload",
                fileElementId: "file",// input[type=file] 的 id
                dataType: "json",// 返回值类型，一般位 `json` 或者 `text`
                //data: data,// 添加参数，无参数时注释掉
                header: headers,
                success: function (ret, status) {
                    // 成功
                	console.log(ret); 
                	if(ret&&ret.id){
                		var attachmentId = $("input[name='file_id']").val();
                		$("input[name='file_id']").val(attachmentId+(attachmentId?",":"")+ret.id);     
                		var imgs = $(".zs_img").children();	
                		if(imgs&&imgs.length>0){
                			imgs[imgs.length-1].id = 'img_'+ret.id;
                    	}
                    }
                    $('#file').val('')
                },
                error: function (data, status, e) {
                    // 失败
                	console.log(e);                	
                },
                complete: function(){
                	$("body").mLoading("hide");//隐藏loading组件
                }
            });
        }
        function deleteFile(id){
        	if(!id) return;
        	var ids = id.split('_', 2);
        	if(!ids[1]) return;
        	var attachmentId = $("input[name='file_id']").val();
        	attachmentId = attachmentId.replace(','+ids[1],'').replace(ids[1]+',','').replace(ids[1],'')
        	console.log(attachmentId);
        	$("input[name='file_id']").val(attachmentId);
            $.get('/jz/task/fileDelet',{'id':ids[1]},function(data){
                console.log(data);            
            });
        }
        function clearError(obj){
    		//console.log(obj);
    		$(obj).parent().find('p').remove();
        }
        function backToMy(){
        	window.location.href = "{!! url('jz/my') !!}";
        }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('proposal-style','style/proposal.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('ajaxFileUpload-js', 'libs/jquery.ajaxFileUpload.js') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('mloading-js', 'libs/jquery.mloading.js') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('mloading-css', 'libs/jquery.mloading.css') !!}