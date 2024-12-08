<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Cars</title>
    @vite('resources/css/app.css')
</head>
<body class="m-0 p-0 bg-black text-white font-poppins">

    <!-- Header Section -->
    <header class="relative w-full bg-no-repeat" style="background-image: url('assets/img/background/bg-car.png');">
    <nav class="flex justify-between items-center mx-[10%] p-[45px_50px] border-b-4 border-[#5C5857] pb-[5px] bg-transparent">
        <div class="flex w-[25px] h-[25px]">
            <img src="assets/img/logo/Group 72.png" alt="Naltlan Dealer Logo">
        </div>
        <ul class="flex gap-5">
    <li class="inline-block mr-2 relative">
        <a href="#" class="relative group">
            Home
            <span class="absolute left-0 right-0 bottom-0 top-9 h-1 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </a>
    </li>
    <li class="inline-block mr-2 relative">
        <a href="#" class="relative group">
            About us
            <span class="absolute left-0 right-0 bottom-0 top-9 h-1 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </a>
    </li>
    <li class="inline-block mr-2 relative">
        <a href="#" class="relative group">
            Supercar
            <span class="absolute left-0 right-0 bottom-0 top-9 h-1 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </a>
    </li>
    <li class="inline-block mr-2 relative">
        <a href="#" class="relative group">
            Engine
            <span class="absolute left-0 right-0 bottom-0 top-9 h-1 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </a>
    </li>
    <li class="inline-block mr-2 relative">
        <a href="#" class="relative group">
            Contact
            <span class="absolute left-0 right-0 bottom-0 top-9 h-1 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </a>
    </li>
</ul>

        <div class="text-[24px] text-[#747474]">
        <a href="/" class="relative group">
            <i class="fas fa-user-circle"></i>
        </a>
        </div>
    </nav>
    <div class="flex flex-auto">
        <div class="text-left py-[15%] bg-cover bg-center ml-[13%]">
            <h1 class="text-[4.5em] m-0 font-bold">NALTLAN DEALER</h1>
            <p class="text-[2.9em] mt-[-5%] font-medium">Supercars & Engines</p>
        </div>
        <div class="horizontal-line"></div>
    </div>
