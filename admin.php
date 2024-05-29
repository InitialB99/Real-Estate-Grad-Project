<?php
session_start();

require_once 'Classes/Connect.php';
require_once 'Classes/checks.php';

$db = new Database();
$check = new Checks();

// Check if the user is an admin
$id = $_SESSION['realestate_sessionid'];
$user_data = $check->check_admin($id); // Implement check_admin method to verify admin access
if (!$user_data) {
    header("Location: log_in.php");
    die;
}

// Fetch all users
$users = $db->read("SELECT id, first_name, access FROM users where id <> 1");

// Fetch all properties
$properties = $db->read("SELECT propertyid, title, featured FROM properties");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update user access
    if (isset($_POST['update_access'])) {
        $user_id = $_POST['user_id'];
        $access_level = $_POST['access_level'];
        $db->save("UPDATE users SET access = ? WHERE id = ?", [$access_level, $user_id]);
    }

    // Update property featured status
    if (isset($_POST['update_featured'])) {
        $property_id = $_POST['property_id'];
        $featured = $_POST['featured'];
        $db->save("UPDATE properties SET featured = ? WHERE propertyid = ?", [$featured, $property_id]);
    }

    // Refresh to show updated data
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page - ImobPlus</title>
    <link href="output.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="bg-customOrange-500 border-gray-200">
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
                    <ul class="flex flex-col py-2 px-4 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-customOrange-500">
                        <li>
                            <a href="agent_dashboard.php" class="block hover:md:text-gray-900 py-1 px-2 text-white rounded md:hover:bg-transparent md:border-0 md:p-0">Inapoi</a>
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
        <section>
            <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>

            <h3 class="text-xl font-semibold mb-2">Update User Access</h3>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">User ID</th>
                        <th class="py-2 px-4 border-b">Name</th>
                        <th class="py-2 px-4 border-b">Access Level</th>
                        <th class="py-2 px-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo $user['id']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($user['first_name']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $user['access']; ?></td>
                        <td class="py-2 px-4 border-b">
                            <form method="post">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <select name="access_level">
                                    <option value="0" <?php if ($user['access'] == 0) echo 'selected'; ?>>User</option>
                                    <option value="1" <?php if ($user['access'] == 1) echo 'selected'; ?>>Agent</option>
                                </select>
                                <button type="submit" name="update_access" class="bg-customBlue-500 text-white px-2 py-1 rounded">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3 class="text-xl font-semibold mt-8 mb-2">Update Property Featured Status</h3>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Property ID</th>
                        <th class="py-2 px-4 border-b">Title</th>
                        <th class="py-2 px-4 border-b">Featured</th>
                        <th class="py-2 px-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($properties as $property): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo $property['propertyid']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($property['title']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $property['featured']; ?></td>
                        <td class="py-2 px-4 border-b">
                            <form method="post">
                                <input type="hidden" name="property_id" value="<?php echo $property['propertyid']; ?>">
                                <select name="featured">
                                    <option value="0" <?php if ($property['featured'] == 0) echo 'selected'; ?>>No</option>
                                    <option value="1" <?php if ($property['featured'] == 1) echo 'selected'; ?>>Yes</option>
                                </select>
                                <button type="submit" name="update_featured" class="bg-customBlue-500 text-white px-2 py-1 rounded">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
