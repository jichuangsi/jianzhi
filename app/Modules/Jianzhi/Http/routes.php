<?php

/* Route::group(['prefix' => 'test'], function() {
	Route::get('/', function() {
		dd('This is the Test module index page.');
	});
}); */

Route::group(['prefix' => 'jz'], function () {
    //登陆注册路由
    Route::post('login', 'Auth\jzAuthController@postLogin');
    Route::get('logout', 'Auth\jzAuthController@getLogout');
    Route::get('register', 'Auth\jzAuthController@getRegister');
    Route::post('register', 'Auth\jzAuthController@postRegister');
    Route::get('agreement', 'Auth\jzAuthController@getAgreement');
    
    //首页面路由
    Route::get('home', 'jzHomeController@home');
    Route::get('task', 'jzHomeController@task');
    Route::get('my', 'jzHomeController@my');
    
    //用户中心路由
    Route::get('auth', 'jzUserCenterController@auth');
    Route::get('help', 'jzUserCenterController@help');
    Route::get('proposal', 'jzUserCenterController@proposal');
    Route::get('contact', 'jzUserCenterController@contact');
    Route::get('info', 'jzUserCenterController@info');
    Route::get('skill', 'jzUserCenterController@skill');
    Route::get('comment', 'jzUserCenterController@comment');
    
    //任务相关路由
    Route::post('/task/createTask','jzTaskController@createNewTask');
    Route::get('/task/ajaxcity','jzTaskController@ajaxcity');
    Route::get('/task/ajaxarea','jzTaskController@ajaxarea');
    Route::get('/task/ajaxSubTaskType','jzTaskController@ajaxSubTaskType');
    Route::post('/task/fileUpload','jzTaskController@fileUpload');
    Route::get('/task/fileDelet','jzTaskController@fileDelet');
    Route::get('/task/ajaxEmyTasks','jzTaskController@ajaxEmyTasks');   
    Route::get('/task/{id}','jzTaskController@getTaskdetail')->where('id', '[0-9]+');
    Route::get('/task/fileDownload/{id}','jzTaskController@fileDownload')->where('id', '[0-9]+');
    Route::post('/task/ajaxTasks','jzTaskController@ajaxTasks');  
    Route::get('/task/contract', 'jzTaskController@getTaskContract');
    Route::post('/task/ajaxCreateNewWork','jzTaskController@ajaxCreateNewWork');
    Route::get('/task/ajaxMyTasks','jzTaskController@ajaxMyTasks'); 
    Route::get('/task/ajaxCancelMyWork','jzTaskController@ajaxCancelMyWork'); 
    Route::post('/task/ajaxAssignTask','jzTaskController@ajaxAssignTask');
    Route::get('/task/workDelivery/{id}','jzTaskController@workDelivery')->where('id', '[0-9]+');
    Route::post('/task/createDeliver','jzTaskController@createDeliver');    
    Route::get('/task/approveDelivery/{id}','jzTaskController@approveDelivery')->where('id', '[0-9]+');
    Route::post('/task/createApproval','jzTaskController@createApproval');
    Route::post('/task/ajaxSettleTask','jzTaskController@ajaxSettleTask');
    
    //用户信息相关路由
    Route::post('/user/infoUpdate','jzUserCenterController@infoUpdate');
    Route::post('/user/einfoUpdate','jzUserCenterController@einfoUpdate');
    //个人实名认证路由
    Route::post('/user/realnameAuth', 'jzUserCenterController@createRealnameAuth');
    Route::get('/user/reAuth', 'jzUserCenterController@reAuth');
    //个人技能路由
    Route::post('/user/ajaxSaveSkills','jzUserCenterController@ajaxSaveSkills');
    //反馈建议路由
    Route::post('/user/createFeedback','jzUserCenterController@createFeedback');
    //企业实名认证路由
    Route::post('/user/enterpriseAuth', 'jzUserCenterController@createEnterpriseAuth');
    
});

