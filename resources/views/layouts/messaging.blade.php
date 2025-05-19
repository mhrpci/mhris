<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Messaging App')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.12.0/cdn.min.js" defer></script>
    
    @yield('styles')
</head>
<body class="bg-gray-100 h-screen flex flex-col">
    <header class="bg-blue-600 text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-xl font-bold">Messaging App</h1>
            </div>
            <nav class="hidden md:block">
                <ul class="flex space-x-6">
                    <li><a href="#" class="hover:text-blue-200">Home</a></li>
                    <li><a href="#" class="hover:text-blue-200">Messages</a></li>
                    <li><a href="#" class="hover:text-blue-200">Contacts</a></li>
                    <li><a href="#" class="hover:text-blue-200">Settings</a></li>
                </ul>
            </nav>
            <div class="flex items-center space-x-4">
                <button class="md:hidden" x-data="{}" @click="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <i class="fas fa-bars text-white text-xl"></i>
                </button>
                <div class="hidden md:flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-blue-400 flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-blue-700 px-4 py-2">
            <ul class="space-y-2">
                <li><a href="#" class="block hover:text-blue-200">Home</a></li>
                <li><a href="#" class="block hover:text-blue-200">Messages</a></li>
                <li><a href="#" class="block hover:text-blue-200">Contacts</a></li>
                <li><a href="#" class="block hover:text-blue-200">Settings</a></li>
                <li class="pt-2 border-t border-blue-500">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-blue-400 flex items-center justify-center">
                            <i class="fas fa-user"></i>
                        </div>
                        <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    </div>
                </li>
            </ul>
        </div>
    </header>

    <main class="flex-grow overflow-hidden">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2025 Messaging App. All rights reserved.</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>