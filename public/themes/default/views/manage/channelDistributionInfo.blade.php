</div>
<style>
        .jnbox {
            width: 41%;
            font-size: 14px;
            padding-bottom: 20px;
        }
        .jn_check {
            width: 100%;
            display: flex;
            line-height: 36px;
            margin-bottom: 10px;
        }
        .jn_check div:first-child {
            padding-left: 20px;
        }
        .jn_check div:last-child {
            flex: 1;
        }
        .jn span {
            background-color: #3da7f4;
            display: inline-block;
            margin-right: 40px;
            position: relative;
            padding-left: 10px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .jn span em {
            font-style: normal;
            padding: 0px 10px;
        }
        .jn span em:hover {
            background-color: #0094ff;
        }
        .jn span i {
            display: inline-block;
            border: 18px solid #3da7f4;
            position: absolute;
            right: -36px;
            top: 0px;
            border-color:transparent transparent transparent #3da7f4;
        }
        .jn_box {
            width: 99%;
            margin: 0 auto;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #f3f3f3;
        }
        .jn_li {
            width: 100%;
        }
        .jn_li .title {
            width: 100%;
            background-color: #f3f3f3;
            line-height: 46px;
            padding: 0px 20px;
        }
        .jn_li .text {
            width: 100%;
            background-color: #fff;
            line-height: 36px;
            height: 0px;
            overflow: hidden;
        }
        .jn_li .text_check {
            height: auto;
            padding: 20px;
        }
        .jn_li .text span {
            background-color: #f3f3f3;
            display: inline-block;
            margin-right: 40px;
            position: relative;
            padding-left: 10px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .jn_li .text span:hover {
            background-color: #3da7f4;
            color: #fff;
        }
        .jn_li .text span:hover i {
            border-color:transparent transparent transparent #3da7f4;
        }
        .jn_li .text em {
            font-style: normal;
        }
        .jn_li .text i {
            display: inline-block;
            border: 18px solid #f3f3f3;
            position: absolute;
            right: -36px;
            top: 0px;
            border-color:transparent transparent transparent #f3f3f3;
        }
        .bigimg {
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: none;
            position: fixed;
            left: 0px;
            top: 0px;
            /*justify-content: center;
            align-items: center;*/
            overflow: auto;
            z-index:9999;
        }
        .bigimg img {
            width: 60%;
            position: absolute;
            top: 10%;
            left: 25%;
            /* height: 80%;
            transform: scale(1.5); */
            /* 放大倍数 */
        }
        .table_box {
            width: 100%;
        }
        .table_box table {
            width: 100%;
            line-height: 36px;
            text-align: left;
            color: rgb(103, 106, 108);
        }
        .table_box table tr{
            width: 100%;
            height: 36px;
            display: block;
            border-bottom: 1px solid rgb(103, 106, 108);;
            display: flex;
        }
        .table_box table tr th,.table_box table tr td{
            flex: 1;
        }
    </style>
<h3 class="header smaller lighter blue mg-top12 mg-bottom20">分配渠道商</h3>
    
	<div class="g-backrealdetails clearfix bor-border">
			<form class="form-horizontal" id="chanform" role="form" action="{!! url('manage/channelDistributionInfo') !!}" method="post" enctype="multipart/form-data" >
			{!! csrf_field() !!}
			<div class="bankAuth-bottom clearfix col-xs-12">
				<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 企业名称：</p>
				<p class="col-sm-10">
					{{$entinfo['company_name']}}
					<input type="hidden" name="eid" value="{{$entinfo['id']}}" />
				</p>
			</div>
			<div class="bankAuth-bottom clearfix col-xs-12">
				<p class="col-sm-1 control-label no-padding-left" for="form-field-1"> 销售人员：</p>
				<select name="mid" class="col-sm-3 mid">
					@foreach($managelist as $k=>$item)
						<option value="{{$item['id']}}">{{$item['realname']}}</option>
					@endforeach
				</select>
			</div>
		</form>
		<div class="col-xs-12">
			<div class="clearfix row bg-backf5 padding20 mg-margin12">
				<div class="col-xs-12">
					<div class="col-md-1 text-right"></div>
					<div class="col-md-10">
						<div class="btn btn-primary btn-sm" style="margin-left: 50px" onclick="sub()">保存</div>
						<div class="btn btn-primary btn-sm" style="margin-left: 50px" onclick="window.location.href = '{!! url('manage/channelDistribution') !!}'">返回</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="space col-xs-12"></div>
		<div class="col-xs-12 space">

		</div>
	</div>
	<div class="bigimg" onclick="$('.bigimg').css('display','none')">
        <img src="" alt="">
    </div>
	<script>
		function sub(){
			$('#chanform').submit();
		}
		function toEdit(id){
			window.location.href = "{!! url('manage/userEdit') !!}" + "/" + id;		
		}
		function bigimg(val){
            $('.bigimg > img')[0].src = val.src
            $('.bigimg').css('display','flex')
        }
    </script>
{!! Theme::asset()->container('custom-css')->usePath()->add('back-stage-css', 'css/backstage/backstage.css') !!}
{!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}
{!! Theme::asset()->container('specific-css')->usePath()->add('datepicker-css', 'plugins/ace/css/datepicker.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('datepicker-js', 'plugins/ace/js/date-time/bootstrap-datepicker.min.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('userManage-js', 'js/userManage.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('main-js', 'js/main.js') !!}
