let canvas, drawingContext, model, gestureEstimator;
const videoWidth = 600;
const videoHeight = 400;

const host = "http://localhost:3000";

async function backAction() {
  const result = await fetch(host + "/back", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
  });
  return await result.json();
}

async function nextAction() {
  const result = await fetch(host + "/next", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
  });
  return await result.json();
}

const btnOpen = document.getElementById("btn-open");
btnOpen.addEventListener("click", async (ev) => {
  const currentState = ev.target.textContent.trim();
  if (currentState == "Open Slide") {
    const slideUrl = document.getElementById("slide-url").getAttribute("value");
    const result = await fetch(host + "/launch", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        slideUrl: slideUrl,
      }),
    });
    const data = await result.json();
    if ((data.status = "success")) {
      ev.target.textContent = "Close Slide";
    }
  } else {
    const result = await fetch(host + "/close", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (result.ok) {
      ev.target.textContent = "Open Slide";
    }
  }
});

async function loadWebcam(width, height) {
  const video = document.getElementById("webcam");
  video.muted = true;

  const stream = await navigator.mediaDevices.getUserMedia({
    audio: false,
    video: { facingMode: "user", width, height, frameRate: { max: 30 } },
  });

  video.srcObject = stream;

  return new Promise((resolve) => {
    video.onloadedmetadata = () => resolve(video);
  });
}

async function loadVideo() {
  const video = await loadWebcam();
  video.play();
  return video;
}

function drawLines(points) {
  drawingContext.strokeStyle = "white";
  drawingContext.lineWidth = 2;

  drawingContext.save();
  drawingContext.translate(canvas.width, 0);
  drawingContext.scale(-1, 1);

  drawingContext.beginPath();
  drawingContext.moveTo(points[0][0], points[0][1]);
  for (let i = 1; i < points.length; i++) {
    drawingContext.lineTo(points[i][0], points[i][1]);
  }
  drawingContext.stroke();
  drawingContext.restore();
}

function drawPoints(points) {
  drawingContext.fillStyle = "red";

  for (let i = 1; i <= 4; i++) {
    drawingContext.save();
    drawingContext.translate(canvas.width, 0);
    drawingContext.scale(-1, 1);
    drawingContext.beginPath();
    drawingContext.arc(points[i][0], points[i][1], 6, 0, Math.PI * 2);
    drawingContext.fill();
    drawingContext.restore();
  }
}

function drawKeypoints(landmarks) {
  const fingerIndices = {
    thumbs: [0, 1, 2, 3, 4],
    index: [0, 5, 6, 7, 8],
    middle: [0, 9, 10, 11, 12],
    ring: [0, 13, 14, 15, 16],
    pinky: [0, 17, 18, 19, 20],
  };

  const fingers = Object.keys(fingerIndices);

  for (let finger of fingers) {
    const points = fingerIndices[finger].map((index) => landmarks[index]);
    drawPoints(points);
    drawLines(points);
  }
}

//  Pre-defined data from Handpose
const knownGestures = [fp.Gestures.VictoryGesture, fp.Gestures.ThumbsUpGesture];

async function continuouslyDetect(video) {
  // Load tensorflow model hand pose
  model = await handpose.load();
  gestureEstimator = new fp.GestureEstimator(knownGestures);

  async function detect() {
    const predictions = await model.estimateHands(video);
    const isLaunch = btnOpen.textContent.includes("Close Slide");
    let action = localStorage.getItem("action");

    const gestureNameEl = document.getElementById("gesture-name");
    let gestureName = "Detecting...";

    // Draw image video to webcam
    drawingContext.save();
    drawingContext.translate(canvas.width, 0);
    drawingContext.scale(-1, 1);
    drawingContext.drawImage(
      video,
      0,
      0,
      videoWidth,
      videoHeight,
      0,
      0,
      canvas.width,
      canvas.height,
    );
    drawingContext.restore();

    // Check hand detection
    if (predictions.length > 0) {
      const prediction = predictions[0];
      if (Object.keys(prediction).includes("landmarks")) {
        drawKeypoints(prediction.landmarks);
        // Estimate hand pose
        const est = gestureEstimator.estimate(prediction.landmarks, 9);
        if (est.gestures.length > 0) {
          let finalGesture = est.gestures.reduce((g1, g2) =>
            g1.score > g2.score ? g1 : g2,
          );

          if (finalGesture.score > 9.85) {
            if (finalGesture.name == "thumbs_up") {
              gestureName = "Thumbs up! 👍";

              if (isLaunch && action == null) {
                const result = await nextAction();
                localStorage.setItem("action", result.action);
              }
            } else if (finalGesture.name == "victory") {
              gestureName = "Victory! ✌️";

              if (isLaunch && action == null) {
                const result = await backAction();
                localStorage.setItem("action", result.action);
              }
            }
          }
        }
      }
    } else {
      if (action != null) {
        localStorage.removeItem("action");
      }
    }

    gestureNameEl.textContent = gestureName;
    requestAnimationFrame(detect);
  }

  detect();
}

async function main() {
  let video = await loadVideo();

  canvas = document.getElementById("canvas");
  canvas.width = videoWidth;
  canvas.height = videoHeight;
  drawingContext = canvas.getContext("2d");

  // Continuosly detect model and draw in the canvas
  continuouslyDetect(video);
}

main();
