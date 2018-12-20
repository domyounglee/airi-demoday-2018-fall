<html>
<head>
	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> 
	<style type="text/css">
	#responseHolder {
                width: 100%;
                height: 100%;
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
	#S-manual-img{
		display: block;	
		margin-left: auto;
		margin-right: auto;
		margin-top: auto;
		margin-bottom: auto;
	}
	</style>
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
          <a href="#" class="navbar-brand d-flex align-items-center">
            <strong>Santafe Manual Chatbot</strong>
          </a>
      	  <span id="logo_fixed" class="navbar-toggler-icon" href="#"><img src="./AIRI_logo.png" width="90px" height="53px"/></a></span>
        </div>
      </div>
    </header>
    <section class="jumbotron text-center" style="padding: 1rem 0rem;">
        <div class="container">
          <h1 class="lead text-muted">
		안녕하세요. 산타페 챗봇 매뉴얼 입니다.
                <input type ="button" value="제네시스와 채팅하기" onclick="openChild()"><br>
	  </h1>
          <p class="lead text-muted">
		<img src="./Santafe2.png" class="img-fluid" alt="Responsive image" style="width:300px;" />
	  </p>
        </div>
    </section>
    <div class="container-fluid">
	<div class="row">
    		<div class="col-md-8" style="height:800px;">
			   <div class="card">
                 <div class="card-body" style="height: 500px;">
                     <img id="S-manual-img" src="../img/default_santafe.jpeg" class="img-fluid" alt="Responsive image" />
                 </div>
             </div>
            </div>
    		<div class="col-md-4" style="height:500px;">
				<div id="responseHolder"></div>
				</div>
			</div>
        <div class="row" style="visibility:hidden;">
		<form id="frmChat" action="#" > <!-- start FORM -->
     			<div class="formRow"> <!-- start FORM ROW 1 -->
     				<h4>사용자명:</h4>
     				<input type="text" id="txtUser" name="user" size="10" value="제네시스봇"/>
     				<input type="hidden" name="send"/>
     			</div> <!-- end FORM ROW 1 -->
     			<div class="formRow"> <!-- start FORM ROW 2 -->
     				<h4></h4>
     			</div> <!-- end FORM ROW 2 -->
     			<div class="formRow"> <!-- start FORM ROW 2 -->
     				<h4>대화를 입력하여 주세요.</h4>
     			</div> <!-- end FORM ROW 2 -->
     			<div class="formRow"> <!-- start FORM ROW 2 -->
     				<h4></h4>
     			</div> <!-- end FORM ROW 2 -->
     			<div class="formRow"> <!-- start FORM ROW 3 -->
     				<h4>대화:</h4>
     				<input type="text" name="message" id="SantafeTM"/>
				<input type="submit" name="send" value="Send" id="send"/>
     			</div> 
			<!-- end FORM ROW 3 -->
    		</form>
	</div>
    </div>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript">
        var cbAutoSend = 'checked';
	//var cbTTSEnabled = 'checked';
	var cbTTSEnabled = 'unchecked';
	var openWin = null;
	function openChild()
	{
		window.name = "parentForm";
		openWin = window.open("Genesis.php", "childForm", "width=1024, height=965, resizable=no, scrollbars=no");
		setTimeout(function(){setChildText('무엇을 할 수 있지?')},2500);
	}
        function setChildText(message){
		openWin.document.getElementById("GenesisTM").value = message;
		openWin.callGenesisResult();
	}
var botName = '제네시스';		// change this to your bot name
// declare timer variables
var alarm = null;
var callback = null;
var loopback = null;
$(function(){
	$('#frmChat').submit(function(e){
		e.preventDefault();  // Prevent the default submit() method
		setChildText(document.getElementById('SantafeTM').value);
	});
	
});


