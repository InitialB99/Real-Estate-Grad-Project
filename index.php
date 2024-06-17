<?php

session_start();
require_once 'Classes/connect.php';

$db = new Database();

$query = 'select * from properties where featured = ? limit 3';
$params = [1];
$properties = $db->read($query, $params);

// Debugging: Output the fetched properties
/*echo '<pre>';
print_r($properties);
echo '</pre>';*/

if ($properties === false) {
    echo 'Error fetching properties';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ImobPlus</title>
  <link rel="stylesheet" href="output.css">
  <style>
        .page-background {
            background-image: url('uploads/background2.jpg');
            background-size: cover;
        }
    </style>
</head>
<body class="page-background">
<header class="bg-blue-500 shadow-sm p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">ImobPlus</h1>
        <nav class="bg-customOrange-500 fixed w-full z-20 top-0 start-0 border-b">
            <div class="max-w-screen-xxl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="log_in.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="./logo.png" class="h-8">
                    <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">ImobPlus</span>
                </a>
                <div class="flex md:order-2 space-x-3 md:space-x-4 rtl:space-x-reverse">
                    <button type="button" class="text-white bg-customBlue-500 hover:bg-customBlue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center" onclick="window.location.href='log_in.php'">Intra in cont</button>
                    <button type="button" class="text-white bg-customBlue-500 hover:bg-customBlue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center" onclick="window.location.href='sign_up.php'">Cont nou</button>
                    <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-400 rounded-lg md:hidden hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
                        <span class="sr-only">Meniu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>
                <div class="items-center justify-center hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                    <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border rounded-lg bg-customOrange-500 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-customOrange-500">
                        <li>
                            <a href="#" class="block py-2 px-3 text-white rounded md:hover:text-gray-900 md:p-0">Contact</a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-3 text-white rounded md:hover:text-gray-900 md:p-0">Despre noi</a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-3 text-white rounded md:hover:text-gray-900 md:p-0">Servicii</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>


  <main class="container mx-auto py-12">
    <section class="hero p-12 text-center bg-hero-pattern drop-shadow bg-left-bottom rounded-lg shadow-lg border-4 border-white">
      <h1 class="text-3xl font-bold mb-4">Gaseste-ti casa de vis</h1>
      <p class="text-xl mb-8">Cauta in lista noastra extinsa si gaseste proprietatea perfecta pentru tine.</p>
    </section>

    <section class="featured-properties py-12">
      <h2 class="text-2xl font-bold text-center mb-8">Proprietati promovate</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php if ($properties): ?>
              <?php foreach ($properties as $property): ?>
                <div class="property-card bg-white rounded-lg shadow-md p-4">
                  <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image" class="w-full h-48 object-cover rounded-t-lg">
                  <h3 class="text-xl font-bold p-2"><?php echo htmlspecialchars($property['title']); ?></h3>
                  <p class="text-gray-600 p-2"><?php echo htmlspecialchars($property['location']); ?></p>
                  <a href="property_details.php?id=<?php echo htmlspecialchars($property['propertyid']); ?>" class="block text-center mt-4 bg-customBlue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-customBlue-700">Vezi Detalii</a>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-center">Nu s-au găsit proprietăți recomandate.</p>
            <?php endif; ?>
          </div>
    </section>

  <footer class="text-center p-2 bg-gray-200 max-w-md mx-auto">
    <p>&copy; ImobPlus 2024</p>
  </footer>
  </main>
</body>
</html>