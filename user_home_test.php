<?php
session_start();

require_once 'Classes/Connect.php';
require_once 'Classes/Login.php';
require_once 'Classes/User.php';
require_once 'Classes/checks.php';

// Check user
$id = $_SESSION['realestate_sessionid'];
$checks = new checks();
$user_data = $checks->check_client($id);

if(!$user_data){
    header("Location: log_in.php");
}
$db = new Database();

//SAVE PROPERTY
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_property'])) {
    $property_id = $_POST['property_id'];

    $query = "select id from users where sessionid = ?";
    $params = [$id];
    $result = $db->read($query, $params)[0];
    $userid = $result['id'];

    $query = "insert into saved_properties (userid, spropertyid) 
              values (?, ?)";
    $params = [$userid, $property_id];
    $db->save($query, $params);
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
        titleEl.className = "text-gray-600";

        const priceEl = document.createElement('p');
        priceEl.innerText = `Pret: ${card.price}`
        priceEl.className = "text-gray-600";

        const roomsEl = document.createElement('p');
        roomsEl.innerText = `Camere: ${card.rooms}`
        roomsEl.className = "text-gray-600";

        const bathroomsEl = document.createElement('p');
        bathroomsEl.innerText = `Bai: ${card.bathrooms}`
        bathroomsEl.className = "text-gray-600";

        const buttonsContainer = document.createElement('div');
        buttonsContainer.className = "flex justify-between mt-4";

        const detailsLink =document.createElement('a');
        detailsLink.className = "bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700"
        detailsLink.innerText = "Detalii"
        detailsLink.href = `detalii_proprietate.php?${card.propertyid}`

        // Create form element
        const form = document.createElement('form');
        form.setAttribute('action', 'user_home_test.php');
        form.setAttribute('method', 'post');

        // Create hidden input element
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'property_id');
        hiddenInput.setAttribute('value', card.propertyid);

        // Create button element
        const button = document.createElement('button');
        button.setAttribute('type', 'submit');
        button.setAttribute('name', 'save_property');
        button.classList.add('bg-green-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'font-bold', 'hover:bg-green-700');
        button.textContent = 'Salveaza';

        // Append hidden input and button to form
        form.appendChild(hiddenInput);
        form.appendChild(button);

        buttonsContainer.append(detailsLink, form)
        wrapperDiv.append(imageEl, titleEl, LocatieEl, priceEl, roomsEl, bathroomsEl, buttonsContainer)

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
