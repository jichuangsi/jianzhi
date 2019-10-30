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
                    <li>1. 请先下载任务模板进行填写，再导入；</li>
                    <li>2. 任务模板Excel文件第一行、第二行为标题行，不得更改；</li>
                    <li>3. 每个任务以【编号】作为区分，只要【编号】相同，就视为同一个任务；任务的具体信息以【编号】第一次出现的所在行为准；</li>
                    <li>4. 同一【编号】的任务需相邻填写，包括该任务关联的接单人也需要相邻填写；</li>
                    <li>5. 任务派单给多个接单人时，除第一个接单人所在行需要有具体任务信息外，其他行只需要有【编号】即可；</li>
                    <li>6. 数据导入之前请先点击【数据校验】按钮进行检验，校验结果详见【数据检查结果】；</li>
                    <li>7. 【数据检查结果】中会罗列存在的问题和可能的逻辑隐患，逻辑隐患不影响导入，但会更改数据库中的相关数据；</li>
                    <li>8. 【存在的问题】行号会标*，【逻辑隐患】不会标*</li>
                    <li>9. 点击【导入】按钮时，系统仅将对【任务相关信息】进行有效性验证和导入；</li>
                    <li>10. 点击【导入并派单】按钮时，系统将对【任务相关信息】和【接单人相关信息】进行有效性验证和导入；</li>
                    <li>11. 同一个Excel文件多次导入，将视为要导入新任务，系统不做任务重复的处理；</li>
                </ul>
            </div>
        </div>
        	<form action="{!! url('manage/taskExcel') !!}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
        	<div class="text">
                <div>文件选择</div>
                <div class="ipt">
                    <input type="file" name="tasklistexcel" id="file" onchange="uploadFile()">
                    <div>选择文件</div>
                    <input type="text" class="file_name">
                </div>
            </div>
            <div class="text">
                <div class="btn_box">
                    <button class="dr_btn btn">导入</button>
                    <div class="fh_btn btn" onclick="window.location.href = '{!! url('manage/taskList') !!}'">返回</div>
                </div>
            </div>
            </form>
    </div>
    <div class="table_box" style="display: none;">
        <table style="table-layout: fixed;">
        	<thead>数据导入结果</thead>
            <tr>
                <th>行号</th>
                <th>任务ID</th>
                <th>任务名称	</th>
                <th>接单人姓名</th>
                <th>身份证号</th>
                <th>手机号</th>
                <th>验收状态</th>
                <th>验收时间</th>
                <th>结算金额</th>
                <th>导入说明</th>
            </tr>
            @if(isset($tasksCheck))
            	@foreach($tasksCheck as $item)
            		<tr>
            			<td>{{$item['num']}}</td>
            			<td>{{$item[0]}}</td>
            			<td>{{$item[1]}}</td>
            			<td>{{$item[14]}}</td>
            			<td>{{$item[15]}}</td>
            			<td>{{$item[16]}}</td>
            			<td>{{$item['c_status']}}</td>
            			<td>
            			{{$item[21]}}
            			</td>
            			<td>
            			{{$item[22]}}
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
