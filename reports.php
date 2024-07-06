<?php
session_start();

require_once 'Classes/Connect.php';
require_once 'Classes/checks.php';
require_once 'Classes/reports.php';

// Check user
$id = $_SESSION['realestate_sessionid'];
$checks = new checks();
$user_data = $checks->check_admin($id);
if (!$user_data) {
    header("Location: log_in.php");
    die;
}

$db = new Database();
$query = ("SELECT id, first_name, last_name FROM users WHERE access <> 0 /*AND sessionid <> ?*/");
$params = [/*$id*/];
$agents = $db->read($query,$params);

$report = new Report();
$properties = [];
$totalProperties = 0;
$savedPropertiesCount = 0;
$selectedType = '';
$selectedAgentId = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agent_filter'])) {
        $selectedAgentId = $_POST['agent_id'];
        $properties = $report->getPropertiesByAgent($selectedAgentId);
        $totalProperties = count($properties);
    } elseif (isset($_POST['price_range'])) {
        $minPrice = $_POST['min_price'];
        $maxPrice = $_POST['max_price'];
        $properties = $report->getPropertiesByPriceRange($minPrice, $maxPrice);
        $totalProperties = count($properties);
    } elseif (isset($_POST['location_filter'])) {
        $location = $_POST['location'];
        $properties = $report->getPropertiesByLocation($location);
        $totalProperties = count($properties);
    } elseif (isset($_POST['type_filter'])) {
        $selectedType = $_POST['type'];
        $properties = $report->getPropertiesByType($selectedType);
        $totalProperties = count($properties);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Reports - ImobPlus</title>
    <link href="CSS/output.css" rel="stylesheet">
    <style>
        .report-form {
            display: none;
        }
        .report-form.active {
            display: block;
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
                        <a href="admin.php" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Inapoi</a>
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
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-1/4 bg-gray-100 p-4">
            <h2 class="text-xl font-bold mb-4">Rapoarte Disponibile</h2>
            <ul class="space-y-4">
                <li><button class="report-btn text-left w-full bg-customBlue-500 text-white px-4 py-2 rounded" data-target="agent-form">Raport Proprietati Agent</button></li>
                <li><button class="report-btn text-left w-full bg-customBlue-500 text-white px-4 py-2 rounded" data-target="price-range-form">Raport Preț</button></li>
                <li><button class="report-btn text-left w-full bg-customBlue-500 text-white px-4 py-2 rounded" data-target="location-form">Raport Locație</button></li>
                <li><button class="report-btn text-left w-full bg-customBlue-500 text-white px-4 py-2 rounded" data-target="type-form">Raport Tip Oferta</button></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <section class="w-3/4 bg-white p-4">
            <h2 class="text-2xl font-bold mb-4">Generați Rapoarte</h2>

            <!-- Price Range Report Form -->
            <form id="price-range-form" method="post" class="report-form <?php echo isset($_POST['price_range']) ? 'active' : ''; ?>">
                <h3 class="text-xl font-semibold mb-2">Filtru Interval de Preț</h3>
                <div class="flex space-x-8">
                    <div>
                        <label for="min_price" class="block text-lg font-medium">Preț Minim</label>
                        <input type="number" id="min_price" name="min_price" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div>
                        <label for="max_price" class="block text-lg font-medium">Preț Maxim</label>
                        <input type="number" id="max_price" name="max_price" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                </div>
                <button type="submit" name="price_range" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Generați Raport</button>
            </form>

            <!-- Location Report Form -->
            <form id="location-form" method="post" class="report-form <?php echo isset($_POST['location_filter']) ? 'active' : ''; ?>">
                <h3 class="text-xl font-semibold mb-2">Filtru Locație</h3>
                <div>
                    <label for="location" class="block text-lg font-medium">Locație</label>
                    <input type="text" id="location" name="location" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <button type="submit" name="location_filter" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Generați Raport</button>
            </form>

            <!-- Agent Report Form -->
            <form id="agent-form" method="post" class="report-form <?php echo isset($_POST['agent_filter']) ? 'active' : ''; ?>">
                <h3 class="text-xl font-semibold mb-2">Filtru Agent</h3>
                <div>
                    <label for="agent_id" class="block text-lg font-medium">Agent</label>
                    <select id="agent_id" name="agent_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                        <?php foreach ($agents as $agent) {
                            $selected = $agent['id'] == $selectedAgentId ? 'selected' : '';
                            echo "<option value='{$agent['id']}' {$selected}>{$agent['first_name']} {$agent['last_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="agent_filter" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Generați Raport</button>
            </form>

            <!-- Type Report Form -->
            <form id="type-form" method="post" class="report-form <?php echo isset($_POST['type_filter']) ? 'active' : ''; ?>">
                <h3 class="text-xl font-semibold mb-2">Filtru Tip Ofertă</h3>
                <div>
                    <label for="type" class="block text-lg font-medium">Tip Ofertă</label>
                    <select id="type" name="type" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="De vanzare" <?php echo $selectedType == 'De vanzare' ? 'selected' : ''; ?>>De vânzare</option>
                        <option value="De inchiriat" <?php echo $selectedType == 'De inchiriat' ? 'selected' : ''; ?>>De închiriat</option>
                    </select>
                </div>
                <button type="submit" name="type_filter" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Generați Raport</button>
            </form>

            <!-- Report Results -->
            <?php if (!empty($properties)): ?>
                <h3 class="text-xl font-semibold mb-4 mt-8">Rezultate:</h3>
                <p class="mb-4">Număr de proprietăți găsite: <?php echo $totalProperties; ?></p>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID proprietate</th>
                            <th class="py-2 px-4 border-b">Titlu</th>
                            <th class="py-2 px-4 border-b">Tip oferta</th>
                            <th class="py-2 px-4 border-b">Locatie</th>
                            <th class="py-2 px-4 border-b">Pret in euro</th>
                            <th class="py-2 px-4 border-b">Camere</th>
                            <th class="py-2 px-4 border-b">Bai</th>
                            <?php if(!empty($properties[0]['save_count'])): ?>
                            <th class="py-2 px-4 border-b">Număr de salvări</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    <?php foreach ($properties as $property): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo $property['propertyid']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($property['title']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($property['listing_type']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($property['location']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($property['price']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($property['rooms']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($property['bathrooms']); ?></td>
                            <?php if(!empty($property['save_count'])): ?> 
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($property['save_count']); ?></td>
                            <?php endif ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <p class="text-center text-gray-600">Nicio proprietate gasita pentru agentul selectat</p>
            <?php endif; ?>
        </section>
    </div>
</main>
<script>
    document.querySelectorAll('.report-btn').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.report-form').forEach(form => form.classList.remove('active'));
            document.getElementById(button.getAttribute('data-target')).classList.add('active');
        });
    });
</script>
</body>
</html>

