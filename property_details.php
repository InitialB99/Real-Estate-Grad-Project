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
    <header>
    <nav class="bg-red-600 border-gray-200">
            <div class="max-w-screen-xxl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="./logo.png" class="h-8" alt="Logo" />
                <span class="text-2xl font-semibold text-white">ImobPlus</span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center rounded-lg md:hidden" aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Meniu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="flex flex-col p-4 mt-4 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-red-600">
                <li>
                    <a href="agent_dashboard.php" class="block hover:md:text-gray-900 py-2 px-3 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Inapoi</a>
                </li>
                <li>
                    <a href="#" class="block hover:md:text-gray-900 py-2 px-3 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Contact</a>
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
