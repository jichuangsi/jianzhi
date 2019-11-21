<?php

/* Route::group(['prefix' => 'test'], function() {
	Route::get('/', function() {
		dd('This is the Test module index page.');
	});
}); */

Route::group(['prefix' => 'jz'], function () {
    //��½ע��·��
    Route::post('login', 'Auth\jzAuthController@postLogin');
    Route::post('wxlogin', 'Auth\jzAuthController@postWxLogin');    
    Route::post('ajaxCheckOpenid', 'Auth\jzAuthController@ajaxCheckOpenid');
    Route::post('ajaxWxAuth', 'Auth\jzAuthController@ajaxWxAuth');
    Route::post('ajaxSendCode', 'Auth\jzAuthController@ajaxSendCode');    
    Route::get('logout', 'Auth\jzAuthController@getLogout');
    Route::get('register', 'Auth\jzAuthController@getRegister');
    Route::post('register', 'Auth\jzAuthController@postRegister');
    Route::get('agreement', 'Auth\jzAuthController@getAgreement');
    Route::get('notice', 'Auth\jzAuthController@getNotice');
    Route::get('partner', 'Auth\jzAuthController@getPartner');
    Route::get('taskContract', 'Auth\jzAuthController@getTaskContract');
    Route::get('serviceContract', 'Auth\jzAuthController@getServiceContract');
    Route::get('dispatchContract', 'Auth\jzAuthController@getDispatchContract');
    
    //��ҳ��·��
    Route::get('home', 'jzHomeController@home');
    Route::get('task', 'jzHomeController@task');
    Route::get('my', 'jzHomeController@my');
    
    //�û�����·��
    Route::get('auth', 'jzUserCenterController@auth');
    Route::get('help', 'jzUserCenterController@help');
    Route::get('proposal', 'jzUserCenterController@proposal');
    Route::get('contact', 'jzUserCenterController@contact');
    Route::get('info', 'jzUserCenterController@info');
    Route::get('skill', 'jzUserCenterController@skill');
    Route::get('comment', 'jzUserCenterController@comment');
    
    //�������·��
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
    
    //�û���Ϣ���·��
    Route::post('/user/infoUpdate','jzUserCenterController@infoUpdate');
    Route::post('/user/einfoUpdate','jzUserCenterController@einfoUpdate');
    //����ʵ����֤·��
    Route::post('/user/realnameAuth', 'jzUserCenterController@createRealnameAuth');
    Route::get('/user/reAuth', 'jzUserCenterController@reAuth');
    //���˼���·��
    Route::post('/user/ajaxSaveSkills','jzUserCenterController@ajaxSaveSkills');
    //��������·��
    Route::post('/user/createFeedback','jzUserCenterController@createFeedback');
    //��ҵʵ����֤·��
    Route::post('/user/enterpriseAuth', 'jzUserCenterController@createEnterpriseAuth');
    
    Route::post('/user/ajaxSendCode','jzUserCenterController@ajaxSendCode');
    
});

