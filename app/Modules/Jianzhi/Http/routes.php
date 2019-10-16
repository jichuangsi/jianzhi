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
    Route::get('/task/agreement', 'jzTaskController@getTaskAgreement');
    Route::post('/task/ajaxCreateNewWork','jzTaskController@ajaxCreateNewWork');
    Route::get('/task/ajaxMyTasks','jzTaskController@ajaxMyTasks'); 
    Route::get('/task/ajaxCancelMyWork','jzTaskController@ajaxCancelMyWork'); 
    Route::post('/task/ajaxAssignTask','jzTaskController@ajaxAssignTask');
    
});

