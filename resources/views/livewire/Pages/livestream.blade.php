<x-layouts.main-layout>
    <div class="h-full w-full md:-my-10 max-md:-mt-3 max-md:-mb-12 max-md:!h-fit p-3 md:p-5 max-md:pb-14">
        <h1 class="text-2xl font-semibold pb-3 max-md:pt-2 !pt-5">{{ $name }}'s livestream</h1> 

        <div class="grid grid-cols-12 w-full h-full gap-3">
            <div class="col-span-12 md:col-span-7">
                <div class="bg-white w-full h-fit rounded-lg flex items-center justify-center max-md:h-[500px] shadow" id="videoContainer"></div>

                <div class="w-full flex-col items-center justify-center gap-3 py-3 bg-white shadow rounded-lg mt-3" id="speakerView" style="display: none">
                    <h3 id="hlsStatusHeading"></h3>

                    <div class="flex items-center justify-center gap-3">
                        <x-mini-button id="leaveBtn" negative rounded icon="power" />
                        <x-mini-button id="toggleMicBtn" rounded icon="microphone" />
                        <x-mini-button id="toggleWebCamBtn" rounded icon="video-camera" />
                        <x-mini-button id="stopHlsBtn" rounded negative icon="stop" />
                        <x-mini-button id="startHlsBtn" rounded positive icon="play" />
                    </div>
                </div>
            </div>

            <!-- Chat section -->
            <div class="col-span-12 md:col-span-5">
                <div class="w-full h-full bg-white rounded-lg shadow">
                    <div class="p-3 shadow">
                        <p class="text-xl font-semibold">Chats</p>
                    </div>

                    <div class="p-5 text-sm font-medium space-y-5 overflow-y-auto !h-[calc(100vh-120px)]">
                        <!-- sent -->
                        <div class="flex flex-col gap-1 items-end">
                            {{-- <img src="https://i.pravatar.cc" alt="" class="w-5 h-5 rounded-full shadow"> --}}
                            <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gradient-to-tr from-sky-500 to-blue-500 text-white shadow break-words text-wrap hyphens-auto space-y-3 text-sm">
                                <span>Testing</span>
                            </div>
                                <small class="pe-2">12:34 PM</small>
                        </div> 

                        <!-- received -->
                        @for ($i = 0; $i < 10; $i++)
                            <div>
                                <div class="ms-12">
                                    <small class="font-semibold">Juan Dela Cruz</small>
                                </div>

                                <div class="flex gap-3">
                                    <img src="https://i.pravatar.cc" alt="" class="w-9 h-9 rounded-full shadow">
                                    <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gray-100 break-words !text-wrap hyphens-auto space-y-3 text-sm">
                                        <span>Testingdsadas</span>
                                    </div>
                                </div>

                                <div class="!ms-14">
                                    <small>12:34 PM</small>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="flex items-center md:gap-4 gap-2 p-3 overflow-hidden">
                        <x-input label="" wire:model='' placeholder="Message" />
                        
                        <x-mini-button rounded flat black icon="paper-airplane" wire:click='sendMessage' />
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let role = @js($role);
            let meetingId = @js($meetingId);
            let name = @js($name);
            console.log(meetingId);

            const videoContainer = document.getElementById("videoContainer");
            const hlsStatusHeading = document.getElementById("hlsStatusHeading");
            const leaveButton = document.getElementById("leaveBtn");
            const startHlsButton = document.getElementById("startHlsBtn");
            const stopHlsButton = document.getElementById("stopHlsBtn");
            const toggleMicButton = document.getElementById("toggleMicBtn");
            const toggleWebCamButton = document.getElementById("toggleWebCamBtn");

            const Constants = VideoSDK.Constants;

            function initializeMeeting() {}
            function createLocalParticipant() {}
            function createVideoElement() {}
            function createAudioElement() {}
            function setTrack() {}

            // Initialize meeting
            function initializeMeeting(mode) {
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
                        // we will pin the local participant if he joins in `CONFERENCE` mode
                        meeting.localParticipant.pin();
                
                        document.getElementById("speakerView").style.display = "flex";
                    }
                });
            
                meeting.on("meeting-left", () => {
                    videoContainer.innerHTML = "";
                });
            
                meeting.on("hls-state-changed", (data) => {
                    const { status } = data;

                    switch(status){
                        case "HLS_STARTING":
                            hlsStatusHeading.textContent = 'Livestream is now starting.';
                            break;

                        case "HLS_STARTED":
                            hlsStatusHeading.textContent = 'Livestream has started.';
                            break;

                        case "HLS_PLAYABLE":
                            hlsStatusHeading.textContent = 'You are live now!';
                            break;

                        case "HLS_STOPPED":
                            hlsStatusHeading.textContent = 'Livestream has stopped or paused!';
                            break;

                        default:
                            hlsStatusHeading.textContent = `${status}`;
                            break;
                    }

                    if (mode === Constants.modes.VIEWER) {
                        if (status === Constants.hlsEvents.HLS_PLAYABLE) {
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
                        }
                
                        if (status === Constants.hlsEvents.HLS_STOPPING) {
                            videoContainer.innerHTML = "";
                        }
                    }
                });
            
                if (mode === Constants.modes.CONFERENCE) {
                    // creating local participant
                    createLocalParticipant();
                
                    // setting local participant stream
                    meeting.localParticipant.on("stream-enabled", (stream) => {
                        setTrack(stream, null, meeting.localParticipant, true);
                    });
                
                    // participant joined
                    meeting.on("participant-joined", (participant) => {
                        if (participant.mode === Constants.modes.CONFERENCE) {
                            participant.pin();
                    
                            let videoElement = createVideoElement(
                                participant.id,
                                participant.displayName
                            );
                    
                            participant.on("stream-enabled", (stream) => {
                                setTrack(stream, audioElement, participant, false);
                            });
                    
                            let audioElement = createAudioElement(participant.id);
                            videoContainer.appendChild(videoElement);
                            videoContainer.appendChild(audioElement);
                        }
                    });
                
                    // participants left
                    meeting.on("participant-left", (participant) => {
                        let vElement = document.getElementById(`f-${participant.id}`);
                        vElement.remove(vElement);
                
                        let aElement = document.getElementById(`a-${participant.id}`);
                        aElement.remove(aElement);
                    });
                }
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

            // leave Meeting Button Event Listener
            leaveButton.addEventListener("click", async () => {
                meeting?.leave();
                document.getElementById("grid-screen").style.display = "none";
                document.getElementById("join-screen").style.display = "block";
            });

            // Toggle Mic Button Event Listener
            toggleMicButton.addEventListener("click", async () => {
                if (isMicOn) {
                    // Disable Mic in Meeting
                    meeting?.muteMic();
                } else {
                    // Enable Mic in Meeting
                    meeting?.unmuteMic();
                }
                isMicOn = !isMicOn;
            });

            // Toggle Web Cam Button Event Listener
            toggleWebCamButton.addEventListener("click", async () => {
                if (isWebCamOn) {
                    // Disable Webcam in Meeting
                    meeting?.disableWebcam();

                    let vElement = document.getElementById(`f-${meeting.localParticipant.id}`);
                    vElement.style.display = "none";
                } else {
                    // Enable Webcam in Meeting
                    meeting?.enableWebcam();

                    let vElement = document.getElementById(`f-${meeting.localParticipant.id}`);
                    vElement.style.display = "inline";
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
            });

            // Stop Hls Button Event Listener
            stopHlsButton.addEventListener("click", async () => {
                meeting?.stopHls();
            });

            if(role == 'host' && meetingId != null){
                initializeMeeting(Constants.modes.CONFERENCE);
            }else{
                initializeMeeting(Constants.modes.VIEWER);
            }

        </script>
    @endpush
</x-layouts.main-layout>