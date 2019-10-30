	<style>
        * {
            box-sizing: border-box;
        }
        body {
            padding: 20px;
            background-color: #f2f2f2;
        }
        .file_box {
            width: 100%;
            background-color: #fff;
            padding: 20px;
            padding-bottom: 60px;
            border-radius: 10px
        }
        .file_box .text {
            width: 100%;
            padding-left: 100px;
            margin-bottom: 20px;
        }
        .file_box .text div{
            display: inline-block;
            vertical-align: top;
        }
        .file_box .text div ul {
            margin: 0;
        }
        .file_box .text .ipt {
            margin-left: 40px;
            border: 1px solid #f3f3f3;
            position: relative;
        }
        .file_box .text .ipt1 {
            margin-left: 40px;
            position: relative;
        }
        .file_box .text .ipt div{
            padding: 0px 20px;
            line-height: 36px;
        }
        .file_box .text .ipt .file_name {
            height: 36px;
            line-height: 36px;
            border: 1px solid #f3f3f3;
            width: 250px;
        }
        .file_box .text .ipt #file{
            position: absolute;
            left: 0px;
            top: 0px;
            opacity: 0;
            width: 100%;
            height: 100%;
        }
        .file_box .text .btn_box{
            float: right;
        }
        .file_box .text .btn {
            padding: 10px 20px;
            color: #fff;
            border-radius: 5px;
            background-color: #23c6c8;
        }
        .file_box .text .fh_btn{
            background-color: #f8ac59;  
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
	<div class="file_box">
        <div class="text">
            <div>文件上传说明</div>
            <div class="ipt1">
                <ul>
                    <li>企业信息导入文件必须为一个合法格式的Excel文件(<a href="{{ URL('/attachment/sys/templates/template_company.xlsx') }}" target="#">下载模板</a>)</li>
                    <li>Excel文件第一行为标题行</li>
                    <li>请务必正确填写企业名称、纳税人识别号、联系电话等信息</li>
                    <li>营业执照副本照片请尽量插入JPG格式的图片</li>
                    <li>联系人电话就是该企业注册本平台时的登录名</li>
                    <li>文件大小需控制在{{$filesize}}M以内！</li>
                </ul>
            </div>
        </div>
        	<form action="{!! url('manage/enterpriseImport') !!}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
        	<div class="text">
                <div>文件选择</div>
                <div class="ipt">
                    <input type="file" name="enterprisesfile" id="file" onchange="uploadFile()">
                    <div>选择文件</div>
                    <input type="text" class="file_name">
                </div>
            </div>
            <div class="text">
                <div class="btn_box">
                    <button class="dr_btn btn">导入</button>
                    <div class="fh_btn btn" onclick="window.location.href = '{!! url('manage/enterpriseList') !!}'">返回</div>
                </div>
            </div>
            </form>
    </div>
    <div class="table_box">
        <table style="table-layout: fixed;">
            <tr>
                <th>企业名称</th>
                <th>所在城市</th>
                <th>纳税人识别号</th>
                <th>联系人电话</th>
                <th>导入结果</th>
            </tr>
            @if(isset($enterprises))
            	@foreach($enterprises as $item)
            		<tr>
            			<td>{{$item[0]}}</td>
            			<td>{{$item[1]}}</td>
            			<td>{{$item[2]}}</td>
            			<td>{{$item[7]}}</td>
            			<td>
            			@if(isset($item['msg']))
            				{{$item['msg']}}
            			@else
            				未知
            			@endif
            			</td>
            		</tr>
            	@endforeach
            @endif
        </table>
    </div>
	<script type="text/javascript">
		function uploadFile(){
	    	var reads = new FileReader();
	    	var f = document.getElementById('file').files[0];
	    	$(".file_name").val(f.name);
	    }
    </script>
{!! Theme::asset()->container('custom-css')->usePath()->add('back-stage-css', 'css/backstage/backstage.css') !!}
{!! Theme::asset()->container('specific-css')->usePath()->add('validform-css', 'plugins/jquery/validform/css/style.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('validform-js', 'plugins/jquery/validform/js/Validform_v5.3.2_min.js') !!}
{!! Theme::asset()->container('specific-css')->usePath()->add('datepicker-css', 'plugins/ace/css/datepicker.css') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('datepicker-js', 'plugins/ace/js/date-time/bootstrap-datepicker.min.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('userManage-js', 'js/userManage.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('main-js', 'js/main.js') !!}
