<?php

include("classes/connect.php");
include("classes/signup.php");

$first_name = "";
$last_name = "";
$email = "";
$password = "";
$password2 = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $signup = new Signup();
    $result = $signup->evaluate($_POST);

    if ($result != "") {
        echo "<div class='text-center text-sm text-white bg-gray-700 rounded-lg p-4'>";
        echo "The following errors occurred: <br><br>";
        echo $result;
        echo "</div>";
    } else {
        header("Location: log_in.php");
        exit();
    }

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - ImobPlus</title>
    <link href="output.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 mt-8 py-8">
        <h1 class="text-3xl font-bold mt-2 mb-4">Sign In</h1>

        <form method="post" class="max-w-md mx-auto">
            <div class="mb-4">
                <label for="first_name" class="block mb-2">First Name:</label>
                <input type="text" id="first_name" value="<?php echo $first_name ?>" name="first_name"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                    pattern="[A-Za-z]+" title="Only characters are allowed">
            </div>

            <div class="mb-4">
                <label for="last_name" class="block mb-2">Last Name:</label>
                <input type="text" id="last_name" value="<?php echo $last_name ?>" name="last_name" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                    pattern="[A-Za-z]+" title="Only characters are allowed">
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-2">Email:</label>
                <input type="email" id="email" value="<?php echo $email ?>" name="email" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="password1" class="block mb-2">Password:</label>
                <input type="password" id="password1" value="<?php echo $password ?>" name="password" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="password2" class="block mb-2">Confirm Password:</label>
                <input type="password" id="password2" value="<?php echo $password2 ?>" name="password2" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <button id='b'
                class="w-full bg-customBlue-500 text-white py-2 px-4 rounded-lg hover:bg-customBlue-600 transition duration-200">Create an Account</button>
        </form>
    </div>
</body>

</html>
