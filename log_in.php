<?php

session_start();

require_once 'classes/checks.php';
require_once 'classes/connect.php';
require_once 'classes/login.php';

$email = "";
$password = "";

//Automatic log-in if session exist:
if (isset($_SESSION['realestate_sessionid']) && is_numeric($_SESSION['realestate_sessionid'])) {
        
        $id = $_SESSION['realestate_sessionid'];
        $checks = new checks();
        $user_data = $checks->check_agent($id);

    if($user_data){
        header("Location: agent_dashboard.php"); // Goes to agent page
        exit();
    } else {
        header("Location: user_home.php"); // Goes to client page
            exit();
        }
}

// If not logged in, continue to display the login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $login = new Login();
    $result = $login->evaluate($_POST);

    if ($result != "") {
        echo "<div class='text-center text-sm text-white bg-gray-700 rounded-lg p-4'>";
        echo "The following errors occurred: <br><br>";
        echo $result;
        echo "</div>";
    } else {
        header("Location: " . $_SERVER['PHP_SELF']); // Refreshes the current page
        exit();
}
        
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - ImobPlus</title>
    <link href="CSS/output.css" rel="stylesheet">
    <style>
        .page-background {
            background-image: url('uploads/background2.jpg');
            background-size: cover;
        }
    </style>
</head>

<body class="page-background">
    <div class="container mx-auto px-4 mt-8 py-8">
        <h1 class="text-3xl font-bold mt-8 mb-4 flex justify-center">Autentifica-te</h1>

        <form method="post" class="max-w-md mx-auto mt-8 bg-white">
            <div class="mb-4">
                <label for="email" class="block mb-2">Email:</label>
                <input value="<?php echo $email ?>" type="email" id="email" name="email" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2">Parola:</label>
                <input value="<?php echo $password ?>" type="password" id="password" name="password" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-customBlue-500">
            </div>

            <button id="login"
                class="w-full bg-customBlue-500 text-white py-2 px-4 rounded-lg hover:bg-customBlue-600 transition duration-200">Intra in cont</button>

            <a href="sign_up.php"
                class="block text-center mt-4 text-customBlue-500 hover:text-customBlue-700">Nu ai cont? Creaza unul acum</a>
        </form>
    </div>
</body>

</html>
