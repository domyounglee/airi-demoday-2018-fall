var pause=true;
(function playButtonHandler() {
    // The play button is the canonical state, which changes via events.
    var playButton = document.getElementById('playbutton');
    playButton.addEventListener('click', function(e) {
        if (this.classList.contains('playing')) {
            playButton.dispatchEvent(new Event('pause'));
	    pause=true;
        } else {
            playButton.dispatchEvent(new Event('play'));
	    pause=false;
        }
    }, true);

    // Update the appearance when the state changes
    playButton.addEventListener('play', function(e) {
        this.classList.add('playing');
//	document.getElementById("start_img").src ="mic-animate.gif";
    });
    playButton.addEventListener('pause', function(e) {
        this.classList.remove('playing');
	//document.getElementById("start_img").src ="mic.gif";
    });
})();


(function audioInit() {
    // Check for non Web Audio API browsers.
    //window.AudioContext = window.AudioContext t;


    var analyser;

    function rafCallback(time) {
        if (!analyser) return;
        var freqByteData = new Uint8Array(analyser.frequencyBinCount);
        analyser.getByteFrequencyData(freqByteData); //analyser.getByteTimeDomainData(freqByteData);
    }
    rafCallback();

    // per https://g.co/cloud/speech/reference/rest/v1beta1/RecognitionConfig
    const SAMPLE_RATE = 16000;
    const SAMPLE_SIZE = 16;

    var playButton = document.getElementById('playbutton');

    // Hook up the play/pause state to the microphone context
    var context = new AudioContext();
    playButton.addEventListener('pause', context.suspend.bind(context));
    playButton.addEventListener('play', context.resume.bind(context));

    // The first time you hit play, connect to the microphone
    playButton.addEventListener('play', function startRecording() {
        var audioPromise = navigator.mediaDevices.getUserMedia({
            audio: {
                echoCancellation: true,
                channelCount: 1,
                sampleRate: {
                    ideal: SAMPLE_RATE
                },
                sampleSize: SAMPLE_SIZE
            }
        });

        audioPromise.then(function(micStream) {
            var microphone = context.createMediaStreamSource(micStream);
            analyser = context.createAnalyser();
            microphone.connect(analyser);
        }).catch(console.log.bind(console));

        initWebsocket(audioPromise);
    }, {once: true});


    /**
     * Hook up event handlers to create / destroy websockets, and audio nodes to
     * transmit audio bytes through it.
     */
    function initWebsocket(audioPromise) {
        var socket;
        var sourceNode;

        // Create a node that sends raw bytes across the websocket
        var scriptNode = context.createScriptProcessor(4096, 1, 1);
        // Need the maximum value for 16-bit signed samples, to convert from float.
        const MAX_INT = Math.pow(2, 16 - 1) - 1;
        scriptNode.addEventListener('audioprocess', function(e) {
            var floatSamples = e.inputBuffer.getChannelData(0);
            // The samples are floats in range [-1, 1]. Convert to 16-bit signed
            // integer.
            var data = Int16Array.from(floatSamples.map(function(n) {
                return n * MAX_INT;
            }));
            socket.send(data);
        });

        function newWebsocket() {
            var websocketPromise = new Promise(function(resolve, reject) {
                var socket = new WebSocket('wss://10.100.0.3:8080/transcribe/websocket');
                socket.addEventListener('open', resolve);
                socket.addEventListener('error', reject);
            });
            Promise.all([audioPromise, websocketPromise]).then(function(values) {
                var micStream = values[0];
                socket = values[1].target;
                // If the socket is closed for whatever reason, pause the mic
                socket.addEventListener('close', function(e) {
                        console.log('Websocket closing..');
			console.log(e);
			document.getElementById("start_img").src ="mic.gif";
			playButton.dispatchEvent(new Event('pause'));
                    	//if (!pause){
            			//document.getElementById('playbutton').dispatchEvent(new Event('play'));
			//} 
                });
                socket.addEventListener('error', function(e) {
                    console.log('Error from websocket', e);
                    playButton.dispatchEvent(new Event('pause'));
                });

                function startByteStream(e) {
                    // Hook up the scriptNode to the mic
                    sourceNode = context.createMediaStreamSource(micStream);
                    sourceNode.connect(scriptNode);
                    scriptNode.connect(context.destination);
                }

                // Send the initial configuration message. When the server acknowledges
                // it, start streaming the audio bytes to the server and listening for
                // transcriptions.
                socket.addEventListener('message', function(e) {
                    console.log(e);
                    socket.addEventListener('message', onTranscription);
                    console.log("start byte stream!!!");
                    startByteStream(e);
		    document.getElementById("start_img").src ="mic-animate.gif";
                }, {once: true});

                socket.send(JSON.stringify({sampleRate: context.sampleRate}));

            }).catch(console.log.bind(console));
        }

        function closeWebsocket() {
            scriptNode.disconnect();
            if (sourceNode) sourceNode.disconnect();
            if (socket && socket.readyState === socket.OPEN) socket.close();
        }

        function toggleWebsocket(e) {
            var context = e.target;
            if (context.state === 'running') {
                newWebsocket();
            } else if (context.state === 'suspended') {
                closeWebsocket();
            }
        }

        /**
         * This function is called with the transcription result from the server.
         */
        function onTranscription(e) {
            var result = JSON.parse(e.data);
            if (result.alternatives_) {
		document.getElementById("txtMessage").value =result.alternatives_[0].transcript_;
            }
            if (result.isFinal_) {
		console.log(result.alternatives_[0].transcript_);
		document.getElementById("txtMessage").value =result.alternatives_[0].transcript_;
		$('#frmChat').submit();	
            }
        }

        // When the mic is resumed or paused, change the state of the websocket too
        context.addEventListener('statechange', toggleWebsocket);
        // initialize for the current state
        toggleWebsocket({target: context});
    }
})();
