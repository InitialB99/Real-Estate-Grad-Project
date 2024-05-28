<?php
session_start();

require_once 'Classes/Connect.php';
require_once 'Classes/checks.php';
require_once 'Classes/manageproperty.php';

//CHECKS USER
$id = $_SESSION['realestate_sessionid'];
$db = new Database();
$manageProperty = new ManageProperty;
$checks = new checks();

$user_data = $checks->check_client($id);
if($user_data === false){
    header("Location: log_in.php");
}

//TAKES USER ID
$userid = $user_data['id'];
$username = $user_data['first_name'];

//SAVE PROPERTY
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_property'])) {
    $property_id = $_POST['property_id'];

    $result = $manageProperty->saveProperty($userid, $property_id);
        //header("Location: user_home.php");
        //exit();    
}

//UNSAVE PROPERTY
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['unsave_property'])) {
    $property_id = $_POST['property_id'];

    $result = $manageProperty->unsaveProperty($userid, $property_id);
        //header("Location: user_home.php");
        //exit();
}

// Fetch saved properties for the user
$query = "SELECT spropertyid FROM saved_properties WHERE userid = ?";
$params = [$userid];
$savedProperties = $db->read($query, $params);
$savedPropertiesIds = array_column($savedProperties, 'spropertyid');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaseste-ti proprietatea - ImobPlus</title>
    <link href="output.css" rel="stylesheet">
</head>

<body>
    <header class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold"><?php echo "Bine ai venit, " . htmlspecialchars($user_data['first_name']) . "!"; ?></h1>
            <nav>
                <ul class="list-none flex space-x-8">
                    <li><a href="user_dashboard.php" class="hover:shadow-lg">Contul Meu</a></li>
                    <li><a href="#" class="hover:shadow-lg">Contact</a></li>
                    <li><a href="log_out.php" class="font-bold hover:shadow-lg">Deconecteaza-te</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto py-12">

        <section class="search-bar flex flex-col items-center mb-8">
            <p class="text-xl font-bold mb-4">Cauta o proprietate</p>
            <form id="search-form" class="flex w-full">
                <input type="text" placeholder="Cauta o proprietate dupa locatie sau ID" class="w-1/2 px-4 py-2 border rounded-l-lg focus:outline-none focus:border-blue-500">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">Cauta</button>
            </form>
        </section>

        <section class="property-listings">
            <h2 class="text-2xl font-bold text-center mb-8">Proprietati recomandate</h2>
            <div id="card-container" class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <p id="no-data" class="text-center hidden">Nu s-au găsit proprietăți recomandate.</p>
            </div>
        </section>
    </main>

    <footer class="text-center p-4 bg-gray-200">
        <p>&copy; ImobPlus 2024</p>
    </footer>

</body>

</html>
<script>
    <?php require("property_search.js");?>
</script>