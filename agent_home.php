<?php
session_start();

require_once 'Classes/Connect.php';
require_once 'Classes/Login.php';
require_once 'Classes/checks.php';
require_once 'Classes/manageproperty.php';

// Check user
$id = $_SESSION['realestate_sessionid'];
$db = new Database();
$manageProperty = new ManageProperty;
$checks = new checks();

$user_data = $checks->check_agent($id);
if (!$user_data) {
    header("Location: log_in.php");
}

// TAKES USER ID
$userid = $user_data['id'];
$username = $user_data['first_name'];
$access = $user_data['access'];

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
  <header>
    <nav class="bg-customOrange-500 border-gray-200">
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
        <ul class="flex flex-col py-2 px-4 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-customOrange-500">
          <li>
            <a href="agent_dashboard.php" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Contul Meu</a>
          </li>
          <li>
            <a href="#" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Contact</a>
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
        
        <section class="search-bar flex flex-col items-center mb-8">
            <p class="text-xl font-bold mb-4">Cauta o proprietate</p>

            <!-- required for JS script -->
            <form id="search-form" class="flex w-full">
                <input type="text" placeholder="Cauta o proprietate dupa titlu sau locatie" class="w-full px-4 py-2 border rounded-l-lg focus:outline-none focus:border-customBlue-500">
                <button class="bg-customBlue-500 text-white px-4 py-2 rounded-r-lg hover:bg-customBlue-700">Cauta</button>
            </form>
            <!-- required for JS script - end -->

        </section>

        <section class="property-listings">
            <h2 class="text-2xl font-bold text-center mb-8">Toate Proprietatile</h2>

            <!-- required for JS script - start -->
            <div id="card-container" class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <p id="no-data" class="text-center hidden">Nu s-au găsit proprietăți.</p>
            </div>
            <!-- required for JS script - end -->

        </section>
    </main>

    <footer class="text-center p-4 bg-gray-200">
        <p>&copy; ImobPlus 2024</p>
    </footer>

</body>

</html>
<script>
    <?php require("property_cards_agent.js");?>
</script>