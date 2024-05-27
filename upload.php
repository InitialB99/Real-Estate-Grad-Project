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
    $file = $_FILES['image'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Property Image</title>
    <link rel="stylesheet" href="output.css">
</head>
<body class="bg-gray-100">
    <header class="bg-blue-500 shadow-sm p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Ravic</h1>
            <nav>
                <ul class="list-none flex space-x-8">
                    <li><a href="index.php" class="hover:shadow-lg">Home</a></li>
                    <li><a href="properties.php" class="hover:shadow-lg">Properties</a></li>
                    <li><a href="contact.php" class="hover:shadow-lg">Contact</a></li>
                    <li><a href="about.php" class="hover:shadow-lg">About Us</a></li>
                    <li><a href="log_in.php" class="font-bold hover:shadow-lg">Log In</a></li>
                    <li><a href="sign_up.php" class="font-bold hover:shadow-lg">Sign Up</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto py-12">
        <section class="p-12 bg-white rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-4 text-center">Upload Property Image</h1>
            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="title" class="block text-lg font-medium">Property Title</label>
                    <input type="text" value="<?php echo $title ?>" name="title" id="title" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="description" class="block text-lg font-medium">Description</label>
                    <textarea name="description" value="<?php echo $description ?>" id="description" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required></textarea>
                </div>
                <div>
                    <label for="location" class="block text-lg font-medium">Location</label>
                    <input type="text" name="location" value="<?php echo $location ?>" id="location" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="price" class="block text-lg font-medium">Price</label>
                    <input type="number" name="price" value="<?php echo $price ?>" id="price" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="rooms" class="block text-lg font-medium">Rooms</label>
                    <input type="number" name="rooms" value="<?php echo $rooms ?>" id="rooms" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="bathrooms" class="block text-lg font-medium">Bathrooms</label>
                    <input type="number" name="bathrooms" value="<?php echo $bathrooms ?>" id="bathrooms" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="image" class="block text-lg font-medium">Upload Image</label>
                    <input type="file" name="image" id="image" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" accept="image/*" required>
                </div>
                <div>
                    <label for="image2" class="block text-lg font-medium">Upload Image 2</label>
                    <input type="file" name="image2" id="image2" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" accept="image/*" required>
                </div>
                <input type="hidden" name="agentid" value="<?php echo $user_data['id']; ?>">
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700">Upload</button>
            </form>
        </section>
    </main>
</body>
</html>
