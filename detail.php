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

    <!-- CDN Fingerpose -->
    <script src="https://cdn.jsdelivr.net/npm/fingerpose@0.1.0/dist/fingerpose.min.js"></script>

    <!-- CDN Tensorflow -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-core@4.22.0/dist/tf-core.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-converter@4.22.0/dist/tf-converter.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-backend-webgl@4.22.0/dist/tf-backend-webgl.min.js"></script>

    <!-- CDN Hand pose detection -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/handpose@0.1.0/dist/handpose.min.js"></script>
    <title>Detail</title>
</head>

<body>
    <main class="flex flex-col justify-center items-center">
        <div class="relative w-[600px] h-[400px] mt-5" id="video-container">
            <video class="opacity-0 absolute top-0 left-0 w-full h-full" id="webcam" autoplay playsinline></video>
            <canvas class="absolute top-0 left-0 w-full h-full" id="canvas"></canvas>
            <a class="relative top-8 left-5 bg-black text-white py-3 px-5 rounded-xl" href="./homepage.php">Back</a>
        </div>

        <div class="bg-black text-white py-5 px-8 text-lg my-3 rounded-full">
            <p id="gesture-name">Gesture name</p>
        </div>

        <div class="border w-[500px] p-3 rounded-lg">
            <h1 class="font-bold text-lg">Saved poses</h1>
            <div class="text-lg mt-3">Next (👍)</div>
            <div class="text-lg">Previous (✌️)</div>
        </div>

        <div id="btn-open" class="bg-black text-white py-5 px-8 text-lg mt-3 rounded-full cursor-pointer">
            <input type="hidden" id="slide-url" value="<?= $slide["url"] ?>">
            Open Slide
        </div>
    </main>

    <script src="./script.js"></script>
</body>

</html>