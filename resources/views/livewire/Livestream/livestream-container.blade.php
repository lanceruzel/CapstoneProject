<style>
    #my-video {
        width: 100%;
        height: 90%;
    }
</style>

<div class="w-full max-md:h-full md:h-[calc(100vh-9rem)]">
    @if($livestream)
            @if($livestream->playback_url && $livestream->status == 'ended')
                <h1 class="text-2xl font-semibold pb-3 max-md:pt-2">Watching {{ $livestream->user->name() }} livestream's playback</h1> 
            @else
                <h1 class="text-2xl font-semibold pb-3 max-md:pt-2">{{ $livestream->user->name() }} livestreaming from {{ $livestream->location }}</h1> 
            @endif

        <div class="grid grid-cols-12 w-full h-full gap-3">
            <div class="col-span-12 lg:col-span-7 2xl:col-span-8" wire:ignore>
                @if($livestream->playback_url && $livestream->status == 'ended')
                    <div class="w-full h-full max-lg:h-[500px]">
                        <video
                            id="my-video"
                            class="video-js"
                            controls
                            preload="auto"
                            controls
                            data-setup="{}"
                        >
                            <source src="{{ $livestream->playback_url }}" type="application/x-mpegURL" />
                        </video>
                    </div>
                @else
                    <!-- Loading Info -->
                    <div class="w-full flex flex-col items-center bg-white shadow rounded-lg justify-center gap-2" id="loadingInfoVideo">
                        <img class="h-[400px]" src="{{ asset('assets/svg/18737145_6020154.svg') }}" alt="Loading"/>

                        <div class="flex items-center justify-center gap-2 py-2 mb-2">
                            <span>
                                <x-icon name="arrow-path" class="w-7 h-7 animate-spin" />
                            </span>
                            
                            <span class="text-xl font-semibold">
                                Livestream will start shortly...
                            </span>
                        </div>
                    </div>

                    <!-- Livestream pause Info -->
                    <div class="w-full h-[24rem] hidden items-center bg-white shadow rounded-lg justify-center gap-2 p-5" id="pausedInfoVideo">
                        <span class="text-xl font-semibold text-center">
                            Livestream has been stopped wait for the host to resume the stream again.
                        </span>
                    </div>

                    <div class="hidden bg-white rounded-lg shadow relative" id="videoContainer"></div>

                    <div class="w-full flex-col items-center justify-center gap-3 py-3 bg-white shadow rounded-lg mt-3" id="speakerView" style="display: none">
                        <h3 id="hlsStatusHeading"></h3>

                        <div class="flex items-center justify-center gap-3">
                            <!-- wire:click='leaveConfirmation' -->
                            <x-button label="End live" id="leaveBtn" negative onclick="Livewire.dispatch('end-confirm');"/>

                            <x-mini-button class="hidden" id="openMicBtn" negative rounded icon="microphone" />
                            <x-mini-button id="closeMicBtn" rounded icon="microphone" />

                            <x-mini-button class="hidden" id="openWebCamBtn" rounded icon="video-camera-slash" />
                            <x-mini-button id="closeWebCamBtn" rounded icon="video-camera" />

                            <x-mini-button id="startHlsBtn" rounded positive icon="play" />
                        </div>
                    </div>
                @endif
            </div>

            <!-- Chat section -->
            <div class="col-span-12 lg:col-span-5 2xl:col-span-4">
                <livewire:Livestream.livestream-chat-container :meetingId="$livestream->id" />
            </div>
        </div>

        @push('scripts')
            <script>
                let role = @js($role);
                let meetingId = @js($livestream->id);
                let name = @js($livestream->user->name());

                document.addEventListener('livewire:init', () => {
                    Livewire.on('end-live', (event) => {
                        meeting?.leave();
                    });

                    Livewire.on('close-modal', (event) => {
                        $closeModal(event[0].modal);
                    });

                    Livewire.on('startLivestreamTimer', (event) => {
                        startTimer(event[0].updated_at);
                    });
                    
                    window.Echo.channel(`new-livestream-reaction.${meetingId}`)
                        .listen('LiveReactionCreated', (e) => {
                            const liveReactions = document.getElementById('videoContainer');

                            const newDiv = document.createElement('div');
                            newDiv.classList.add('text-4xl', 'transition-all', 'ease-in', 'duration-[4000ms]');
                            newDiv.innerHTML = e.reaction;

                            newDiv.style.position = 'absolute';
                            newDiv.style.bottom = '0'; 

                            // Randomize the horizontal (x-axis) position of the newDiv within the container
                            const containerWidth = liveReactions.offsetWidth;
                            const randomX = Math.random() * (containerWidth - 50); // Random x position, subtract some buffer for div width
                            newDiv.style.left = `${randomX}px`;

                            liveReactions.appendChild(newDiv);

                            setTimeout(() => {
                                // newDiv.classList.remove('-translate-y-[100dvh]', 'opacity-100');
                                newDiv.classList.add('-translate-y-[100dvh]', 'opacity-0', 'scale-[2.5]');
                            }, 10);

                            setTimeout(() => {
                                liveReactions.removeChild(newDiv);
                            }, 4000);
                        }
                    );
                });

                let watching = 0;
                let timer = 0;
                let timerID = null;

                const watchingCount = document.getElementById("watchingCount");

                const pausedVideoContainer = document.getElementById("pausedInfoVideo");
                const videoContainer = document.getElementById("videoContainer");
                const loadingInfoVideo = document.getElementById("loadingInfoVideo");
                const hlsStatusHeading = document.getElementById("hlsStatusHeading");
                const leaveButton = document.getElementById("leaveBtn");
                const startHlsButton = document.getElementById("startHlsBtn");

                const openMicButton = document.getElementById("openMicBtn");
                const closeMicButton = document.getElementById("closeMicBtn");

                const openWebCamButton = document.getElementById("openWebCamBtn");
                const closeWebCamButton = document.getElementById("closeWebCamBtn");

                const Constants = VideoSDK.Constants;

                function initializeMeeting() {}
                function createLocalParticipant() {}
                function createVideoElement() {}
                function createAudioElement() {}
                function setTrack() {}

                // Initialize meeting
                function initializeMeeting(mode){
                    window.VideoSDK.config(@js(env('VIDEO_SDK_TOKEN')));
                
                    meeting = window.VideoSDK.initMeeting({
                        meetingId: meetingId, // required
                        name: name, // required
                        mode: mode,
                    });
                
                    meeting.join();
                
                    meeting.on("meeting-joined", () => {
                        if(meeting.hlsState === Constants.hlsEvents.HLS_STOPPED){
                            hlsStatusHeading.textContent = "Livestream haven't started yet";
                        }else{
                            hlsStatusHeading.textContent = `HLS Status: ${meeting.hlsState}`;
                        }
                    
                        if (mode === Constants.modes.CONFERENCE) {
                            meeting.localParticipant.pin();
                        }
                    });
                
                    meeting.on("meeting-left", () => {
                        videoContainer.innerHTML = "";
                        Livewire.dispatch('delete-livestream');
                    });
                
                    meeting.on("hls-state-changed", (data) => {
                        const { status } = data;

                        switch(status){
                            case "HLS_STARTING":
                                hlsStatusHeading.textContent = 'Livestream is now starting.';
                                break;

                            case "HLS_STARTED":
                                hlsStatusHeading.textContent = 'Processing...';
                                break;

                            case "HLS_PLAYABLE":
                                hlsStatusHeading.textContent = 'You are live now!';
                                Livewire.dispatch('insert-playback-url', { url: meeting.hlsUrls.playbackHlsUrl });
                                Livewire.dispatch('prepareTimer');
                                break;

                            case "HLS_STOPPING":
                                hlsStatusHeading.textContent = 'Stopping...';
                                break;

                            case "HLS_STOPPED":
                                hlsStatusHeading.textContent = 'Livestream has stopped!';
                                break;

                            default:
                                hlsStatusHeading.textContent = `${status}`;
                                break;
                        }

                        if(mode === Constants.modes.VIEWER){
                            if (status === Constants.hlsEvents.HLS_PLAYABLE) {
                                if(pausedVideoContainer.classList.contains('flex')){
                                    pausedVideoContainer.classList.remove('flex');
                                    pausedVideoContainer.classList.add('hidden');
                                }

                                const { downstreamUrl } = data;
                                
                                let video = document.createElement("video");
                                video.setAttribute("width", "100%");
                                video.setAttribute("muted", "false");
                                // enableAutoPlay for browser autoplay policy
                                video.setAttribute("autoplay", "true");

                                // Create timer element
                                let timerElement = document.createElement("div");
                                timerElement.setAttribute("id", `timer-containter`);
                                timerElement.style.position = "absolute";
                                timerElement.style.top = "10px";
                                timerElement.style.left = "50%";
                                timerElement.style.transform = "translateX(-50%)"; // Center the timer
                                timerElement.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
                                timerElement.style.color = "#fff";
                                timerElement.style.padding = "5px 10px";
                                timerElement.style.borderRadius = "5px";
                                timerElement.style.fontSize = "16px";
                                timerElement.innerHTML = "00:00:00";
                        
                                if (Hls.isSupported()) {
                                    var hls = new Hls();
                                    hls.loadSource(downstreamUrl);
                                    hls.attachMedia(video);
                                    hls.on(Hls.Events.MANIFEST_PARSED, function () {
                                        video.play();
                                    });
                                } else if (video.canPlayType("application/vnd.apple.mpegurl")) {
                                    video.src = downstreamUrl;
                                    video.addEventListener("canplay", function () {
                                        video.play();
                                    });
                                }
                    
                                videoContainer.appendChild(timerElement);
                                videoContainer.appendChild(video);

                                //Show video
                                videoContainer.classList.remove('hidden');

                                //Hide loading
                                loadingInfoVideo.classList.add('hidden');
                            }
                    
                            if(status === Constants.hlsEvents.HLS_STOPPING) {
                                videoContainer.innerHTML = "";
                                pausedVideoContainer.classList.remove('hidden');
                                pausedVideoContainer.classList.add('flex');
                            }
                        }
                    });
                
                    if(mode === Constants.modes.CONFERENCE) {
                        // creating local participant
                        createLocalParticipant();
                    
                        // setting local participant stream
                        meeting.localParticipant.on("stream-enabled", (stream) => {
                            setTrack(stream, null, meeting.localParticipant, true);

                            //Show video
                            videoContainer.classList.remove('hidden');

                            //Show Speaker Controls
                            document.getElementById("speakerView").style.display = "flex";

                            //Hide loading
                            loadingInfoVideo.classList.add('hidden');
                        });
                    }

                    if(mode === Constants.modes.VIEWER){
                        // participants left
                        meeting.on("participant-left", (participant) => {
                            if(participant.mode == Constants.modes.CONFERENCE){
                                Livewire.dispatch('delete-livestream');
                            }
                        });
                    }

                    // participant joined
                    meeting.on("participant-joined", (participant) => {
                        watching++;
                        watchingCount.innerHTML = watching;
                    });
                    
                    // participants left
                    meeting.on("participant-left", (participant) => {
                        watching--;
                        watchingCount.innerHTML = watching;
                    });
                }

                // creating video element
                function createVideoElement(pId, name) {
                    let videoFrame = document.createElement("div");
                    videoFrame.setAttribute("id", `f-${pId}`);
                    videoFrame.style.position = "relative";  // Set relative positioning for the container

                    // Create timer element
                    let timerElement = document.createElement("div");
                    timerElement.setAttribute("id", `timer-containter`);
                    timerID = pId;
                    timerElement.style.position = "absolute";
                    timerElement.style.top = "10px";
                    timerElement.style.left = "50%";
                    timerElement.style.transform = "translateX(-50%)"; // Center the timer horizontally
                    timerElement.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
                    timerElement.style.color = "#fff";
                    timerElement.style.padding = "5px 10px";
                    timerElement.style.borderRadius = "5px";
                    timerElement.style.fontSize = "16px";
                    timerElement.innerHTML = "00:00"; 

                    videoFrame.appendChild(timerElement);

                    //create video
                    let videoElement = document.createElement("video");
                    videoElement.classList.add("video-frame");
                    videoElement.setAttribute("id", `v-${pId}`);
                    videoElement.setAttribute("playsinline", true);
                    videoElement.setAttribute("width", "300");
                    videoElement.setAttribute("muted", 'muted');
                    videoElement.classList.add("w-full");
                    videoElement.classList.add("rounded-lg");
                    videoFrame.appendChild(videoElement);
                    
                    return videoFrame;
                }

                function startTimer(mysqlDateTime) {
                    // Convert the MySQL datetime (e.g., "2024-10-23 14:30:00") to a JavaScript Date object
                    let startTime = new Date(mysqlDateTime);

                    // Get the current time
                    let currentTime = new Date();

                    // Calculate the difference in seconds between the current time and the MySQL datetime
                    let timeDifferenceInSeconds = Math.floor((currentTime - startTime) / 1000);

                    interval = setInterval(function() {
                        // Increment the timeDifferenceInSeconds every second
                        timeDifferenceInSeconds++;

                        // Calculate minutes and seconds passed since the MySQL datetime
                        let minutes = Math.floor(timeDifferenceInSeconds / 60).toString().padStart(2, '0');
                        let seconds = (timeDifferenceInSeconds % 60).toString().padStart(2, '0');

                        // Update the timer on the page
                        updateTimer(`${minutes}:${seconds}`);
                    }, 1000);  // Update every second
                }

                function updateTimer(time) {
                    let timerElement = document.getElementById(`timer-containter`);
                    if (timerElement) {
                        timerElement.innerHTML = time;
                    }
                }

                function stopTimer() {
                    clearInterval(interval);  // Clears the interval
                }

                // creating audio element
                function createAudioElement(pId) {
                    let audioElement = document.createElement("audio");
                    audioElement.setAttribute("autoPlay", "false");
                    audioElement.setAttribute("playsInline", "true");
                    audioElement.setAttribute("controls", "false");
                    audioElement.setAttribute("id", `a-${pId}`);
                    audioElement.style.display = "none";
                    return audioElement;
                }

                // creating local participant
                function createLocalParticipant() {
                    let localParticipant = createVideoElement(
                        meeting.localParticipant.id,
                        meeting.localParticipant.displayName
                    );

                    videoContainer.appendChild(localParticipant);
                }

                // setting media track
                function setTrack(stream, audioElement, participant, isLocal) {
                    if (stream.kind == "video") {
                        isWebCamOn = true;
                        const mediaStream = new MediaStream();
                        mediaStream.addTrack(stream.track);
                        let videoElm = document.getElementById(`v-${participant.id}`);
                        videoElm.srcObject = mediaStream;
                        videoElm.play().catch((error) => console.error("videoElem.current.play() failed", error));
                    }
                    
                    if (stream.kind == "audio") {
                        if (isLocal) {
                            isMicOn = true;
                        } else {
                            const mediaStream = new MediaStream();
                            mediaStream.addTrack(stream.track);
                            audioElement.srcObject = mediaStream;
                            audioElement
                            .play()
                            .catch((error) => console.error("audioElem.play() failed", error));
                        }
                    }
                }

                // Open Mic Button Event Listener
                openMicButton.addEventListener("click", async () => {
                    if(isMicOn){
                        // Disable Mic in Meeting
                        meeting?.muteMic();
                        openMicButton.classList.toggle('hidden');
                        closeMicButton.classList.toggle('hidden');
                    }

                    isMicOn = !isMicOn;
                });
                
                // Close Mic Button Event Listener
                closeMicButton.addEventListener("click", async () => {
                    if (!isMicOn){
                        // Disable Mic in Meeting
                        meeting?.unmuteMic();
                        openMicButton.classList.toggle('hidden');
                        closeMicButton.classList.toggle('hidden');
                    }

                    isMicOn = !isMicOn;
                });

                // Close Web Cam Button Event Listener
                closeWebCamButton.addEventListener("click", async () => {
                    if (isWebCamOn) {
                        // Disable Webcam in Meeting
                        meeting?.disableWebcam();
                        openWebCamButton.classList.toggle('hidden');
                        closeWebCamButton.classList.toggle('hidden');
                    }
                    
                    isWebCamOn = !isWebCamOn;
                });

                // Open Web Cam Button Event Listener
                openWebCamButton.addEventListener("click", async () => {
                    if(!isWebCamOn) {
                        // Enable Webcam in Meeting
                        meeting?.enableWebcam();
                        openWebCamButton.classList.toggle('hidden');
                        closeWebCamButton.classList.toggle('hidden');
                    }

                    isWebCamOn = !isWebCamOn;
                });

                // Start Hls Button Event Listener
                startHlsButton.addEventListener("click", async () => {
                    meeting?.startHls({
                        layout: {
                            type: "SPOTLIGHT",
                            priority: "PIN",
                            gridSize: "20",
                        },
                        theme: "LIGHT",
                        mode: "video-and-audio",
                        quality: "high",
                        orientation: "landscape",
                    });

                    startHlsButton.classList.toggle('hidden');

                    Livewire.dispatch('live-update', { status: 'started' });
                });

                if(role == 'host' && meetingId != null){
                    initializeMeeting(Constants.modes.CONFERENCE);
                }else{
                    initializeMeeting(Constants.modes.VIEWER);
                }

            </script>
        @endpush
    @else
        loading...
    @endif
</div>

