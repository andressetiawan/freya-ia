<?php
require_once 'utils.php';
require_once 'database.php';

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_POST["delete_slide"])) {
    $slide_id = $_POST["slide_id"];
    $result = query("DELETE FROM slides WHERE id = '$slide_id'");
    if (!$result) {
        echo "<script>alert('Failed to delete slide.')</script>";
    } else {
        echo "<script>alert('Slide deleted successfully.')</script>";
    }
}

if (isset($_POST["create_slide"])) {
    $slide_title = $_POST["slide_title"];
    $slide_url = $_POST["slide_url"];
    $result = query("INSERT INTO slides(title, url) VALUES('$slide_title', '$slide_url');");
    if (!$result) {
        echo "<script>alert('Failed to create new slide')</script>";
    } else {
        echo "<script>alert('Successfully create new slide')</script>";
    }
}

$response = file_get_contents(HOST . "slides.php");
$slides = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Homepage</title>
</head>

<body>
    <main class="relative w-xl mx-auto px-4">
        <nav class="flex justify-center items-center">
            <div class="mt-5">
                <h1 class="text-base">Search URL/Title</h1>
                <div class="w-96 mt-1">
                    <?= component(
                        'input',
                        [
                            'type' => 'text',
                            'name' => 'title',
                            'placeholder' => 'Enter slides title or URL',
                            'require' => true
                        ]
                    ); ?>
                </div>
            </div>

            <form class="absolute top-2 right-0" action="" method="post">
                <button class="cursor-pointer p-3 bg-[#130f40] text-white rounded-md w-full" name="logout" type="submit">
                    <img class="w-5 h-5 object-contain" src="./assets/logout.png">
                </button>
                <p class="text-sm">Logout</p>
            </form>
        </nav>

        <section id="sliders-container" class="grid grid-cols-3 gap-5 mt-8">
            <?php foreach ($slides as $slide) : ?>
                <a href="./detail.php?id=<?= $slide['id'] ?>" class="card relative">
                    <form class="absolute -right-2 top-2 bg-rose-600 w-10 h-10 rounded-full" action="" method="post">
                        <input type="hidden" name="slide_id" value="<?= $slide['id'] ?>">
                        <button name="delete_slide" class="cursor-pointer w-full h-full font-bold text-white" type="submit">X</button>
                    </form>
                    <h1 class="text-lg font-semibold"><?= $slide['title'] ?></h1>
                    <img class="w-full rounded-xl" src="https://placehold.co/400">
                    <p class="tracking-tighter text-sm mt-1">Latest view: <?= formatDateShort($slide['latestView']) ?></p>
                </a>
            <?php endforeach; ?>
        </section>

        <section class="mt-5">
            <button id="btn-open-dialog" class="flex justify-center items-center gap-3 cursor-pointer p-3 bg-[#130f40] active:bg-[#0e0b2e] text-white rounded-md w-full">
                <img class="w-3 h-3" src="./assets/create.png">
                Create new slide
            </button>

            <div id="dialog-overlay" class="hidden fixed inset-0 bg-black/75 z-50 flex justify-center items-center">
                <form method="post" id="dialog-container" class="bg-white p-5 rounded">
                    <div class="flex justify-between gap-3 items-center w-[25rem] mb-3">
                        <h1>Create new slide</h1>
                        <button id="btn-close-dialog" class="cursor-pointer p-3 bg-[#130f40] text-white font-bold rounded-full w-8 h-8 text-sm flex justify-center items-center">X</button>
                    </div>

                    <div class="flex flex-col gap-3">
                        <?= component(
                            'input',
                            [
                                'type' => 'text',
                                'name' => 'slide_title',
                                'placeholder' => 'Slide title',
                                'require' => true
                            ]
                        ); ?>
                        <?= component(
                            'input',
                            [
                                'type' => 'text',
                                'name' => 'slide_url',
                                'placeholder' => 'Slide URL',
                                'require' => true
                            ]
                        ); ?>

                        <button type="submit" name="create_slide" class="cursor-pointer p-3 bg-[#130f40] text-white font-bold rounded-md">Create</button>
                    </div>

                </form>
            </div>
        </section>
    </main>

    <script>
        const btnOpenDialog = document.getElementById("btn-open-dialog")
        const btnCloseDialog = document.getElementById("btn-close-dialog")
        const dialogOverlay = document.getElementById("dialog-overlay")
        btnOpenDialog.addEventListener("click", (ev) => {
            ev.preventDefault();
            dialogOverlay.classList.remove("hidden")
        })

        btnCloseDialog.addEventListener("click", (ev) => {
            ev.preventDefault();
            dialogOverlay.classList.add("hidden")
        })


        function formatDateShort(datetime) {
            const date = new Date(datetime);

            const day = date.getDate(); // j
            const month = date.toLocaleString('en-GB', {
                month: 'short'
            });
            const year = date.getFullYear().toString().slice(-2);
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');

            return `${day} ${month} ${year} ${hours}:${minutes}`;
        }

        const input = document.querySelector("input[name='title']");
        let slidersContainer = document.getElementById("sliders-container");
        input.addEventListener("keyup", async (ev) => {
            const value = ev.target.value.toLowerCase();
            const response = await fetch(`http://localhost/freya-ia/api/slides.php?query=${value}`)
            const slides = await response.json();

            slidersContainer.innerHTML = ""
            slides.map((slide) => (
                slidersContainer.innerHTML += `<a href="./detail.php?id=${slide.id}" class="card relative">
                    <form class="absolute -right-2 top-2 bg-rose-600 w-10 h-10 rounded-full" action="" method="post">
                        <input type="hidden" name="slide_id" value="${slide.id}">
                        <button name="delete_slide" class="cursor-pointer w-full h-full font-bold text-white" type="submit">X</button>
                    </form>
                    <h1 class="text-lg font-semibold">${slide.title}</h1>
                    <img class="w-full rounded-xl" src="https://placehold.co/400">
                    <p class="tracking-tighter text-sm mt-1">Latest view: ${formatDateShort(slide.latestView)}</p>
                </a>`
            ))
        })
    </script>

</body>

</html>