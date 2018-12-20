<html>
<head>
	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="pragma" content="no-cache"/>
	<meta http-equiv="pragma" content="no-store"/>
	<meta http-equiv="cache-control" content="no-cache"/>
	<meta http-equiv="Expires" content="-1"/>
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="manifest" href="manifest.json">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> 
	<style type="text/css">
	.blink {
		animation-duration: 1s;
		animation-name: blink;
		animation-iteration-count: infinite;
		animation-direction: alternate;
		animation-timing-function: ease-in-out;
	}
	@keyframes blink {
		from {
			opacity: 1;
		}
		to {
			opacity: 0;	
		}	
	}
	#responseHolder {
                width: 100%;
                height: 50%;
                overflow: scroll;
                background-color: #D1D1D1;
                border-radius: 5px;
                float: left;
                padding: 20px;
                box-sizing: border-box;
      	}
	#logo_fixed{
            position:fixed;
        	top: 1px;
        	right: 500px;
    }
	#news-brief-img{
		display: block;	
		margin-left: auto;
		margin-right: auto;
		margin-top: auto;
		margin-bottom: auto;
	}
	</style>
<!--
    <link href="style.css" type="text/css" rel="stylesheet">
    <link href="github-gist.css" type="text/css" rel="stylesheet">
    <script src="jquery.js"></script>
    <script src="DetectRTC.js"></script>
-->
</head>
<body>
   <header>
      <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
              <h4 class="text-white">About</h4>
              <p class="text-muted">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
            </div>
            <div class="col-sm-4 offset-md-1 py-4">
              <h4 class="text-white">Contact</h4>
              <ul class="list-unstyled">
                <li><a href="#" class="text-white">Follow on Twitter</a></li>
                <li><a href="#" class="text-white">Like on Facebook</a></li>
                <li><a href="#" class="text-white">Email me</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
        </div>
          <button id="playbutton" type="button" value="microphone">
                                                                <img id="start_img" src="./mic.gif" alt="Start"></button>
          <a href="#" id="chat-hide" class="navbar-brand d-flex align-items-center">
            <strong>맹소식 by <font color=red> AIRI </font> </strong>
          </a>
      </div>
    </header>
    <section class="jumbotron text-center" style="padding: 0.01rem 0.01rem; background:white;">
<!--	<div class="container">
          <p class="lead text-muted"> -->
<!--        
	<img src="./demoday-chatscript02.png" class="img-fluid" alt="Responsive image" style="width:1400px;" />
