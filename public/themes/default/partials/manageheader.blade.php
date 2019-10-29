<!-- #section:basics/sidebar.mobile.toggle -->
<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
    <span class="sr-only">Toggle sidebar</span>

    <span class="icon-bar"></span>

    <span class="icon-bar"></span>

    <span class="icon-bar"></span>
</button>

<!-- /section:basics/sidebar.mobile.toggle -->
<div class="navbar-header pull-left">
    <!-- #section:basics/navbar.layout.brand -->
    <a href="/manage" class="navbar-brand">
        {{--<img src="{!! url(Theme::get('site_config')['site_logo_2'])!!}"/>--}}
        @if(Theme::get('site_config')['site_logo_2'])
            <!-- <img src="{!! url(Theme::get('site_config')['site_logo_2'])!!}"> -->
        @else
            <img src="{!! Theme::asset()->url('images/logo.png') !!}">
        @endif
    </a>

    <!-- /section:basics/navbar.layout.brand -->

    <!-- #section:basics/navbar.toggle -->

    <!-- /section:basics/navbar.toggle -->
</div>

<!-- #section:basics/navbar.dropdown -->
<div class="navbar-buttons navbar-header pull-right" role="navigation">
    <ul class="nav ace-nav">
        {{--<li class="grey">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <i class="ace-icon fa fa-tasks"></i>
                <span class="badge badge-grey">4</span>
            </a>

            <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                <li class="dropdown-header">
                    <i class="ace-icon fa fa-check"></i>
                    4 Tasks to complete
                </li>

                <li>
                    <a href="#">
                        <div class="clearfix">
                            <span class="pull-left">Software Update</span>
                            <span class="pull-right">65%</span>
                        </div>

                        <div class="progress progress-mini">
                            <div style="width:65%" class="progress-bar"></div>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <div class="clearfix">
                            <span class="pull-left">Hardware Upgrade</span>
                            <span class="pull-right">35%</span>
                        </div>

                        <div class="progress progress-mini">
                            <div style="width:35%" class="progress-bar progress-bar-danger"></div>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <div class="clearfix">
                            <span class="pull-left">Unit Testing</span>
                            <span class="pull-right">15%</span>
                        </div>

                        <div class="progress progress-mini">
                            <div style="width:15%" class="progress-bar progress-bar-warning"></div>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <div class="clearfix">
                            <span class="pull-left">Bug Fixes</span>
                            <span class="pull-right">90%</span>
                        </div>

                        <div class="progress progress-mini progress-striped active">
                            <div style="width:90%" class="progress-bar progress-bar-success"></div>
                        </div>
                    </a>
                </li>

                <li class="dropdown-footer">
                    <a href="#">
                        See tasks with details
                        <i class="ace-icon fa fa-arrow-right"></i>
                    </a>
                </li>
            </ul>
        </li>--}}

        <li class="purple">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <i class="ace-icon fa fa-bell icon-animated-bell"></i>
                @if(Theme::get('messageCount'))
                <span class="badge badge-important">{!! Theme::get('messageCount') !!}</span>
                @endif
            </a>

            <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
            	@if(Theme::get('messageCount'))
                <li class="dropdown-header">
                    <i class="ace-icon fa fa-exclamation-triangle"></i>
                    {!! Theme::get('messageCount') !!} 个未读通知
                </li>
                @else
                <li class="dropdown-header">
                    <i class="ace-icon fa fa-exclamation-triangle"></i>
                    	暂无新的未读通知
                </li>
                @endif

				@if(Theme::get('eAuth_messageCount'))
					<li>
                    <a href="#" onclick="jumpPage('enterprise_auth')">
                        <div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-info fa fa-building"></i>
												新增企业认证{{Theme::get('eAuth_messageCount')}}条
											</span>
                            <!-- <span class="pull-right badge badge-info">+11</span> -->
                        </div>
                    </a>
                </li>
				
				@endif

				@if(Theme::get('uAuth_messageCount'))
					<li>
                    <a href="#" onclick="jumpPage('realname_auth')">
                        <div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-info fa fa-user"></i>
												新增个人认证{{Theme::get('uAuth_messageCount')}}条
											</span>
                            <!-- <span class="pull-right badge badge-info">+11</span> -->
                        </div>
                    </a>
                </li>
				
				@endif

				@if(Theme::get('newTask_messageCount'))
					<li>
                    <a href="#" onclick="jumpPage('new_task')">
                        <div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-info fa fa-tasks"></i>
												新增任务审核{{Theme::get('newTask_messageCount')}}条
											</span>
                            <!-- <span class="pull-right badge badge-info">+11</span> -->
                        </div>
                    </a>
                </li>
				
				@endif

				@if(Theme::get('newFeedback_messageCount'))
					<li>
                    <a href="#" onclick="jumpPage('user_feedback')">
                        <div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-info fa fa-comment"></i>
												新增意见反馈{{Theme::get('newFeedback_messageCount')}}条
											</span>
                            <!-- <span class="pull-right badge badge-info">+11</span> -->
                        </div>
                    </a>
                </li>
				
				@endif

                <!-- <li>
                    <a href="#">
                        <div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-pink fa fa-comment"></i>
												New Comments
											</span>
                            <span class="pull-right badge badge-info">+12</span>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="btn btn-xs btn-primary fa fa-user"></i>
                        Bob just signed up as an editor ...
                    </a>
                </li>

                <li>
                    <a href="#">
                        <div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-success fa fa-tasks"></i>
												New Orders
											</span>
                            <span class="pull-right badge badge-success">+8</span>
                        </div>
                    </a>
                </li> -->

                

                <!-- <li class="dropdown-footer">
                    <a href="#">
                        See all notifications
                        <i class="ace-icon fa fa-arrow-right"></i>
                    </a>
                </li> -->
            </ul>
        </li>

     

        <!-- #section:basics/navbar.user_menu -->
        <li class="light-blue">
            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                {{--<img class="nav-user-photo" src="{!! Theme::asset()->url('plugins/ace/avatars/user.jpg') !!}" alt="Jason's Photo" />--}}
                <img class="nav-user-photo" src="{!! Theme::asset()->url('images/default_avatar.png') !!}"  alt="Jason's Photo" />
								<span class="user-info">
									<small>Welcome,</small>
                                    {!! Theme::get('manager') !!}
								</span>

                <i class="ace-icon fa fa-caret-down"></i>
            </a>

            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                <li>
                    <a href="{!! url('manage/managerDetail/1') !!}">
                        <i class="ace-icon fa fa-cog"></i>
                        设置
                    </a>
                </li>


                <li class="divider"></li>

                <li>
                    <a href="{!! url('manage/logout') !!}">
                        <i class="ace-icon fa fa-power-off"></i>
                        退出
                    </a>
                </li>
            </ul>
        </li>

        <!-- /section:basics/navbar.user_menu -->
    </ul>
</div>
<script type="text/javascript">
function jumpPage(type){

	$.get('/manage/changeMessageStatus',{'type':type},function(ret,status,xhr){
        console.log(ret);
        console.log(status);

        switch(type){
    	case 'enterprise_auth': {
    		window.location.href = "{!! url('manage/enterpriseList') !!}";	
    	};
    	break;
    	case 'realname_auth': {
    		window.location.href = "{!! url('manage/userList') !!}";	
        };
        break;
        case 'new_task': {
    		window.location.href = "{!! url('manage/taskList') !!}";	
        };
        break;
        case 'user_feedback': {
    		window.location.href = "{!! url('manage/feedbackList') !!}";	
        };
        break;
    	}
    });
	
	
}
</script>
<!-- /section:basics/navbar.dropdown -->