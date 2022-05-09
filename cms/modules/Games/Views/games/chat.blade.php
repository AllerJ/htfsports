@extends('cms::layouts.dashboard')

@section('pageTitle') TrashTalk @stop

@section('content')
<style type="text/css">
	#chatwindow {
		height: 75vh;
	}
</style>

    <div class="col-12">
	    <h4> {!! date_format($game->game_at, 'm/d/Y') !!} - {!! $game->venue->name !!}</h4>
            <textarea id="chatwindow" class="form-control" readonly></textarea><br>
            <input id="chatnick" type="hidden" placeholder="Commissioner" value="Commissioner" disabled="">
			<div class="input-group mb-3">
			  <input type="text" id="chatmsg" class="form-control" onkeyup="keyup(event.keyCode);" placeholder="Message" aria-label="Message" aria-describedby="basic-addon2">
			  <div class="input-group-append">
			    <input type="button" class="btn btn-outline-success" onclick="submit_msg();"  type="button" value="Send">
			  </div>
			</div>


    </div>


@endsection

@section('javascript')

    @parent
	<script type="text/javascript">
	var nick_maxlength=12;
	var http_request=false;
	var http_request2=false;
	var intUpdate;
	var game_id = {!! $game->id !!};
	
	/* http_request for writing */
	function ajax_request(url){http_request=false;if(window.XMLHttpRequest){http_request=new XMLHttpRequest();if(http_request.overrideMimeType){http_request.overrideMimeType('text/xml');}}else if(window.ActiveXObject){try{http_request=new ActiveXObject("Msxml2.XMLHTTP");}catch(e){try{http_request=new ActiveXObject("Microsoft.XMLHTTP");}catch(e){}}}
	if(!http_request){alert('Giving up :( Cannot create an XMLHTTP instance');return false;}
	http_request.onreadystatechange=alertContents;http_request.open('GET',url,true);http_request.send(null);}
	function alertContents(){if(http_request.readyState==4){if(http_request.status==200){rec_response(http_request.responseText);}else{}}}
	
	/* http_request for reading */
	function ajax_request2(url){http_request2=false;if(window.XMLHttpRequest){http_request2=new XMLHttpRequest();if(http_request2.overrideMimeType){http_request2.overrideMimeType('text/xml');}}else if(window.ActiveXObject){try{http_request2=new ActiveXObject("Msxml2.XMLHTTP");}catch(e){try{http_request2=new ActiveXObject("Microsoft.XMLHTTP");}catch(e){}}}
	if(!http_request2){alert('Giving up :( Cannot create an XMLHTTP instance');return false;}
	http_request2.onreadystatechange=alertContents2;http_request2.open('GET',url,true);http_request2.send(null);}
	function alertContents2(){if(http_request2.readyState==4){if(http_request2.status==200){rec_chatcontent(http_request2.responseText);}else{}}}
	
	/* chat stuff */
	chatmsg.focus()
	var show_newmsg_on_bottom=1;     /* set to 0 to let new msg´s appear on top */
	var waittime=3000;        /* time between chat refreshes (ms) */
	
	intUpdate=window.setTimeout("read_cont();", waittime);
	chatwindow.value = "loading...";
	
	function read_cont()         { zeit = new Date(); ms = (zeit.getHours() * 24 * 60 * 1000) + (zeit.getMinutes() * 60 * 1000) + (zeit.getSeconds() * 1000) + zeit.getMilliseconds(); ajax_request2("/cms/games/trashtalk/msg?_token=" + _token + "&g=" + game_id + "&x=" + ms); }
	function display_msg(msg1)     { chatwindow.value = msg1.trim(); }
	function keyup(arg1)         { if (arg1 == 13) submit_msg(); }
	function submit_msg()         { clearTimeout(intUpdate); if (chatnick.value == "") { check = prompt("please enter username:"); if (check === null) return 0; if (check == "") check="..."; chatnick.value=check; } if (chatnick.value.length > nick_maxlength) chatnick.value=chatnick.value.substring(0,nick_maxlength); spaces=""; for(i=0;i<(nick_maxlength-chatnick.value.length);i++) spaces+=" "; v=chatwindow.value.substring(chatwindow.value.indexOf("\n")) + "\n" + chatnick.value + spaces + "| " + chatmsg.value; if (chatmsg.value != "") chatwindow.value=v.substring(1); write_msg(chatmsg.value,chatnick.value); chatmsg.value=""; intUpdate=window.setTimeout("read_cont();", waittime);}
	function write_msg(msg1,nick1)     { ajax_request("/cms/games/trashtalk?_token=" + _token + "&g=" + game_id + "&m=" + escape(msg1) + "&n=" + escape('2')); }
	function rec_response(str1)     { }
	
	function rec_chatcontent(cont1) {
	    if (cont1 != "") {
	        out1 = unescape(cont1);
	        if (show_newmsg_on_bottom == 0) { out1 = ""; while (cont1.indexOf("\n") > -1) { out1 = cont1.substr(0, cont1.indexOf("\n")) + "\n" + out1; cont1 = cont1.substr(cont1.indexOf("\n") + 1); out1 = unescape(out1); } }
	        if (chatwindow.value != out1) { display_msg(out1); }
	        intUpdate=window.setTimeout("read_cont()", waittime);
	    }
	}
	</script>
@endsection


