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
            margin-left: 30px;
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
            /* height: 36px; */
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
                    <li>任务验收记录导入文件必须为一个合法格式的Excel文件；</li>
                    <li>Excel文件第一行为标题行；</li>
                    <li>请务必正确填写任务ID、收款户名、身份证号、打款金额等信息；</li>
                    <li>已完成给接单人打款操作的结算状态可填写为已结算或ok；</li>
                    <li>文件大小需控制在{{$filesize}}M以内！</li>
                </ul>
            </div>
        </div>
        	<form action="{!! url('manage/taskSettleImport') !!}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
        	<div class="text">
                <div>文件选择</div>
                <div class="ipt">
                    <input type="file" name="tasksettlefile" id="file" onchange="uploadFile()">
                    <div>选择文件</div>
                    <input type="text" class="file_name">
                </div>
            </div>
            <div class="text">
                <div class="btn_box">
                    <button class="dr_btn btn">导入</button>
                    <div class="fh_btn btn" onclick="window.location.href = '{!! url('manage/taskSettle') !!}'">返回</div>
                </div>
            </div>
            </form>
    </div>
    <div class="table_box">
        <table style="table-layout: fixed;">
        	<thead>数据导入结果</thead>
            <tr>
                <th>企业ID</th>
                <th>企业名称</th>
                <th>任务ID</th>
                <th>任务名称</th>
                <th>任务主类别</th>
                <th>验收时间</th>
                <th>收款户名</th>
                <th>收款帐号</th>
                <th>打款金额</th>
                <th>结算状态</th>
                <th>导入说明</th>
            </tr>
            @if(isset($tasksSettle))
            	@foreach($tasksSettle as $item)
            		<tr>
            			<td>{{$item[0]}}</td>
            			<td>{{$item[1]}}</td>
            			<td>{{$item[2]}}</td>
            			<td>{{$item[3]}}</td>
            			<td>{{$item[4]}}</td>
            			<td>{{$item[5]}}</td>
            			<td>
            			{{$item[6]}}
            			</td>
            			<td>
            			{{$item[7]}}
            			</td>    
            			<td>
            			{{$item[8]}}
            			</td>  
            			<td>
            			{{$item[9]}}
            			</td>   
            			<td>
            			{{$item[10]}}
            			</td>     			
            			<td>
            			@if(isset($item['msg']))
            				{{$item['msg']}}
            			@else
            				成功
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
