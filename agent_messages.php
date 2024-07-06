<?php
session_start();

require_once 'Classes/connect.php';
require_once 'Classes/checks.php';

// Verifică dacă agentul este autentificat
$id = $_SESSION['realestate_sessionid'];
if (empty($id)) {
    header("Location: log_in.php");
    die;
}

$db = new Database();
$checks = new checks();

$user_data = $checks->check_agent($id);
if (!$user_data) {
    header("Location: log_in.php");
    die;
}
$agentid = $user_data['id'];

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $query = "DELETE FROM comments WHERE commentid = ? AND cpropertyid IN (SELECT propertyid FROM properties WHERE agentid = ?)";
    $params = [$comment_id, $agentid];
    $db->save($query, $params);
    header("Location: ".$_SERVER['PHP_SELF']);
    die;
}

// Recuperarea comentariilor pentru proprietățile agentului
$query = "SELECT comments.*, properties.title 
          FROM comments 
          JOIN properties ON comments.cpropertyid = properties.propertyid 
          WHERE properties.agentid = ? 
          ORDER BY comments.date DESC";
$params = [$agentid];
$comments = $db->read($query, $params);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentarii Proprietăți</title>
    <link href="CSS/output.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/background.css">
</head>

<body class="page-background">
<header>
    <nav class="bg-blue-500/75 border-b">
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
                <ul class="flex flex-col py-2 px-8 mt-2 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 relative">
                    <li>
                        <a href="agent_dashboard.php" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Inapoi</a>
                    </li>
                    <li>
                        <a href="#" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container mx-auto py-8">
    <section class="comments-section bg-white bg-opacity-50 rounded-lg shadow-md p-4">
        <h2 class="text-2xl font-bold mb-4">Comentarii la proprietățile tale</h2>
        <?php if ($comments): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment mb-4">
                    <p class="text-gray-600 text-xl">
                        <span class="font-bold text-lg"><?php echo htmlspecialchars($comment['username']); ?>:</span>
                        <?php echo htmlspecialchars($comment['comment']); ?>
                    </p>
                    <p class="text-gray-500 text-lg">
                        Proprietate: <?php echo htmlspecialchars($comment['title']); ?> | Data: <?php echo htmlspecialchars($comment['date']); ?>
                    </p>
                    <div class="flex justify-start mt-2 space-x-3">
                        <a href="property_details.php?id=<?php echo htmlspecialchars($comment['cpropertyid']); ?>" class="bg-customBlue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-customBlue-700">Vezi proprietatea</a>
                        <form method="POST" action="" class="">
                            <input type="hidden" name="comment_id" value="<?php echo htmlspecialchars($comment['commentid']); ?>">
                            <button type="submit" name="delete_comment" class="bg-red-500 text-white px-4 py-2 rounded-md font-bold hover:bg-red-700">Șterge comentariul</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-600">Nu sunt comentarii.</p>
        <?php endif; ?>
    </section>
</main>
</body>

</html>
