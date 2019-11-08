	<style>
        .nav {
            width: 100%;
            border-bottom: 1px solid #e6e6e6;
        }
        .nav_title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .nav_ul {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .nav_ul_excel {
            justify-content: flex-start;
        }
        .nav_ul .nav_li {
            width: 20%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0px 20px;
            color: #fff;
            font-weight: 600;
            font-size: 20px;
            margin: 0px 20px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .nav_ul .nav_li:hover {
            box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.3);
        }
        .nav_ul .nav_li_column {
            width: 10%;
            /* height: 120px; */
            padding: 20px 0px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            border-radius: 5px;
            margin: 0px 20px;
            margin-bottom: 20px;
            cursor: pointer;
            border: 1px solid #f2f2f2;
        }
        .nav_ul .nav_li_column:hover {
            box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.3);
        }
        .nav_ul .nav_li_column div {
            margin-top: 10px;
        }
        .nav_ul .nav_li_column img {
            /* transform: scale(0.3); */
            width: 40%;
        }
        .nav_ul .nav_li_excel {
            width: 13%;
            /* height: 120px; */
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            border-radius: 10px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .nav_ul .nav_li_excel div {
            margin-top: 10px;
        }
        .nav_ul .nav_li_excel img {
            width: 60%;
            height: 70px;
        }
        .nav_ul .nav_li div {
            display: flex;
            flex-direction: column;
            white-space: nowrap;
            /* align-items: center; */
        }
        .nav_ul .nav_li img {
            transform: scale(0.8);
        }
        .nav_ul .nav_li:nth-child(1) {
            background-image: linear-gradient(to right,#0E90FE,#12B7F9);
        }
        .nav_ul .nav_li:nth-child(2) {
            background-image: linear-gradient(to right,#21D095,#2DE2B6);
        }
        .nav_ul .nav_li:nth-child(3) {
            background-image: linear-gradient(to right,#FFBE3E,#FFE33E);
        }
        .nav_ul .nav_li:nth-child(4) {
            background-image: linear-gradient(to right,#FC5452,#F79064);
        }
    </style>
	<div class="nav_box">
        <div class="nav_title">
            待处理事项
        </div>
        <div class="nav_ul">
            <div class="nav_li" onclick="window.location.href = '{!! url('manage/enterpriseList') !!}'">
                <div>
                    <span>{{$pendingCount['enterpriseAuth']}}</span>
                    <span>企业认证</span>
                </div>
                <img src="/themes/default/assets/img/qyrz_index.png" alt="">
            </div>
            <div class="nav_li"  onclick="window.location.href = '{!! url('manage/userList') !!}'">
                <div>
                    <span>{{$pendingCount['userAuth']}}</span>
                    <span>个人认证</span>
                </div>
                <img src="/themes/default/assets/img/grrz_index.png" alt="">
            </div>
            <div class="nav_li"  onclick="window.location.href = '{!! url('manage/taskList') !!}'">
                <div>
                    <span>{{$pendingCount['taskAudit']}}</span>
                    <span>任务审核</span>
                </div>
                <img src="/themes/default/assets/img/rwsh_index.png" alt="">
            </div>
            <div class="nav_li" onclick="window.location.href = '{!! url('manage/taskSettle') !!}'">
                <div>
                    <span>{{$pendingCount['workSettle']}}</span>
                    <span>任务结算</span>
                </div>
                <img src="/themes/default/assets/img/rwjs_index.png" alt="">
            </div>
        </div>
    </div>
    <div class="nav_box">
        <div class="nav_title">
            常用工具
        </div>
        <div class="nav_ul">
            <div class="nav_li_column" onclick="window.location.href = '{!! url('manage/enterpriseList') !!}'">
                <img src="/themes/default/assets/img/company.png" alt="">
                <div>企业管理</div>
            </div>
            <div class="nav_li_column" onclick="window.location.href = '{!! url('manage/userList') !!}'">
                <img src="/themes/default/assets/img/personal.png" alt="">
                <div>个人管理</div>
            </div>
            <div class="nav_li_column" onclick="window.location.href = '{!! url('manage/taskList') !!}'">
                <img src="/themes/default/assets/img/task.png" alt="">
                <div>任务管理</div>
            </div>
            <div class="nav_li_column" onclick="window.location.href = '{!! url('manage/taskDispatch') !!}'">
                <img src="/themes/default/assets/img/pass.png" alt="">
                <div>任务派单</div>
            </div>
            <div class="nav_li_column" onclick="window.location.href = '{!! url('manage/taskCheck') !!}'">
                <img src="/themes/default/assets/img/task_ys.png" alt="">
                <div>任务验收导入</div>
            </div>
            <div class="nav_li_column" onclick="window.location.href = '{!! url('manage/taskSettle') !!}'">
                <img src="/themes/default/assets/img/task_js.png" alt="">
                <div>任务状态同步</div>
            </div>
            <div class="nav_li_column" onclick="window.location.href = '{!! url('manage/channelDistribution') !!}'">
                <img src="/themes/default/assets/img/fp.png" alt="">
                <div>分配渠道商</div>
            </div>
        </div>
    </div>
    <div class="nav_box">
        <div class="nav_title">
            表格下载
        </div>
        <div class="nav_ul nav_ul_excel">
            <div class="nav_li_excel" onclick="window.location.href = '{!! url('/attachment/sys/templates/template_company.xlsx') !!}'">
                <img src="/themes/default/assets/img/excel.png" alt="">
                <div>企业导入模板</div>
            </div>
            <div class="nav_li_excel" onclick="window.location.href = '{!! url('/attachment/sys/templates/template_personal.xlsm') !!}'">
                <img src="/themes/default/assets/img/excel.png" alt="">
                <div>个人导入模板</div>
            </div>
            <div class="nav_li_excel" onclick="window.location.href='/attachment/sys/templates/template_task.xlsx'">
                <img src="/themes/default/assets/img/excel.png" alt="">
                <div>任务导入模板</div>
            </div>
        </div>
    </div>
    <script>
    </script>


{!! Theme::asset()->container('specific-js')->usePath()->add('excanvas-js', 'plugins/ace/js/jquery.min.js') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('easypiechart-js', 'plugins/ace/js/jquery.easypiechart.min.js') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('sparkline-js', 'plugins/ace/js/jquery.sparkline.min.js') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('flot-js', 'plugins/ace/js/flot/jquery.flot.min.js') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('flotPie-js', 'plugins/ace/js/flot/jquery.flot.pie.min.js') !!}
{!! Theme::asset()->container('specific-js')->usePath()->add('flotResize-js', 'plugins/ace/js/flot/jquery.flot.resize.min.js') !!}
{!! Theme::asset()->container('custom-js')->usePath()->add('backstage-js', 'js/backstage.js') !!}
