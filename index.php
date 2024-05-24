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
    echo 'Error fetching properties: ' . mysqli_error($db->connect());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ravic</title>
  <link rel="stylesheet" href="output.css">
</head>
<body>
  <header class="bg-blue-500 shadow-sm p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-xl font-bold">Ravic</h1>
      <nav>
        <ul class="list-none flex space-x-8">
          <li><a href="properties.php" class="hover:shadow-lg">Proprietati</a></li>
          <li><a href="contact.php" class="hover:shadow-lg">Contact</a></li>
          <li><a href="about.php" class="hover:shadow-lg">Despre noi</a></li>
          <li><a href="log_in.php" class="font-bold hover:shadow-lg">Intra in cont</a></li>
          <li><a href="sign_up.php" class="font-bold hover:shadow-lg">Cont nou</a></li>
          <li><a href="log_out.php" class="font-bold hover:shadow-lg">Delete Session</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container mx-auto py-12">
    <section class="hero p-12 text-center bg-gray-100 rounded-lg shadow-md">
      <h1 class="text-3xl font-bold mb-4">Gaseste-ti casa de vis</h1>
      <p class="text-xl mb-8">Cauta in lista noastra extinsa si gaseste proprietatea perfecta pentru tine.</p>
      <section class="search-bar flex justify-center items-center mb-8">
        <input type="text" placeholder="Cauta o proprietate dupa locatie sau ID" class="w-1/2 px-4 py-2 border rounded-l-lg focus:outline-none focus:border-blue-500">
        <button class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">Cauta</button>
      </section>
    </section>

    <section class="featured-properties py-12">
      <h2 class="text-2xl font-bold text-center mb-8">Toate proprietatile</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php if ($properties): ?>
          <?php foreach ($properties as $property): ?>
            <div class="property-card bg-white rounded-lg shadow-md p-4">
              <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image" class="w-full h-48 object-cover rounded-t-lg">
              <h3 class="text-xl font-bold p-2"><?php echo htmlspecialchars($property['title']); ?></h3>
              <p class="text-gray-600 p-2"><?php echo htmlspecialchars($property['location']); ?></p>
              <a href="detalii_proprietate.php?id=<?php echo htmlspecialchars($property['propertyid']); ?>" class="bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700">Vezi Detalii</a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center">Nu s-au găsit proprietăți recomandate.</p>
        <?php endif; ?>
      </div>
    </section>

    <footer class="text-center p-4 bg-gray-200">
      <p>&copy; Ravic 2024</p>
    </footer>
  </main>
</body>
</html>
