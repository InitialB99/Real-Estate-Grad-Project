<?php
session_start();

// Include the necessary files
require_once 'Classes/connect.php';

// Fetch agents' data from the database
$db = new Database();
$query = "SELECT first_name, last_name, number FROM users WHERE access != ?"; 
$params = [0];
$agents = $db->read($query, $params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - ImobPlus</title>
    <link href="CSS/output.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/background.css">
</head>
<body class="page-background">
<header>
    <nav class="bg-blue-500/75 border-b">
        <div class="max-w-screen-xxl flex flex-wrap items-center justify-between mx-auto p-2">
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
                <ul class="flex flex-col py-2 px-4 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                    <li>
                        <a href="index.php" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Inapoi</a>
                    </li>
                    <li>
                        <a href="log_out.php" class="block font-bold py-1 px-2 text-white rounded md:bg-transparent hover:md:text-gray-900 hover:shadow-md md:p-0" aria-current="page">Deconecteaza-te</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="container mx-auto py-12">
    <section class="mt-8 p-8 max-w-md mx-auto md:mx-0 md:ml-0 md:w-1/3">
        <h2 class="text-2xl font-bold mb-4">Contacteaza echipa noastră de agenti:</h2>
        <div class="space-y-8">
            <?php foreach ($agents as $agent): ?>
            <div class="p-4 bg-white bg-opacity-50 rounded-lg shadow">
                <h3 class="text-xl font-bold"><?php echo htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']); ?></h3>
                <p class="text-gray-700">0<?php echo htmlspecialchars($agent['number']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
</body>
</html>
