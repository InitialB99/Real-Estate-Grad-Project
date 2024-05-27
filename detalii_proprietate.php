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
$user_data = new User();
$manageProperty = new manageProperty;

//TAKES PROPERTYID
if(isset($_GET['id'])){
  $propertyid = $_GET['id'];
} 
if(empty($propertyid)){
  die('Property ID is missing.');
}

//TAKES USER ID and NAME
$result = $user_data->get_data($id);
$userid = $result['id'];
$username = $result['first_name'];

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
    header("Location: detalii_proprietate.php?id=$propertyid");
    exit();
  } else{
    echo $result;
  }
}

// UNSAVE PROPERTY
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['unsave_property'])) {
  $result = $manageProperty->unsaveProperty($userid, $propertyid);
    if($result === true){
    header("Location: detalii_proprietate.php?id=$propertyid");
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
  <link href="output.css" rel="stylesheet">
</head>

<body>
  <header class="bg-blue-500 p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-xl font-bold">ImobPlus</h1>
      <nav>
        <ul class="list-none flex space-x-8">
          <li><button onclick="history.back()">Inapoi</button></li>
          <li><a href="contact.php" class="hover:shadow-lg">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container mx-auto py-8">

    <section class="property-details flex flex-wrap">
      <div class="images w-full md:w-1/2 mb-8 md:mb-0">
        <div class="swiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image 1" class="w-auto h-auto object-cover rounded-lg">
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
          <p class="text-gray-800">Locatie: <?php echo htmlspecialchars($property['location']); ?></p>
          <p class="text-gray-800">Pret: <?php echo htmlspecialchars($property['price']); ?></p>
          <p class="text-gray-800">Camere: <?php echo htmlspecialchars($property['rooms']); ?></p>
          <p class="text-gray-800">Bai: <?php echo htmlspecialchars($property['bathrooms']); ?></p>
          <p class="text-gray-800 mb-4"><?php echo htmlspecialchars($property['description']); ?></p>
          <div class="flex justify-between mt-4">
              <?php if ($savedProperty): ?>
                  <form action="detalii_proprietate.php?id=<?php echo htmlspecialchars($propertyid); ?>" method="post">
                      <button type="submit" name="unsave_property" class="bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700">Salvat</button>
                  </form>
              <?php else: ?>
                  <form action="detalii_proprietate.php?id=<?php echo htmlspecialchars($propertyid); ?>" method="post">
                      <button type="submit" name="save_property" class="bg-green-500 text-white px-4 py-2 rounded-md font-bold hover:bg-green-700">Salveaza</button>
                  </form>
              <?php endif; ?>
          </div>

        <div class="contact-agent bg-gray-100 rounded-lg p-4 mb-8">
          <h3 class="text-xl font-bold mb-2">Agent: <?php echo htmlspecialchars($agentid['first_name']); ?></h3>
          <p class="text-gray-600 mb-2 font-bold">Scrie un mesaj sau apeleaza agentul: <span class="font-bold"><?php echo htmlspecialchars($agentid['number']); ?></span></p>
          <form action="detalii_proprietate.php?id=<?php echo htmlspecialchars($propertyid); ?>" method="post">
            <textarea type="text" name="comment" id="comment" rows="5" placeholder="Lasa un mesaj iar agentul te va contacta cat mai curand posibil!" class="w-full rounded-lg border px-4 py-2 focus:outline-none focus:border-blue-500"></textarea>
            <button type="submit" name="post_comment"class="bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700 mt-4">Trimite mesaj</button>
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