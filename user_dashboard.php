<?php
    session_start();

    require_once 'Classes/Connect.php';
    require_once 'Classes/Login.php';
    require_once 'Classes/User.php';
    require_once 'Classes/checks.php';

    // Check user

    $id = $_SESSION['realestate_sessionid'];
    $checks = new checks();
    $user_data = $checks->check_client($id);

    if($user_data){
        echo 'Everything is fine';
    } else {
        header("Location: log_in.php");
    }

$db = new Database();

//$query = "select id from users where sessionid = ?";
//$params = [$id];
//$result = $db->read($query, $params);
$userid = $user_data['id'];

$query = "select * from properties, saved_properties 
          where propertyid = spropertyid and userid = ?";
$params = [$userid];
$saved_properties = $db->read($query, $params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - ImobPlus</title>
    <link rel="stylesheet" href="output.css">
</head>
<body class="bg-gray-100">
    <header class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Contul Meu - <?php echo htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']); ?></h1>
            <nav>
                <ul class="list-none flex space-x-8">
                    <li><a href="user_home.php" class="hover:shadow-lg">Acasa</a></li>
                    <li><a href="user_dashboard.php" class="hover:shadow-lg">Dashboard</a></li>
                    <li><a href="log_out.php" class="font-bold hover:shadow-lg">Deconecteaza-te</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto py-12">
        <section class="p-12 bg-white rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-4 text-center">Proprietatile Salvate</h1>
            <div class="property-list grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if ($saved_properties): ?>
                    <?php foreach ($saved_properties as $property): ?>
                        <div class="property-item bg-white p-4 rounded-lg shadow-md">
                            <h4 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($property['title']); ?></h4>
                            <p class="text-gray-700"><?php echo htmlspecialchars($property['description']); ?></p>
                            <p class="text-gray-700">Locatie: <?php echo htmlspecialchars($property['location']); ?></p>
                            <p class="text-gray-700">Pret: $<?php echo htmlspecialchars($property['price']); ?></p>
                            <p class="text-gray-700">Camere: <?php echo htmlspecialchars($property['rooms']); ?></p>
                            <p class="text-gray-700">Bai: <?php echo htmlspecialchars($property['bathrooms']); ?></p>
                            <a href="detalii_proprietate.php?id=<?php echo htmlspecialchars($property['propertyid']); ?>" class="block mt-4 bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700">Detalii</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-700">Nu s-au găsit proprietăți salvate.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
