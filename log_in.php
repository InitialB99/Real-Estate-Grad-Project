<?php

session_start();

require_once 'classes/checks.php';
require_once 'classes/connect.php';
require_once 'classes/login.php';
require_once 'classes/user.php';

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
        header("Location: user_dashboard.php"); // Goes to client page
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
    <link href="output.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Log In</h1>

        <form method="post" class="max-w-md mx-auto">
            <div class="mb-4">
                <label for="email" class="block mb-2">Email:</label>
                <input value="<?php echo $email ?>" type="email" id="email" name="email" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2">Password:</label>
                <input value="<?php echo $password ?>" type="password" id="password" name="password" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <button id="login"
                class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">Log
                In</button>

            <a href="sign_up.php"
                class="block text-center mt-4 text-blue-500 hover:text-blue-700">Create an Account</a>
        </form>
    </div>
</body>

</html>
