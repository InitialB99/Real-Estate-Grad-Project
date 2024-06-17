<?php
session_start();

    require_once 'Classes/Connect.php';
    require_once 'Classes/Login.php';
    require_once 'Classes/User.php';
    require_once 'Classes/checks.php';

    // Check user
    $id = $_SESSION['realestate_sessionid'];
    $checks = new checks();
    $user_data = $checks->check_agent($id);

    if(!$user_data){
        header("Location: log_in.php");
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
    <style>
        .page-background {
            background-image: url('uploads/background2.jpg');
            background-size: cover;
        }
    </style>
</head>
<body class="page-background">
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
            <a href="admin.php" class="block">Admin</a>
          </li>
          <li>
            <a href="agent_home.php" class="block hover:md:text-gray-900 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Proprietati</a>
          </li>
          <li>
            <a href="agent_messages.php" class="block hover:md:text-gray-900 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Mesajele mele</a>
          </li>
          <li>
            <a href="update_agent.php" class="block hover:md:text-gray-900 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Datele mele</a>
          </li>
          <li>
            <a href="log_out.php" class="block font-bold text-white rounded md:bg-transparent hover:md:text-gray-900 hover:shadow-md md:p-0" aria-current="page">Deconecteaza-te</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

    <main class="container mx-auto py-12">
        <section class="p-12 bg-white bg-opacity-50 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-4 text-center">Proprietatile tale</h1>
            <div class="dashboard">
                <h2 class="text-2xl font-bold mb-4">Bun venit, <?php echo htmlspecialchars($user_data['first_name']); ?></h2>
                <p class="mb-4">Aici iti poti vedea proprietatile si adauga unele noi</p>

                <div class="flex mb-4">
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
                              <p class="text-gray-700 font-bold"><?php echo htmlspecialchars($property['listing_type']); ?></p>
                              <p class="text-gray-700">Locatie: <?php echo htmlspecialchars($property['location']); ?></p>
                              <p class="text-gray-700">Pret: <?php echo htmlspecialchars($property['price']); ?>€<?php if($property['listing_type'] == "De inchiriat"): echo "/pe luna"?><?php endif ?></p>
                              <p class="text-gray-700">Camere: <?php echo htmlspecialchars($property['rooms']); ?></p>
                              <p class="text-gray-700">Bai: <?php echo htmlspecialchars($property['bathrooms']); ?></p>
                              <a href="update_property.php?id=<?php echo htmlspecialchars($property['propertyid']); ?>" class="block text-center mt-4 bg-customBlue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-customBlue-700">Editeaza</a>
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