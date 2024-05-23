<?php
session_start();

require_once 'Classes/Connect.php';
require_once 'Classes/Login.php';
require_once 'Classes/User.php';

if (!isset($_SESSION['realestate_sessionid']) || !is_numeric($_SESSION['realestate_sessionid'])) {
    header("Location: log_in.php");
    die;
}

    $id = $_SESSION['realestate_sessionid'];
    $login = new Login();
    $result = $login->check_login($id);

if ($result) {
    $user = new User($id);
    $user_data = $user->get_data($id);

    if (!$user_data) {
            header("Location: log_in.php");
            die;
            } else if ($user_data['access'] !== 0) {
                header("Location: home.php");
                die;
        }
    }else {
            header("Location: log_in.php");
            die;
}

$property_id = "";

// Handle saving properties
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_property'])) {
    $property_id = $_POST['property_id'];

    $db = new Database();

    $query = "select id from users where sessionid = ?";
    $params = [$id];
    $result = $db->read($query, $params);
    $userid = $result[0]['id'];

    $query = "insert into saved_properties (userid, spropertyid) 
              values (?, ?)";
    $params = [$userid, $property_id];

    $db->save($query, $params);
    header("Location: user_home.php"); // Refresh the page to avoid resubmission
    die;

}

// Show properties
$db = new Database();
$query = 'select * from properties limit 9';
$params = [];
$properties = $db->read($query, $params);

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
            <div class="flex w-full">
                <input type="text" placeholder="Cauta o proprietate dupa locatie sau ID" class="w-1/2 px-4 py-2 border rounded-l-lg focus:outline-none focus:border-blue-500">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">Cauta</button>
            </div>
        </section>

        <section class="property-listings">
            <h2 class="text-2xl font-bold text-center mb-8">Proprietati recomandate</h2>
            <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if ($properties): ?>
                    <?php foreach ($properties as $property): ?>
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image" class="w-full h-48 object-cover rounded-t-lg">
                            <h3 class="text-xl font-bold mt-4"><?php echo htmlspecialchars($property['title']); ?></h3>
                            <p class="text-gray-600">Locatie: <?php echo htmlspecialchars($property['location']); ?></p>
                            <p class="text-gray-600">Pret: <?php echo htmlspecialchars($property['price']); ?></p>
                            <p class="text-gray-600">Camere: <?php echo htmlspecialchars($property['rooms']); ?></p>
                            <p class="text-gray-600">Bai: <?php echo htmlspecialchars($property['bathrooms']); ?></p>
                            <div class="flex justify-between mt-4">
                                <a href="detalii_proprietate.php?id=<?php echo htmlspecialchars($property['propertyid']); ?>" class="bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700">Detalii</a>
                                <form action="user_home.php" method="post">
                                    <input type="hidden" name="property_id" value="<?php echo $property['propertyid']; ?>">
                                    <button type="submit" name="save_property" class="bg-green-500 text-white px-4 py-2 rounded-md font-bold hover:bg-green-700">Salveaza</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">Nu s-au găsit proprietăți recomandate.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="text-center p-4 bg-gray-200">
        <p>&copy; ImobPlus 2024</p>
    </footer>

</body>

</html>
