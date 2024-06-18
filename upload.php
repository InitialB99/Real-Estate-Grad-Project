<?php
session_start();
require_once 'Classes/upload.php';
require_once 'Classes/checks.php';

    $id = $_SESSION['realestate_sessionid'];
    $checks = new checks();
    $user_data = $checks->check_agent($id);
    if(!$user_data){
        header("Location: log_in.php");
    }

$result = "";
$title = "";
$description = "";
$location = "";
$price = "";
$rooms = "";
$bathrooms = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $upload = new Upload();
    $result = $upload->evaluate($_POST,$_FILES);

    //DEBUG:
    //print_r($_FILES);
    //print_r($_POST);

    if ($result !== true) {
        echo "<div class='text-center text-sm text-white bg-gray-700 rounded-lg p-4'>";
        echo "The following errors occurred: <br><br>";
        echo $result;
        echo "</div>";
    } else { 
        header("Location: agent_dashboard.php");
        exit();
    }

    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $rooms = $_POST['rooms'];
    $bathrooms = $_POST['bathrooms'];
    $listing_type = $_POST['listing_type'];
    $file = $_FILES['image'];
    $file = $_FILES['image2'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Property Image</title>
    <link rel="stylesheet" href="output.css">
    <link rel="stylesheet" href="background.css">
</head>
<body class="page-background">
    <header>
    <nav class="bg-blue-500/75 border-b">
        <div class="max-w-screen-xxl flex flex-wrap items-center justify-between mx-auto p-2">
            <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="./logo.png" class="h-8" alt="Logo" />
            <span class="text-2xl font-semibold text-white">Buna, <?php echo htmlspecialchars($user_data['first_name'])?>!</span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center rounded-lg md:hidden" aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Meniu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
            </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul class="flex flex-col py-2 px-8 mt-2 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 relative">
                <li>
                <a href="agent_dashboard.php" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Inapoi</a>
                </li>
                <li>
                <a href="#" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Contact</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    </header>

    <main class="container mx-auto py-12">
        <section class="p-12 bg-white bg-opacity-50 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-4 text-center">Incarca o noua proprietate!</h1>
            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="listing_type" class="block text-lg font-medium">Tip ofertă</label>
                    <select name="listing_type" id="listing_type" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="De inchiriat">De închiriat</option>
                        <option value="De vanzare">De vânzare</option>
                    </select>
                </div>
                <div>
                    <label for="title" class="block text-lg font-medium">Titlu</label>
                    <input type="text" value="<?php echo $title ?>" name="title" id="title" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="description" class="block text-lg font-medium">Descriere</label>
                    <textarea name="description" value="<?php echo $description ?>" id="description" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required></textarea>
                </div>
                <div>
                    <label for="location" class="block text-lg font-medium">Locatie</label>
                    <input type="text" name="location" value="<?php echo $location ?>" id="location" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="price" class="block text-lg font-medium">Suma in Euro</label>
                    <input type="number" name="price" value="<?php echo $price ?>" id="price" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="rooms" class="block text-lg font-medium">Camere</label>
                    <input type="number" name="rooms" value="<?php echo $rooms ?>" id="rooms" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="bathrooms" class="block text-lg font-medium">Bai</label>
                    <input type="number" name="bathrooms" value="<?php echo $bathrooms ?>" id="bathrooms" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="image" class="block text-lg font-medium">Incarca Imagine</label>
                    <input type="file" name="image" id="image" class="w-full px-4 py-4 border rounded-lg focus:outline-none focus:border-customBlue-500" accept="image/*" required>
                </div>
                <div>
                    <label for="image2" class="block text-lg font-medium">Incarca Imagine 2</label>
                    <input type="file" name="image2" id="image2" class="w-full px-4 py-4 border rounded-lg focus:outline-none focus:border-customBlue-500" accept="image/*" required>
                </div>
                <input type="hidden" name="agentid" value="<?php echo $user_data['id']; ?>">
                <button type="submit" class="w-full bg-green-500 text-white px-4 py-4 rounded-lg font-bold hover:bg-green-700">Adauga</button>
            </form>
        </section>
    </main>
</body>
</html>
