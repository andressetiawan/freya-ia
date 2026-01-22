<?php
require_once 'utils.php';
require_once 'database.php';

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: homepage.php");
    exit();
}

$slide_id = $_GET["id"];
$slide = query("SELECT * FROM slides WHERE id = '$slide_id'")[0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- CDN Tensorflow -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.22.0/dist/tf.min.js"></script>
    <!-- CDN Hand pose detection -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/hand-pose-detection@2.0.1/dist/hand-pose-detection.min.js"></script>
    <title>Detail</title>
</head>

<body>
    <main class="flex flex-col justify-center items-center">
        <video class="w-[600px] h-[400px] bg-gray-300 rounded-xl mt-5" id="webcam" autoplay playsinline></video>

        <div class="bg-black text-white py-5 px-8 text-lg mt-3 rounded-full">Gesture name</div>
    </main>


    <script>
        const webcam = document.getElementById("webcam");
        if (navigator.mediaDevices.getUserMedia) {
            const stream = navigator.mediaDevices.getUserMedia({
                video: {
                    width: 600,
                    height: 400
                }
            }).then((stream) => {
                webcam.srcObject = stream;
            })
        }
    </script>
</body>

</html>