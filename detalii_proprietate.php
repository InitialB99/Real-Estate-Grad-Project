<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalii proprietate</title>
  <link href="output.css" rel="stylesheet">
</head>

<body>
  <header class="bg-blue-500 p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-xl font-bold">ImobPlus</h1>
      <nav>
        <ul class="list-none flex space-x-8">
          <li><a href="#" class="hover:shadow-lg">Inapoi</a></li>
          <li><a href="contact.php" class="hover:shadow-lg">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container mx-auto py-12">

    <section class="property-details flex flex-wrap">
      <div class="images w-full md:w-1/2 mb-8 md:mb-0">
        <div class="swiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="property-image1.jpg" alt="Property Image 1" class="w-full h-auto object-cover rounded-lg">
            </div>
            <div class="swiper-slide">
              <img src="property-image2.jpg" alt="Property Image 2" class="w-full h-auto object-cover rounded-lg">
            </div>
            </div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
        </div>
      <div class="details w-full md:w-1/2 px-4">
        <h2 class="text-2xl font-bold mb-4">Titlul Proprietatii</h2>
        <p class="text-gray-600 mb-2">Locatie: Oras, Judet</p>
        <p class="text-gray-600 mb-2">Pret: $X,XXX,XXX</p>
        <div class="grid grid-cols-2 gap-4 mb-4">
          <div class="text-sm font-medium">Camere:</div>
          <div class="text-sm font-bold">X</div>
          <div class="text-sm font-medium">Bai:</div>
          <div class="text-sm font-bold">X</div>
          <div class="text-sm font-medium">Suprafata:</div>
          <div class="text-sm font-bold">Y mp²</div>
        </div>
        <p class="text-gray-600 mb-4">Descriere detaliata a proprietatii...</p>

        <div class="contact-agent bg-gray-100 rounded-lg p-4 mb-8">
          <h3 class="text-xl font-bold mb-2">Contacteaza Agentul</h3>
          <p class="text-gray-600 mb-2">Numarul Agentului: <span class="font-bold">XXXX-YYYY-ZZZZ</span></p>
          <form action="#">
            <textarea name="message" id="message" rows="5" class="w-full rounded-lg border px-4 py-2 focus:outline-none focus:border-blue-500"></textarea>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700 mt-4">Trimite mesaj</button>
          </form>
        </div>

        <button class="bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700">Salveaza Anuntul</button>
      </div>
    </section>

  </main>

  
