<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Foto - Bayan Soccer Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
        }
        
        .main-title {
            font-family: 'Arial Black', 'Arial', sans-serif;
            font-weight: 900;
            line-height: 0.85;
            letter-spacing: -0.02em;
        }
        
        .video-background {
            object-fit: cover;
        }

        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: linear-gradient(135deg, #005effff 0%, #0065a3ff 50%, #005effff 100%);
            background-size: 200% 200%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .preloader-text {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            color: #fefae0;
            font-size: clamp(2.5rem, 12vw, 6rem);
            font-weight: 1000;
            opacity: 0;
            padding: 0 1rem;
            text-align: center;
            width: 100%;
        }

        @media (max-width: 768px) {
            .preloader-text {
                gap: 0.75rem;
                font-size: clamp(2rem, 10vw, 3rem);
            }
        }

        @media (max-width: 480px) {
            .preloader-text {
                gap: 0.5rem;
                font-size: clamp(1.5rem, 8vw, 2.5rem);
                flex-direction: column;
            }
        }
        
        /* Gallery Styles */
        .gallery-card {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease-out;
        }
        
        #photosGrid {
            display: grid;
            gap: 1rem;
        }
        
        #photosGrid .gallery-card {
            animation: cardFadeIn 0.5s ease-out forwards;
        }
        
        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Add animation delay for each card */
        #photosGrid .gallery-card:nth-child(1) { animation-delay: 0.1s; }
        #photosGrid .gallery-card:nth-child(2) { animation-delay: 0.2s; }
        #photosGrid .gallery-card:nth-child(3) { animation-delay: 0.3s; }
        #photosGrid .gallery-card:nth-child(4) { animation-delay: 0.4s; }
        #photosGrid .gallery-card:nth-child(5) { animation-delay: 0.5s; }
        #photosGrid .gallery-card:nth-child(6) { animation-delay: 0.6s; }
            
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
            overflow: auto;
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            display: flex;
            flex-direction: column;
            max-height: 90vh;
            overflow-y: auto;
            margin: auto;
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .modal-body {
            padding: 1rem;
            overflow-y: auto;
            max-height: calc(90vh - 100px);
        }
        
        @keyframes zoomIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #12bbfd;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .scroll-indicator {
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .filter-badge {
            transition: all 0.3s ease;
        }

        .filter-badge.active {
            background: #12bbfd;
            color: white;
            transform: scale(1.05);
        }

        /* Image loading placeholder */
        .img-loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Download progress indicator */
        .download-progress {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            z-index: 10001;
            display: none;
            text-align: center;
        }

        .download-progress.active {
            display: block;
        }

        /* Notification animations */
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-black text-white overflow-x-hidden">
    <!-- Preloader -->
    <div id="preloader" class="preloader">
        <div id="preloaderText" class="preloader-text">
            <span id="preloaderWord">GALERI</span>
        </div>
    </div>

    <!-- Download Progress Indicator -->
    <div id="downloadProgress" class="download-progress">
        <div class="loader mb-4"></div>
        <p class="text-lg font-bold">Mengunduh foto resolusi tinggi...</p>
        <p class="text-sm text-gray-300 mt-2">Mohon tunggu sebentar</p>
    </div>

    <!-- Hero Section with Video Background -->
    <section id="heroSection" class="relative min-h-screen">
        <!-- Video Background -->
        <video id="videoBg" class="video-background fixed top-0 left-0 w-screen h-screen -z-20" autoplay muted loop playsinline>
            <source src="https://res.cloudinary.com/dgcedsrzf/video/upload/v1758861275/dji_fly_20241027_081323_0_1730259840479_video_cache_ig3omc.mp4" type="video/mp4">
        </video>
        
        <!-- Overlay -->
        <div class="fixed top-0 left-0 w-full h-full bg-black/40 -z-10"></div>

        <!-- Header -->
      <header id="header" class="fixed top-0 left-0 right-0 flex justify-between items-center z-50 opacity-0">
            <div class="logo absolute top-2 left-2 md:top-4 md:left-4">
                <img src="{{ asset('images/white.png') }}" alt="BAYAN SC Logo" class="h-10 md:h-12 w-auto">
            </div>
        </header>

        <!-- Main Container -->
        <div class="relative w-full min-h-screen flex flex-col justify-center items-center px-4 md:px-8 py-20">
            <h1 id="mainTitle" class="main-title text-center text-white uppercase opacity-0"
                style="font-size: clamp(48px, 10vw, 200px); text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
                SEMUA<br>
                FOTO
            </h1>
            
            <!-- Scroll Indicator -->
            <div id="scrollIndicator" class="scroll-indicator mt-12 opacity-0">
                <div class="flex flex-col items-center gap-2 cursor-pointer" onclick="scrollToGallery()">
                    <span class="text-white/80 text-sm font-semibold">LIHAT GALERI</span>
                    <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div id="breadcrumb" class="fixed bottom-4 left-4 md:bottom-10 md:left-10 lg:left-20 flex items-center gap-2 md:gap-3 text-xs md:text-sm font-semibold tracking-wider opacity-0">
            <a href="/" class="text-white/80 transition-all duration-300 hover:text-[#12bbfd] hover:opacity-100">HOME</a>
            <span class="text-white/60">/</span>
            <a href="#" class="text-[#12bbfd]">GALERI</a>
        </div>
        
        <!-- Side Info -->
        <div id="sideInfo" class="hidden md:block fixed right-5 lg:right-10 text-xs lg:text-sm font-semibold tracking-widest text-white/80 opacity-0"
             style="top: 50%; transform: translateY(-50%) rotate(90deg); transform-origin: center;">
            BALIKPAPAN STADION BATAKAN
        </div>
        
        <!-- Mobile Side Info -->
        <div id="sideInfoMobile" class="md:hidden fixed bottom-20 left-1/2 transform -translate-x-1/2 text-xs font-semibold tracking-wider text-white/80 opacity-0 text-center whitespace-nowrap">
            BALIKPAPAN STADION BATAKAN
        </div>
        
        <!-- Award Info -->
        <div id="awardInfo" class="fixed bottom-4 right-4 md:bottom-10 md:right-10 lg:right-20 bg-[#12bbfd] px-4 py-2 md:px-5 md:py-3 font-bold text-white tracking-wide opacity-0">
            <div class="text-xs md:text-sm">8 - 9</div>
            <div class="text-[10px] md:text-xs mt-1 opacity-90">NOVEMBER 2025</div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallerySection" class="relative bg-gradient-to-b from-[#005eff] to-[#0065a3] py-20 px-4">
        <div class="max-w-7xl mx-auto">
            
            <!-- Gallery Header -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-8 mb-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl md:text-4xl font-black text-white mb-2">GALERI FOTO SOCCER CLINIC</h2>
                        <p class="text-white/80 text-sm md:text-base" id="photoCount">Loading...</p>
                        <p class="text-white/60 text-xs md:text-sm mt-1">💡 Foto ditampilkan dalam kualitas preview. Download untuk kualitas HD.</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="/" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-bold transition-all text-sm md:text-base shadow-lg">
                         Home
                        </a>
                    </div>
                </div>

                <!-- Filter by Event -->
                <div id="filterSection" class="flex flex-wrap gap-2 items-center">
                    <span class="text-white/80 font-semibold text-sm">Filter:</span>
                    <button onclick="filterByEvent('all')" class="filter-badge active bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold">
                        Semua
                    </button>
                    <div id="eventFilters" class="flex flex-wrap gap-2">
                        <!-- Event filters will be inserted here -->
                    </div>
                </div>
            </div>

            <!-- Loading Section -->
            <div id="loadingSection" class="text-center py-20">
                <div class="loader mb-6"></div>
                <p class="text-white text-xl md:text-2xl font-bold">Loading photos...</p>
            </div>

            <!-- Photos Grid -->
            <div id="photosGrid" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                <!-- Photos will be inserted here -->
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-20">
                <div class="text-6xl md:text-8xl mb-6">📷</div>
                <h3 class="text-xl md:text-3xl font-bold text-white mb-3">Belum Ada Foto</h3>
                <p class="text-white/70 text-sm md:text-base">Belum ada foto yang diupload. Silakan cek kembali nanti!</p>
            </div>
        </div>
    </section>

    <!-- Photo Detail Modal -->
    <div id="photoModal" class="modal">
        <div class="modal-content">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6 flex items-center justify-between sticky top-0 z-10">
                <h3 class="text-xl md:text-2xl font-bold text-white">Detail Foto</h3>
                <button onclick="closeModal()" class="text-white hover:text-gray-200 text-3xl md:text-4xl font-bold leading-none">×</button>
            </div>
            <div id="modalBody" class="modal-body">
                <!-- Photo details will be inserted here -->
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const API_BASE_URL = 'https://fotokuu.web.id';
        
        let allPhotos = [];
        let currentFilter = 'all';

        // Function to generate low-res URL from Cloudinary
        function getLowResUrl(url) {
            // Check if it's a Cloudinary URL
            if (url.includes('cloudinary.com')) {
                // Insert quality and dimension transformations
                // This reduces quality to 50% and limits width to 600px
                return url.replace('/upload/', '/upload/q_50,w_600,f_auto/');
            }
            return url; // Return original if not Cloudinary
        }

        // Function to get high-res URL (original)
        function getHighResUrl(url) {
            return url; // Return original URL for high resolution
        }

        // Preloader logic
        const words = ["GALERI", "FOTO", "SOCCER CLINIC"];
        let currentIndex = 0;
        const preloaderWord = document.getElementById('preloaderWord');
        const preloaderText = document.getElementById('preloaderText');
        const preloader = document.getElementById('preloader');
        
        gsap.to(preloaderText, { opacity: 1, duration: 0.2 });
        
        function cycleWords() {
            if (currentIndex >= words.length - 1) {
                setTimeout(() => {
                    gsap.to(preloader, {
                        top: '-100vh',
                        duration: 0.5,
                        ease: 'power3.inOut',
                        delay: 0.5,
                        onComplete: () => {
                            preloader.style.display = 'none';
                            initMainAnimations();
                        }
                    });
                }, 500);
                return;
            }
            
            currentIndex++;
            gsap.to(preloaderWord, {
                opacity: 0,
                duration: 0.1,
                onComplete: () => {
                    preloaderWord.textContent = words[currentIndex];
                    gsap.to(preloaderWord, { opacity: 1, duration: 0.2 });
                }
            });
            
            setTimeout(cycleWords, currentIndex === 1 ? 1000 : 150);
        }
        
        setTimeout(cycleWords, 1000);

        // Main animations
        function initMainAnimations() {
            gsap.to('#header', { opacity: 1, y: 0, duration: 1, ease: 'power3.out', delay: 0.2 });
            gsap.to('#mainTitle', { opacity: 1, y: 0, duration: 1.2, ease: 'power3.out', delay: 0.4 });
            gsap.to('#actionButtons', { opacity: 1, y: 0, duration: 1, ease: 'power2.out', delay: 0.6 });
            gsap.to('#scrollIndicator', { opacity: 1, duration: 1, ease: 'power2.out', delay: 0.8 });
            gsap.to('#sideInfo, #sideInfoMobile, #awardInfo', { opacity: 1, duration: 1.5, ease: 'power2.out', delay: 1 });
            gsap.to('#breadcrumb', { opacity: 1, duration: 1.5, ease: 'power2.out', delay: 1.2 });

            // Load photos after animations
            setTimeout(loadAllPhotos, 500);
        }

        // Scroll to gallery
        function scrollToGallery() {
            document.getElementById('gallerySection').scrollIntoView({ behavior: 'smooth' });
        }

        // Video handling
        const video = document.querySelector('#videoBg');
        video.addEventListener('error', () => {
            document.body.style.background = 'linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%)';
        });

        // Load all photos from API
        async function loadAllPhotos() {
            try {
                const response = await fetch(`${API_BASE_URL}/api/user/all_photos`);
                const data = await response.json();
                
                document.getElementById('loadingSection').classList.add('hidden');
                
                if (data.success && data.photos.length > 0) {
                    allPhotos = data.photos;
                    displayPhotos(allPhotos);
                    generateEventFilters(allPhotos);
                } else {
                    document.getElementById('emptyState').classList.remove('hidden');
                }
            } catch (error) {
                document.getElementById('loadingSection').classList.add('hidden');
                document.getElementById('emptyState').classList.remove('hidden');
                console.error('Error loading photos:', error);
            }
        }

        // Generate event filters
        function generateEventFilters(photos) {
            const events = [...new Set(photos.map(p => p.metadata.event_name || 'Unknown Event'))];
            const filterContainer = document.getElementById('eventFilters');
            
            filterContainer.innerHTML = events.map(event => `
                <button onclick="filterByEvent('${event.replace(/'/g, "\\'")}')" 
                        class="filter-badge bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-white/30">
                    ${event}
                </button>
            `).join('');
        }

        // Filter photos by event
        function filterByEvent(eventName) {
            currentFilter = eventName;
            
            // Update active state
            document.querySelectorAll('.filter-badge').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Filter photos
            const filtered = eventName === 'all' 
                ? allPhotos 
                : allPhotos.filter(p => p.metadata.event_name === eventName);
            
            displayPhotos(filtered);
        }

        // Display photos with low resolution
        function displayPhotos(photos) {
            const grid = document.getElementById('photosGrid');
            const emptyState = document.getElementById('emptyState');

            document.getElementById('photoCount').textContent = 
                `Total ${photos.length} foto${photos.length > 1 ? '' : ''}`;

            if (photos.length === 0) {
                grid.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            grid.classList.remove('hidden');
            emptyState.classList.add('hidden');
            
            grid.innerHTML = photos.map(photo => {
                const date = new Date(photo.metadata.date).toLocaleDateString('id-ID', { 
                    year: 'numeric', month: 'long', day: 'numeric' 
                });
                
                const lowResUrl = getLowResUrl(photo.url);
                
                return `
                    <div class="gallery-card bg-white rounded-xl overflow-hidden shadow-xl cursor-pointer hover:scale-105 transition-transform"
                         onclick='showPhotoDetail(${JSON.stringify(photo).replace(/'/g, "&#39;")})'>
                        <div class="relative">
                            <img src="${lowResUrl}" 
                                 alt="${photo.filename}" 
                                 class="w-full h-48 md:h-64 object-cover"
                                 loading="lazy">
                            <div class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded">
                                Preview
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-base md:text-lg mb-2 text-gray-800 line-clamp-1">${photo.metadata.event_name || 'Event'}</h3>
                            <p class="text-gray-600 text-xs md:text-sm mb-1">📍 ${photo.metadata.location || 'Location'}</p>
                            <p class="text-gray-600 text-xs md:text-sm mb-1">📅 ${date}</p>
                            <p class="text-gray-600 text-xs md:text-sm">📸 ${photo.metadata.photographer || 'Photographer'}</p>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Download high resolution photo (Direct download without fetch to avoid CORS)
        function downloadHighResPhoto(photo) {
            try {
                const highResUrl = getHighResUrl(photo.url);
                
                // Create a temporary link element and trigger download
                const a = document.createElement('a');
                a.href = highResUrl;
                a.download = photo.filename;
                a.target = '_blank'; // Open in new tab as fallback
                
                // Add link to document, click it, then remove
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                
                // Show success message
                showNotification('Download dimulai! Foto akan tersimpan dalam kualitas HD penuh.', 'success');
            } catch (error) {
                console.error('Error downloading photo:', error);
                showNotification('Gagal mengunduh foto. Silakan coba lagi.', 'error');
            }
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-[10002] px-6 py-4 rounded-lg shadow-2xl text-white font-semibold text-sm md:text-base max-w-md ${
                type === 'success' ? 'bg-green-600' : 
                type === 'error' ? 'bg-red-600' : 
                'bg-blue-600'
            }`;
            notification.style.animation = 'slideInRight 0.3s ease-out';
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="text-2xl">${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}</span>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Show photo detail modal with low-res preview
        function showPhotoDetail(photo) {
            const modal = document.getElementById('photoModal');
            const modalBody = document.getElementById('modalBody');
            
            const date = new Date(photo.metadata.date).toLocaleDateString('id-ID', { 
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
            });
            const time = new Date(photo.metadata.date).toLocaleTimeString('id-ID');
            
            const lowResUrl = getLowResUrl(photo.url);
            
            modalBody.innerHTML = `
                <div class="relative">
                    <img src="${lowResUrl}" 
                         alt="${photo.filename}" 
                         class="w-full max-h-[50vh] md:max-h-[60vh] object-contain bg-black">
                    <div class="absolute top-4 right-4 bg-yellow-500 text-black text-xs font-bold px-3 py-2 rounded-lg shadow-lg">
                        🔍 PREVIEW QUALITY
                    </div>
                </div>
                <div class="p-4 md:p-6 space-y-3 md:space-y-4">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                        <p class="text-sm text-blue-800">
                            <strong>ℹ️ Info:</strong> Foto ini ditampilkan dalam kualitas preview. Klik tombol download untuk mendapatkan foto kualitas HD penuh.
                        </p>
                    </div>
                    <div class="flex items-center justify-between border-b pb-3">
                        <span class="text-gray-600 font-semibold text-sm md:text-base">Nama Event</span>
                        <span class="text-gray-800 font-medium text-sm md:text-base text-right">${photo.metadata.event_name || 'N/A'}</span>
                    </div>
                    <div class="flex items-center justify-between border-b pb-3">
                        <span class="text-gray-600 font-semibold text-sm md:text-base">Lokasi</span>
                        <span class="text-gray-800 font-medium text-sm md:text-base text-right">${photo.metadata.location || 'N/A'}</span>
                    </div>
                    <div class="flex items-center justify-between border-b pb-3">
                        <span class="text-gray-600 font-semibold text-sm md:text-base">Tanggal</span>
                        <span class="text-gray-800 font-medium text-sm md:text-base text-right">${date}</span>
                    </div>
                    <div class="flex items-center justify-between border-b pb-3">
                        <span class="text-gray-600 font-semibold text-sm md:text-base">Waktu</span>
                        <span class="text-gray-800 font-medium text-sm md:text-base">${time}</span>
                    </div>
                    <div class="flex items-center justify-between border-b pb-3">
                        <span class="text-gray-600 font-semibold text-sm md:text-base">Photographer</span>
                        <span class="text-gray-800 font-medium text-sm md:text-base text-right">${photo.metadata.photographer || 'N/A'}</span>
                    </div>
                    <div class="flex items-center justify-between border-b pb-3">
                        <span class="text-gray-600 font-semibold text-sm md:text-base">Nama File</span>
                        <span class="text-gray-500 font-mono text-xs md:text-sm text-right break-all">${photo.filename}</span>
                    </div>
                    <div class="flex flex-col md:flex-row gap-3 pt-4">
                        <button onclick='downloadHighResPhoto(${JSON.stringify(photo).replace(/'/g, "&#39;")})' 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 md:px-6 py-3 rounded-lg font-bold text-center transition-all text-sm md:text-base shadow-lg flex items-center justify-center gap-2">
                            <span class="text-xl">📥</span>
                            <span>Download HD (Kualitas Penuh)</span>
                        </button>
                        <a href="${photo.url}" 
                           target="_blank" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-3 rounded-lg font-bold text-center transition-all text-sm md:text-base shadow-lg flex items-center justify-center gap-2">
                            <span class="text-xl">🔗</span>
                            <span>Buka di Tab Baru</span>
                        </a>
                        <button onclick="closeModal()" 
                                class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 md:px-6 py-3 rounded-lg font-bold transition-all text-sm md:text-base">
                            ✕ Tutup
                        </button>
                    </div>
                </div>
            `;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close modal
        function closeModal() {
            document.getElementById('photoModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('photoModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>