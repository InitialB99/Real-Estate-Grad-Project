<?php
session_start();

require_once 'Classes/connect.php';
require_once 'Classes/user.php';

// TAKES SESSIONID
$id = $_SESSION['realestate_sessionid'];
if (empty($id)) {
    header("Location: log_in.php");
    die;
}

$db = new Database();
$user = new User();

// FETCH USER DATA
$user_data = $user->get_data($id);
if (!$user_data) {
    die('User not found.');
}

$userid = $user_data['id'];
$first_name = $user_data['first_name'];
$last_name = $user_data['last_name'];
$email = $user_data['email'];
$number = $user_data['number'];

// UPDATE USER DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_agent'])) {
    $new_data = [
        'first_name' => !empty($_POST['first_name']) ? $_POST['first_name'] : null,
        'last_name' => !empty($_POST['last_name']) ? $_POST['last_name'] : null,
        'email' => !empty($_POST['email']) ? $_POST['email'] : null,
        'password' => !empty($_POST['password']) ? $_POST['password']: null,
        'number' => !empty($_POST['number']) ? $_POST['number'] : null,
    ];

    if ($user->update_user_data($id, $new_data)) {
        header("Location: agent_dashboard.php");
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
    <title>Update Agent Data</title>
    <link href="output.css" rel="stylesheet">
</head>

<body>
    <header class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">ImobPlus</h1>
            <nav>
                <ul class="list-none flex space-x-8">
                    <li><a href="agent_dashboard.php" class="hover:shadow-lg">Inapoi</a></li>
                    <li><a href="contact.php" class="hover:shadow-lg">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto py-8">
        <section class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">Actualizeaza-ti informatiile!</h2>
            <form action="update_agent.php" method="post" class="space-y-4">
                <div>
                    <label for="first_name" class="block text-gray-700">First Name:</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="last_name" class="block text-gray-700">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="number" class="block text-gray-700">Number:</label>
                    <input type="text" id="number" name="number" value="<?php echo htmlspecialchars($number); ?>" class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500">
                </div>
                <div class="flex justify-center">
                    <button type="submit" name="update_agent" class="bg-blue-500 text-white px-8 py-4 rounded-md mt-4 font-bold hover:bg-blue-700">Update</button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>
