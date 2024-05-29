
function createCardNode(card) {
    const wrapperDiv = document.createElement('div');
    wrapperDiv.className = "bg-white rounded-lg shadow-md p-4";
    
    const imageEl = document.createElement('img');
    imageEl.src = card.image;
    imageEl.className = "w-full h-48 object-cover rounded-t-lg";

    const titleEl = document.createElement('h3');
    titleEl.textContent = card.title;
    titleEl.className = "text-xl font-bold mt-4 mb-2";
    
    const typeEl = document.createElement('p');
    typeEl.innerText = `${card.listing_type}`;
    typeEl.className = "text-gray-600 font-bold";
    
    const LocatieEl = document.createElement('p');
    LocatieEl.innerText = `Locatie: ${card.location}`;
    LocatieEl.className = "text-gray-600";

    const priceEl = document.createElement('p');
    priceEl.innerText = `Pret: ${card.price}`;
    priceEl.className = "text-gray-600";

    const roomsEl = document.createElement('p');
    roomsEl.innerText = `Camere: ${card.rooms}`;
    roomsEl.className = "text-gray-600";

    const bathroomsEl = document.createElement('p');
    bathroomsEl.innerText = `Bai: ${card.bathrooms}`;
    bathroomsEl.className = "text-gray-600 mb-4";

    const detailsLink = document.createElement('a');
    detailsLink.className = "block mt-4 text-center bg-customBlue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-customBlue-700";
    detailsLink.innerText = "Detalii";
    detailsLink.href = `property_details.php?id=${card.propertyid}`;

    wrapperDiv.append(imageEl, titleEl, typeEl, LocatieEl, priceEl, roomsEl, bathroomsEl, detailsLink);

    return wrapperDiv;
}

async function loadApartmentCards(search = '') {
    const cardContainer = document.querySelector('#card-container');
    
    // clear the old items
    cardContainer.innerHTML = '';

    // display loading
    
    const res = await fetch(`/ImobPlus/api/properties.php?q=${search}`);
    const data = await res.json();
    
    // hide loading

    if (data?.length === 0) {
        document.querySelector('#no-data').classList.toggle('hidden');
        return;    
    }

    // display elements
    data?.forEach(apartment => {
        const cardEl = createCardNode(apartment);
        cardContainer.append(cardEl);
    });
}

document.getElementById('search-form').onsubmit = (event) => {
    event.preventDefault();
    loadApartmentCards(event.target[0].value);
}

window.onload = () => loadApartmentCards();