	<div class="top_nav">
        <div class="top_nav_check"  data-id="0"><span>已报名</span></div>
        <div data-id="1"><span>进行中</span></div>
        <div data-id="2"><span>待验收</span></div>
        <div data-id="3"><span>待结算</span></div>
        <div data-id="5"><span>已结算</span></div>
    </div>
    <div class="center">
        <div class="center_box mescroll" id="mescroll">
            <div class="center_kong">
                <img src="/themes/jianzhi/assets/images/developmenting.png" alt="">
            </div>
            <div class="center_list">
                <!-- 
                <div class="list_box" onclick="details()">
                    <div class="list_box_title">
                        报名时间：2019年08月01日 12:00 <span>已报名审核</span>
                    </div>
                    <div class="list_box_top">
                        <div class="img">
                            <img src="/themes/jianzhi/assets/images/xxjs.png" alt="">
                        </div>
                        <div class="text_box">
                            <div class="title">福建省福州市-平台开发</div>
                            <div class="text">任务类型：信息技术服务</div>
                            <div class="money">预算：32750</div>
                        </div>
                    </div>
                    <div class="list_box_bottom">
                        <div>服务时间：2019年08月01日-2019年08月31日</div>
                        <div>服务地点：福建省福州市鼓楼区</div>
                        <div class="qxbtn">取消报名</div>
                    </div>
                </div>
                 -->
                 
            </div>
            <div style="font-size: 15px;line-height: 15px; display: block;">
				 <span>
				 	Copyright 2020 广州网金创纪信息技术有限公司 All rights reserved <a style="color: blue;text-decoration: underline;" href="http://www.miitbeian.gov.cn/"> 粤ICP备19125254号-1</a>
				</span>
			</div>
        </div>
    </div>
    <div class="foot">
        <div onclick="window.location.href = '{!! url('jz/home') !!}'">
            <span class="iconfont icon-fangzi"></span>
            任务大厅
        </div>
        <div class="foot_check">
            <span class="iconfont icon-thin-_notebook_p"></span>
            我的任务
        </div>
        <div onclick="window.location.href = '{!! url('jz/my') !!}'">
            <span class="iconfont icon-yonghu-tianchong"></span>
            个人中心
        </div>
    </div>
    <div class="qx_bj">
        <div class="qx_box">
            <div class="qx_text">取消确认</div>
            <div class="qx_title">
                是否确认取消订单？
            </div>
            <div class="qx_btn">
                <div class="qx_left_btn" onclick="$('.qx_bj').css('display','none')">暂不取消</div>
                <div class="qx_right_btn" onclick="cancelWork()">确认取消</div>
            </div>
        </div>
    </div>
    <script>
    	var size = 5;
        $('.top_nav > div').click(function(){
            if(this.className.indexOf('top_nav_check') != -1){
                return
            }else{
                $(this).addClass('top_nav_check').siblings().removeClass('top_nav_check')
                $('.center_list').empty();
                mescroll.resetUpScroll(); 
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
        	$.get('/jz/task/ajaxMyTasks',{'status':status, 'page':page.num, 'size': page.size},function(ret,status,xhr){
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
				var tStatus = '其他';
				var wStartDate = '';
				var wAction = '';
				var wActionData = '';
				var tDuration = '';
				var tDistrict = '';
				var tType = '';
				var html = '';
				switch(curPageData[i].work_status)
				{
					case 0: {
						if(curPageData[i].status>3){
							tStatus = '报名失败';
						}else{
							tStatus = '已报名待审核'; wAction = '取消报名'; wActionData = 'c';
						}	
					}
					break;
					case 1: {
						//接单人任务提交不受任务状态所限
						//if(curPageData[i].status===3){
							//tStatus = '报名通过待开始';
						//}else{
							tStatus = '已派单进行中'; wAction = '提交验收'; wActionData = 's'; 
						//}
					}
					break;
					case 2: tStatus = '已提交待验收'; break;
					case 3: tStatus = '已验收待结算'; break;
					case 5: tStatus = '已结算'; break;
					default: tStatus = '其他'; break;
				}
				if(curPageData[i].begin_at||curPageData[i].end_at){
					if(curPageData[i].begin_at){
						var begin = new Date(Date.parse(curPageData[i].begin_at));
						tDuration += dateFormat(df, begin)
					}else{
						tDuration += '/'
					}

					tDuration += '—'

					if(curPageData[i].end_at){
						var end = new Date(Date.parse(curPageData[i].end_at));
						tDuration += dateFormat(df, end)
					}else{
						tDuration += '/'
					}
				}
				if(curPageData[i].work_created_at){
					var start = new Date(Date.parse(curPageData[i].work_created_at));
					wStartDate += dateFormat(df, start)
				}
				if(curPageData[i].province_name||curPageData[i].city_name||curPageData[i].area_name||curPageData[i].address){
					if(curPageData[i].province) tDistrict +=curPageData[i].province_name + ' ';
					if(curPageData[i].city) tDistrict +=curPageData[i].city_name + ' ';
					if(curPageData[i].area) tDistrict +=curPageData[i].area_name + ' ';
					if(curPageData[i].address) tDistrict +=curPageData[i].address;
				}
				if(curPageData[i].type_name||curPageData[i].sub_type_name){
					if(curPageData[i].type_name) tType += curPageData[i].type_name;
					if(curPageData[i].sub_type_name) tType += '/'+ curPageData[i].sub_type_name;
				}
				var html = '';
				html += '<div class="list_box" onclick="taskDetail('+curPageData[i].id+')">';
				html += '<div class="list_box_title">报名时间：'+wStartDate+' <span>'+tStatus+'</span></div>';
				html += '<div class="list_box_top"><div class="img"><img src="/'+curPageData[i].type_icon+'" alt=""></div>';
				html += '<div class="text_box"><div class="title">'+curPageData[i].title+'</div><div class="text">任务类型：'+tType+'</div><div class="money">预算：'+curPageData[i].bounty+' 元</div></div></div>';
				html += '<div class="list_box_bottom"><div>服务时间：'+tDuration+'</div><div>服务地点：'+tDistrict+'</div>';
				if(wAction) html +='<div class="qxbtn" data-id="'+wActionData+'" onclick="takeWorkAction(event,'+curPageData[i].id+')">'+wAction+'</div>';
				html += '</div></div>';

				//html += '<div class="list_box" onclick="taskDetail('+curPageData[i].id+')">'+curPageData[i].title+'<span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></span></div>'
				$('.center_list').append(html);
			}
		}
		function taskDetail(id){
            var url = '/jz/task/'+id;
            window.location.href = url;
        }
        /* $('.qxbtn').click(function(event){
        	$('.qx_bj').css('display','block')
            event.stopPropagation()
        }) */
        function takeWorkAction(event,taskId){
        	event.stopPropagation()
        	var action = $('.qxbtn').attr('data-id');
			console.log(action);
			if(action === 'c'){
				$('.qx_bj').attr('data-id', taskId);
				$('.qx_bj').css('display','block')
			}else if(action === 's'){
				var url = '/jz/task/workDelivery/'+taskId;
	            window.location.href = url;
			}
        }
        function cancelWork(){
			var taskId = $('.qx_bj').attr('data-id');
			if(!taskId) return;
			$.get('/jz/task/ajaxCancelMyWork',{'taskId':taskId},function(ret,status,xhr){
                console.log(ret);
                console.log(status);
				if(status==='success'){
					if(ret){
						$('.qx_bj').css('display','none')
						$('.center_list').empty();
			            mescroll.resetUpScroll();
					}else{
						if(ret.errMsg) popUpMessage(ret.errMsg);
					}
				}else{
					popUpMessage("取消报名失败");
				}
            });
        }
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('mescroll-style','libs/mescroll.css') !!}
    {!! Theme::asset()->container('specific-css')->usepath()->add('task-style','style/task.css') !!}
    {!! Theme::asset()->container('specific-js')->usepath()->add('mescroll-js','libs/mescroll.js') !!}