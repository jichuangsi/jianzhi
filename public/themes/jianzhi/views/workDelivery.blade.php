	<div class="top">
        提交验收材料
        <div class="out iconfont icon-fanhui" onclick="window.history.back(-1);"></div>
    </div>
    <form action="/jz/task/createDeliver" method="post" id="deliverform" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="center">
    	<input type="hidden" name="task_id" value="{{ $taskId }}"/>
        <div class="text_box">
            <div>验收说明
            		@if($errors->first('desc'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('desc') !!}</p>
        			@endif
            </div>
            <textarea name="desc" id="desc" cols="30" rows="10" nullmsg="描述不能为空" errormsg="字数超过限制" onkeydown='clearError(this)'></textarea>
        </div>
        <!-- <div class="text_box">
            <div>上传验收材料：</div>
            <div class="scbtn">选择文件<input type="file" name="file" id="file" multiple onchange="addfile()"></div>
        </div>
        <div class="text_box file_box">
        </div> -->
        
        <div class="img_box">
            	上传验收材料：
            <div class="img">
                <div class="zs_img">
                    <!-- <img src="" id="img" alt=""> -->
                </div>
                <div class="add">
                    +
                    <input type="file" name="file" id="file" onchange="imgfile()">
                </div>
            </div>
        </div>
        
        
        
        <input type="hidden" name="file_id">
        <div class="text_box">
            若您的文件过大，可以和企业联系通过邮箱发送，并在这里提交发送截图的文件即可。
        </div>
    </div>
    <button class="btn">提交验收</button>
    </form>
    	<div class="bigimg" onclick="$('.bigimg').css('display','none')">
            <img src="" alt="">
        </div>
    <script type="text/javascript">
    $(function(){
		var old_desc = "{{old('desc')}}";
		if(old_desc){
			$("textarea[name='desc']").val(old_desc);
		}

    });
    var deliverform=$("#deliverform").Validform({
        tiptype:3,
        label:".label",
        showAllError:true,
        ajaxPost:false,
        dataType:{
            'positive':/^[1-9]\d*$/,
        },
    });

    function addfile(){
        if($('.file_box').children().length < 3){
            var reads = new FileReader();
            f = document.getElementById('file').files[0];
            console.log(f)
            var div = '<div onclick="delfile(this)"><span>'+f.name+'</span><em>-</em></div>'
            $('.file_box').append(div)
            uploadFile();
        }else{
        	popUpMessage('最多上传三个文件')
        }
    }
    function delfile(val){
        $(val).remove()
    	deleteFile(val.id);
    }

    function imgfile(){
        if($(".zs_img").children().length < 3){
            var reads = new FileReader();
            f = document.getElementById('file').files[0];
            reads.readAsDataURL(f);
            reads.onload = function(e) {
                $(".zs_img").css("display", "flex");
                var html = '<div onclick="bigimg(this)" class="box_img"><img src ="'+this.result+'"><em onclick="delimg(this)">-</em></div>'
                $(".zs_img").append(html)
                //$('#img_file').val('')
                uploadFile();
            };
        }else{
        	popUpMessage('最多上传三个文件')
        }
    }
    function delimg(val){
        $(val).parent().remove()
        event.stopPropagation()
        if($(".zs_img").children().length == 0){
            $(".zs_img").css("display", "none");
        } 
        deleteFile($(val).parent()[0].id);
    }
    function bigimg(val){
        $('.bigimg').css('display','flex')
        $('.bigimg > img')[0].src = $(val).find('img')[0].src
    }
    
    /**
    	上传文件
    */
    function uploadFile(){
    	$("body").mLoading({text:"上传中"});//显示loading组件
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
            		var files = $(".zs_img").children();	
            		if(files&&files.length>0){
            			files[files.length-1].id = 'file_'+ret.id;
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
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('submit-style','style/submit.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('ajaxFileUpload-js', 'libs/jquery.ajaxFileUpload.js') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('mloading-js', 'libs/jquery.mloading.js') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('mloading-css', 'libs/jquery.mloading.css') !!}