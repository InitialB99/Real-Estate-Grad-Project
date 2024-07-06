<?php
session_start();

require_once 'Classes/connect.php';
require_once 'Classes/manageproperty.php';
require_once 'Classes/user.php';
require_once 'Classes/checks.php';

//TAKES SESSIONID
$id = $_SESSION['realestate_sessionid'];
$db = new Database();
$manageProperty = new manageProperty;
$checks = new checks();

$user_data = $checks->check_agent($id);
if(!$user_data){
    header("Location: log_in.php");
}

//TAKES PROPERTYID
if(isset($_GET['id'])){
    $propertyid = $_GET['id'];
} 
if(empty($propertyid)){
    die('Property ID is missing.');
}

//TAKES USER ID
$userid = $user_data['id'];

//TAKES PROPERTY DATA
$query = 'select * from properties where propertyid = ? and agentid = ?';
$params = [$propertyid, $userid];
$property = $db->read($query, $params)[0];
if(!$property){
    die('Property not found.');
}

//DEBUGGING
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
}

// UPDATE PROPERTY
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_property'])) {
    $result = $manageProperty->update_property($propertyid, $userid, $_POST, $_FILES);
    if($result === true) {
        header("Location: agent_dashboard.php");
        exit();
    } else {
        echo "<div class='text-center text-sm text-white bg-gray-700 rounded-lg p-4'>";
        echo "The following errors occurred: <br><br>";
        echo $result;
        echo "</div>";
    }
}

// DELETE PROPERTY
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_property'])) {
    $result = $manageProperty->delete_property($propertyid);
    if($result === true) {
        header("Location: agent_dashboard.php");
        exit();
    } else {
        echo "<div class='text-center text-sm text-white bg-gray-700 rounded-lg p-4'>";
        echo "The following errors occurred: <br><br>";
        echo $result;
        echo "</div>";
    }
}

//FETCH COMMENTS
$query = "SELECT * FROM comments WHERE cpropertyid = ? ORDER BY date DESC";
$params = [$propertyid];
$comments = $db->read($query, $params);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editează Proprietatea</title>
    <link href="CSS/output.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/background.css">
    <script>
    function confirmDelete(event) {
        event.preventDefault(); // Prevent the form from submitting immediately
        const userConfirmed = confirm("Esti sigur ca vrei sa stergi aceasta proprietate?");
        if (userConfirmed) {
            console.log("User confirmed deletion");
            event.target.closest('form').submit(); // Submit the form if the user confirmed
        } else {
            console.log("User canceled deletion");
        }
    }
</script>
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

    <main class="container mx-auto py-8">

        <section class="property-details flex flex-wrap">
            <div class="images w-full md:w-1/2 mb-8 md:mb-0">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image 1" class="w-auto h-auto object-cover mb-2 rounded-lg">
                        </div>
                        <div class="swiper-slide">
                            <img src="<?php echo htmlspecialchars($property['image2']); ?>" alt="Property Image 2" class="w-full h-auto object-cover rounded-lg">
                        </div>
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
            <div class="details w-full md:w-1/2 px-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-xl font-bold mb-2">Editează Proprietatea</h3>
                    <form action="update_property.php?id=<?php echo htmlspecialchars($propertyid); ?>" method="post" enctype="multipart/form-data">
                        <div>
                            <label for="listing_type" class="block text-lg font-medium">Tip ofertă:</label>
                            <select name="listing_type" id="listing_type" class="appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-lg leading-tight focus:outline-none focus:border-customOrange-500 focus:bg-white focus:text-gray-700" required>
                                <option value="De inchiriat" <?php echo $property['listing_type'] == 'De inchiriat' ? 'selected' : ''; ?>>De închiriat</option>
                                <option value="De vanzare" <?php echo $property['listing_type'] == 'De vanzare' ? 'selected' : ''; ?>>De vânzare</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.516 7.548a.713.713 0 00-.394 1.048L9.606 15.45c.3.495.997.495 1.296 0l4.484-6.854a.713.713 0 00-.393-1.048l-9.477-.02z"/></svg>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700">Titlu:</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($property['title']); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="location" class="block text-gray-700">Locație:</label>
                            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($property['location']); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-gray-700">Suma in Euro:</label>
                            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($property['price']); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="rooms" class="block text-gray-700">Camere:</label>
                            <input type="text" id="rooms" name="rooms" value="<?php echo htmlspecialchars($property['rooms']); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="bathrooms" class="block text-gray-700">Băi:</label>
                            <input type="text" id="bathrooms" name="bathrooms" value="<?php echo htmlspecialchars($property['bathrooms']); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">Descriere:</label>
                            <textarea id="description" name="description" rows="5" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500"><?php echo htmlspecialchars($property['description']); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="image" class="block text-gray-700">Imagine 1:</label>
                            <input type="file" id="image" name="image" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="image2" class="block text-gray-700">Imagine 2:</label>
                            <input type="file" id="image2" name="image2" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                        </div>
                        <div class="flex justify-between">
                            <button type="submit" name="update_property" class="bg-green-500 text-white px-4 py-2 rounded-md font-bold hover:bg-green-700">Actualizează</button>
                        </div>
                    </form>
                    <form id="delete-form" action="update_property.php?id=<?php echo htmlspecialchars($propertyid); ?>" method="post">
                    <input type="hidden" name="delete_property" value="<?php echo htmlspecialchars($propertyid); ?>">
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md font-bold hover:bg-red-700 mt-4" onclick="confirmDelete(event)">Șterge</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
