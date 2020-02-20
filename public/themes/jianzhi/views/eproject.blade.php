	<div class="top_nav">
        <div class="top_nav_check" data-id="2"><span>审核中</span></div>
        <div data-id="3"><span>招募中</span></div>
        <div data-id="4"><span>进行中</span></div>
        <div data-id="5"><span>待验收</span></div>
        <div data-id="6"><span>已验收</span></div>
        <div data-id="7"><span>已结算</span></div>
    </div>
    <div class="center">
        <div class="center_box mescroll" id="mescroll">
            <div class="center_kong">
                <img src="/themes/jianzhi/assets/images/developmenting.png" alt="">
            </div>
            <div class="center_list">
                <!-- <div class="list_box" onclick="task_user()">
                    	任务A <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span>
                </div> -->
            </div>
            <div style="font-size: 15px;line-height: 15px;margin-top: 10px;">
	    		 <span>
	    		 	Copyright 2020 广州网金创纪信息技术有限公司 All rights reserved <a style="color: blue;text-decoration: underline;" href="http://www.beian.miit.gov.cn"> 粤ICP备19125254号-1</a>
				</span>
			</div>
        </div>
        
    </div>
    
    <div class="foot">
        <div onclick="window.location.href = '{!! url('jz/home') !!}'">
            <span class="iconfont icon-emizhifeiji"></span>
            发布任务
        </div>
        <div class="foot_check">
            <span class="iconfont icon-thin-_notebook_p"></span>
            项目管理
        </div>
        <div onclick="window.location.href = '{!! url('jz/my') !!}'">
            <span class="iconfont icon-dalou4"></span>
            企业中心
        </div>
    </div>
    <script>
    	var size = 8;
    	$(function(){
    		//loadMyTasks($('.top_nav_check').attr('data-id'), 1);
       	});
        $('.top_nav > div').click(function(){
            if(this.className.indexOf('top_nav_check') != -1){
                return
            }else{
                $(this).addClass('top_nav_check').siblings().removeClass('top_nav_check')
                $('.center_list').empty();
                mescroll.resetUpScroll();   
                //loadMyTasks($('.top_nav_check').attr('data-id'), 1);
            }
        })
        var mescroll = new MeScroll("mescroll", { //第一个参数"mescroll"对应上面布局结构div的id
            //如果下拉刷新是重置列表数据,那么down完全可以不用配置
            down: {
            	auto: false, //是否在初始化完毕之后自动执行下拉回调callback; 默认true
                callback: downCallback //下拉刷新的回调
            },
            up: {
            	auto: true, //是否在初始化时以上拉加载的方式自动加载第一页数据; 默认false
                htmlNodata: '<p class="upwarp-nodata">没有更多了..</p>',
                page: {
                    num: 0,
                    size: size,
                    time: null
                },
                callback: upCallback,    //上拉加载的回调
                toTop:{ //配置回到顶部按钮
					src : "/themes/jianzhi/assets/images/mescroll-totop.png", //默认滚动到1000px显示,可配置offset修改
					offset : 1000
				}
            }
        });
        function loadMyTasks(status, page, successCallback, errorCallback){
            if(!status) return
        	$.get('/jz/task/ajaxEmyTasks',{'status':status, 'page':page.num, 'size': page.size},function(ret,status,xhr){
                console.log(ret);
                console.log(status);
				if(status==='success'){
					if(ret&&ret.data&&ret.data.length>0){
						successCallback&&successCallback(ret.data, ret.total);
					}else{
						errorCallback&&errorCallback();
					}
				}else{
					errorCallback&&errorCallback();
				}
            });
        }
        function downCallback(){
            console.log('下拉')     
            $('.center_list').empty();	
            mescroll.resetUpScroll();       
            /* loadMyTasks($('.top_nav_check').attr('data-id'), {'num':0,'size':size},
            		function(data){
    				//联网成功的回调,隐藏下拉刷新的状态
    				mescroll.endSuccess();
    				//设置列表数据
    				setListData(data, false);
    			}, function(){
    				//联网失败的回调,隐藏下拉刷新的状态
                    mescroll.endErr();
    			}); */
        }
        function upCallback(page){
            console.log('上拉')
            loadMyTasks($('.top_nav_check').attr('data-id'), page, function(curPageData, totalSize){
            		//方法二(推荐): 后台接口有返回列表的总数据量 totalSize    
    				mescroll.endBySize(curPageData.length, totalSize); //必传参数(当前页的数据个数, 总数据量)
                	//设置列表数据    
    				setListData(curPageData, true);
                }, function(){
                    mescroll.endErr();
    
    			});
            
        }
        /*设置列表数据*/
		function setListData(curPageData, isAppend) {	
			//if(!isAppend) $('.center_list').empty();		
			for(var i in curPageData){
				var html = '';
				html += '<div class="list_box" onclick="taskDetail('+curPageData[i].id+')">'+curPageData[i].title+'<span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span></div>'
				$('.center_list').append(html);
			}
		}
        
        function taskDetail(id){
            var url = '/jz/task/'+id;
            window.location.href = url;
        }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('mescroll-style','libs/mescroll.css') !!}
    {!! Theme::asset()->container('specific-css')->usepath()->add('enterprise_task-style','style/enterprise_task.css') !!}
    {!! Theme::asset()->container('specific-js')->usepath()->add('mescroll-js','libs/mescroll.js') !!}