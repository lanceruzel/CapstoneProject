<div class="w-full max-md:h-full md:h-[calc(100vh-9rem)]">
    <h1 class="text-2xl font-semibold pb-3 max-md:pt-2">{{ $name }}'s livestream</h1> 

    <div class="grid grid-cols-12 w-full h-full gap-3">
        <div class="col-span-12 lg:col-span-7 2xl:col-span-8" wire:ignore>
            <!-- Loading Info -->
            <div class="w-full h-[24rem] flex items-center bg-white shadow rounded-lg justify-center gap-2" id="loadingInfoVideo">
                <span>
                    <x-icon name="arrow-path" class="w-7 h-7 animate-spin" />
                </span>
                
                <span class="text-xl font-semibold">
                    Loading...
                </span>
            </div>

            <!-- Livestream pause Info -->
            <div class="w-full h-[24rem] hidden items-center bg-white shadow rounded-lg justify-center gap-2 p-5" id="pausedInfoVideo">
                <span class="text-xl font-semibold text-center">
                    Live stream has been stopped wait for the host to start the stream again
                </span>
            </div>

            <div class="bg-white hidden rounded-lg shadow" id="videoContainer"></div>

            <div class="w-full flex-col items-center justify-center gap-3 py-3 bg-white shadow rounded-lg mt-3" id="speakerView" style="display: none">
                <h3 id="hlsStatusHeading"></h3>

                <div class="flex items-center justify-center gap-3">
                    <x-mini-button id="leaveBtn" negative rounded icon="power" wire:click='leaveConfirmation' />

                    <x-mini-button class="hidden" id="openMicBtn" negative rounded icon="microphone" />
                    <x-mini-button id="closeMicBtn" rounded icon="microphone" />

                    <x-mini-button class="hidden" id="openWebCamBtn" rounded icon="video-camera-slash" />
                    <x-mini-button id="closeWebCamBtn" rounded icon="video-camera" />

                    <x-mini-button class="hidden" id="stopHlsBtn" rounded negative icon="stop" />
                    <x-mini-button id="startHlsBtn" rounded positive icon="play" />
                </div>
            </div>
        </div>

        <!-- Chat section -->
        <div class="col-span-12 lg:col-span-5 2xl:col-span-4">
            <livewire:Livestream.livestream-chat-container :meetingId="$meetingId" />
        </div>
    </div>

    @push('scripts')
        <script>
            let role = @js($role);
            let meetingId = @js($meetingId);
            let name = @js($name);

            let watching = 0;

            const watchingCount = document.getElementById("watchingCount");

            const pausedVideoContainer = document.getElementById("pausedInfoVideo");
            const videoContainer = document.getElementById("videoContainer");
            const loadingInfoVideo = document.getElementById("loadingInfoVideo");
            const hlsStatusHeading = document.getElementById("hlsStatusHeading");
            const leaveButton = document.getElementById("leaveBtn");
            const startHlsButton = document.getElementById("startHlsBtn");
            const stopHlsButton = document.getElementById("stopHlsBtn");

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
                window.VideoSDK.config(TOKEN);
            
                meeting = window.VideoSDK.initMeeting({
                    meetingId: meetingId, // required
                    name: name, // required
                    mode: mode,
                });
            
                meeting.join();
            
                meeting.on("meeting-joined", () => {
                    if (meeting.hlsState === Constants.hlsEvents.HLS_STOPPED) {
                        hlsStatusHeading.textContent = "Livestream haven't started yet";
                    } else {
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

            document.addEventListener('livewire:init', () => {
                Livewire.on('end-live', (event) => {
                    meeting?.leave();
                });
            });

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
                stopHlsButton.classList.toggle('hidden');
            });

            // Stop Hls Button Event Listener
            stopHlsButton.addEventListener("click", async () => {
                meeting?.stopHls();

                startHlsButton.classList.toggle('hidden');
                stopHlsButton.classList.toggle('hidden');
            });

            if(role == 'host' && meetingId != null){
                initializeMeeting(Constants.modes.CONFERENCE);
            }else{
                initializeMeeting(Constants.modes.VIEWER);
            }

        </script>
    @endpush
</div>

