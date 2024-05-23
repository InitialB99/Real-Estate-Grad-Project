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
            } else if ($user_data['access'] !== 1) {
                header("Location: home.php");
        }
    }else {
            header("Location: log_in.php");
            die;
}

$db = new Database();
$query = "select * from properties, users where sessionid = ? and agentid = id";
$params = [$id];
$properties = $db->read($query, $params);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard</title>
    <link rel="stylesheet" href="output.css">
</head>
<body class="bg-gray-100">
    <header class="bg-blue-500 shadow-sm p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Buna, <?php echo htmlspecialchars($user_data['first_name'])?>!</h1>
            <nav>
                <ul class="list-none flex space-x-8">
                    <li><a href="home.php" class="hover:shadow-lg">Acasa</a></li>
                    <li><a href=# class="hover:shadow-lg">Dashboard</a></li>
                    <li><a href="log_out.php" class="font-bold hover:shadow-lg">Deconecteaza-te</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto py-12">
        <section class="p-12 bg-white rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-4 text-center">Proprietatile tale</h1>
            <div class="dashboard">
                <h2 class="text-2xl font-bold mb-4">Bun venit, <?php echo htmlspecialchars($user_data['first_name']); ?></h2>
                <p class="mb-4">Aici iti poti vedea proprietatile si adauga unele noi</p>

                <div class="flex justify-end mb-4">
                    <a href="upload.php" class="bg-green-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-700">
                        Incarca o proprietate +
                    </a>
                </div>

                <h3 class="text-xl font-bold mb-4">Proprietatile tale</h3>
                <div class="property-list grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if ($properties): ?>
                        <?php foreach ($properties as $property): ?>
                            <div class="property-item bg-white p-4 rounded-lg shadow-md">
                            <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image" class="w-full h-48 object-cover rounded-t-lg">
                                <h3 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($property['title']); ?></h3>
                                <p class="text-gray-700"><?php echo htmlspecialchars($property['description']); ?></p>
                                <p class="text-gray-700">Locatie: <?php echo htmlspecialchars($property['location']); ?></p>
                                <p class="text-gray-700">Pret: $<?php echo htmlspecialchars($property['price']); ?></p>
                                <p class="text-gray-700">Camere: <?php echo htmlspecialchars($property['rooms']); ?></p>
                                <p class="text-gray-700">Bai: <?php echo htmlspecialchars($property['bathrooms']); ?></p>
                                <a href="detalii_proprietate.php?id=<?php echo htmlspecialchars($property['propertyid']); ?>" class="block mt-4 bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700">Editeaza</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-700">No properties found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</body>
</html>