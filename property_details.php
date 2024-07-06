<?php
session_start();

require_once 'Classes/connect.php';
require_once 'Classes/manageproperty.php';
require_once 'Classes/user.php';

//TAKES SESSIONID
$id = $_SESSION['realestate_sessionid'];
if(empty($id)){
  header("Location: log_in.php");
        die;
}

$db = new Database();
$user = new User();
$manageProperty = new manageProperty;

//TAKES PROPERTYID
if(isset($_GET['id'])){
  $propertyid = $_GET['id'];
} 
if(empty($propertyid)){
  die('Property ID is missing.');
}

//TAKES USER ID and NAME
$user_data = $user->get_data($id);
$userid = $user_data['id'];
$username = $user_data['first_name'];

//TAKES PROPERTY DATA
$query = 'select * from properties where propertyid = ?';
$params = [$propertyid];
$property = $db->read($query, $params)[0];
  if(!$property){
    die('Property not found.');
}

//GET AGENT INFO
$query = 'select * from users, properties where id = agentid and agentid = ? limit 1';
$params = [$property['agentid']];
$agentid = $db->read($query, $params)[0];

// Check if property is already saved
$savedProperty = $manageProperty->checkSaved($userid, $propertyid);

//SAVE PROPERTY
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_property'])) {
  $result = $manageProperty->saveProperty($userid, $propertyid);
    if($result === true){
    header("Location: property_details.php?id=$propertyid");
    exit();
  } else{
    echo $result;
  }
}

// UNSAVE PROPERTY
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['unsave_property'])) {
  $result = $manageProperty->unsaveProperty($userid, $propertyid);
    if($result === true){
    header("Location: property_details.php?id=$propertyid");
    exit();
  } else{
    echo $result;
  }
}

//POST COMMENT
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_comment'])) {
  $comment = $_POST['comment'];
  $current_date = date("Y-m-d H:i:s");

  $query = "insert into comments (userid,cpropertyid,username,comment,date)
            values (?,?,?,?,?)";
  $params = [$userid,$propertyid,$username,$comment,$current_date];
  $db->read($query, $params);
}

//FETCH COMMENTS
$query = "SELECT * FROM comments WHERE cpropertyid = ? ORDER BY date DESC";
$params = [$propertyid];
$comments = $db->read($query,$params);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalii proprietate</title>
  <link rel="stylesheet" href="CSS/output.css">
  <link rel="stylesheet" href="CSS/background.css">
</head>

<body class="page-background">
<header>
    <nav class="bg-blue-500/75 border-b">
        <div class="max-w-screen-xxl flex flex-wrap items-center justify-between mx-auto p-2">
            <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="./logo.png" class="h-8" alt="Logo" />
            <span class="text-2xl font-semibold text-white">Buna, <?php echo htmlspecialchars($username)?>!</span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center rounded-lg md:hidden" aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Meniu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
            </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul class="flex flex-col py-2 px-8 mt-2 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 relative">
                <li>
                <a href="agent_home.php" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Inapoi</a>
                </li>
                <li>
                <a href="contact.php" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Contact</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>
</header>

  <main class="container mx-auto py-8">

    <section class="property-details flex flex-wrap">
      <div class="images w-full md:w-1/2 mb-8 md:mb-0">
        <div class="swiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image 1" class="w-auto h-auto object-cover mb-2 rounded-lg">
            </div>
            <div class="swiper-slide">
              <img src="<?php echo htmlspecialchars($property['image2']); ?>" alt="Property Image 2" class="w-full h-auto object-cover rounded-lg">
            </div>
          </div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>
      <div class="details w-full md:w-1/2 px-4">
      <div class="bg-white rounded-lg shadow-md p-4">
        <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($property['title']); ?></h3>
          <p class="text-gray-700 font-bold"><?php echo htmlspecialchars($property['listing_type']); ?></p>
          <p class="text-gray-800">Locatie: <?php echo htmlspecialchars($property['location']); ?></p>
          <p class="text-gray-800">Pret: <?php echo htmlspecialchars($property['price']); ?></p>
          <p class="text-gray-800">Camere: <?php echo htmlspecialchars($property['rooms']); ?></p>
          <p class="text-gray-800">Bai: <?php echo htmlspecialchars($property['bathrooms']); ?></p>
          <p class="text-gray-800 mb-4"><?php echo htmlspecialchars($property['description']); ?></p>
          <div class="flex justify-between mt-4">
              <?php if ($savedProperty): ?>
                  <form action="property_details.php?id=<?php echo htmlspecialchars($propertyid); ?>" method="post">
                      <button type="submit" name="unsave_property" class="bg-customBlue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-customBlue-700 mb-4">Salvat</button>
                  </form>
              <?php elseif ($user_data['access'] == 1): ?>
                <!-- Do nothing, hide the button -->
              <?php else: ?>
                  <form action="property_details.php?id=<?php echo htmlspecialchars($propertyid); ?>" method="post">
                      <button type="submit" name="save_property" class="bg-green-500 text-white px-4 py-2 rounded-md mb-4 font-bold hover:bg-green-700">Salveaza</button>
                  </form>
              <?php endif; ?>
          </div>

        <div class="contact-agent bg-gray-100 rounded-lg p-4 mb-8">
          <h3 class="text-xl font-bold mb-2">Agent: <?php echo htmlspecialchars($agentid['first_name']); ?></h3>
          <p class="text-gray-600 mb-2 font-bold">Scrie un mesaj sau apeleaza agentul: (+40)0<span class="font-bold"><?php echo htmlspecialchars($agentid['number']); ?></span></p>
          <form action="property_details.php?id=<?php echo htmlspecialchars($propertyid); ?>" method="post">
            <textarea type="text" name="comment" id="comment" rows="5" placeholder="Lasa un mesaj iar agentul te va contacta cat mai curand posibil!" class="w-full rounded-lg border px-4 py-2 focus:outline-none focus:border-customBlue-500"></textarea>
            <button type="submit" name="post_comment" class="bg-customBlue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-customBlue-700 mt-4">Trimite mesaj</button>
          </form>
        </div>
        <div class="comments-section bg-white rounded-lg shadow-md p-4">
          <h3 class="text-xl font-bold mb-2">Comentarii</h3>
          <?php if($comments): ?>
            <?php foreach($comments as $comment): ?>
              <div class="comment mb-4">
                <p class="text-gray-600">
                  <span class="font-bold">
                    <?php echo htmlspecialchars($comment['username']); ?>:
                  </span>
                    <?php echo htmlspecialchars($comment['comment']); ?>
                </p>
                <p class="text-gray-500 text-sm">
                  <?php htmlspecialchars($comment['date']); ?>
                </p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-gray-600">
              Nu sunt comentarii.
            </p>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>
</body>
</html>