<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayan Soccer Clinic</title>
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
            animation: gradientShift 3s ease infinite;
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

        .preloader-dot {
            height: clamp(1rem, 3vw, 1.5rem);
            width: clamp(1rem, 3vw, 1.5rem);
            background-color: #fefae0;
            border-radius: 9999px;
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
            max-height: 90vh; /* Atur tinggi maksimum modal */
            overflow-y: auto; /* Aktifkan scroll */
            margin: auto;
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Tambahkan bayangan untuk efek */
        }

        .modal-body {
            padding: 1rem; /* Tambahkan padding untuk konten */
            overflow-y: auto; /* Pastikan konten dapat di-scroll */
            max-height: calc(90vh - 100px); /* Tinggi maksimum konten modal */
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
        
        #cameraVideo {
            transform: scaleX(-1);
        }
        
        .scroll-indicator {
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
    </style>
</head>
<body class="bg-black text-white overflow-x-hidden">
    <!-- Preloader -->
    <div id="preloader" class="preloader">
        <div id="preloaderText" class="preloader-text">
            <span id="preloaderWord">BAYAN</span>
        </div>
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
                BAYAN<br>
                SOCCER<br>
                CLINIC
            </h1>
            
            <!-- Scroll Indicator -->
            <div id="scrollIndicator" class="scroll-indicator mt-12 opacity-0">
                <div class="flex flex-col items-center gap-2 cursor-pointer" onclick="scrollToGallery()">
                    <span class="text-white/80 text-sm font-semibold">LIHAT FOTO ANDA</span>
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
            <a href="/adminajah/login" class="text-white/80 transition-all duration-300 hover:text-[#12bbfd] hover:opacity-100">ADMIN</a>
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
            
            <!-- Registration Section -->
            <div id="registrationSection" class="mb-16">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-12 text-center">
                    <h2 class="text-3xl md:text-5xl font-black text-white mb-4">TEMUKAN FOTO KAMU</h2>
                    <h6 class="text-white/90 font-semibold md:text-lg mb-8 max-w-3xl mx-auto leading-relaxed">
                        Ambil foto wajah kamu untuk melihat semua foto kegiatan soccer clinic di mana wajah kamu terdeteksi. 
                        <br class="hidden md:block">
                    </h6>

                    <!-- Camera Section -->
                    <div id="cameraSection" class="max-w-2xl mx-auto">
                        <video id="cameraVideo" class="w-full rounded-xl mb-6 bg-black shadow-2xl" autoplay playsinline muted></video>
                        
                        <div class="flex flex-wrap gap-3 md:gap-4 justify-center">
                            <button onclick="startCamera()" 
                                    class="bg-[#12bbfd] hover:bg-[#0ea5e0] text-white px-6 md:px-8 py-3 rounded-lg font-bold transition-all text-sm md:text-base shadow-lg hover:shadow-xl">
                                Hidupkan Kamera
                            </button>
                            <button id="captureBtn" onclick="captureAndRegister()" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-6 md:px-8 py-3 rounded-lg font-bold transition-all hidden text-sm md:text-base shadow-lg hover:shadow-xl">
                                Foto & Daftar Wajah
                            </button>
                            <button onclick="stopCamera()" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-6 md:px-8 py-3 rounded-lg font-bold transition-all text-sm md:text-base shadow-lg hover:shadow-xl">
                                Matikan Kamera
                            </button>
                        </div>
                        
                        <div id="statusMessage" class="mt-6 p-4 rounded-lg hidden text-sm md:text-base"></div>
                    </div>
                </div>
            </div>

            <!-- Loading Section -->
            <div id="loadingSection" class="hidden text-center py-20">
                <div class="loader mb-6"></div>
                <p class="text-white text-xl md:text-2xl font-bold">Loading your photos...</p>
            </div>

        <!-- Gallery Results Section -->
            <div id="galleryResults" class="hidden">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-8 mb-8">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl md:text-4xl font-black text-white mb-2">FOTO ANDA SELAMA SOCCER CLINIC</h2>
                            <p class="text-white/80 text-sm md:text-base" id="photoCount">Ditemukan 0 foto</p>
                        </div>
                        <button onclick="resetFaceData()" 
                                class="w-full md:w-auto bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-bold transition-all text-sm md:text-base shadow-lg">
                            🔄 Ulang Foto
                        </button>
                    </div>
                </div>


                <!-- Photos Grid -->
                <div id="photosGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 mb-12">
                    <!-- Photos will be inserted here -->
                </div>
                
                <!-- Call to Action - Lihat Semua Foto -->
              <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-10 text-center shadow-2xl transform hover:scale-105 transition-all duration-300 mt-8">
                    <h3 class="text-2xl md:text-3xl font-black text-white mb-3">
                        Foto Kamu Belum Ketemu? 
                    </h3>
                    <p class="text-white/90 font-semibold text-sm md:text-lg mb-6 max-w-2xl mx-auto leading-relaxed">
                        Jangan khawatir! Mungkin foto kamu ada di galeri lengkap kami. 
                        <br class="hidden md:block font-semibold">
                        Yuk jelajahi ratusan momen seru Soccer Clinic bersama teman-temanmu! 
                    </p>
                    <a href="{{ route('public.galeri.index') }}" 
                    class="inline-block bg-white hover:bg-gray-100 text-[#12bbfd] px-8 md:px-12 py-4 rounded-xl font-black text-base md:text-lg shadow-xl transition-all transform hover:scale-110 hover:shadow-2xl">
                        DOKUMENTASI LENGKAP
                    </a>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="hidden text-center py-20">
                    <div class="text-6xl md:text-8xl mb-6">📷</div>
                    <h3 class="text-xl md:text-3xl font-bold text-white mb-3">Belum Ada Foto dengan Wajah Kamu</h3>
                    <p class="text-white/70 text-sm md:text-base mb-8">Sepertinya belum ada foto dengan wajah kamu yang terdeteksi.<br class="hidden md:block">Tapi tenang, masih banyak foto seru lainnya!</p>
                    
                    <!-- CTA Button in Empty State -->
                    <a href="{{ route('public.galeri.index') }}" 
                    class="inline-block bg-[#12bbfd] hover:bg-[#0ea5e0] text-white px-8 py-4 rounded-xl font-bold text-lg shadow-xl transition-all transform hover:scale-105">
                        🖼️ Lihat Semua Foto Event
                    </a>
                </div>
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
        // Configuration - CHANGE THIS TO YOUR API URL
        // IMPORTANT: Use HTTP (not HTTPS) if you get SSL certificate errors
        // Or open https://192.168.0.69:5000 in browser first and accept the certificate
        const API_BASE_URL = 'http://103.150.191.9:4000'; // Changed to HTTP to avoid SSL errors
        
        let videoStream = null;
        let faceEmbedding = null;

        // Preloader logic
        const words = ["BAYAN", "SOCCER CLINIC", "WELCOME"];
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
            gsap.to('#scrollIndicator', { opacity: 1, duration: 1, ease: 'power2.out', delay: 0.8 });
            gsap.to('#sideInfo, #sideInfoMobile, #awardInfo', { opacity: 1, duration: 1.5, ease: 'power2.out', delay: 1 });
            gsap.to('#breadcrumb', { opacity: 1, duration: 1.5, ease: 'power2.out', delay: 1.2 });

            if (window.innerWidth > 768) {
                const title = document.querySelector('#mainTitle');
                document.addEventListener('mousemove', function(e) {
                    const x = (e.clientX / window.innerWidth) * 100;
                    const y = (e.clientY / window.innerHeight) * 100;
                    gsap.to(title, {
                        textShadow: `${(x - 50) * 0.1}px ${(y - 50) * 0.1}px 20px rgba(0, 0, 0, 0.8), 2px 2px 4px rgba(0, 0, 0, 0.5)`,
                        duration: 0.3
                    });
                });
            }
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

        // Check if face already registered on page load
        window.addEventListener('load', () => {
            const stored = localStorage.getItem('bayan_face_embedding');
            if (stored) {
                faceEmbedding = JSON.parse(stored);
                document.getElementById('registrationSection').classList.add('hidden');
                loadPhotos();
            }
        });

        async function startCamera() {
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: 1280, height: 720 },
                    audio: false
                });
                document.getElementById('cameraVideo').srcObject = videoStream;
                document.getElementById('captureBtn').classList.remove('hidden');
                showStatus('✅ Kamera Siap! Arahkan wajah Anda dan klik Foto & Daftar Wajah', 'success');
            } catch (error) {
                showStatus('❌ Camera error: ' + error.message, 'error');
            }
        }

        function stopCamera() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
                document.getElementById('cameraVideo').srcObject = null;
                document.getElementById('captureBtn').classList.add('hidden');
            }
        }

        async function captureAndRegister() {
            const video = document.getElementById('cameraVideo');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.scale(-1, 1);
            ctx.drawImage(video, -canvas.width, 0);
            
            const imageData = canvas.toDataURL('image/jpeg', 0.8);
            showStatus('🔍 Extracting face embedding...', 'info');
            
            try {
                const response = await fetch(`${API_BASE_URL}/api/user/register_face`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ image: imageData })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    faceEmbedding = data.embedding;
                    localStorage.setItem('bayan_face_embedding', JSON.stringify(faceEmbedding));
                    showStatus('✅ Face registered successfully! Loading your photos...', 'success');
                    stopCamera();
                    
                    setTimeout(() => {
                        document.getElementById('registrationSection').classList.add('hidden');
                        loadPhotos();
                    }, 1500);
                } else {
                    showStatus('❌ Error: ' + data.error, 'error');
                }
            } catch (error) {
                showStatus('❌ Connection error: ' + error.message, 'error');
            }
        }

        async function loadPhotos() {
            document.getElementById('loadingSection').classList.remove('hidden');
            document.getElementById('galleryResults').classList.add('hidden');
            
            try {
                const response = await fetch(`${API_BASE_URL}/api/user/my_photos`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ embedding: faceEmbedding })
                });
                
                const data = await response.json();
                
                document.getElementById('loadingSection').classList.add('hidden');
                document.getElementById('galleryResults').classList.remove('hidden');
                
                if (data.success && data.photos.length > 0) {
                    displayPhotos(data.photos);
                } else {
                    document.getElementById('emptyState').classList.remove('hidden');
                    document.getElementById('photosGrid').classList.add('hidden');
                }
            } catch (error) {
                document.getElementById('loadingSection').classList.add('hidden');
                showStatus('❌ Error loading photos: ' + error.message, 'error');
            }
        }

        function displayPhotos(photos) {
            const grid = document.getElementById('photosGrid');
            const emptyState = document.getElementById('emptyState');

            document.getElementById('photoCount').textContent = `Ditemukan ${photos.length} foto${photos.length > 1 ? '' : ''}`;

            grid.classList.remove('hidden');
            emptyState.classList.add('hidden');
            
            grid.innerHTML = photos.map(photo => {
                const matchScore = (100 - photo.distance * 100).toFixed(0);
                const date = new Date(photo.metadata.date).toLocaleDateString('en-US', { 
                    year: 'numeric', month: 'long', day: 'numeric' 
                });
                
                return `
                    <div class="gallery-card bg-white rounded-xl overflow-hidden shadow-xl cursor-pointer"
                         onclick='showPhotoDetail(${JSON.stringify(photo).replace(/'/g, "&#39;")})'>
                        <img src="${photo.url}" alt="${photo.filename}" class="w-full h-48 md:h-64 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold text-base md:text-lg mb-1 text-gray-800 line-clamp-1">${photo.metadata.event_name || 'Event'}</h3>
                            <p class="text-gray-600 text-xs md:text-sm">📍 ${photo.metadata.location || 'Location'}</p>
                            <p class="text-gray-600 text-xs md:text-sm">📅 ${date}</p>
                            <p class="text-gray-600 text-xs md:text-sm">📸 ${photo.metadata.photographer || 'Photographer'}</p>
                        </div>
                    </div>
                `;
            }).join('');
            
        }

        function showPhotoDetail(photo) {
            const modal = document.getElementById('photoModal');
            const modalBody = document.getElementById('modalBody');
            
            const matchScore = (100 - photo.distance * 100).toFixed(0);
            const date = new Date(photo.metadata.date).toLocaleDateString('en-US', { 
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
            });
            const time = new Date(photo.metadata.date).toLocaleTimeString();
            
            modalBody.innerHTML = `
                <img src="${photo.url}" alt="${photo.filename}" class="w-full max-h-[50vh] md:max-h-[60vh] object-contain bg-black">
                <div class="p-4 md:p-6 space-y-3 md:space-y-4">
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
                    <div class="flex flex-col md:flex-row gap-3 pt-4">
                        <a href="${photo.url}" download="${photo.filename}" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-3 rounded-lg font-bold text-center transition-all text-sm md:text-base">
                            📥 Download Foto
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

        function closeModal() {
            document.getElementById('photoModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function resetFaceData() {
            if (confirm('Are you sure you want to register a different face? Your current registration will be removed.')) {
                localStorage.removeItem('bayan_face_embedding');
                faceEmbedding = null;
                document.getElementById('galleryResults').classList.add('hidden');
                document.getElementById('registrationSection').classList.remove('hidden');
                scrollToGallery();
            }
        }

        function showStatus(message, type) {
            const statusEl = document.getElementById('statusMessage');
            statusEl.classList.remove('hidden');
            
            const colors = {
                success: 'bg-green-500/20 text-green-100 border-2 border-green-500',
                error: 'bg-red-500/20 text-red-100 border-2 border-red-500',
                info: 'bg-blue-500/20 text-blue-100 border-2 border-blue-500'
            };
            
            statusEl.className = `mt-6 p-4 rounded-lg font-semibold ${colors[type]}`;
            statusEl.textContent = message;
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