
$(function() {
	popUpMessage(message);
})

function popUpMessage(message){
	//弹出失败消息
    if (success == 1 || error == 1 || message) {
        $.gritter.add({
        //  title: '消息提示：',
            text: message,//'<div><span class="text-center"><h5>' + message + '</h5></span></div>',
            sticky: false,
            class_name: 'gritter-info gritter-center gritter-light'
        });
    }
}
