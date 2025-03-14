<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance Preview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
        @font-face {
            font-family: 'Font Awesome 5 Free';
            font-style: normal;
            font-weight: 900;
            font-display: block;
            src: url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/webfonts/fa-solid-900.woff2) format('woff2'),
                 url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/webfonts/fa-solid-900.woff) format('woff'),
                 url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/webfonts/fa-solid-900.ttf) format('truetype');
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #000;
            height: 100vh;
            overflow: hidden;
        }

        .preview-container {
            position: relative;
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #000;
        }

        .preview-image-container {
            position: relative;
            width: 100%;
            height: 100%;
            background: #000;
            overflow: hidden;
        }

        .preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                to bottom,
                rgba(0, 0, 0, 0.4) 0%,
                rgba(0, 0, 0, 0) 30%,
                rgba(0, 0, 0, 0) 60%,
                rgba(0, 0, 0, 0.8) 100%
            );
        }

        .preview-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1rem;
            box-sizing: border-box;
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.5rem;
        }

        .preview-logo {
            width: 60px;
            height: auto;
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: white;
            padding: 8px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .clock-in-badge {
            display: inline-flex;
            align-items: center;
            background: #28a745;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-weight: 600;
            font-size: 1.1rem;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .clock-in-badge.out {
            background: #dc3545;
        }

        .preview-info {
            padding: 1rem;
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .preview-time {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .preview-date {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .preview-location {
            font-size: 1.1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .preview-location i {
            margin-top: 0.3rem;
        }

        .preview-name {
            font-size: 1.2rem;
            margin-bottom: 0.25rem;
        }

        .preview-company {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0.25rem;
        }

        .preview-position {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .preview-code {
            font-size: 0.9rem;
            opacity: 0.7;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .preview-buttons {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 50%, transparent 100%);
        }

        .btn-retake, .btn-confirm {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-retake {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .btn-confirm {
            background: #28a745;
            color: white;
        }

        .btn-retake:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .btn-confirm:hover {
            background: #218838;
        }

        @media (max-width: 768px) {
            .preview-time {
                font-size: 2rem;
            }

            .preview-date {
                font-size: 1.1rem;
            }

            .preview-location,
            .preview-name,
            .preview-company,
            .preview-position {
                font-size: 0.9rem;
            }

            .clock-in-badge {
                font-size: 1rem;
                padding: 0.4rem 0.8rem;
            }

            .preview-logo {
                width: 50px;
                padding: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-image-container">
            <img id="previewImage" class="preview-image" src="" alt="Captured image">
            <div class="preview-overlay"></div>
            <div class="preview-content">
                <div class="preview-header">
                    <img src="{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}" alt="Logo" class="preview-logo">
                </div>
                <div class="preview-info">
                    <div class="clock-in-badge" id="preview-status">
                        <i class="fas fa-clock"></i>
                        <span>Clock In</span>
                    </div>
                    <div class="preview-time" id="preview-time">00:00</div>
                    <div class="preview-date" id="preview-date"></div>
                    <div class="preview-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="preview-location">Fetching location...</span>
                    </div>
                    @if($employee)
                        <div class="preview-name" id="preview-name">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                        @if($employee->department->name == "MHRHCI")
                        <div class="preview-company">Medical & Hospital Resources Health Care, Inc.</div>
                        @elseif($employee->department->name == "BGPDI")
                        <div class="preview-company">Bay Gas Petroleum Distribution Inc.</div>
                        @elseif($employee->department->name == "VHI")
                        <div class="preview-company">Verbena Healthcare Inc.</div>
                        @else
                        <div class="preview-company">MHR Property Conglomerates, Inc.</div>
                        @endif
                        <div class="preview-position" id="preview-position">{{ $employee->position->name }} - {{ $employee->department->name }}</div>
                        <div class="preview-code">
                            <i class="fas fa-shield-alt"></i>
                            <span>Photo code: <span id="photo-code">AUUF349</span>, Verified by MHRPCI</span>
                        </div>
                    @else
                        <div class="preview-name" id="preview-name">Employee Not Found</div>
                        <div class="preview-company">MHR Property Conglomerates, Inc.</div>
                        <div class="preview-position" id="preview-position">Unknown Position</div>
                        <div class="preview-code">
                            <i class="fas fa-shield-alt"></i>
                            <span>Photo code: <span id="photo-code">AUUF349</span>, Verified by MHRPCI</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="preview-buttons">
            <button class="btn-retake" onclick="retakePhoto()">
                <i class="fas fa-redo"></i>
                Cancel
            </button>
            <button class="btn-confirm" onclick="confirmAttendance()">
                <i class="fas fa-check"></i>
                Save
            </button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        let imageBlob = null;
        let finalImageBlob = null;
        let assetsLoaded = {
            logo: false,
            fontAwesome: false
        };

        // Server time handling
        let serverTimeOffset = 0;
        let lastServerSync = null;

        // Fetch and update server time
        async function updateDateTime() {
            try {
                // Fetch server time
                const response = await fetch('/api/server-time');
                if (!response.ok) {
                    throw new Error('Failed to fetch server time');
                }
                
                const data = await response.json();
                const serverTime = new Date(data.timestamp);
                
                // Store the hash for verification
                localStorage.setItem('timestamp_hash', data.hash);
                
                // Update time displays with server time
                document.getElementById('preview-time').textContent = new Intl.DateTimeFormat('en-US', { 
                    timeZone: 'Asia/Manila',
                    hour12: true,
                    hour: '2-digit',
                    minute: '2-digit'
                }).format(serverTime).toUpperCase();
                
                document.getElementById('preview-date').textContent = new Intl.DateTimeFormat('en-US', { 
                    timeZone: 'Asia/Manila',
                    weekday: 'short',
                    month: 'short',
                    day: '2-digit',
                    year: 'numeric'
                }).format(serverTime);

                // Update status badge if needed
                const urlParams = new URLSearchParams(window.location.search);
                const type = urlParams.get('type') || 'in';
                const statusElement = document.getElementById('preview-status');
                if (statusElement) {
                    const statusText = type === 'in' ? 'Clock In' : 'Clock Out';
                    statusElement.innerHTML = `<i class="fas fa-clock"></i><span>${statusText}</span>`;
                    statusElement.className = `clock-in-badge ${type}`;
                }

                // Store server timestamp for verification
                localStorage.setItem('serverTimestamp', data.timestamp);
                
                // Calculate server time offset
                const clientNow = new Date();
                serverTimeOffset = serverTime.getTime() - clientNow.getTime();
                lastServerSync = clientNow.getTime();
                
            } catch (error) {
                console.error('Error updating time:', error);
            }
        }

        // Get current server time based on last sync
        function getCurrentServerTime() {
            const clientNow = new Date();
            const estimatedServerTime = new Date(clientNow.getTime() + serverTimeOffset);
            return estimatedServerTime;
        }

        // Verify timestamp hasn't been tampered with
        async function verifyTimestamp(timestamp, hash) {
            try {
                const response = await fetch(`/api/verify-timestamp/${encodeURIComponent(timestamp)}/${encodeURIComponent(hash)}`);
                const data = await response.json();
                return data.valid;
            } catch (error) {
                console.error('Error verifying timestamp:', error);
                return false;
            }
        }

        // Check and request mobile storage permission
        async function checkStoragePermission() {
            try {
                // Check if running on mobile
                const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                if (!isMobile) {
                    return true; // Skip permission check for desktop
                }

                // For Android devices
                if (navigator.userAgent.match(/Android/i)) {
                    if ('permissions' in navigator) {
                        try {
                            // Request storage permission
                            const result = await navigator.permissions.query({ name: 'persistent-storage' });
                            if (result.state === 'granted') {
                                return true;
                            }
                        } catch (e) {
                            console.warn('Standard permission API not supported');
                        }

                        // Try alternative Android storage permission
                        if ('requestFileSystem' in window) {
                            return new Promise((resolve) => {
                                window.requestFileSystem(window.PERSISTENT, 1024*1024, 
                                    () => resolve(true), 
                                    () => resolve(false)
                                );
                            });
                        }
                    }
                }
                
                // For iOS devices
                if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {
                    if ('requestPermission' in navigator.storage) {
                        try {
                            const permission = await navigator.storage.persist();
                            return permission;
                        } catch (e) {
                            console.warn('Storage permission API not supported on iOS');
                        }
                    }
                }

                return true; // Default to true if no permission API available
            } catch (error) {
                console.warn('Storage permission check failed:', error);
                return true; // Default to true on error
            }
        }

        // Save file to mobile device storage
        async function saveToDeviceStorage(blob, fileName) {
            try {
                const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                const isAndroid = /Android/i.test(navigator.userAgent);
                const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);

                if (isMobile) {
                    if (isAndroid) {
                        // Try Android-specific file saving
                        if ('chooseFileSystemEntries' in window) {
                            try {
                                const handle = await window.chooseFileSystemEntries({
                                    type: 'save-file',
                                    accepts: [{
                                        description: 'JPEG Image',
                                        extensions: ['jpg'],
                                        mimeTypes: ['image/jpeg'],
                                    }],
                                });
                                const writer = await handle.createWriter();
                                await writer.write(0, blob);
                                await writer.close();
                                return true;
                            } catch (err) {
                                console.warn('Android file system API failed:', err);
                            }
                        }

                        // Try using MediaStore API for Android
                        if ('mediaDevices' in navigator) {
                            try {
                                const file = new File([blob], fileName, { type: 'image/jpeg' });
                                const share = await navigator.share({
                                    files: [file],
                                    title: 'Save Attendance Image',
                                });
                                return true;
                            } catch (err) {
                                console.warn('MediaStore API failed:', err);
                            }
                        }
                    } else if (isIOS) {
                        // For iOS: Use share sheet or Files app integration
                        try {
                            const file = new File([blob], fileName, { type: 'image/jpeg' });
                            if ('share' in navigator) {
                                await navigator.share({
                                    files: [file],
                                    title: 'Save Attendance Image',
                                });
                                return true;
                            }
                        } catch (err) {
                            console.warn('iOS share API failed:', err);
                            
                            // Fallback for iOS: Try direct download
                            const url = URL.createObjectURL(blob);
                            window.location.href = url;
                            setTimeout(() => URL.revokeObjectURL(url), 100);
                            return true;
                        }
                    }
                }

                // Fallback to traditional download
                return false;
            } catch (error) {
                console.error('Error saving to device storage:', error);
                return false;
            }
        }

        // Preload Font Awesome
        function preloadFontAwesome() {
            return new Promise((resolve) => {
                const font = new FontFace(
                    'Font Awesome 5 Free',
                    'url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/webfonts/fa-solid-900.woff2)',
                    { weight: '900' }
                );

                font.load().then(() => {
                    document.fonts.add(font);
                    assetsLoaded.fontAwesome = true;
                    resolve();
                }).catch(() => {
                    console.warn('Font Awesome failed to load, continuing anyway');
                    assetsLoaded.fontAwesome = true;
                    resolve();
                });
            });
        }

        // Preload logo
        function preloadLogo() {
            return new Promise((resolve) => {
                const logo = document.querySelector('.preview-logo');
                const logoUrl = "{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}";
                
                // Create a new image to preload
                const preloadImg = new Image();
                preloadImg.crossOrigin = "anonymous";
                
                preloadImg.onload = function() {
                    // Create a canvas to convert the image
                    const canvas = document.createElement('canvas');
                    canvas.width = this.naturalWidth;
                    canvas.height = this.naturalHeight;
                    
                    // Draw the image to canvas
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(this, 0, 0);
                    
                    // Convert to data URL and set as source
                    try {
                        const dataUrl = canvas.toDataURL('image/png');
                        logo.src = dataUrl;
                        assetsLoaded.logo = true;
                        resolve();
                    } catch (e) {
                        console.warn('Logo conversion failed, trying direct load');
                        logo.src = logoUrl;
                        assetsLoaded.logo = true;
                        resolve();
                    }
                };
                
                preloadImg.onerror = function() {
                    console.warn('Logo preload failed, trying direct load');
                    logo.src = logoUrl;
                    assetsLoaded.logo = true;
                    resolve();
                };
                
                // Start loading with cache buster
                preloadImg.src = logoUrl + '?cache=' + new Date().getTime();
            });
        }

        // Initialize preview with server time
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                // Preload assets
                await Promise.all([
                    preloadLogo(),
                    preloadFontAwesome()
                ]);

                // Initial server time sync
                await updateDateTime();
                
                // Set up periodic updates
                let secondsCounter = 0;
                setInterval(async () => {
                    secondsCounter++;
                    if (secondsCounter >= 60) {
                        // Fetch fresh server time every minute
                        secondsCounter = 0;
                        await updateDateTime();
                    } else {
                        // Update display using calculated server time
                        const serverTime = getCurrentServerTime();
                        document.getElementById('preview-time').textContent = new Intl.DateTimeFormat('en-US', { 
                            timeZone: 'Asia/Manila',
                            hour12: true,
                            hour: '2-digit',
                            minute: '2-digit'
                        }).format(serverTime).toUpperCase();
                    }
                }, 1000);

                // Load and verify stored data
                const storedImage = localStorage.getItem('capturedImage');
                const storedTimestamp = localStorage.getItem('serverTimestamp');
                const storedHash = localStorage.getItem('timestamp_hash');
                
                if (storedImage && storedTimestamp && storedHash) {
                    // Verify the timestamp
                    const isValid = await verifyTimestamp(storedTimestamp, storedHash);
                    if (!isValid) {
                        alert('Warning: The timestamp verification failed. The image may have been tampered with.');
                        window.location.href = '/attendance';
                        return;
                    }
                    
                    // Display the image
                    const previewImage = document.getElementById('previewImage');
                    if (previewImage) {
                        previewImage.src = storedImage;
                        
                        // Create a blob from the base64 image for later use
                        const response = await fetch(storedImage);
                        imageBlob = await response.blob();
                    }
                    
                    // Set location if available
                    const storedLocation = localStorage.getItem('userLocation');
                    const locationElement = document.getElementById('preview-location');
                    if (locationElement && storedLocation) {
                        locationElement.textContent = storedLocation;
                    }

                    // Update status badge based on type
                    const urlParams = new URLSearchParams(window.location.search);
                    const type = urlParams.get('type') || 'in';
                    const statusElement = document.getElementById('preview-status');
                    if (statusElement) {
                        const statusText = type === 'in' ? 'Clock In' : 'Clock Out';
                        statusElement.innerHTML = `<i class="fas fa-clock"></i><span>${statusText}</span>`;
                        statusElement.className = `clock-in-badge ${type}`;
                    }
                } else {
                    console.error('Required data not found in localStorage');
                    window.location.href = '/attendance';
                }
            } catch (error) {
                console.error('Error initializing preview:', error);
                alert('Error initializing preview. Please try again.');
                window.location.href = '/attendance';
            }
        });

        async function waitForAssets() {
            return new Promise((resolve) => {
                const checkAssets = () => {
                    if (assetsLoaded.logo && assetsLoaded.fontAwesome) {
                        resolve();
                    } else {
                        setTimeout(checkAssets, 100);
                    }
                };
                checkAssets();
            });
        }

        async function capturePreview() {
            try {
                // Wait for all assets to load
                await waitForAssets();
                
                const previewContainer = document.querySelector('.preview-container');
                
                // Hide buttons during capture
                const buttons = document.querySelector('.preview-buttons');
                buttons.style.display = 'none';

                // Force render any pending font awesome icons
                document.querySelectorAll('.fas').forEach(icon => {
                    icon.style.fontFamily = 'Font Awesome 5 Free';
                    icon.style.fontWeight = '900';
                });
                
                // Configure html2canvas options for better quality
                const canvas = await html2canvas(previewContainer, {
                    scale: 2, // Increase quality
                    useCORS: true, // Allow cross-origin images
                    allowTaint: true,
                    backgroundColor: '#000000',
                    logging: false,
                    removeContainer: false,
                    foreignObjectRendering: true,
                    imageTimeout: 15000, // Increase timeout for image loading
                    onclone: function(clonedDoc) {
                        // Ensure logo and icons are visible in cloned document
                        const clonedLogo = clonedDoc.querySelector('.preview-logo');
                        if (clonedLogo) {
                            clonedLogo.style.visibility = 'visible';
                            clonedLogo.style.opacity = '1';
                        }
                        
                        // Ensure Font Awesome icons are rendered
                        clonedDoc.querySelectorAll('.fas').forEach(icon => {
                            icon.style.fontFamily = 'Font Awesome 5 Free';
                            icon.style.fontWeight = '900';
                        });
                    }
                });

                // Show buttons again
                buttons.style.display = 'flex';

                // Convert canvas to blob with maximum quality
                return new Promise((resolve) => {
                    canvas.toBlob((blob) => {
                        resolve(blob);
                    }, 'image/jpeg', 1.0); // Maximum quality JPEG
                });
            } catch (error) {
                console.error('Error capturing preview:', error);
                throw new Error('Failed to capture preview image');
            }
        }

        // Modified saveImage function
        async function saveImage() {
            try {
                // Show loading overlay with mobile-friendly styling
                const loadingOverlay = document.createElement('div');
                loadingOverlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.85);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                    backdrop-filter: blur(5px);
                    -webkit-backdrop-filter: blur(5px);
                `;
                loadingOverlay.innerHTML = `
                    <div style="color: white; text-align: center; padding: 2rem;">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                        <div style="margin-top: 1.5rem; font-size: 1.2rem; font-weight: 500;">Saving attendance image...</div>
                        <div style="margin-top: 0.5rem; font-size: 0.9rem; opacity: 0.8;">Please allow storage permission if prompted</div>
                    </div>
                `;
                document.body.appendChild(loadingOverlay);

                // Check mobile storage permission
                const hasPermission = await checkStoragePermission();
                if (!hasPermission) {
                    throw new Error('Storage permission required. Please allow access to save images.');
                }

                // Capture the preview with overlays
                finalImageBlob = await capturePreview();
                
                if (!finalImageBlob) {
                    throw new Error('No image data available');
                }

                // Generate filename with timestamp and user info
                const now = new Date();
                const dateStr = now.getFullYear() +
                    (now.getMonth() + 1).toString().padStart(2, '0') +
                    now.getDate().toString().padStart(2, '0');
                const timeStr = now.getHours().toString().padStart(2, '0') +
                    now.getMinutes().toString().padStart(2, '0') +
                    now.getSeconds().toString().padStart(2, '0');

                const params = new URLSearchParams(window.location.search);
                const clockType = params.get('type') || 'in';
                const fullName = params.get('name') || 'Unknown';
                const sanitizedName = fullName.replace(/[^a-zA-Z0-9]/g, '_').toLowerCase();

                // Create filename
                const fileName = `attendance_${dateStr}_${timeStr}_clock${clockType}_${sanitizedName}.jpg`;

                // Try to save to device storage with mobile-specific handling
                const savedToDevice = await saveToDeviceStorage(finalImageBlob, fileName);

                if (!savedToDevice) {
                    // Mobile-friendly fallback
                    const base64Data = await new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.onloadend = () => resolve(reader.result);
                        reader.readAsDataURL(finalImageBlob);
                    });

                    // For mobile devices, try to use the share API first
                    if ('share' in navigator) {
                        try {
                            const file = new File([finalImageBlob], fileName, { type: 'image/jpeg' });
                            await navigator.share({
                                files: [file],
                                title: 'Save Attendance Image',
                            });
                        } catch (err) {
                            // If share fails, fall back to download
                            const downloadLink = document.createElement('a');
                            downloadLink.href = base64Data;
                            downloadLink.download = fileName;
                            downloadLink.style.display = 'none';
                            document.body.appendChild(downloadLink);
                            downloadLink.click();
                            setTimeout(() => document.body.removeChild(downloadLink), 1000);
                        }
                    }
                }

                // Show mobile-friendly success message
                const successMessage = document.createElement('div');
                successMessage.style.cssText = `
                    position: fixed;
                    top: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: #28a745;
                    color: white;
                    padding: 15px 30px;
                    border-radius: 5px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                    z-index: 1000;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    max-width: 90%;
                    text-align: center;
                `;
                successMessage.innerHTML = '<i class="fas fa-check-circle"></i> Image saved successfully!';
                document.body.appendChild(successMessage);

                // Animate success message
                setTimeout(() => {
                    successMessage.style.opacity = '1';
                    setTimeout(() => {
                        successMessage.style.opacity = '0';
                        setTimeout(() => successMessage.remove(), 300);
                    }, 3000);
                }, 100);

                // Remove loading overlay
                document.body.removeChild(loadingOverlay);
                return true;

            } catch (error) {
                console.error('Error saving image:', error);
                const loadingOverlay = document.querySelector('div[style*="position: fixed"]');
                if (loadingOverlay) {
                    document.body.removeChild(loadingOverlay);
                }

                // Show mobile-friendly error message
                const errorMessage = document.createElement('div');
                errorMessage.style.cssText = `
                    position: fixed;
                    top: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: #dc3545;
                    color: white;
                    padding: 15px 30px;
                    border-radius: 5px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                    z-index: 1000;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    max-width: 90%;
                    text-align: center;
                `;
                errorMessage.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${error.message}`;
                document.body.appendChild(errorMessage);

                // Remove error message after 5 seconds
                setTimeout(() => errorMessage.remove(), 5000);
                
                throw error;
            }
        }

        async function retakePhoto() {
            try {
                // Show confirmation dialog
                const confirmRetake = confirm('Are you sure you want to cancel? Any unsaved changes will be lost.');
                
                if (confirmRetake) {
                    // Clear stored data
                    localStorage.removeItem('capturedImage');
                    localStorage.removeItem('userLocation');
                    localStorage.removeItem('serverTimestamp');
                    localStorage.removeItem('timestamp_hash');
                    
                    // Redirect back to attendance page
                    window.location.href = '/attendance';
                }
            } catch (error) {
                console.error('Error during cancellation:', error);
                alert('There was an error returning to the previous page. Please try again.');
            }
        }

        async function confirmAttendance() {
            try {
                // Verify timestamp before saving
                const storedTimestamp = localStorage.getItem('serverTimestamp');
                const storedHash = localStorage.getItem('timestamp_hash');
                
                if (!storedTimestamp || !storedHash) {
                    throw new Error('Missing timestamp verification data');
                }
                
                const isValid = await verifyTimestamp(storedTimestamp, storedHash);
                if (!isValid) {
                    throw new Error('Timestamp verification failed. Please try again.');
                }

                // Show loading state
                const confirmBtn = document.querySelector('.btn-confirm');
                const originalBtnContent = confirmBtn.innerHTML;
                confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                confirmBtn.disabled = true;

                // First, check current attendance status
                const statusResponse = await fetch('/attendance/status', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const statusResult = await statusResponse.json();
                if (!statusResponse.ok) {
                    throw new Error(statusResult.message || 'Failed to verify attendance status');
                }

                const type = new URLSearchParams(window.location.search).get('type') || 'in';

                // Verify that the action matches the current status
                if (type === 'in' && statusResult.action !== 'clock_in') {
                    throw new Error('You have already clocked in for today.');
                } else if (type === 'out' && statusResult.action !== 'clock_out') {
                    if (statusResult.action === 'clock_in') {
                        throw new Error('You must clock in first before clocking out.');
                    } else if (statusResult.action === 'completed') {
                        throw new Error('You have already completed your attendance for today.');
                    }
                }

                // Capture the final preview image
                finalImageBlob = await capturePreview();
                if (!finalImageBlob) {
                    throw new Error('Failed to capture preview image');
                }

                // Convert blob to base64
                const base64Image = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result);
                    reader.readAsDataURL(finalImageBlob);
                });

                // Get stored data
                const location = localStorage.getItem('userLocation');

                // Prepare the request data
                const data = {
                    type: type,
                    image: base64Image,
                    location: location,
                    timestamp: storedTimestamp
                };

                // Send the request to the server
                const response = await fetch('/attendance/capture', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to save attendance');
                }

                if (result.status === 'success') {
                    // Save the image locally if needed
                    await saveImage();

                    // Show success message with animation
                    const successMessage = document.createElement('div');
                    successMessage.style.cssText = `
                        position: fixed;
                        top: 20px;
                        left: 50%;
                        transform: translateX(-50%);
                        background: #28a745;
                        color: white;
                        padding: 15px 30px;
                        border-radius: 5px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                        z-index: 1000;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    `;
                    successMessage.innerHTML = '<i class="fas fa-check-circle"></i> ' + result.message;
                    document.body.appendChild(successMessage);

                    // Animate success message and redirect
                    setTimeout(() => {
                        successMessage.style.opacity = '1';
                        setTimeout(() => {
                            successMessage.style.opacity = '0';
                            setTimeout(() => {
                                successMessage.remove();
                                // Clear stored data
                                localStorage.removeItem('capturedImage');
                                localStorage.removeItem('userLocation');
                                localStorage.removeItem('serverTimestamp');
                                localStorage.removeItem('timestamp_hash');
                                
                                // Redirect back to attendance page
                                window.location.href = '/attendance';
                            }, 300);
                        }, 2000);
                    }, 100);
                } else {
                    throw new Error(result.message || 'Unknown error occurred');
                }
            } catch (error) {
                console.error('Error during confirmation:', error);
                
                // Reset button state
                const confirmBtn = document.querySelector('.btn-confirm');
                confirmBtn.innerHTML = '<i class="fas fa-check"></i> Save';
                confirmBtn.disabled = false;
                
                // Show error message with animation
                const errorMessage = document.createElement('div');
                errorMessage.style.cssText = `
                    position: fixed;
                    top: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: #dc3545;
                    color: white;
                    padding: 15px 30px;
                    border-radius: 5px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                    z-index: 1000;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    max-width: 90%;
                    text-align: center;
                `;
                errorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + error.message;
                document.body.appendChild(errorMessage);

                // Animate error message
                setTimeout(() => {
                    errorMessage.style.opacity = '1';
                    setTimeout(() => {
                        errorMessage.style.opacity = '0';
                        setTimeout(() => errorMessage.remove(), 300);
                    }, 3000);
                }, 100);
            }
        }
    </script>
</body>
</html> 