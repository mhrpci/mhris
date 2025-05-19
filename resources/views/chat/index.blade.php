@extends('layouts.messaging')

@section('title', 'Dashboard - Messaging App')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row h-[calc(100vh-12rem)]">
        <!-- Sidebar - Contacts List -->
        <div class="w-full md:w-1/3 lg:w-1/4 bg-white shadow-md rounded-lg overflow-hidden md:mr-4 mb-4 md:mb-0 h-64 md:h-full" x-data="{ activeTab: 'contacts' }">
            <!-- Tabs Navigation -->
            <div class="flex border-b">
                <button 
                    @click="activeTab = 'contacts'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'contacts', 'text-gray-600': activeTab !== 'contacts' }"
                    class="w-1/2 py-3 font-medium text-center border-b-2 border-transparent hover:text-blue-500 focus:outline-none transition"
                >
                    <i class="fas fa-user-friends mr-1"></i> Contacts
                </button>
                <button 
                    @click="activeTab = 'recent'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'recent', 'text-gray-600': activeTab !== 'recent' }"
                    class="w-1/2 py-3 font-medium text-center border-b-2 border-transparent hover:text-blue-500 focus:outline-none transition"
                >
                    <i class="fas fa-clock mr-1"></i> Recent
                </button>
            </div>
            
            <!-- Search Box -->
            <div class="p-3 border-b">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Search..." 
                        class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <!-- Contacts Tab Content -->
            <div x-show="activeTab === 'contacts'" class="overflow-y-auto h-[calc(100%-6rem)]">
                <div class="cursor-pointer hover:bg-gray-100 p-3 border-b flex items-center" 
                     x-data="{}" 
                     @click="document.getElementById('mobileConversation').classList.remove('hidden'); document.getElementById('mobileList').classList.add('hidden');">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                        <span class="text-blue-600 font-bold">JD</span>
                    </div>
                    <div>
                        <h3 class="font-medium">John Doe</h3>
                        <p class="text-sm text-gray-600">Online</p>
                    </div>
                </div>
                
                <div class="cursor-pointer hover:bg-gray-100 p-3 border-b flex items-center">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                        <span class="text-green-600 font-bold">JS</span>
                    </div>
                    <div>
                        <h3 class="font-medium">Jane Smith</h3>
                        <p class="text-sm text-gray-600">Last seen 2h ago</p>
                    </div>
                </div>
                
                <div class="cursor-pointer hover:bg-gray-100 p-3 border-b flex items-center">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                        <span class="text-purple-600 font-bold">MB</span>
                    </div>
                    <div>
                        <h3 class="font-medium">Michael Brown</h3>
                        <p class="text-sm text-gray-600">Last seen yesterday</p>
                    </div>
                </div>
                
                <div class="cursor-pointer hover:bg-gray-100 p-3 border-b flex items-center">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                        <span class="text-red-600 font-bold">EW</span>
                    </div>
                    <div>
                        <h3 class="font-medium">Emily Wilson</h3>
                        <p class="text-sm text-gray-600">Online</p>
                    </div>
                </div>
                
                <div class="cursor-pointer hover:bg-gray-100 p-3 border-b flex items-center">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                        <span class="text-yellow-600 font-bold">DT</span>
                    </div>
                    <div>
                        <h3 class="font-medium">David Thompson</h3>
                        <p class="text-sm text-gray-600">Last seen 3d ago</p>
                    </div>
                </div>
            </div>
            
            <!-- Recent Tab Content -->
            <div x-show="activeTab === 'recent'" class="overflow-y-auto h-[calc(100%-6rem)]">
                <div class="cursor-pointer hover:bg-gray-100 p-3 border-b flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                        <span class="text-blue-600 font-bold">JD</span>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-medium">John Doe</h3>
                        <p class="text-sm text-gray-600 truncate">Hey, how are you doing?</p>
                    </div>
                    <div class="text-xs text-gray-500">3m</div>
                </div>
                
                <div class="cursor-pointer hover:bg-gray-100 p-3 border-b flex items-center">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                        <span class="text-red-600 font-bold">EW</span>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-medium">Emily Wilson</h3>
                        <p class="text-sm text-gray-600 truncate">Let's meet tomorrow at 2pm</p>
                    </div>
                    <div class="text-xs text-gray-500">1h</div>
                </div>
                
                <div class="cursor-pointer hover:bg-gray-100 p-3 border-b flex items-center">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                        <span class="text-green-600 font-bold">JS</span>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-medium">Jane Smith</h3>
                        <p class="text-sm text-gray-600 truncate">Did you see the latest update?</p>
                    </div>
                    <div class="text-xs text-gray-500">2h</div>
                </div>
            </div>
        </div>
        
        <!-- Mobile View Management -->
        <div id="mobileList" class="md:hidden"></div>
        <div id="mobileConversation" class="hidden md:block w-full md:w-2/3 lg:w-3/4">
            <!-- Conversation Area -->
            <div class="bg-white shadow-md rounded-lg h-full flex flex-col">
                <!-- Conversation Header -->
                <div class="p-3 border-b flex items-center justify-between">
                    <div class="flex items-center">
                        <button class="md:hidden mr-2 text-gray-600" x-data="{}" @click="document.getElementById('mobileConversation').classList.add('hidden'); document.getElementById('mobileList').classList.remove('hidden');">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-blue-600 font-bold">JD</span>
                        </div>
                        <div>
                            <h3 class="font-medium">John Doe</h3>
                            <p class="text-xs text-gray-600">Online</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <button class="text-gray-600 hover:text-blue-500">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button class="text-gray-600 hover:text-blue-500">
                            <i class="fas fa-video"></i>
                        </button>
                        <button class="text-gray-600 hover:text-blue-500">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Conversation Messages -->
                <div class="flex-grow p-4 overflow-y-auto" id="message-container">
                    <!-- Date Divider -->
                    <div class="text-center my-4">
                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">Today</span>
                    </div>
                    
                    <!-- Received Message -->
                    <div class="flex mb-4">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2 flex-shrink-0">
                            <span class="text-blue-600 font-bold text-xs">JD</span>
                        </div>
                        <div class="max-w-xs md:max-w-md">
                            <div class="bg-gray-100 rounded-lg p-3">
                                <p>Hey there! How's it going?</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">10:30 AM</p>
                        </div>
                    </div>
                    
                    <!-- Sent Message -->
                    <div class="flex mb-4 justify-end">
                        <div class="max-w-xs md:max-w-md">
                            <div class="bg-blue-500 text-white rounded-lg p-3">
                                <p>Hi John! I'm doing well, thanks for asking. How about you?</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-right">10:32 AM</p>
                        </div>
                    </div>
                    
                    <!-- Received Message -->
                    <div class="flex mb-4">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2 flex-shrink-0">
                            <span class="text-blue-600 font-bold text-xs">JD</span>
                        </div>
                        <div class="max-w-xs md:max-w-md">
                            <div class="bg-gray-100 rounded-lg p-3">
                                <p>I'm good! Working on that project we discussed earlier. Making good progress!</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">10:35 AM</p>
                        </div>
                    </div>
                    
                    <!-- Sent Message -->
                    <div class="flex mb-4 justify-end">
                        <div class="max-w-xs md:max-w-md">
                            <div class="bg-blue-500 text-white rounded-lg p-3">
                                <p>That's great to hear! Let me know if you need any help with it.</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-right">10:36 AM</p>
                        </div>
                    </div>
                </div>
                
                <!-- Message Input Area -->
                <div class="p-3 border-t" x-data="{ message: '' }">
                    <div class="flex items-center">
                        <button class="text-gray-600 hover:text-blue-500 mr-3">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <div class="flex-grow relative">
                            <input 
                                type="text" 
                                placeholder="Type a message..." 
                                class="w-full py-2 px-4 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                x-model="message"
                                @keydown.enter="
                                    if (message.trim()) {
                                        const container = document.getElementById('message-container');
                                        const newMsg = document.createElement('div');
                                        newMsg.className = 'flex mb-4 justify-end';
                                        newMsg.innerHTML = `
                                            <div class='max-w-xs md:max-w-md'>
                                                <div class='bg-blue-500 text-white rounded-lg p-3'>
                                                    <p>${message}</p>
                                                </div>
                                                <p class='text-xs text-gray-500 mt-1 text-right'>${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                                            </div>
                                        `;
                                        container.appendChild(newMsg);
                                        container.scrollTop = container.scrollHeight;
                                        message = '';
                                    }
                                "
                            >
                        </div>
                        <button 
                            class="ml-3 bg-blue-500 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-blue-600 focus:outline-none"
                            @click="
                                if (message.trim()) {
                                    const container = document.getElementById('message-container');
                                    const newMsg = document.createElement('div');
                                    newMsg.className = 'flex mb-4 justify-end';
                                    newMsg.innerHTML = `
                                        <div class='max-w-xs md:max-w-md'>
                                            <div class='bg-blue-500 text-white rounded-lg p-3'>
                                                <p>${message}</p>
                                            </div>
                                            <p class='text-xs text-gray-500 mt-1 text-right'>${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                                        </div>
                                    `;
                                    container.appendChild(newMsg);
                                    container.scrollTop = container.scrollHeight;
                                    message = '';
                                }
                            "
                        >
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Automatically scroll to bottom of messages on page load
    document.addEventListener('DOMContentLoaded', function() {
        const messageContainer = document.getElementById('message-container');
        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
        
        // Handle mobile view
        if (window.innerWidth < 768) {
            document.getElementById('mobileConversation').classList.add('hidden');
            document.getElementById('mobileList').classList.remove('hidden');
        }
    });
</script>
@endsection