</header>


    <div class="absolute top-[62%] right-0 transform -translate-y-1/2 flex items-center z-100">
        <div class="flex flex-col items-end">
            <span class="text-white text-[20px] font-semibold">01</span>
            <span class="text-white text-[25px] font-semibold">02</span>
            <span class="text-white text-[35px] font-semibold">03</span>
            <span class="text-white text-[25px] font-semibold">04</span>
            <span class="text-white text-[20px] font-semibold">05</span>
        </div>
        <div class="flex-auto w-[180px] h-[5px] bg-white ml-[20px]"></div>
    </div>

    <div class="ml-[14%] flex justify-around w-[72%] pt-[35px] flex-wrap">
        <img src="assets/img/featured/steer.png" alt="Steering Wheel Icon" class="h-[40px] w-[40px] mt-[15px]">
        <div class="flex flex-col items-center max-w-[250px]">
            <h3 class="text-[10px] tracking-[1px] uppercase mb-[15px]">Where Does Beauty Come From?</h3>
            <p class="text-[11px]">This question has occupied the thoughts of philosophers, artists and scientists since the dawn of time. An enigma that continuously engages our thoughts as well.</p>
        </div>

        <img src="assets/img/featured/loading.png" alt="Empathy Icon" class="h-[40px] w-[40px] mt-[15px]">
        <div class="flex flex-col items-center max-w-[250px]">
            <h3 class="text-[10px] tracking-[1px] uppercase mb-[15px]">Empathy</h3>
            <p class="text-[11px]">Our visions embraced the desires and the fantasies of all the people who are part of the Pagani world — whether customers, collaborators, or enthusiasts — to forge a connection that enabled our latest creation to narrate the most important story: yours.</p>
        </div>

        <img src="assets/img/featured/kuas.png" alt="Craftsmanship Icon" class="h-[40px] w-[40px] mt-[15px]">
        <div class="flex flex-col items-center max-w-[250px]">
            <h3 class="text-[10px] tracking-[1px] uppercase mb-[15px]">Craftsmanship</h3>
            <p class="text-[11px]">The exquisite craftsmanship of the Utopia serves a precise purpose: to express the uniqueness of each owner through a car tailored to the smallest detail.</p>
        </div>
    </div>
    
    <div class="mx-[14%] my-[5%] border-b border-white"></div>



    <div class="text-center py-8">
        <h1 class="text-4xl font-bold">OUR CARS</h1>
        <p class="text-gray-500 mt-2">Available 70 Cars</p>
    </div>

    <!-- Filter Buttons -->
    <div class="flex justify-center gap-4 mb-8">
        <button class="px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded">New</button>
        <button class="px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded">Pre Order</button>
        <button class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded">All</button>
    </div>

    <!-- Cars Grid -->
    <div class="container mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

        <!-- Car Item -->
        <div class="shadow rounded-lg p-4 border hover:border-red-600 transition">
            <img src="https://hips.hearstapps.com/hmg-prod/images/2025-audi-rs6-avant-gt-motion-103-65bbc522aae10.jpg?crop=0.540xw:0.457xh;0.135xw,0.356xh&resize=1200:*" alt="Audi RS6 Avant" class="w-full h-48 object-cover rounded-md">
            <div class="mt-4">
                <h2 class="text-lg font-semibold">Audi RS6 Avant</h2>
                <p class="text-red-600 font-bold text-xl mt-2">$271,378.98</p>
                <div class="flex items-center mt-4 text-gray-500 text-sm">
                    <span class="flex items-center"><i class="fas fa-user mr-1"></i>4</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-cogs mr-1"></i>V8</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-road mr-1"></i>621 ft</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-tachometer-alt mr-1"></i>8 Speed</span>
                </div>
                <div class="flex mt-4 gap-2">
                    <button class="bg-red-600 text-white px-3 py-1 rounded">Buy</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Audi</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Type : RS6</button>
                </div>
            </div>
        </div>

        <!-- Car Item -->
        <div class="shadow rounded-lg p-4 border hover:border-red-600 transition">
            <img src="https://hips.hearstapps.com/hmg-prod/images/2025-audi-rs6-avant-gt-motion-103-65bbc522aae10.jpg?crop=0.540xw:0.457xh;0.135xw,0.356xh&resize=1200:*" alt="Audi RS6 Avant" class="w-full h-48 object-cover rounded-md">
            <div class="mt-4">
                <h2 class="text-lg font-semibold">Audi RS6 Avant</h2>
                <p class="text-red-600 font-bold text-xl mt-2">$271,378.98</p>
                <div class="flex items-center mt-4 text-gray-500 text-sm">
                    <span class="flex items-center"><i class="fas fa-user mr-1"></i>4</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-cogs mr-1"></i>V8</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-road mr-1"></i>621 ft</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-tachometer-alt mr-1"></i>8 Speed</span>
                </div>
                <div class="flex mt-4 gap-2">
                    <button class="bg-red-600 text-white px-3 py-1 rounded">Buy</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Audi</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Type : RS6</button>
                </div>
            </div>
        </div>

        <!-- Car Item -->
        <div class="shadow rounded-lg p-4 border hover:border-red-600 transition">
            <img src="https://hips.hearstapps.com/hmg-prod/images/2025-audi-rs6-avant-gt-motion-103-65bbc522aae10.jpg?crop=0.540xw:0.457xh;0.135xw,0.356xh&resize=1200:*" alt="Audi RS6 Avant" class="w-full h-48 object-cover rounded-md">
            <div class="mt-4">
                <h2 class="text-lg font-semibold">Audi RS6 Avant</h2>
                <p class="text-red-600 font-bold text-xl mt-2">$271,378.98</p>
                <div class="flex items-center mt-4 text-gray-500 text-sm">
                    <span class="flex items-center"><i class="fas fa-user mr-1"></i>4</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-cogs mr-1"></i>V8</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-road mr-1"></i>621 ft</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-tachometer-alt mr-1"></i>8 Speed</span>
                </div>
                <div class="flex mt-4 gap-2">
                    <button class="bg-red-600 text-white px-3 py-1 rounded">Buy</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Audi</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Type : RS6</button>
                </div>
            </div>
        </div>

        <!-- Car Item -->
        <div class="shadow rounded-lg p-4 border hover:border-red-600 transition">
            <img src="https://hips.hearstapps.com/hmg-prod/images/2025-audi-rs6-avant-gt-motion-103-65bbc522aae10.jpg?crop=0.540xw:0.457xh;0.135xw,0.356xh&resize=1200:*" alt="Audi RS6 Avant" class="w-full h-48 object-cover rounded-md">
            <div class="mt-4">
                <h2 class="text-lg font-semibold">Audi RS6 Avant</h2>
                <p class="text-red-600 font-bold text-xl mt-2">$271,378.98</p>
                <div class="flex items-center mt-4 text-gray-500 text-sm">
                    <span class="flex items-center"><i class="fas fa-user mr-1"></i>4</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-cogs mr-1"></i>V8</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-road mr-1"></i>621 ft</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-tachometer-alt mr-1"></i>8 Speed</span>
                </div>
                <div class="flex mt-4 gap-2">
                    <button class="bg-red-600 text-white px-3 py-1 rounded">Buy</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Audi</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Type : RS6</button>
                </div>
            </div>
        </div>

        <!-- Car Item -->
        <div class="shadow rounded-lg p-4 border hover:border-red-600 transition">
            <img src="https://hips.hearstapps.com/hmg-prod/images/2025-audi-rs6-avant-gt-motion-103-65bbc522aae10.jpg?crop=0.540xw:0.457xh;0.135xw,0.356xh&resize=1200:*" alt="Audi RS6 Avant" class="w-full h-48 object-cover rounded-md">
            <div class="mt-4">
                <h2 class="text-lg font-semibold">Audi RS6 Avant</h2>
                <p class="text-red-600 font-bold text-xl mt-2">$271,378.98</p>
                <div class="flex items-center mt-4 text-gray-500 text-sm">
                    <span class="flex items-center"><i class="fas fa-user mr-1"></i>4</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-cogs mr-1"></i>V8</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-road mr-1"></i>621 ft</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-tachometer-alt mr-1"></i>8 Speed</span>
                </div>
                <div class="flex mt-4 gap-2">
                    <button class="bg-red-600 text-white px-3 py-1 rounded">Buy</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Audi</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Type : RS6</button>
                </div>
            </div>
        </div>

        <!-- Car Item -->
        <div class="shadow rounded-lg p-4 border hover:border-red-600 transition">
            <img src="https://hips.hearstapps.com/hmg-prod/images/2025-audi-rs6-avant-gt-motion-103-65bbc522aae10.jpg?crop=0.540xw:0.457xh;0.135xw,0.356xh&resize=1200:*" alt="Audi RS6 Avant" class="w-full h-48 object-cover rounded-md">
            <div class="mt-4">
                <h2 class="text-lg font-semibold">Audi RS6 Avant</h2>
                <p class="text-red-600 font-bold text-xl mt-2">$271,378.98</p>
                <div class="flex items-center mt-4 text-gray-500 text-sm">
                    <span class="flex items-center"><i class="fas fa-user mr-1"></i>4</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-cogs mr-1"></i>V8</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-road mr-1"></i>621 ft</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-tachometer-alt mr-1"></i>8 Speed</span>
                </div>
                <div class="flex mt-4 gap-2">
                    <button class="bg-red-600 text-white px-3 py-1 rounded">Buy</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Audi</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Type : RS6</button>
                </div>
            </div>
        </div>

        <!-- Car Item -->
        <div class="shadow rounded-lg p-4 border hover:border-red-600 transition">
            <img src="https://hips.hearstapps.com/hmg-prod/images/2025-audi-rs6-avant-gt-motion-103-65bbc522aae10.jpg?crop=0.540xw:0.457xh;0.135xw,0.356xh&resize=1200:*" alt="Audi RS6 Avant" class="w-full h-48 object-cover rounded-md">
            <div class="mt-4">
                <h2 class="text-lg font-semibold">Audi RS6 Avant</h2>
                <p class="text-red-600 font-bold text-xl mt-2">$271,378.98</p>
                <div class="flex items-center mt-4 text-gray-500 text-sm">
                    <span class="flex items-center"><i class="fas fa-user mr-1"></i>4</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-cogs mr-1"></i>V8</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-road mr-1"></i>621 ft</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-tachometer-alt mr-1"></i>8 Speed</span>
                </div>
                <div class="flex mt-4 gap-2">
                    <button class="bg-red-600 text-white px-3 py-1 rounded">Buy</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Audi</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Type : RS6</button>
                </div>
            </div>
        </div>

        <!-- Car Item -->
        <div class="shadow rounded-lg p-4 border hover:border-red-600 transition">
            <img src="https://hips.hearstapps.com/hmg-prod/images/2025-audi-rs6-avant-gt-motion-103-65bbc522aae10.jpg?crop=0.540xw:0.457xh;0.135xw,0.356xh&resize=1200:*" alt="Audi RS6 Avant" class="w-full h-48 object-cover rounded-md">
            <div class="mt-4">
                <h2 class="text-lg font-semibold">Audi RS6 Avant</h2>
                <p class="text-red-600 font-bold text-xl mt-2">$271,378.98</p>
                <div class="flex items-center mt-4 text-gray-500 text-sm">
                    <span class="flex items-center"><i class="fas fa-user mr-1"></i>4</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-cogs mr-1"></i>V8</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-road mr-1"></i>621 ft</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-tachometer-alt mr-1"></i>8 Speed</span>
                </div>
                <div class="flex mt-4 gap-2">
                    <button class="bg-red-600 text-white px-3 py-1 rounded">Buy</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Audi</button>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">Type : RS6</button>
                </div>
            </div>
        </div>
        
    </div>

    

</body>
</html>
