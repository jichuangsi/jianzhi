
<div class="widget-header mg-bottom20 mg-top12 widget-well">
    <div class="widget-toolbar no-border pull-left no-padding">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="/manage/config/site">站点配置</a>
            </li>
{{--
            <li>
                <a href="/manage/config/basic">基本配置</a>
            </li>--}}

            <li>
                <a href="/manage/config/seo">SEO配置</a>
            </li>
            <li>
                <a href="/manage/config/email">邮箱配置</a>
            </li>
        </ul>
    </div>
</div><!-- <div class="dataTables_borderWrap"> -->

                <!-- PAGE CONTENT BEGINS -->
<form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="/manage/config/site">
    {!!  csrf_field() !!}
    <!-- #section:elements.form -->
    <div class="g-backrealdetails clearfix bor-border interface">

    <div class="space-8 col-xs-12"></div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1">网站名称</label>

        <div class="col-sm-4">
            <input type="text" id="form-field-1" placeholder="" class="col-xs-10 col-sm-12" name="web_site"
                   @if(!empty($site['site_name']))value="{{$site['site_name']}}" @endif/>
        </div>
        <div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 网站名称并非网站标题,仅在页底显示及发送邮件等处使用</div>
    </div>

        <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-2">网站URL</label>

        <div class="col-sm-4">
            <input type="text" id="form-field-2" placeholder="" class="col-xs-10 col-sm-12" name="web_url"
                   @if(!empty($site['site_url']))value="{{$site['site_url']}}" @endif/>
        </div>
        <div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 填写您站点的完整域名。例如: http://www.gcharms.com，不要以斜杠结尾 (“/”)</div>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1">网站logo1</label>

        <div class="col-sm-4">
            <div class="memberdiv pull-left">
                <div class="position-relative">

                    <div id="imgdiv1">
                        @if($site['site_logo_1'])
                        <img id="imgShow1" width="120" height="120" src="{{url($site['site_logo_1'])}}" />
                        @endif
                    </div>

                    <a class="filea btn btn-sm btn-primary btn-block" href="javascript:void(0);">
                        上传logo
                        <input class="btn-file" type="file" id="up_img1" name="web_logo_1" />
                    </a>
                    {{--<input multiple="" type="file" id="id-input-file-3" name="web_logo_1"/>--}}
                    {{--@if($site['site_logo_1'])--}}
                        {{--<img src="{!! url($site['site_logo_1']) !!}" width="240" height="40">--}}
                    {{--@endif--}}
                </div>
            </div>
        </div>
        <div class="col-sm-5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> LOGO1位于网站前台首栏以及帐号激活邮件内,建议图片尺寸240px*40px</div>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1">网站logo2</label>

        <div class="col-sm-4">
            <div class="memberdiv pull-left">
                <div class="position-relative">

                        <div id="imgdiv2">
                            @if($site['site_logo_2'])
                            <img id="imgShow2" width="120" height="120" src="{{url($site['site_logo_2'])}}" />
                            @endif
                        </div>

                    <a class="filea btn btn-sm btn-primary btn-block" href="javascript:void(0);">
                        上传logo
                        <input class="btn-file" type="file" id="up_img2" name="web_logo_2" />
                    </a>
                    {{--<input multiple="" type="file" id="id-input-file-3img" name="web_logo_2"/>--}}
                    {{--@if($site['site_logo_2'])--}}
                        {{--<img src="{!! url($site['site_logo_2']) !!}" width="170" height="25" class="" style="background-color: #000;opacity: 0.2;">--}}
                    {{--@endif--}}
                </div></div>
        </div>
        <div class="col-sm-5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> LOGO2位于用户中心首栏以及后台首栏,建议上传无底色图片且尺寸为170px*25px </div>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1">公司名称</label>

        <div class="col-sm-4">
            <input type="text" id="form-field-1" placeholder="" class="col-xs-10 col-sm-12" name="company_name"
                   @if(!empty($site['company_name']))value="{{$site['company_name']}}"@endif/>
        </div>
        <div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 将显示在页面底部的联系方式处</div>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1">公司地址</label>

        <div class="col-sm-4">
            <input type="text" id="form-field-1" placeholder="" class="col-xs-10 col-sm-12" name="company_address"
                   @if(!empty($site['company_address']))value="{{$site['company_address']}}" @endif/>
        </div>
        <div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 将显示在页面底部的联系方式处</div>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1">联系电话</label>

        <div class="col-sm-4">
            <input type="text" id="form-field-1" placeholder="" class="col-xs-10 col-sm-12" name="phone"
                   @if(!empty($site['phone']))value="{{$site['phone']}}"@endif/>
        </div>
        <div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 将显示在页面底部的联系方式处</div>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1">联系邮箱</label>

        <div class="col-sm-4">
            <input type="text" id="form-field-1" placeholder="" class="col-xs-10 col-sm-12" name="Email"
                   @if(!empty($site['Email']))value="{{$site['Email']}}"@endif/>
        </div>
        <div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 将显示在页面底部的联系邮箱处</div>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1">网站备案号</label>

        <div class="col-sm-4">
            <input type="text" id="form-field-1" placeholder="" class="col-xs-10 col-sm-12" name="site_record_code"
                   @if(!empty($site['record_number']))value="{{$site['record_number']}}" @endif/>
        </div>
        <div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 如果网站已备案，输入您的备案信息，页面底部将显示 ICP备案信息</div>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1">页脚版权信息</label>

        <div class="col-sm-4">
            <input type="text" id="form-field-1" placeholder="" class="col-xs-10 col-sm-12" name="footer_copyright"
                   @if(!empty($site['copyright']))value="{{$site['copyright']}}"@endif/>
        </div>
        <div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 将显示在页面底部的版权信息处</div>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 第三方统计代码： </label>

        <div class="col-sm-4">
            <textarea class="col-xs-10 col-sm-12" name="third_party_code">@if(!empty($site['statistic_code'])){{$site['statistic_code']}}@endif</textarea>
        </div>
        <div class="col-sm-5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 填写第三方流量统计JS代码</div>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 关注链接 </label>

        <div class="col-sm-9">
            <table class="table">
               {{-- <thead>
                <tr>
                    <th>项目 </th>
                    <th>地址</th>
                    <th>是否开启</th>
                </tr>
                </thead>--}}
                <tbody>
                <tr>
                    <td>
                        新浪微博
                    </td>
                    <td>
                        <div class="memberdiv pull-left">
                            <input type="text" name="sina_url" @if($site['sina']['sina_url'])value="{{$site['sina']['sina_url']}}" @endif placeholder="请输入完整链接（以http或https开头）">
                        </div>
                        {{--<div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 请输入完整链接（以http或https开头）</div>--}}
                        {{--<label><input class="ace" type="radio" name="sina_switch" value="2" @if($site['sina']['sina_switch'] && $site['sina']['sina_switch'] == 2)checked="checked"@endif><span class="lbl"> 关闭 </span></label>
                        <label><input class="ace" type="radio" name="sina_switch" value="1" @if($site['sina']['sina_switch'] && $site['sina']['sina_switch'] == 1)checked="checked"@endif><span class="lbl"> 开启</span></label>
--}}
                    </td>
                    <td>
                        <div class=" h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 请输入完整链接（以http或https开头）</div>
                    </td>
                    <td>
                        <label><input class="ace" type="radio" name="sina_switch" value="2" @if($site['sina']['sina_switch'] && $site['sina']['sina_switch'] == 2)checked="checked"@endif><span class="lbl"> 关闭 </span></label>
                        <label><input class="ace" type="radio" name="sina_switch" value="1" @if($site['sina']['sina_switch'] && $site['sina']['sina_switch'] == 1)checked="checked"@endif><span class="lbl"> 开启</span></label>
                    </td>
                    {{--<td>
                        腾讯微博
                    </td>
                    <td>
                        <div class="memberdiv pull-left">
                            <input type="text" name="tencent_url" @if($site['tencent']['tencent_url'])value="{{$site['tencent']['tencent_url']}}" @endif placeholder="请输入完整链接（以http或https开头）">
                        </div>
                        <div>
                            <label><input class="ace" type="radio" name="tencent_switch" value="2" @if($site['tencent']['tencent_switch'] && $site['tencent']['tencent_switch'] == 2)checked="checked"@endif><span class="lbl"> 关闭 </span></label>
                            <label><input class="ace" type="radio" name="tencent_switch" value="1" @if($site['tencent']['tencent_switch'] && $site['tencent']['tencent_switch'] == 1)checked="checked"@endif><span class="lbl"> 开启</span></label>
                        </div>
                        --}}{{--<div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 请输入完整链接（以http或https开头）</div>--}}{{--
                    </td>--}}
                    {{--<td>
                        <label><input class="ace" type="radio" name="tencent_switch" value="2" @if($site['tencent']['tencent_switch'] && $site['tencent']['tencent_switch'] == 2)checked="checked"@endif><span class="lbl"> 关闭 </span></label>
                        <label><input class="ace" type="radio" name="tencent_switch" value="1" @if($site['tencent']['tencent_switch'] && $site['tencent']['tencent_switch'] == 1)checked="checked"@endif><span class="lbl"> 开启</span></label>
                    </td>--}}
                </tr>
                <tr>
                    <td>
                        腾讯微博
                    </td>
                    <td>
                        <div class="memberdiv pull-left">
                            <input type="text" name="tencent_url" @if($site['tencent']['tencent_url'])value="{{$site['tencent']['tencent_url']}}" @endif placeholder="请输入完整链接（以http或https开头）">
                        </div>
                        {{--<div class="col-sm-5 h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 请输入完整链接（以http或https开头）</div>--}}
                    </td>
                    <td>
                        <div class="h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 请输入完整链接（以http或https开头）</div>
                    </td>
                    <td>
                        <label><input class="ace" type="radio" name="tencent_switch" value="2" @if($site['tencent']['tencent_switch'] && $site['tencent']['tencent_switch'] == 2)checked="checked"@endif><span class="lbl"> 关闭 </span></label>
                        <label><input class="ace" type="radio" name="tencent_switch" value="1" @if($site['tencent']['tencent_switch'] && $site['tencent']['tencent_switch'] == 1)checked="checked"@endif><span class="lbl"> 开启</span></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        微信
                    </td>
                    <td>
                        <div class="memberdiv pull-left">
                            <div class="position-relative">
                                <input multiple="" type="file" id="id-input-file-4img" name="wechat_pic"/>
                                    @if($site['wechat']['wechat_pic'])
                                    <img src="{!! url($site['wechat']['wechat_pic']) !!}"  width="100px" height="100px">
                                    @endif
                            </div>
                        </div>
                        {{--<div class=" h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 图片大小100px*100px</div>--}}
                    </td>
                    <td>
                        <div class=" h5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> 图片大小100px*100px</div>
                    </td>
                    <td>
                        <label><input class="ace" type="radio" name="wechat_switch" value="2" @if($site['wechat']['wechat_switch'] && $site['wechat']['wechat_switch'] == 2)checked="checked"@endif><span class="lbl"> 关闭 </span></label>
                        <label><input class="ace" type="radio" name="wechat_switch" value="1" @if($site['wechat']['wechat_switch'] && $site['wechat']['wechat_switch'] == 1)checked="checked"@endif><span class="lbl"> 开启</span></label>
                    </td>
                    <td>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> CSS自适应 </label>

        <div class="col-sm-4">
            <label><input class="ace" type="radio" name="css_adaptive" value="2" @if($basic['css_adaptive'] && $basic['css_adaptive'] == 2)checked="checked"@endif><span class="lbl"> 关闭 </span></label>
            <label><input class="ace" type="radio" name="css_adaptive" value="1" @if($basic['css_adaptive'] && $basic['css_adaptive'] == 1)checked="checked"@endif><span class="lbl"> 开启</span></label>
        </div>
    </div>
    {{--<div class="form-group basic-form-bottom basic-form-bottom-im">--}}
    <div class="form-group interface-bottom col-xs-12 basic-form-bottom-im">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 开启IM</label>

        <div class="col-sm-4">
            <label class="im-close"><input class="ace" type="radio" name="open_IM" value="2" @if($basic['open_IM'] && $basic['open_IM'] == 2)checked="checked"@endif><span class="lbl"> 关闭 </span></label>
            <label class="im-open"><input class="ace" type="radio" name="open_IM" value="1" @if($basic['open_IM'] && $basic['open_IM'] == 1)checked="checked"@endif><span class="lbl"> 开启</span></label>
            <div class="im-inputxt">
                <div class="block">
                    　 IM服务器IP：<input type="text" name="IM_ip" class=" " @if(!empty($basic['IM_config']['IM_ip']))value="{!! $basic['IM_config']['IM_ip'] !!}"@endif >
                </div>
                <div class="space-4"></div>
                <div class="block">
                     IM服务器端口：<input type="text" name="IM_port" class=" " @if(!empty($basic['IM_config']['IM_port'])) value="{!! $basic['IM_config']['IM_port'] !!}" @endif>
                </div>
                <div class="space-4"></div>
            </div>
        </div>
        <label class="col-sm-5 cor-gray87"><i class="fa fa-exclamation-circle cor-orange text-size18"></i> (此功能需单独购买IM工具，否则无效 )</label>
    </div>
    <div class="form-group interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 客服QQ</label>

        <div class="col-sm-4">
            <input type="text" id="form-field-1" placeholder="" class="col-xs-10 col-sm-12" name="customer_service_qq"
                   @if(!empty($basic['qq'])) value="{{$basic['qq']}}" @endif/>
        </div>
    </div>

    <div class="interface-bottom col-xs-12">
        <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 网站开关 </label>

        <div class="col-sm-4">
            <label><input class="ace" type="radio" name="site_switch" value="2" @if($site['site_close'] && $site['site_close'] == 2)checked="checked"@endif><span class="lbl"> 关闭 </span></label>
            <label><input class="ace" type="radio" name="site_switch" value="1" @if($site['site_close'] && $site['site_close'] == 1)checked="checked"@endif><span class="lbl"> 开启</span></label>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="clearfix row bg-backf5 padding20 mg-margin12">
            <div class="col-xs-12">
                <div class="col-sm-1 text-right"></div>
                <div class="col-sm-10"><button type="submit" class="btn btn-sm btn-primary">提交</button></div>
            </div>
        </div>
    </div>
    {{--<div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">
            <div class="row">
                <button class="btn btn-info btn-sm" type="submit">
                    提交
                </button>
            </div>

        </div>
    </div>--}}

    </div>
</form>

                {{--<div id="modal-form" class="modal" tabindex="-1">--}}
                    {{--<div class="modal-dialog">--}}
                        {{--<div class="modal-content">--}}
                            {{--<div class="modal-header">--}}
                                {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                                {{--<h4 class="blue bigger">Please fill the following form fields</h4>--}}
                            {{--</div>--}}

                            {{--<div class="modal-body">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-xs-12 col-sm-5">--}}
                                        {{--<div class="space"></div>--}}

                                        {{--<input type="file" />--}}
                                    {{--</div>--}}

                                    {{--<div class="col-xs-12 col-sm-7">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="form-field-select-3">Location</label>--}}

                                            {{--<div>--}}
                                                {{--<select class="chosen-select" data-placeholder="Choose a Country...">--}}
                                                    {{--<option value="">&nbsp;</option>--}}
                                                    {{--<option value="AL">Alabama</option>--}}
                                                    {{--<option value="AK">Alaska</option>--}}
                                                    {{--<option value="AZ">Arizona</option>--}}
                                                    {{--<option value="AR">Arkansas</option>--}}
                                                    {{--<option value="CA">California</option>--}}
                                                    {{--<option value="CO">Colorado</option>--}}
                                                    {{--<option value="CT">Connecticut</option>--}}
                                                    {{--<option value="DE">Delaware</option>--}}
                                                    {{--<option value="FL">Florida</option>--}}
                                                    {{--<option value="GA">Georgia</option>--}}
                                                    {{--<option value="HI">Hawaii</option>--}}
                                                    {{--<option value="ID">Idaho</option>--}}
                                                    {{--<option value="IL">Illinois</option>--}}
                                                    {{--<option value="IN">Indiana</option>--}}
                                                    {{--<option value="IA">Iowa</option>--}}
                                                    {{--<option value="KS">Kansas</option>--}}
                                                    {{--<option value="KY">Kentucky</option>--}}
                                                    {{--<option value="LA">Louisiana</option>--}}
                                                    {{--<option value="ME">Maine</option>--}}
                                                    {{--<option value="MD">Maryland</option>--}}
                                                    {{--<option value="MA">Massachusetts</option>--}}
                                                    {{--<option value="MI">Michigan</option>--}}
                                                    {{--<option value="MN">Minnesota</option>--}}
                                                    {{--<option value="MS">Mississippi</option>--}}
                                                    {{--<option value="MO">Missouri</option>--}}
                                                    {{--<option value="MT">Montana</option>--}}
                                                    {{--<option value="NE">Nebraska</option>--}}
                                                    {{--<option value="NV">Nevada</option>--}}
                                                    {{--<option value="NH">New Hampshire</option>--}}
                                                    {{--<option value="NJ">New Jersey</option>--}}
                                                    {{--<option value="NM">New Mexico</option>--}}
                                                    {{--<option value="NY">New York</option>--}}
                                                    {{--<option value="NC">North Carolina</option>--}}
                                                    {{--<option value="ND">North Dakota</option>--}}
                                                    {{--<option value="OH">Ohio</option>--}}
                                                    {{--<option value="OK">Oklahoma</option>--}}
                                                    {{--<option value="OR">Oregon</option>--}}
                                                    {{--<option value="PA">Pennsylvania</option>--}}
                                                    {{--<option value="RI">Rhode Island</option>--}}
                                                    {{--<option value="SC">South Carolina</option>--}}
                                                    {{--<option value="SD">South Dakota</option>--}}
                                                    {{--<option value="TN">Tennessee</option>--}}
                                                    {{--<option value="TX">Texas</option>--}}
                                                    {{--<option value="UT">Utah</option>--}}
                                                    {{--<option value="VT">Vermont</option>--}}
                                                    {{--<option value="VA">Virginia</option>--}}
                                                    {{--<option value="WA">Washington</option>--}}
                                                    {{--<option value="WV">West Virginia</option>--}}
                                                    {{--<option value="WI">Wisconsin</option>--}}
                                                    {{--<option value="WY">Wyoming</option>--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="space-4"></div>--}}

                                        {{--<div class="form-group">--}}
                                            {{--<label for="form-field-username">Username</label>--}}

                                            {{--<div>--}}
                                                {{--<input class="input-large" type="text" id="form-field-username" placeholder="Username" value="alexdoe" />--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="space-4"></div>--}}

                                        {{--<div class="form-group">--}}
                                            {{--<label for="form-field-first">Name</label>--}}

                                            {{--<div>--}}
                                                {{--<input class="input-medium" type="text" id="form-field-first" placeholder="First Name" value="Alex" />--}}
                                                {{--<input class="input-medium" type="text" id="form-field-last" placeholder="Last Name" value="Doe" />--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="modal-footer">--}}
                                {{--<button class="btn btn-sm" data-dismiss="modal">--}}
                                    {{--<i class="ace-icon fa fa-times"></i>--}}
                                    {{--Cancel--}}
                                {{--</button>--}}

                                {{--<button class="btn btn-sm btn-primary">--}}
                                    {{--<i class="ace-icon fa fa-check"></i>--}}
                                    {{--Save--}}
                                {{--</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div><!-- PAGE CONTENT ENDS -->--}}


{!! Theme::asset()->container('specific-js')->usepath()->add('datepicker', 'plugins/ace/css/bootstrap-datetimepicker/datepicker.css') !!}
{!! Theme::asset()->container('specific-js')->usepath()->add('jquery.webui-popover', '/plugins/jquery/css/jquery.webui-popover.min.css') !!}
{!! Theme::asset()->container('custom-css')->usepath()->add('backstage', 'css/backstage/backstage.css') !!}

{{--上传图片--}}
{!! Theme::asset()->container('specific-js')->usepath()->add('custom', 'plugins/ace/js/jquery-ui.custom.min.js') !!}
{!! Theme::asset()->container('specific-js')->usepath()->add('touch-punch', 'plugins/ace/js/jquery.ui.touch-punch.min.js') !!}
{!! Theme::asset()->container('specific-js')->usepath()->add('chosen', 'plugins/ace/js/chosen.jquery.min.js') !!}
{!! Theme::asset()->container('specific-js')->usepath()->add('autosize', 'plugins/ace/js/jquery.autosize.min.js') !!}
{!! Theme::asset()->container('specific-js')->usepath()->add('inputlimiter', 'plugins/ace/js/jquery.inputlimiter.1.3.1.min.js') !!}
{!! Theme::asset()->container('specific-js')->usepath()->add('maskedinput', 'plugins/ace/js/jquery.maskedinput.min.js') !!}

{!! Theme::asset()->container('custom-js')->usepath()->add('dataTab', 'plugins/ace/js/dataTab.js') !!}
{!! Theme::asset()->container('custom-js')->usepath()->add('jquery_dataTables', 'plugins/ace/js/jquery.dataTables.bootstrap.js') !!}

{!! Theme::asset()->container('custom-js')->usepath()->add('configsite', 'js/doc/configsite.js') !!}
{!! Theme::asset()->container('custom-js')->usepath()->add('uploadimg', 'js/doc/uploadimg.js') !!}
{!! Theme::widget('uploadimg')->render() !!}