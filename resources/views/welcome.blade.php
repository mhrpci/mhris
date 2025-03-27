<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MHR Property Conglomerate Inc. | Leading Business Conglomerate</title>
    <meta name="description" content="MHR Property Conglomerate Inc. (MHRPCI) - A diverse business conglomerate operating across healthcare, fuel distribution, construction, and hospitality sectors in the Philippines.">
    <meta name="keywords" content="MHRPCI, MHR Property Conglomerate, healthcare, fuel distribution, construction, hospitality, Cebu business, medical supplies">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-dark: #4338CA;
            --primary-light: #EEF2FF;
            --text-dark: #1F2937;
            --text-light: #F9FAFB;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        html {
            font-size: 100%;
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }
        
        @media (max-width: 320px) {
            html { font-size: 85%; }
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.5;
            min-width: 320px; /* Set minimum width for very small devices */
            overflow-x: hidden;
        }
        
        /* Container for smaller screens */
        .container {
            width: 100%;
            max-width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        @media (min-width: 640px) {
            .container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        
        @media (min-width: 1280px) {
            .container {
                max-width: 1280px;
                margin-left: auto;
                margin-right: auto;
            }
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }
        
        .text-gradient {
            background: linear-gradient(90deg, #4F46E5, #7C3AED);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .service-card {
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .nav-link.active {
            color: #6D28D9;
            border-bottom: 2px solid #6D28D9;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        /* Responsive improvements */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100%, 1fr));
            gap: 1.5rem;
        }
        
        @media (min-width: 640px) {
            .grid {
                grid-template-columns: repeat(auto-fit, minmax(275px, 1fr));
            }
        }
        
        /* Improve touch target sizes on mobile */
        @media (max-width: 768px) {
            .nav-link, button, a.bg-indigo-600, .service-card a {
                padding: 0.625rem 1rem;
                min-height: 48px;
            }
            
            button, a.bg-indigo-600 {
                min-height: 48px;
                min-width: 48px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            input, textarea, select {
                font-size: 16px; /* Prevents iOS zoom on focus */
                padding: 0.75rem;
            }
            
            .service-card {
                padding: 1.25rem;
            }
        }
        
        /* Fix for very small screens */
        @media (max-width: 359px) {
            .flex-wrap {
                justify-content: center;
            }
            
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }
        }
        
        /* Ensure all images scale properly */
        img, iframe, video, object {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }
        
        /* Typography improvements */
        h1, h2, h3, h4, h5, h6 {
            line-height: 1.2;
            overflow-wrap: break-word;
            word-wrap: break-word;
            hyphens: auto;
        }
        
        p {
            max-width: 100%;
            overflow-wrap: break-word;
        }
        
        /* Accessibility improvements */
        a:focus, button:focus, input:focus, textarea:focus, select:focus {
            outline: 2px solid #4F46E5;
            outline-offset: 2px;
        }
        
        /* Flex utility for service cards */
        .flex-grow {
            flex-grow: 1;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header & Navigation -->
    <header class="bg-white shadow-md fixed w-full z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <img src="{{ asset('vendor/adminlte/dist/img/LOGO_ICON.png') }}" alt="MHRPCI Logo" class="h-8 sm:h-10 md:h-12 w-auto">
                    <div>
                        <h1 class="text-base sm:text-lg md:text-xl font-bold text-indigo-700">MHRPCI</h1>
                        <p class="text-xs text-gray-500 hidden xs:block">Property Conglomerate Inc.</p>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-3 lg:space-x-8">
                    <a href="#home" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300 px-2 py-1">Home</a>
                    <a href="#about" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300 px-2 py-1">About Us</a>
                    <a href="#services" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300 px-2 py-1">Our Services</a>
                    <a href="#history" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300 px-2 py-1">Our History</a>
                    <a href="#careers" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300 px-2 py-1">MHR Careers</a>
                </nav>
                
                <!-- Contact Button -->
                <div class="hidden md:block">
                    <a href="#contact" class="bg-indigo-600 text-white px-4 lg:px-5 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 text-sm lg:text-base">Contact Us</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-indigo-600 focus:outline-none p-2" aria-label="Toggle menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 py-2 animate-fadeIn">
                <a href="#home" class="block py-3 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Home</a>
                <a href="#about" class="block py-3 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">About Us</a>
                <a href="#services" class="block py-3 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Our Services</a>
                <a href="#history" class="block py-3 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Our History</a>
                <a href="#careers" class="block py-3 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">MHR Careers</a>
                <a href="#contact" class="block py-3 px-4 text-indigo-600 font-medium">Contact Us</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero-gradient pt-24 sm:pt-28 md:pt-36 lg:pt-40 pb-12 sm:pb-16 md:pb-24 lg:pb-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-6 md:gap-8 items-center">
                <div data-aos="fade-right" data-aos-duration="1000" class="text-center md:text-left">
                    <h2 class="text-white text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mb-4 sm:mb-6">Transforming Industries, Empowering Growth</h2>
                    <p class="text-indigo-100 text-sm xs:text-base sm:text-lg mb-6 sm:mb-8 max-w-2xl mx-auto md:mx-0">MHR Property Conglomerate Inc. is a diverse business group operating across healthcare, fuel distribution, construction, and hospitality sectors in the Philippines.</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-3 sm:gap-4">
                        <a href="#about" class="bg-white text-indigo-600 px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-300 text-sm sm:text-base">Discover More</a>
                        <a href="#contact" class="border-2 border-white text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-medium hover:bg-white hover:text-indigo-600 transition duration-300 text-sm sm:text-base">Contact Us</a>
                    </div>
                </div>
                <div class="hidden md:block" data-aos="fade-left" data-aos-duration="1000">
                    <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO4.png') }}" alt="MHRPCI Logo" class="w-full max-w-md mx-auto">
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4" data-aos="fade-up">About Us</h2>
                <div class="w-20 h-1 bg-indigo-600 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    Learn more about MHR Property Conglomerate Inc., our mission, vision, and the values that drive our success across multiple industries.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <h3 class="text-2xl font-semibold mb-4 text-indigo-700">Who We Are</h3>
                    <p class="text-gray-600 mb-4">
                        MHR Property Conglomerate Inc. (MHRPCI) is a dynamic business group with a diverse portfolio spanning healthcare, fuel distribution, construction, and hospitality. Beginning in 2000 with the establishment of Cebic Trading, MHRPCI has grown into a leading conglomerate with multiple companies working in synergy across various industries.
                    </p>
                    <p class="text-gray-600 mb-6">
                        Today, we're proud to have expanded our operations across the Philippines, delivering excellent products and services while creating value for our stakeholders and contributing to community development.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-indigo-600 mb-2">Our Mission</h4>
                            <p class="text-gray-600 text-sm">To deliver excellence across industries through innovative solutions and sustainable practices.</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-indigo-600 mb-2">Our Vision</h4>
                            <p class="text-gray-600 text-sm">To be a leading conglomerate that transforms industries and empowers growth across the Philippines and beyond.</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-gray-700">Integrity & Excellence</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-gray-700">Innovation & Growth</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-gray-700">Social Responsibility</span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4" data-aos="fade-left" data-aos-duration="1000">
                    <div class="space-y-4">
                        <img src="{{ asset('vendor/adminlte/dist/img/companies.png') }}" alt="MHRPCI Companies" class="rounded-lg shadow-md">
                        <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="MHRPCI Logo" class="rounded-lg shadow-md h-40 w-full object-contain bg-gray-100 p-4">
                    </div>
                    <div class="relative w-full h-full rounded-lg overflow-hidden shadow-md">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full"
                            src="https://www.youtube.com/embed/4DRktuQ5tno"
                            title="About MHRPCI"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Services Section -->
    <section id="services" class="py-12 xs:py-16 sm:py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-12 md:mb-16">
                <h2 class="text-xl xs:text-2xl sm:text-3xl md:text-4xl font-bold mb-3 sm:mb-4" data-aos="fade-up">Our Services</h2>
                <div class="w-16 sm:w-20 h-1 bg-indigo-600 mx-auto mb-4 sm:mb-6"></div>
                <p class="text-gray-600 max-w-3xl mx-auto text-sm sm:text-base" data-aos="fade-up" data-aos-delay="200">
                    MHRPCI delivers exceptional services across multiple industries, driven by innovation and commitment to excellence.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 md:gap-8">
                <!-- Healthcare Service -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 service-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-10 h-10 xs:w-12 xs:h-12 sm:w-14 sm:h-14 bg-indigo-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                        <i class="fas fa-hospital text-indigo-600 text-lg xs:text-xl sm:text-2xl"></i>
                    </div>
                    <h3 class="text-base xs:text-lg sm:text-xl font-semibold mb-2 sm:mb-3 text-gray-800">Healthcare</h3>
                    <p class="text-gray-600 mb-3 sm:mb-4 text-sm sm:text-base flex-grow">
                        Through MHRHCI, we provide high-quality medical supplies, equipment, and healthcare solutions to hospitals and clinics across the Philippines.
                    </p>
                    <ul class="text-gray-600 space-y-1 sm:space-y-2 mb-3 sm:mb-4 text-sm sm:text-base">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle-check text-indigo-600 mt-1 text-xs sm:text-sm"></i>
                            <span>Medical Supplies Distribution</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle-check text-indigo-600 mt-1 text-xs sm:text-sm"></i>
                            <span>Hospital Equipment</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle-check text-indigo-600 mt-1 text-xs sm:text-sm"></i>
                            <span>Healthcare Consulting</span>
                        </li>
                    </ul>
                    <a href="{{ route('mhrhci') }}" class="text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center p-1 text-sm sm:text-base">
                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Fuel Distribution Service -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 service-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-10 h-10 xs:w-12 xs:h-12 sm:w-14 sm:h-14 bg-indigo-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                        <i class="fas fa-gas-pump text-indigo-600 text-lg xs:text-xl sm:text-2xl"></i>
                    </div>
                    <h3 class="text-base xs:text-lg sm:text-xl font-semibold mb-2 sm:mb-3 text-gray-800">Fuel Distribution</h3>
                    <p class="text-gray-600 mb-3 sm:mb-4 text-sm sm:text-base flex-grow">
                        We deliver efficient and reliable fuel distribution services across the Philippines through our Bay Gas Petroleum Distributors Inc.
                    </p>
                    <ul class="text-gray-600 space-y-1 sm:space-y-2 mb-3 sm:mb-4 text-sm sm:text-base">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle-check text-indigo-600 mt-1 text-xs sm:text-sm"></i>
                            <span>Petroleum Distribution</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle-check text-indigo-600 mt-1 text-xs sm:text-sm"></i>
                            <span>LPG Supply Chain</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle-check text-indigo-600 mt-1 text-xs sm:text-sm"></i>
                            <span>Fuel Management Services</span>
                        </li>
                    </ul>
                    <a href="{{ route('bgpdi') }}" class="text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center p-1 text-sm sm:text-base">
                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Construction Service -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 service-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-10 h-10 xs:w-12 xs:h-12 sm:w-14 sm:h-14 bg-indigo-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                        <i class="fas fa-hard-hat text-indigo-600 text-lg xs:text-xl sm:text-2xl"></i>
                    </div>
                    <h3 class="text-base xs:text-lg sm:text-xl font-semibold mb-2 sm:mb-3 text-gray-800">Construction</h3>
                    <p class="text-gray-600 mb-3 sm:mb-4 text-sm sm:text-base flex-grow">
                        Our construction division delivers quality construction and development projects with a focus on sustainability and excellence.
                    </p>
                    <ul class="text-gray-600 space-y-1 sm:space-y-2 mb-3 sm:mb-4 text-sm sm:text-base">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle-check text-indigo-600 mt-1 text-xs sm:text-sm"></i>
                            <span>Commercial Construction</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle-check text-indigo-600 mt-1 text-xs sm:text-sm"></i>
                            <span>Residential Development</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle-check text-indigo-600 mt-1 text-xs sm:text-sm"></i>
                            <span>Project Management</span>
                        </li>
                    </ul>
                    <a href="{{ route('cio') }}" class="text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center p-1 text-sm sm:text-base">
                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- View All Services Button -->
            <div class="text-center mt-8 sm:mt-10 md:mt-12" data-aos="fade-up" data-aos-delay="500">
                <a href="{{ route('all_subsidiaries') }}" class="bg-indigo-600 text-white px-4 sm:px-6 md:px-8 py-2 sm:py-3 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-flex items-center text-sm sm:text-base">
                    View All Services <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Our History Section -->
    <section id="history" class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4" data-aos="fade-up">Our History</h2>
                <div class="w-20 h-1 bg-indigo-600 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    From humble beginnings to a thriving conglomerate, explore the journey that shaped MHRPCI into what it is today.
                </p>
            </div>

            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-indigo-100"></div>
                
                <!-- Timeline Items -->
                <div class="space-y-16">
                    <!-- 2000: Foundation -->
                    <div class="relative" data-aos="fade-up">
                        <div class="absolute left-1/2 transform -translate-x-1/2 -mt-3">
                            <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <div class="md:text-right md:pr-12 order-2 md:order-1">
                                <h3 class="text-2xl font-semibold text-indigo-600 mb-2">2000: Our Beginning</h3>
                                <p class="text-gray-600">
                                    MHR Property Conglomerate Inc. began in 2000 with the establishment of Cebic Trading, starting with just a 20,000-peso capital in hospital and office medical supplies.
                                </p>
                            </div>
                            <div class="md:pl-12 order-1 md:order-2">
                                <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="Founding of MHRPCI" class="rounded-lg shadow-md w-full max-w-xs mx-auto">
                            </div>
                        </div>
                    </div>
                    
                    <!-- 2003: Expansion -->
                    <div class="relative" data-aos="fade-up" data-aos-delay="100">
                        <div class="absolute left-1/2 transform -translate-x-1/2 -mt-3">
                            <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <div class="md:pl-12 order-2">
                                <img src="{{ asset('vendor/adminlte/dist/img/companies.png') }}" alt="MHRHCI Formation" class="rounded-lg shadow-md w-full max-w-xs mx-auto">
                            </div>
                            <div class="md:pr-12 order-1">
                                <h3 class="text-2xl font-semibold text-indigo-600 mb-2">2003: Healthcare Expansion</h3>
                                <p class="text-gray-600">
                                    In 2003, we expanded operations in Cebu by forming Medical & Hospital Resources Health Care, Inc. (MHRHCI) to focus on medical supplies and forge international partnerships.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 2010: Diversification -->
                    <div class="relative" data-aos="fade-up" data-aos-delay="200">
                        <div class="absolute left-1/2 transform -translate-x-1/2 -mt-3">
                            <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <div class="md:text-right md:pr-12 order-2 md:order-1">
                                <h3 class="text-2xl font-semibold text-indigo-600 mb-2">2010: Industry Diversification</h3>
                                <p class="text-gray-600">
                                    By 2010, MHRPCI had diversified its portfolio by entering the fuel distribution industry with Bay Gas Petroleum Distributors Inc. and expanded into construction and development projects.
                                </p>
                            </div>
                            <div class="md:pl-12 order-1 md:order-2">
                                <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="MHRPCI Diversification" class="rounded-lg shadow-md w-full max-w-xs mx-auto">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Present: Conglomerate -->
                    <div class="relative" data-aos="fade-up" data-aos-delay="300">
                        <div class="absolute left-1/2 transform -translate-x-1/2 -mt-3">
                            <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg">
                                <i class="fas fa-flag"></i>
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <div class="md:pl-12 order-2">
                                <img src="{{ asset('vendor/adminlte/dist/img/companies.png') }}" alt="MHRPCI Today" class="rounded-lg shadow-md w-full max-w-xs mx-auto">
                            </div>
                            <div class="md:pr-12 order-1">
                                <h3 class="text-2xl font-semibold text-indigo-600 mb-2">Today: A Thriving Conglomerate</h3>
                                <p class="text-gray-600">
                                    Today, MHRPCI has grown into a conglomerate with 10 companies working in synergy across various industries including healthcare, fuel distribution, construction, and hospitality.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MHR Careers Section -->
    <section id="careers" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4" data-aos="fade-up">MHR Careers</h2>
                <div class="w-20 h-1 bg-indigo-600 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    Join our dynamic team and grow your career with MHRPCI. We offer exciting opportunities across multiple industries.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <h3 class="text-2xl font-semibold mb-6 text-indigo-700">Why Join Our Team?</h3>
                    
                    <div class="space-y-6">
                        <div class="flex space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                    <i class="fas fa-rocket text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xl font-medium mb-2">Growth Opportunities</h4>
                                <p class="text-gray-600">
                                    We offer continuous learning and development programs to help our employees grow professionally in a dynamic environment.
                                </p>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xl font-medium mb-2">Collaborative Culture</h4>
                                <p class="text-gray-600">
                                    Work with talented professionals in a collaborative environment that values teamwork and innovation.
                                </p>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                    <i class="fas fa-medal text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xl font-medium mb-2">Competitive Benefits</h4>
                                <p class="text-gray-600">
                                    We offer competitive compensation packages and comprehensive benefits to support our employees' wellbeing.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('careers') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition duration-300">View Open Positions</a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6" data-aos="fade-left" data-aos-duration="1000">
                    <!-- Featured Job Positions -->
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="text-indigo-600 mb-4">
                            <i class="fas fa-briefcase-medical text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Healthcare Professionals</h4>
                        <p class="text-gray-600 text-sm mb-4">Join our healthcare division and make a difference in the lives of patients.</p>
                        <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Full-time</span>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="text-indigo-600 mb-4">
                            <i class="fas fa-code text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">IT Specialists</h4>
                        <p class="text-gray-600 text-sm mb-4">Drive technological innovation across our various business units.</p>
                        <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Full-time</span>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="text-indigo-600 mb-4">
                            <i class="fas fa-chart-bar text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Business Analysts</h4>
                        <p class="text-gray-600 text-sm mb-4">Help shape our business strategy with data-driven insights.</p>
                        <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Full-time</span>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="text-indigo-600 mb-4">
                            <i class="fas fa-truck text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Logistics Specialists</h4>
                        <p class="text-gray-600 text-sm mb-4">Optimize our supply chain operations across multiple industries.</p>
                        <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Full-time</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-12 xs:py-16 sm:py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-12 md:mb-16">
                <h2 class="text-xl xs:text-2xl sm:text-3xl md:text-4xl font-bold mb-3 sm:mb-4" data-aos="fade-up">Contact Us</h2>
                <div class="w-16 sm:w-20 h-1 bg-indigo-600 mx-auto mb-4 sm:mb-6"></div>
                <p class="text-gray-600 max-w-3xl mx-auto text-sm sm:text-base" data-aos="fade-up" data-aos-delay="200">
                    Get in touch with our team. We're here to answer your questions and provide the information you need.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 md:gap-10 lg:gap-12">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <div class="bg-gray-50 p-5 sm:p-6 md:p-8 rounded-xl shadow-md">
                        <h3 class="text-lg xs:text-xl sm:text-2xl font-semibold mb-4 sm:mb-6 text-indigo-700">Send us a message</h3>
                        
                        <form>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Your Name</label>
                                    <input type="text" id="name" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Email Address</label>
                                    <input type="email" id="email" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                                </div>
                            </div>
                            <div class="mb-4 sm:mb-6">
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Subject</label>
                                <input type="text" id="subject" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                            </div>
                            <div class="mb-4 sm:mb-6">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Message</label>
                                <textarea id="message" rows="4" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent"></textarea>
                            </div>
                            <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white px-5 sm:px-6 py-2 sm:py-3 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 text-sm sm:text-base">Send Message</button>
                        </form>
                    </div>
                </div>

                <div data-aos="fade-left" data-aos-duration="1000">
                    <div class="bg-gray-50 p-8 rounded-xl shadow-md h-full">
                        <h3 class="text-2xl font-semibold mb-6 text-indigo-700">Our Information</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-map-marker-alt text-xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium mb-1">Office Address</h4>
                                    <p class="text-gray-600">
                                        MHR Building, Cebu City, Philippines<br>
                                        6000
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-phone-alt text-xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium mb-1">Phone Number</h4>
                                    <p class="text-gray-600">+63 (32) 123 4567</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-envelope text-xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium mb-1">Email Address</h4>
                                    <p class="text-gray-600">info@mhrpci.site</p>
                                </div>
                            </div>
                        </div>

                        <!-- Map Embed -->
                        <div class="mt-8 rounded-lg overflow-hidden shadow-md">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3925.548838499838!2d123.88810111744384!3d10.297145070566!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a9994ff893430f%3A0x89d023abb6ff3793!2sCebu%20City%2C%20Cebu!5e0!3m2!1sen!2sph!4v1653647029565!5m2!1sen!2sph"
                                width="100%" 
                                height="250" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>

                        <!-- Social Media -->
                        <div class="mt-8">
                            <h4 class="text-lg font-medium mb-4">Connect With Us</h4>
                            <div class="flex space-x-4">
                                <a href="https://www.facebook.com/mhrpciofficial/" target="_blank" class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 hover:bg-indigo-600 hover:text-white transition duration-300">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 hover:bg-indigo-600 hover:text-white transition duration-300">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 hover:bg-indigo-600 hover:text-white transition duration-300">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO_ICON.png') }}" alt="MHRPCI Logo" class="h-10 w-auto">
                        <div>
                            <h3 class="text-lg font-bold">MHRPCI</h3>
                            <p class="text-gray-400 text-xs">Property Conglomerate Inc.</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        A diverse business conglomerate operating across healthcare, fuel distribution, construction, and hospitality sectors.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#about" class="hover:text-indigo-400 transition duration-300">About Us</a></li>
                        <li><a href="#services" class="hover:text-indigo-400 transition duration-300">Our Services</a></li>
                        <li><a href="#history" class="hover:text-indigo-400 transition duration-300">Our History</a></li>
                        <li><a href="#careers" class="hover:text-indigo-400 transition duration-300">MHR Careers</a></li>
                        <li><a href="#contact" class="hover:text-indigo-400 transition duration-300">Contact Us</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Our Companies</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('mhrhci') }}" class="hover:text-indigo-400 transition duration-300">MHRHCI</a></li>
                        <li><a href="{{ route('bgpdi') }}" class="hover:text-indigo-400 transition duration-300">Bay Gas</a></li>
                        <li><a href="{{ route('cio') }}" class="hover:text-indigo-400 transition duration-300">Cebic Industries</a></li>
                        <li><a href="{{ route('rcg') }}" class="hover:text-indigo-400 transition duration-300">RCG Construction</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('terms') }}" class="hover:text-indigo-400 transition duration-300">Terms of Service</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-indigo-400 transition duration-300">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-10 pt-6 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} MHR Property Conglomerate Inc. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Initialize AOS animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add 'xs' class to body if viewport is less than 400px for extra-small device detection
            if (window.innerWidth < 400) {
                document.body.classList.add('xs');
            }
            
            AOS.init({
                once: true,
                disable: window.innerWidth < 768 ? true : false,
                duration: 700,
                easing: 'ease-out-cubic',
                delay: 100,
                offset: 120
            });
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
                
                // Close mobile menu when clicking on a link
                const mobileLinks = document.querySelectorAll('#mobile-menu a');
                mobileLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }
            
            // Navigation active state
            const navLinks = document.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('section');
            
            const setActiveNavLink = () => {
                let current = '';
                const scrollPosition = window.pageYOffset;
                
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    
                    if (scrollPosition >= (sectionTop - sectionHeight / 3)) {
                        current = section.getAttribute('id');
                    }
                });
                
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').substring(1) === current) {
                        link.classList.add('active');
                    }
                });
            };
            
            // Set active link on scroll with throttling for performance
            let isScrolling = false;
            window.addEventListener('scroll', function() {
                if (!isScrolling) {
                    window.requestAnimationFrame(function() {
                        setActiveNavLink();
                        isScrolling = false;
                    });
                    isScrolling = true;
                }
            });
            
            // Set active link on page load
            setActiveNavLink();
            
            // Handle resize events
            window.addEventListener('resize', function() {
                const isXS = window.innerWidth < 400;
                if (isXS) {
                    document.body.classList.add('xs');
                } else {
                    document.body.classList.remove('xs');
                }
            });
        });
    </script>
</body>
</html>
