<?php
session_start();

require_once 'Classes/connect.php';
require_once 'Classes/manageproperty.php';
require_once 'Classes/user.php';

//TAKES SESSIONID
$id = $_SESSION['realestate_sessionid'];
if(empty($id)){
    header("Location: log_in.php");
    die;
}

$db = new Database();
$user_data = new User();
$manageProperty = new manageProperty;

//TAKES PROPERTYID
if(isset($_GET['id'])){
    $propertyid = $_GET['id'];
} 
if(empty($propertyid)){
    die('Property ID is missing.');
}

//TAKES USER ID and NAME
$result = $user_data->get_data($id);
$userid = $result['id'];
$username = $result['first_name'];

//TAKES PROPERTY DATA
$query = 'select * from properties where propertyid = ?';
$params = [$propertyid];
$property = $db->read($query, $params)[0];
if(!$property){
    die('Property not found.');
}

//GET AGENT INFO
$query = 'select * from users, properties where id = agentid and agentid = ? limit 1';
$params = [$property['agentid']];
$agentid = $db->read($query, $params)[0];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalii Proprietate</title>
    <link href="output.css" rel="stylesheet">
</head>

<body>
    <header class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">ImobPlus</h1>
            <nav>
                <ul class="list-none flex space-x-8">
                    <li><button onclick="history.back()">Inapoi</button></li>
                    <li><a href="contact.php" class="hover:shadow-lg">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto py-8">

        <section class="property-details flex flex-wrap">
            <div class="images w-full md:w-1/2 mb-8 md:mb-0">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image 1" class="w-auto h-auto object-cover rounded-lg">
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
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($property['title']); ?></h3>
                    <p class="text-gray-800">Locatie: <?php echo htmlspecialchars($property['location']); ?></p>
                    <p class="text-gray-800">Pret: <?php echo htmlspecialchars($property['price']); ?></p>
                    <p class="text-gray-800">Camere: <?php echo htmlspecialchars($property['rooms']); ?></p>
                    <p class="text-gray-800">Bai: <?php echo htmlspecialchars($property['bathrooms']); ?></p>
                    <p class="text-gray-800 mb-4"><?php echo htmlspecialchars($property['description']); ?></p>
                </div>

                <div class="contact-agent bg-gray-100 rounded-lg p-4 mt-4">
                    <h3 class="text-xl font-bold mb-2">Agent: <?php echo htmlspecialchars($agentid['first_name']); ?></h3>
                    <p class="text-gray-600 mb-2 font-bold">Numar de telefon: <span class="font-bold"><?php echo htmlspecialchars($agentid['number']); ?></span></p>
                </div>
            </div>
        </section>

    </main>
</body>
</html>
