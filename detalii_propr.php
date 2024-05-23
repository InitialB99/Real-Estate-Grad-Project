<?php
// Start session and include necessary files
session_start();
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");

// Check if user is logged in
if(isset($_SESSION['realestate_sessionid']) && is_numeric($_SESSION['realestate_sessionid'])) {
    $id = $_SESSION['realestate_sessionid'];
    $login = new Login();
    $result = $login->check_login($id);
    if($result) {
        // Retrieve user data
        $user = new User($id);
        $user_data = $user->get_data($id);
        if(!$user_data) {
            header("Location: log_in.php");
            die;
        }
    } else {
        header("Location: log_in.php");
        die;
    }
} else {
    header("Location: log_in.php");
    die;
}

// Dummy property data
$property_images = array("property-image1.jpg", "property-image2.jpg", "property-image3.jpg");
$property_title = "Titlul Proprietatii";
$property_location = "Sample Location, City";
$property_price = "XXX,XXX";
$property_rooms = 3;
$property_bathrooms = 2;
$agent_number = "Agent: XXX-XXX-XXXX";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $property_title; ?></title>
    <link href="output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.8.4/swiper-bundle.min.css" />
</head>

<body>
    <header class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold"><?php echo "Bine ai venit, " . $user_data['first_name'] . "!"; ?></h1>
            <nav>
                <ul class="list-none flex space-x-8">
                    <li><a href="#" class="hover:shadow-lg">Despre noi</a></li>
                    <li><a href="#" class="hover:shadow-lg">Contact</a></li>
                    <li><a href="index.php" class="font-bold hover:shadow-lg">Deconecteaza-te</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto py-12">

        <section class="property-details mb-16">
            <div class="container mx-auto">
                <h2 class="text-2xl font-bold mb-8"><?php echo $property_title; ?></h2>
                <!-- Property images -->
                <div class="images w-full mb-8">
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($property_images as $image) : ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo $image; ?>" alt="Property Image" class="w-full h-auto object-cover rounded-lg">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
                <!-- Property specifications -->
                <div class="details w-full px-4">
                    <p class="text-gray-600"><strong>Locatie:</strong> <?php echo $property_location; ?></p>
                    <p class="text-gray-600"><strong>Pret:</strong> <?php echo $property_price; ?></p>
                    <div class="grid grid-cols-2 gap-4 mt-4 mb-8">
                        <div class="text-sm font-medium">Camere:</div>
                        <div class="text-sm font-bold"><?php echo $property_rooms; ?></div>
                        <div class="text-sm font-medium">Bai:</div>
                        <div class="text-sm font-bold"><?php echo $property_bathrooms; ?></div>
                        <!-- Alte specificatii -->
                    </div>
                    <p class="text-gray-600 mb-4">Descriere detaliata a proprietatii...</p>

                    <div class="contact-agent bg-gray-100 rounded-lg p-4 mb-8">
                        <h3 class="text-xl font-bold mb-2">Contacteaza Agentul</h3>
                        <p class="text-gray-600 mb-2">Numarul Agentului: <span class="font-bold"><?php echo $agent_number; ?></span></p>
                        <form action="#">
                            <textarea name="message" id="message" rows="5" class="w-full rounded-lg border px-4 py-2 focus:outline-none focus:border-blue-500"></textarea>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700 mt-4">Trimite mesaj</button>
                        </form>
                    </div>

                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700">Salveaza Anuntul</button>
                </div>
            </div>
        </section>

    </main>

    <footer class="text-center p-4 bg-gray-200">
        <p>&copy; ImobPlus 2024</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.8.4/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".swiper", {
            loop: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>
</body>

</html>
