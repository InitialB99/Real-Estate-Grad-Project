<?php
session_start();

// Check user session or other conditions if needed
// require_once 'Classes/Connect.php';
// require_once 'Classes/checks.php';
// require_once 'Classes/reports.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - ImobPlus</title>
    <link href="output.css" rel="stylesheet">
    <style>
        .bg-custom {
            background-color: #f8f8f8;
        }
        .team-member {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
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
    <section class="bg-custom p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-center">Despre Noi</h1>
        <p class="text-lg mb-6 text-gray-700">ImobPlus este o platformă dedicată agenților imobiliari și clienților acestora. Oferim o gamă largă de servicii pentru a facilita interacțiunea între agenții imobiliari și clienți, asigurând o experiență simplă și eficientă pentru toți utilizatorii noștri.</p>
        
        <h2 class="text-2xl font-bold mb-4">Misiunea Noastră</h2>
        <p class="text-lg mb-6 text-gray-700">Scopul nostru este să îmbunătățim modul în care agențiile imobiliare și clienții colaborează, oferind o platformă robustă și ușor de utilizat care să răspundă nevoilor tuturor utilizatorilor. Credem în transparență, inovație și servicii de calitate.</p>
        
        <h2 class="text-2xl font-bold mb-4">Echipa Noastră</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="team-member bg-white p-4">
                <img src="team_member_1.jpg" alt="Team Member 1" class="w-full h-48 object-cover">
                <h3 class="text-xl font-bold mt-4">John Doe</h3>
                <p class="text-gray-700">CEO</p>
            </div>
            <div class="team-member bg-white p-4">
                <img src="team_member_2.jpg" alt="Team Member 2" class="w-full h-48 object-cover">
                <h3 class="text-xl font-bold mt-4">Jane Smith</h3>
                <p class="text-gray-700">CTO</p>
            </div>
            <div class="team-member bg-white p-4">
                <img src="team_member_3.jpg" alt="Team Member 3" class="w-full h-48 object-cover">
                <h3 class="text-xl font-bold mt-4">Emily Johnson</h3>
                <p class="text-gray-700">Marketing Manager</p>
            </div>
        </div>
    </section>
</main>
</body>
</html>
