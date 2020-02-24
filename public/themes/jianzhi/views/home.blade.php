	<div class="swiper-container">
		<div class="swiper-wrapper">
		@if(!empty($banner))
        	@foreach($banner as $key => $value)
        		<div class="swiper-slide">
        			<img src="{!!  url($value['ad_file'])  !!}" alt="">
        		</div>
        	@endforeach
        @else
            <div class="swiper-slide">
                <img src="/themes/jianzhi/assets/images/1.jpg" alt="">
            </div>
            <div class="swiper-slide">
                <img src="/themes/jianzhi/assets/images/2.jpg" alt="">
            </div>
            <div class="swiper-slide">
                <img src="/themes/jianzhi/assets/images/3.jpg" alt="">
            </div>
        @endif                
        </div>
        <!-- 如果需要分页器 -->
        <div class="swiper-pagination"></div>
        <!-- 如果需要导航按钮 -->
        <!-- <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div> -->
        
        <!-- 如果需要滚动条 -->
        <!-- <div class="swiper-scrollbar"></div> -->
    </div>
    <div class="nav">
        <select name="taskCate" id="taskCate" style="width:35%" onchange="refreshTasks()">
        	<option value="">任务类别</option>
        	@if(!empty($taskType))
        		@foreach($taskType as $key => $value)
        			<option value="{{$value['id']}}">{{$value['name']}}</option>
        		@endforeach
        	@endif
            <!-- <option value="">任务类别</option>
            <option value="">推广服务</option>
            <option value="">设计制作</option>
            <option value="">信息咨询</option> -->
        </select>
        <!-- <span class="iconfont icon-jiantou9">
        </span> --><span class="iconfont">|</span>
        <select name="taskDura" id="taskDura" style="width:35%" onchange="refreshTasks()">
            <option value="">工作周期</option>
            <option value="1">0-1天</option>
            <option value="2">2-7天</option>
            <option value="3">8-15天</option>
            <option value="4">16-30天</option>
            <option value="5">30-N天</option>
        </select>
        <!-- <span class="iconfont icon-jiantou9">
        </span> -->
        <span class="iconfont">|</span>
        
            <select name="province" style="width:35%"  onchange="checkprovince(this)">            
            <option value="" id="province-back">省份</option>
            @if(!empty($province))
                @foreach($province as $v)
                	<option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                @endforeach
            @endif
            </select>
            <!-- <span class="iconfont icon-jiantou9">
            </span> --> <span class="iconfont">|</span>
            <!-- 城市 -->
            <select  id="province_check" name="city" style="width:35%" onchange="refreshTasks()">
                <option value="" id="city-back">城市</option>
            </select>            
            <!-- <span class="iconfont icon-jiantou9">
        	</span> --> <span class="iconfont">|</span>
            <!-- <select name="city" style="width:40%"data-city="福州市"></select> -->
    	
        <select name="status" id="status" onchange="refreshTasks()">
            <option value="">状态</option>
            <option value="3">招募中</option>
            <option value="4">进行中</option>
            <option value="7">已结算</option>
        </select>
        <!-- <span class="iconfont icon-jiantou9">
        </span> -->
    </div>
    <div class="center">
        <div class="center_top">
            任务大厅
        </div>
        <div class="center_box mescroll" id="mescroll">
            <div class="center_kong">
                <img src="/themes/jianzhi/assets/images/developmenting.png" alt="">
            </div>
            <div class="center_list">
                <!-- 
                <div class="list_box" onclick="details()">
                    <div class="list_box_top">
                        <div class="img">
                            <img src="/themes/jianzhi/assets/images/xxjs.png" alt="">
                        </div>
                        <div class="text_box">
                            <div class="title">福建省福州市-平台开发</div>
                            <div class="text">任务类型：信息技术服务</div>
                            <div class="money">预算：32750<span>人数1/1</span></div>
                            <div class="state">进行中</div>
                        </div>
                    </div>
                    <div class="list_box_bottom">
                        <div>服务时间：2019年08月01日-2019年08月31日</div>
                        <div>服务地点：福建省福州市鼓楼区</div>
                    </div>
                </div>
                 -->
            </div>
            
        </div>
        <div style="padding-left: 5px;font-size: 10px;line-height: 12px; display: block;position: absolute;bottom: 1rem;background: #fff;">
				 <span>
				 	Copyright 2020 广州网金创纪信息技术有限公司 All rights reserved <a style="color: blue;text-decoration: underline;" href="http://www.beian.miit.gov.cn"> 粤ICP备19125254号-1</a>
				</span>
			</div>
    </div>
    
    <div class="foot">
        <div class="foot_check">
            <span class="iconfont icon-fangzi"></span>
            任务大厅
        </div>
        <div onclick="window.location.href = '{!! url('jz/task') !!}'">
            <span class="iconfont icon-thin-_notebook_p"></span>
            我的任务
        </div>
        <div onclick="window.location.href = '{!! url('jz/my') !!}'">
            <span class="iconfont icon-yonghu-tianchong"></span>
            个人中心
        </div>
    </div>
    <script>
        var size = 5;
    	$(function(){
    		//loadMyTasks($('.top_nav_check').attr('data-id'), 1);
       	});
        var mySwiper = new Swiper ('.swiper-container', {
            loop: true, // 循环模式选项
            autoplay: true,
            
            // 如果需要分页器
            pagination: {
            el: '.swiper-pagination',
            },
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
        function loadTasks(page, successCallback, errorCallback){
            var url = '/jz/task/ajaxTasks';
			if(page&&page.num){
				url += '?page='+page.num;
				if(page.size) url += '&size='+page.size;
			}
			var data = {'_token': '{{ csrf_token() }}'};
			if($("#taskCate").val()) data.type = $("#taskCate").val();
			if($("#taskDura").val()) data.duration = $("#taskDura").val();
			if($("select[name='province']").val()) data.province = $("select[name='province']").val();
			if($("select[name='city']").val()) data.city = $("select[name='city']").val();
			if($("#status").val()) data.status = $("#status").val();
			console.log(data);
        	$.post(url,data,function(ret,status,xhr){
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
        function refreshTasks(){
        	$('.center_list').empty();	
            mescroll.resetUpScroll();
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
            loadTasks(page, function(curPageData, totalSize){
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
				var tDuration = '';
				var tDistrict = '';
				var tType = '';
				var html = '';
				if(curPageData[i].status){
					switch(curPageData[i].status)
					{
						case 3: tStatus = '招募中'; break;
						case 4: tStatus = '进行中'; break;
						case 7: tStatus = '待验收'; break;
						case 8: tStatus = '已验收'; break;
						case 9: tStatus = '已结算'; break;
					}
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
				html += '<div class="list_box" onclick="taskDetail('+curPageData[i].id+')"><div class="list_box_top">';
				html += '<div class="img"><img src="/'+curPageData[i].type_icon+'" alt=""></div>';
				html += '<div class="text_box"><div class="title">'+curPageData[i].title+'</div>';
				html += '<div class="text">任务类型：'+tType+'</div>';
				html += '<div class="money">预算：'+curPageData[i].bounty+' 元<span>人数：'+curPageData[i].delivery_count+'/'+curPageData[i].worker_num+'</span></div>';
				html += '<div class="state">'+tStatus+'</div></div></div>';
				html += '<div class="list_box_bottom"><div>服务时间：'+tDuration+'</div><div>服务地点：'+tDistrict+'</div></div></div>';
				$('.center_list').append(html);
			}
		}
        
        function taskDetail(id){
            var url = '/jz/task/'+id;
            window.location.href = url;
        }
        /**
         * 省级切换
         * @param obj
         */
        function checkprovince(obj){
            var id = obj.value;
            refreshTasks();
            $('#province-back').val(0);
            $.get('/jz/task/ajaxcity',{'id':id},function(data){
                var html = '<option value=\"'+data.id+'\">城市</option>';
                var area = '';
                for(var i in data.province){
                    html+= "<option value=\""+data.province[i].id+"\">"+data.province[i].name+"<\/option>";
                    area+= "<tr id='area-delete-"+data.province[i].id+"'  area_id=\""+data.province[i].id+"\"><td><input class=\"area-index\" type=\"text\" name=\"displayorder["+data.province[i].id+"]\" value=\""+data.province[i].displayorder+"\" area_id=\""+data.province[i].id+"\" onchange=\"area_change($(this))\"><\/td><td class=\"text-left\"><input type=\"text\" name=\"name["+data.province[i].id+"]\"  value=\""+ data.province[i].name+"\" area_id=\""+data.province[i].id+"\" onchange=\"area_change($(this))\"><\/td><td width=\"40%\"><span class=\"btn btn-sm btn-primary\" area_id=\""+data.province[i].id+"\" onclick=\"area_delete($(this))\">删除<\/span><\/td><\/tr>";
                }
                if(data.id!=0){
                    $('#province_check').html(html);
                }else{
                    html = '<option value=\"\">城市</option>';
                    $('#province_check').html(html);
                }
                //替换数据列表
                $('#area_data_change').html(area);
                $('#area-change').attr('value','');
            });
        }
        /**
         * 市级切换
         * @param obj
         */
        /* function checkcity(obj){
            var id = obj.value;
            $('#city-back').attr('value',id);
            $.get('/manage/ajaxarea',{'id':id},function(data){
                var html = '';
                var area = '';
                for(var i in data){
                    html += "<option value=\""+data[i].id+"\">"+data[i].name+"<\/option>";
                    area+= "<tr id='area-delete-"+data[i].id+"' area_id=\""+data[i].id+"\"><td><input class=\"area-index\" type=\"text\" name=\"displayorder["+data[i].id+"]\" value=\""+data[i].displayorder+"\" area_id=\""+data[i].id+"\" onchange=\"area_change($(this))\"><\/td><td class=\"text-left\"><input type=\"text\" name=\"name["+data[i].id+"]\" value=\""+ data[i].name+"\" area_id=\""+data[i].id+"\" onchange=\"area_change($(this))\"><\/td><td width=\"40%\"><span class=\"btn btn-sm btn-primary\" area_id=\""+data[i].id+"\" onclick=\"area_delete($(this))\">删除<\/span><\/td><\/tr>";
                }
                $('#area_data_change').html(area);
                $('#area-change').attr('value','');
            });
        } */
    </script>
    {!! Theme::asset()->container('specific-css')->usepath()->add('swiper-style','libs/swiper.css') !!}
    {!! Theme::asset()->container('specific-css')->usepath()->add('mescroll-style','libs/mescroll.css') !!}
    {!! Theme::asset()->container('specific-css')->usepath()->add('index-style','style/index.css') !!}
    {!! Theme::asset()->container('specific-js')->usepath()->add('distpicker-js','libs/distpicker.js') !!}
    {!! Theme::asset()->container('specific-js')->usepath()->add('swiper-js','libs/swiper.js') !!}
    {!! Theme::asset()->container('specific-js')->usepath()->add('mescroll-js','libs/mescroll.js') !!}