function callSantafeResult(){
	//e.preventDefault();  // Prevent the default submit() method
	// this function overrides the form's submit() method, allowing us to use AJAX calls to communicate with the ChatScript server
	var name = $('#txtUser').val();
	if (name == '') {
		alert('Please provide your name.');
		document.getElementById('txtUser').focus();
	 }
	var chatLog = $('#responseHolder').html();
	var youSaid = '<font size=5>' + '<strong>' + name + ':</strong> ' + $('#SantafeTM').val() + '</font>' + "<br>\n";
		update(youSaid);
		var data = $('#frmChat').serialize();
		sendMessage(data);
		$('#SantafeTM').val('').focus();
	
	}


	function sendMessage(data){ //Sends inputs to the ChatScript server, and returns the response-  data - a JSON string of input information
	$.ajax({
		type: 'POST',
		url: 'ui_S.php',
		//data type은 자동으로 ajax result parsing해줌
		dataType: 'json',
		data: data,
	    success: function(response){
		setTimeout(function(){processResponse(parseCommands(response));
},2500)
//		processResponse(parseCommands(response));
	    },
	    error: function(xhr, status, error){
			alert('oops? Status = ' + status + ', error message = ' + error + "\nResponse = " + xhr.responseText);
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
								callback = setTimeout(function(){sendMessage(timeoutmsg );}, interval);
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
			for (var i = 0, max = text.length; i < max; i++)
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
	       	u.rate = .8;  // .85	// 0.1 ~ 10 : speed 결정
	    	u.pitch = .7;  // .9	// 0 ~ 2
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
		if (response['img'] !=null ){
			callManImg(response);
		}
		else{
			$('#S-manual-img').attr('src','../img/default_santafe.jpeg');
		}
		update(botSaid);
		speak(response['text']);
		setTimeout(function(){setChildText(response['text'])},2500)
		
	}
	function callManImg(response){
		console.log(response);
		var man=openWin.document.getElementById("G-manual-img");
		man.src='../img/pages/s/' + response['img'] + '.png';	
	}  

	// Continuous Speech recognition code taken and modified from here:
	// https://github.com/GoogleChrome/webplatform-samples/tree/master/webspeechdemo
	//----------------------------------------------------------------------------------------------------
	var final_transcript = '';
	var recognizing = false;
	var ignore_onend;
	var start_timestamp;
	if (!('webkitSpeechRecognition' in window)) {
	  info.innerHTML = "You need to use Google Chrome to use speech to text functionality. Everything else should still work as expected.";
	} else {
	  btnMicrophone.style.display = 'inline-block';
	  var recognition = new webkitSpeechRecognition();
	  recognition.continuous = true;
	  recognition.interimResults = true;
	  recognition.lang = 'en-US';
	  recognition.onstart = function() {
	    recognizing = true;
	    info.innerHTML =  " Speak now.";
	    start_img.src = 'mic-animate.gif';
	  };
	  recognition.onerror = function(event) {
	    if (event.error == 'no-speech') {
	      start_img.src = 'mic.gif';
	      info.innerHTML = "You did not say anything.";
	      ignore_onend = true;
	    }
	    if (event.error == 'audio-capture') {
	      start_img.src = 'mic.gif';
	      info.innerHTML = "You need a microphone.";
	      ignore_onend = true;
	    }
	    if (event.error == 'not-allowed') {
	      if (event.timeStamp - start_timestamp < 100) {
		//Added more detailed message to unblock access to microphone.
		info.innerHTML = " I am blocked. In Chrome go to settings. Click Advanced Settings at the bottom. Under Privacy click the Content Settings button. Under Media click Manage Exceptions Button. Remove this site from the blocked sites list. ";
	      } else {
		info.innerHTML = "You did not click the allow button."
	      }
	      ignore_onend = true;
	    }
	  };
	  recognition.onend = function() {
	    recognizing = false;
	    if (ignore_onend) {
	      return;
	    }
	    start_img.src = 'mic.gif';
	    if (!final_transcript) {
	      info.innerHTML = "Click on the microphone icon and begin speaking.";
	      return;
	    }
	    info.innerHTML = "";
	   
	  };
	  recognition.onresult = function(event) {
	    var interim_transcript = '';
	    for (var i = event.resultIndex; i < event.results.length; ++i) {
	      if (event.results[i].isFinal) {
		final_transcript += event.results[i][0].transcript;
		//----Added this section to integrate with Chatscript submit functionality-----
		processFinalTranscript(final_transcript);
		final_transcript ='';
		//-----------------------------------------------------------------------------
	      } else {
		interim_transcript += event.results[i][0].transcript;
	      }
	    } 
	    final_span.innerHTML = final_transcript;
	    interim_span.innerHTML = interim_transcript;  
	  };
	}
	function microphoneClick(event) {
	  if (recognizing) {
	    recognition.stop();
	    return;
	  }
	  final_transcript = '';
	  SantafeTM.value = '';
	  recognition.start();
	  ignore_onend = false;
	  final_span.innerHTML = '';
	  interim_span.innerHTML = '';
	  start_img.src = 'mic-slash.gif';
	  info.innerHTML = " Click the Allow button above to enable your microphone.";
	  start_timestamp = event.timeStamp;
	  
	}
	function capitalize(string) {
	    return string.charAt(0).toUpperCase() + string.slice(1);
	}
	function processFinalTranscript(transcript) {
	
		transcript = transcript.trim();	
	
		var lastWord = transcript.split(" ").slice(-1);
		if ( lastWord == 'cancel') { 
			final_span.innerHTML = '';
		        interim_span.innerHTML = '';
			return; 
			}
		else {
			var lastLetter = transcript.slice(-1);
			var firstWord =  transcript.split(' ')[0];
			var punctuation = '.';
			if ( ['who','what','where','how','why','did','do','does','will','can', 'could','would','should','is','are','am','shall',			'whom'].indexOf(firstWord) > -1) { punctuation = '?'; }
			if ( ['.', '?', '!', ',',':',';'].indexOf(lastLetter ) < 0) { transcript += punctuation; }
			transcript = capitalize(transcript);
			SantafeTM.value = transcript;
			final_span.innerHTML = '';
			interim_span.innerHTML = '';
			if (cbAutoSend == 'checked') { frmChat(); }
		}
	}
	//End of Continuous Speech Recognition Block
	//----------------------------------------------------------------------------------------------------
	</script>

</body>
</html>
