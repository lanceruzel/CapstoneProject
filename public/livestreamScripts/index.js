// getting Elements from Dom

const videoContainer = document.getElementById("videoContainer");

// declare Variables
let meeting = null;
let meetingId = "";
let isMicOn = false;
let isWebCamOn = false;

const Constants = VideoSDK.Constants;

function initializeMeeting() {}

function createLocalParticipant() {}

function createVideoElement() {}

function createAudioElement() {}

function setTrack() {}

// Create Meeting Button Event Listener


// Initialize meeting
function initializeMeeting(mode) {
    window.VideoSDK.config(TOKEN);
  
    meeting = window.VideoSDK.initMeeting({
      meetingId: meetingId, // required
      name: "Thomas Edison", // required
      mode: mode,
    });
  
    meeting.join();
  
    meeting.on("meeting-joined", () => {
      textDiv.textContent = null;
  
      document.getElementById("grid-screen").style.display = "block";
      document.getElementById(
        "meetingIdHeading"
      ).textContent = `Meeting Id: ${meetingId}`;
  
      if (meeting.hlsState === Constants.hlsEvents.HLS_STOPPED) {
        hlsStatusHeading.textContent = "HLS has not stared yet";
      } else {
        hlsStatusHeading.textContent = `HLS Status: ${meeting.hlsState}`;
      }
  
      if (mode === Constants.modes.CONFERENCE) {
        // we will pin the local participant if he joins in `CONFERENCE` mode
        meeting.localParticipant.pin();
  
        document.getElementById("speakerView").style.display = "block";
      }
    });
  
    meeting.on("meeting-left", () => {
      videoContainer.innerHTML = "";
    });
  
    meeting.on("hls-state-changed", (data) => {
      const { status } = data;
  
      hlsStatusHeading.textContent = `HLS Status: ${status}`;
  
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
    videoFrame.appendChild(videoElement);
  
    let displayName = document.createElement("div");
    displayName.innerHTML = `Name : ${name}`;
  
    videoFrame.appendChild(displayName);
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
      videoElm
        .play()
        .catch((error) =>
          console.error("videoElem.current.play() failed", error)
        );
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
  
  