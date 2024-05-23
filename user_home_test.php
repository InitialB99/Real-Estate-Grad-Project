<?php
session_start();

require_once 'Classes/Connect.php';
require_once 'Classes/Login.php';
require_once 'Classes/User.php';

if (!isset($_SESSION['realestate_sessionid']) || !is_numeric($_SESSION['realestate_sessionid'])) {
    header("Location: log_in.php");
    die;
}

    $id = $_SESSION['realestate_sessionid'];
    $login = new Login();
    $result = $login->check_login($id);

if ($result) {
    $user = new User($id);
    $user_data = $user->get_data($id);

    if (!$user_data) {
            header("Location: log_in.php");
            die;
            } else if ($user_data['access'] !== 0) {
                header("Location: home.php");
                die;
        }
    }else {
            header("Location: log_in.php");
            die;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaseste-ti proprietatea - ImobPlus</title>
    <link href="output.css" rel="stylesheet">
</head>

<body>
    <header class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold"><?php echo "Bine ai venit, " . htmlspecialchars($user_data['first_name']) . "!"; ?></h1>
            <nav>
                <ul class="list-none flex space-x-8">
                    <li><a href="user_dashboard.php" class="hover:shadow-lg">Contul Meu</a></li>
                    <li><a href="#" class="hover:shadow-lg">Contact</a></li>
                    <li><a href="log_out.php" class="font-bold hover:shadow-lg">Deconecteaza-te</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto py-12">

        <section class="search-bar flex flex-col items-center mb-8">
            <p class="text-xl font-bold mb-4">Cauta o proprietate</p>
            <form id="search-form" class="flex w-full">
                <input type="text" placeholder="Cauta o proprietate dupa locatie sau ID" class="w-1/2 px-4 py-2 border rounded-l-lg focus:outline-none focus:border-blue-500">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">Cauta</button>
            </form>
        </section>

        <section class="property-listings">
            <h2 class="text-2xl font-bold text-center mb-8">Proprietati recomandate</h2>
            <div id="card-container" class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <p id="no-data" class="text-center hidden">Nu s-au găsit proprietăți recomandate.</p>
            </div>
        </section>
    </main>

    <footer class="text-center p-4 bg-gray-200">
        <p>&copy; ImobPlus 2024</p>
    </footer>

</body>

</html>

<script>
    function createCardNode(card) {
        const wrapperDiv = document.createElement('div');
        wrapperDiv.className = "bg-white rounded-lg shadow-md p-4";
        
        const imageEl = document.createElement('img');
        imageEl.src = card.image;
        imageEl.className = "w-full h-48 object-cover rounded-t-lg"

        const titleEl = document.createElement('h3');
        titleEl.textContent = card.title;
        titleEl.className = "text-xl font-bold mt-4";
        
        const LocatieEl = document.createElement('p');
        LocatieEl.innerText = `Locatie: ${card.location}`
        titleEl.className = "text-xl font-bold mt-4";

        const priceEl = document.createElement('p');
        priceEl.innerText = `Pret: ${card.price}`

        const roomsEl = document.createElement('p');
        roomsEl.innerText = `Camere: ${card.rooms}`

        const bathroomsEl = document.createElement('p');
        bathroomsEl.innerText = `Bai: ${card.bathrooms}`


        wrapperDiv.append(imageEl, titleEl, LocatieEl, priceEl, roomsEl, bathroomsEl)

        return wrapperDiv;
    }

    async function loadApartmentCards(search = '') {
        const cardContainer = document.querySelector('#card-container');
        
        // clear the old items
        cardContainer.innerHTML = ''
    
        // display loading
        
        const res = await fetch(`/ImobPlus/api/properties.php?q=${search}`);
        const data = await res.json();
        
        // hide loading


        if (data?.length === 0) {
            document.querySelector('#no-data').classList.toggle('hidden')
            return;    
        }

        // display elememts
        data?.forEach(apartment => {
            const cardEl = createCardNode(apartment)
            cardContainer.append(cardEl);
        });
    }


    document.getElementById('search-form').onsubmit = (event) => {
        event.preventDefault()
        loadApartmentCards(event.target[0].value)
    }

    window.onload = () => loadApartmentCards()
</script>
