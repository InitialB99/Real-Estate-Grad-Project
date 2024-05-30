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

    if(!$user_data){
        header("Location: log_in.php");
    } else{
    $userid = $user_data['id'];
    }

$db = new Database();

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
<header>
  <nav class="bg-customOrange-500 border-gray-200">
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
        <ul class="flex flex-col px-8 py-2 mt-2 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-customOrange-500">
          <li>
            <a href="agent_home.php" class="block hover:md:text-gray-900 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Acasa</a>
          </li>
          <li>
            <a href="update_user.php" class="block hover:md:text-gray-900 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Datele mele</a>
          </li>
          <li>
            <a href="log_out.php" class="block font-bold text-white rounded md:bg-transparent hover:md:text-gray-900 hover:shadow-md md:p-0" aria-current="page">Deconecteaza-te</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

    <main class="container mx-auto py-8">
        <section class="p-12 bg-white rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-8 text-center">Proprietatile salvate de tine</h1>
            
            <div class="property-list grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if ($saved_properties): ?>
                    <?php foreach ($saved_properties as $property): ?>
                        <div class="property-item bg-white p-4 rounded-lg shadow-xl mt-8">
                            <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image" class="w-full h-48 object-cover rounded-t-lg">
                            <h4 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($property['title']); ?></h4>
                            <p class="text-gray-700"><?php echo htmlspecialchars($property['description']); ?></p>
                            <p class="text-gray-700">Locatie: <?php echo htmlspecialchars($property['location']); ?></p>
                            <p class="text-gray-700">Pret: <?php echo htmlspecialchars($property['price']); ?>€<?php if($property['listing_type'] == "De inchiriat"): echo "/pe luna"?><?php endif ?></p>
                            <p class="text-gray-700">Camere: <?php echo htmlspecialchars($property['rooms']); ?></p>
                            <p class="text-gray-700">Bai: <?php echo htmlspecialchars($property['bathrooms']); ?></p>
                            <a href="property_details.php?id=<?php echo htmlspecialchars($property['propertyid']); ?>" class="block mt-4 bg-customBlue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-customBlue-700">Detalii</a>
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
