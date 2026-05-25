var p = location.protocol;
//190121 新環境のものに変更
var url = p + '//settleinfo.ir-service.net'   

window.addEventListener('message', changeFrameSize, false);

function changeFrameSize(e){

	//メッセージ送信元のoriginがTCSのガジェットかどうか確認
	if (e.origin !== url) {
	    //console.log("Fail. Invalid origin. url:" + e.origin)
		return;
	}
	
	//第一引数として渡されたメッセージ取得
    var child_height = e.data;
    
    console.log("Success. height from settleinfo.ir-service.net:" + child_height);
    
    var target = document.getElementById('tc_iframe');
    target.height = child_height;
}
