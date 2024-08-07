<?php
session_start();

require_once 'Classes/connect.php';
require_once 'Classes/checks.php';
require_once 'Classes/user.php';

// TAKES SESSIONID
$id = $_SESSION['realestate_sessionid'];
if (empty($id)) {
    header("Location: log_in.php");
    die;
}

$db = new Database();
$check = new Checks();
$user = new User();

// FETCH USER DATA
$user_data = $check->check_client($id);
if (!$user_data) {
    header("Location: log_in.php");
    die;
}

$userid = $user_data['id'];
$first_name = $user_data['first_name'];
$last_name = $user_data['last_name'];
$email = $user_data['email'];
$number = $user_data['number'];

// UPDATE USER DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $new_data = [
        'first_name' => !empty($_POST['first_name']) ? $_POST['first_name'] : null,
        'last_name' => !empty($_POST['last_name']) ? $_POST['last_name'] : null,
        'email' => !empty($_POST['email']) ? $_POST['email'] : null,
        'password' => !empty($_POST['password']) ? $_POST['password']: null,
        'number' => !empty($_POST['number']) ? $_POST['number'] : null,
    ];

    $result = $user->update_user_data($id, $new_data);
    if($result === true){
        header("Location: user_dashboard.php");
        exit();
    } else {
        echo "<div class='text-center text-sm text-white bg-gray-700 rounded-lg p-4'>";
        echo "The following errors occurred: <br><br>";
        echo "Failed to update user data.";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Data</title>
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
                <a href="user_dashboard.php" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Inapoi</a>
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
        <section class="bg-white bg-opacity-50 rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">Actualizeaza-ti informatiile!</h2>
            <form action="update_user.php" method="post" class="space-y-4">
                <div>
                    <label for="first_name" class="block text-gray-700">Nume:</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="last_name" class="block text-gray-700">Prenume:</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Parola:</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="number" class="block text-gray-700">Numar de telefon:</label>
                    <input type="text" id="number" name="number" value="<?php echo htmlspecialchars($number); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                </div>
                <div class="flex justify-center">
                    <button type="submit" name="update_user" class="bg-customBlue-500 text-white px-8 py-4 rounded-md mt-4 font-bold hover:bg-customBlue-700">Salveaza</button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>
