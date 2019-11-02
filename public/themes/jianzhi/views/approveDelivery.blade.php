	<div class="top">
        确定验收材料
        <div class="out iconfont icon-fanhui" onclick="window.history.back(-1);"></div>
    </div>
    <form action="/jz/task/createApproval" method="post" id="approvalform" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="center">
    		<input type="hidden" name="work_id" value="{{ $workId }}"/>
            <div class="text_box orange">
                <div>任务金额确定：
                	@if($errors->first('payment'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('payment') !!}</p>
        			@endif
        			</div><input type="number" name="payment" id="payment" onkeydown='clearError(this)' value="{{old('payment')}}">
            </div>
        <div class="text_box">
            <div>评价及说明：
            		@if($errors->first('comment'))
        				<p class="Validform_checktip Validform_wrong">{!! $errors->first('comment') !!}</p>
        			@endif
            </div><textarea cols="30" rows="10" name="comment" id="comment" onkeydown='clearError(this)'>{{old('comment')}}</textarea>
        </div>
    </div>
    <button class="btn" type="submit">确定验收</button>
    </form>
    <script>
    var approvalform=$("#approvalform").Validform({
        tiptype:3,
        label:".label",
        showAllError:true,
        ajaxPost:false,
        dataType:{
            'positive':/^[1-9]\d*$/,
        },
    });
    function clearError(obj){
		//console.log(obj);
		$(obj).parent().find('p').remove();
    }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('acceptance-style','style/acceptance.css') !!}
    {!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
    {!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}