-->
<!--	  </p>
        </div> --> 
    </section> 
    <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="input-group mb-2">
                               <div class="input-group-prepend">
                                </div>
				<input type="text" class="form-control" id="news-title" placeholder="title" value="" style="font-weight:bold;"/> 
                        </div>	
		</div>
	</div>
	<div class="row">		
    		<div class="col-md-6">
			   <div class="card">
               			  <div class="card-body" style="height: 700px;">
 			                    <img id="news-brief-img" src="newspic/main.png"  />
 			                    <iframe id="news-brief-src" src="about:blank" frameborder="0"> </iframe>
  		                  </div>
            		   </div>
                </div>
		<div class="col-md-6">
			<div class="card">
                                  <div class="card-header">
                                        Abstract
                                  </div>
                                  <div class="card-body">
                                        <div id="han_abstract" class="border" style="height:610px;">
                                        </div>
                                  </div>
			</div>
		</div>
	</div>
	<div class="row" id="responseHolderDiv" style="display:none;">	
		<div id="responseHolder"></div>
	</div>
	<!--<div class="row" style="padding-bottom:2.5rem;"> 
	</div>-->
	<div class="row" id="submitDiv" style="display:none;">
		<div class = "col-md-10">
       			<form id="frmChat" action="#" > <!-- start FORM -->	
				<div class="input-group mb-2">
                               		<div class="input-group-prepend">
						<div id="speechcontainer" >
  							<div id="button_panel">
    							<!--	<button id="playbutton" type="button" value="microphone">
    								<img id="start_img" src="./mic.gif" alt="Start"></button> -->
  							</div>
						</div>
					</div>
		 	 			<input type="hidden" id="txtUser" name="user" size="10" value="손님" />
						<input type="text" class="form-control" name="message" id="txtMessage" placeholder="대화를 입력해주세요." value="" style="padding-right:0.5rem;"/>
                                                <input type="hidden" name="send" />
				</div>
			</form>
		</div>
		<input type="submit" name="send" value="Send" id="send" style="height: 55px;"/>  
		<input type="button" name="clear" value="Clear" id="clear" style="height: 55px;" onclick="javascript:location.reload(true)"/>
		<input type="button" name="build" value="Build" id="build" style="height: 55px;" onclick="callBuild()"/>
		<input type="hidden" class="interim" id="interim_span">
		<input type="hidden" class="interim" id="info">
	</div>	
    	<div id="result">
      		<span class="final" id="final_span"></span>
    	</div>



    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" crossorigin="anonymous"></script>
    <script>
        newsData = null
       	$(document).ready(function(){
	    navigator.mediaDevices.getUserMedia({ audio: true })
            $('#playbutton').click(function(){
                 console.log('play button click');
	       	 window.clearTimeout(callback);
            });
	     $.ajax({
                                  dataType: "text",
                                  url: "https://10.100.0.157/test/demonews.json",
                                  success: function(response){
                                        newsData = JSON.parse(response);
                                }
                        });
	});
	var cbAutoSend = 'checked';
	var cbTTSEnabled = 'checked';
        var botName = '맹소식';		// change this to your bot name
	// declare timer variables
	var alarm = null;
	var callback = null;
	var loopback = null;
	window.onload=function(){
		callReset();
	}
	$(document).ready(function(){
		$("#chat-hide").click(function(){
			$("#responseHolderDiv").toggle();
			toggleFullScreen();
			$("#submitDiv").toggle();
		});
		$('#frmChat').submit(function(e){
			e.preventDefault();  // Prevent the default submit() method
			var name = $('#txtUser').val();
        		if (name == '') {
                		alert('Please provide your name.');
                		document.getElementById('txtUser').focus();
        		}
		        var chatLog = $('#responseHolder').html();
		        var youSaid = '<strong>' + name + ':</strong> ' + $('#txtMessage').val() + "<br>\n";
		        update(youSaid);
		        var data = $(this).serialize();
		        sendMessage(data);
		        $('#txtMessage').val('');
		});
	        // any user typing cancels loopback or callback for this round
	        $('#txtMessage').keypress(function(){
	        	window.clearInterval(loopback);
	        	window.clearTimeout(callback);
        	});
	});
	function callTTSFile(text,callback){
                         var data = "file="+encodeURIComponent(text);
                         var success = function(response){
                                   $("#tts").attr('src',response);
                                   $("#tts").unbind('ended');
                                   $("#tts").on('ended',function() {
                                         console.log('ended '+text);
                                         if (callback!=null)
                                                callback();
                                   });
                                   console.log($("#tts"));
                                   $("#tts").trigger("play");
                        }
                        $.ajax({
                                  dataType: "text",
                                  url: "https://10.100.0.157:8889/tts/file",
                                  data: data,
                                  success: success
                        });
                }
        function callTTS(text,callback){
        	var data = "query="+encodeURIComponent(text)+"&lang=ko-KR&gender=female";
		var success = function(response){
                          $("#tts").attr('src',response);
                          $("#tts").unbind('ended');
              $("#tts").on('ended',function(){
                console.log('ended : ' + text);
                callback();
              });
              console.log($("#tts"));
                          $("#tts").trigger("play");
                }
                $.ajax({
                         dataType: "text",
                         //url: "https://10.100.0.157:8889/tts/",
                         url: "https://10.100.0.157:8889/tts/naver",
                         data: data,
                         success: success
                });
        }


	function callReset()
        {
		 resetDoc(':reset');
		 //setTimeout(function(){location.reload(true)}, 2000);
         //setTimeout(function(){resetDoc(':reset')}, 2000);
        }
    	function callBuild(){
		resetDoc(':build Demoday');
	}	
	function resetDoc(message)
	{
		document.getElementById('txtMessage').value = message;
        	var chatLog = $('#responseHolder').html();
	//	update(youSaid);
        	var data = $('#frmChat').serialize();
        	sendMessage(data);
       	 	$('#txtMessage').val('');
	}

	function sendMsg(message)
	{
		document.getElementById('txtMessage').value = message;
		var name = $('#txtUser').val();
	        var chatLog = $('#responseHolder').html();
		var youSaid = '<strong>' + name + ':</strong> ' + $('#txtMessage').val() + "<br>\n";
		update(youSaid);
	        var data = $('#frmChat').serialize();
	        sendMessage(data);
	        $('#txtMessage').val('');
	}
        var count = 0;
	function sendMessage(data, iscallback){ //Sends inputs to the ChatScript server, and returns the response-  data - a JSON string of input information
        if(iscallback && !document.getElementById("tts").paused){
		callback = setTimeout(function() { sendMessage(data, true) }, 1000)
		return ;
	} else if (iscallback && document.getElementById("tts").paused && count==0){
                count=1;
		callback = setTimeout(function() { sendMessage(data, true) }, 1000)
                return;
        }
        count=0;
	$.ajax({
		type: 'POST',
		url: 'ui_HCS.php',
		//data type은 자동으로 ajax result parsing해줌
		dataType: 'json',
		data: data,
	    success: function(response){
			processResponse(parseCommands(response));
	    }, error: function(xhr, status, error){
			alert('oops? Status = ' + status + ', data = ' + data +  ', error message = ' + error + "\nResponse = " + xhr.responseText);
	    }
	  });
	}

	function parseCommands(response){ // Response is data from CS server. This processes OOB commands sent from the CS server returning the remaining response w/o oob commands
		var result = response;
		var response = response['text'];
		var len  = response.length;
		var i = -1;
		while (++i < len )
		{
			if (response.charAt(i) == ' ' || response.charAt(i) == '\t') continue; // starting whitespace
			if (response.charAt(i) == '[')  break;	                         // we have an oob starter
			return result;						// there is no oob data 
		}
		if ( i == len) return result; // no starter found
		var user = $('#txtUser').val();
	     
		// walk string to find oob data and when ended return rest of string
		var start = 0;
		while (++i < len )
		{
			if (response.charAt(i) == ' ' || response.charAt(i) == ']') // separation
			{
				if (start != 0) // new oob chunk
				{
					var blob = response.slice(start,i);
					start = 0;
					var commandArr = blob.split('=');
					if (commandArr.length == 1) continue;	// failed to split left=right
					var command = commandArr[0]; // left side is command 
					var interval = (commandArr.length > 1) ? commandArr[1].trim() : -1;
	// right side is millisecond count
					if (interval == 0)  /* abort timeout item */
					{ 
						switch (command){
							case 'alarm':
								window.clearTimeout(alarm);
								alarm = null;
								break;
							case 'callback':
								window.clearTimeout(callback);
								callback = null;
								break;
							case 'loopback':
								window.clearInterval(loopback);
								loopback = null;
								break;
						}
					}
					else if (interval == -1) interval = -1; // do nothing
					else
					{
						var timeoutmsg = {user: user, send: true, message: '[' + command + ' ]'}; // send naked command if timer goes off 
						switch (command) {
							case 'alarm':
								alarm = setTimeout(function(){sendMessage(timeoutmsg );}, interval);
								break;
							case 'callback':
								callback = setTimeout(function(){sendMessage(timeoutmsg,true );}, interval);
								break;
							case 'loopback':
								loopback = setInterval(function(){sendMessage(timeoutmsg );}, interval);
								break;
		                                        case 'avatar' :
		                                                document.getElementById("avatarImage").src = "images/" + interval;
		                                                break;
						}
					}
				} // end new oob chunk
				if (response.charAt(i) == ']') {
					result['text'] = result['text'].slice(i + 2);
					return result; // return rest of string, skipping over space after ] 
				}	
			} // end if
			else if (start == 0) start = i;	// begin new text blob
		} // end while
		return result;	// should never get here
	 }
	 
	function update(text){ // text is  HTML code to append to the 'chat log' div. This appends the input text to the response div
		var chatLog = $('#responseHolder').html();
		$('#responseHolder').html(chatLog + text);
		var rhd = $('#responseHolder');
		var h = rhd.get(0).scrollHeight;
		rhd.scrollTop(h);
	}
	// TTS code taken and modified from here:
	// http://stephenwalther.com/archive/2/01/05/using-html5-speech-recognition-and-text-to-speech
	//---------------------------------------------------------------------------------------------------
	// say a message
	function speak(text, callback) {
	    if ( cbTTSEnabled == 'checked' ) {
	    	var u = new SpeechSynthesisUtterance();
		// get the voice
	    	var voices = window.speechSynthesis.getVoices();
	    	var selectedVoice = voices.filter(function (voice) {
		    	return voice.name == 'Google 한국의';
	    	})[0];
	    	
	    	// create the utterance
		
			// 괄호안의 단어, alphabet, \/는 발음하지 않기 위해 제거함
	//		u.text = text;
			var bOpenParen = 0;
			var tmp;
//			for (var i = 0, max = text.length; i < max; i++)
			for (var i = 0, max = 150; i < max; i++)
			{
				if (bOpenParen)
				{
					if (text.charAt(i) == '\)')
						bOpenParen = 0;
				}
				else{
					if (text.charAt(i) == '\(')
						bOpenParen = 1;
					else
						tmp += String(text.charAt(i));
				}
			}
			var tmp2 = tmp.replace(/[a-zA-Z\/]/g, "");
			var newText = tmp2.replace(/~/g, "에서");

			u.text = newText;
		    	u.lang = 'ko-KR';	// 'en-GB'
		    	u.voice = selectedVoice;  
		       	u.rate = 1.0;  // .85	// 0.1 ~ 10 : speed 결정
		    	u.pitch = 1.0;  // .9	// 0 ~ 2
		    	u.volume = 1.0;  		// 0 ~ 1
	 
		    u.onend = function () {
			if (callback) {
		    	callback();
			}
		    };
	 
	    	u.onerror = function (e) {
			if (callback) {
		    		callback(e);
			}
	    	};
	 
	    	speechSynthesis.speak(u);
	    }
	}
	//-----End of TTS Code Block-----------------------------------------------------------------------------
	function processResponse(response) { // given the final CS text, converts the parsed response from the CS server into HTML code for adding to the response holder div
		//response = replace('\n','<br>\n');
		var tmp = response['text'].replace(/\\/g, "\%");
		response['text']=unescape(tmp);
		var botSaid = '<strong>' + botName + ':</strong> ' + response['text'] + "<br>\n";
		if (response['abstract'] != '' ){
			console.log(response['abstract'])
			$("#han_abstract").html(newsData[response['abstract']]['abstract'])
			$("#news-title").val(newsData[response['abstract']]['title'])
		}
		if (response['url'] != '' ){
			$('#news-brief-img').attr('src', ' ').attr('style','display:none;');
			$('#news-brief-src').attr({src: 'https://ko.m.wikipedia.org/wiki/'+ response['url'],  
						   width: '100%', 
						   height: '95%'}).attr('style','');
		}else{
			$('#news-brief-src').attr('src','about:blank').attr('style','display:none;');
		}

		if (response['img'] !=null ){
			$('#news-brief-img').attr('src','newspic/' + response['img'] + '.jpg').attr('style','');
		}
		else {
                        if(response['url'] == '' || response['url'] == null)
			    $('#news-brief-img').attr('src','newspic/main.png').attr('style','');
                        else
			    $('#news-brief-img').attr('src','newspic/main.png').attr('style','display:none;');
		}
		update(botSaid);
		//speak(response['text']);
		if(response['sound'] != null){
			callTTSFile(response['sound'],function(){
			});
		} else {
			callTTS(response['text'],function(){ 
				//setChildText(response['text']); 
 			});
		}
		
	}

	function capitalize(string) {
	    return string.charAt(0).toUpperCase() + string.slice(1);
	}
	function processFinalTranscript(transcript) {
	
		transcript = transcript.trim();	
		var lastWord = transcript.split(" ").slice(-1);
		if ( lastWord == '무트') { 
			txtMessage.value = '';
			final_span.innerHTML = '';
		        interim_span.innerHTML = '';
	  		if (recognizing) recognition.stop(); 
			return; 
		} else
/*
		if ( lastWord == '지워') { 
			txtMessage.value = '';
			final_span.innerHTML = '';
		        interim_span.innerHTML = '';
			return; 
		} else
		if ( lastWord == '샌드' || lastWord == '쌘드') { 
			var a_msg = $('#txtMessage').val();
			sendMsg(a_msg); 
			return; 
		} else 
*/
		{
			var lastLetter = transcript.slice(-1);
			var firstWord =  transcript.split(' ')[0];
			var punctuation = '.';
			if ( ['who','what','where','how','why','did','do','does','will','can', 'could','would','should','is','are','am','shall','whom'].indexOf(firstWord) > -1) { punctuation = '?'; }
			if ( ['.', '?', '!', ',',':',';'].indexOf(lastLetter ) < 0) { transcript += punctuation; }
			transcript = capitalize(transcript);
			txtMessage.value = transcript;
			final_span.innerHTML = '';
			interim_span.innerHTML = '';
			var a_msg = $('#txtMessage').val();
			sendMsg(a_msg); 
		}
	}
	//End of Continuous Speech Recognition Block
	//----------------------------------------------------------------------------------------------------
	function toggleFullScreen() {
  		var doc = window.document;
  		var docEl = doc.documentElement;

  		var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
  		var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;
		
  		if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
    		requestFullScreen.call(docEl);
  		}
  		else {
    		cancelFullScreen.call(doc);
  	}
}
	</script>
        <audio width="0" height="0" controls id="tts" src="" style="visibility:hidden;"></audio> 
	<script src="js/main.js"></script>
</body>
</html>
