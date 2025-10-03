<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayan Soccer Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap');
        
        body {
            font-family: 'Arial Black', Arial, sans-serif;
        }
        
        .main-title {
            font-family: 'Montserrat', 'Arial Black', cursive;
            line-height: 0.85;
            letter-spacing: -0.02em;
        }
        
        .video-background {
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-black text-white overflow-x-hidden h-screen">
    <!-- Video Background -->
    <video id="videoBg" class="video-background fixed top-0 left-0 w-screen h-screen -z-20" autoplay muted loop playsinline>
        <source src="https://res.cloudinary.com/dgcedsrzf/video/upload/v1758861275/dji_fly_20241027_081323_0_1730259840479_video_cache_ig3omc.mp4" type="video/mp4">
    </video>
    
    <!-- Overlay -->
    <div class="fixed top-0 left-0 w-full h-full bg-black/40 -z-10"></div>

    <!-- Header -->
    <header id="header" class="fixed top-0 left-0 right-0 flex justify-between items-center px-10 py-5 z-50 md:px-20 opacity-0">
        <div class="logo">
            <img src="{{ asset('images/white.png') }}" alt="BAYAN SC Logo" class="h-10 md:h-12 w-auto">
        </div>
        <!-- Uncomment if needed
        <div class="flex items-center">
            <a href="https://wa.me/6282251752711" 
               class="border-2 border-white px-6 py-3 text-sm font-bold tracking-wider transition-all duration-300 hover:bg-white hover:text-black hover:scale-105">
               CONTACT ↗
            </a>
        </div>
        -->
    </header>

    <!-- Main Container -->
    <div class="relative w-full h-screen flex flex-col justify-center items-center px-5">
        <h1 id="mainTitle" class="main-title text-center text-white uppercase opacity-0"
            style="font-size: clamp(60px, 12vw, 200px); text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
            BAYAN<br>
            SOCCER<br>
            CLINIC
        </h1>
        <a href="/daftar-ssb" 
           id="registerBtn"
           class="bg-[#12bbfd] text-white px-12 py-4 md:px-16 md:py-5 text-base md:text-lg font-black tracking-widest uppercase mt-8 md:mt-10 transition-all duration-300 hover:bg-[#0ea5e0] hover:shadow-2xl opacity-0"
           style="box-shadow: 0 4px 15px rgba(18, 187, 253, 0.4);">
           DAFTAR SEKARANG
        </a>
    </div>

    <!-- Breadcrumb -->
    <div id="breadcrumb" class="fixed bottom-10 left-10 md:left-20 flex items-center gap-3 text-sm font-semibold tracking-wider opacity-0">
        <a href="/" class="text-white/80 transition-all duration-300 hover:text-[#12bbfd] hover:opacity-100">HOME</a>
        <span class="text-white/60">/</span>
        <a href="/adminajah/login" class="text-white/80 transition-all duration-300 hover:text-[#12bbfd] hover:opacity-100">ADMIN</a>
    </div>
    
    <!-- Side Info -->
    <div id="sideInfo" class="fixed right-5 md:right-10 text-sm font-semibold tracking-widest text-white/80 opacity-0"
         style="top: 50%; transform: translateY(-50%) rotate(90deg); transform-origin: center;">
        BALIKPAPAN STADION BATAKAN
    </div>
    
    <!-- Award Info -->
    <div id="awardInfo" class="fixed bottom-10 right-10 md:right-20 bg-[#12bbfd] px-5 py-3 font-bold text-white tracking-wide opacity-0">
        <div class="text-sm">8 - 9</div>
        <div class="text-xs mt-1 opacity-90">NOVEMBER 2025</div>
    </div>

    <script>
        // Initialize GSAP animations
        gsap.registerPlugin();

        // Header animation
        gsap.to('#header', {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: 'power3.out',
            delay: 0.2
        });

        // Main title animation with stagger effect
        const titleLines = document.querySelectorAll('#mainTitle');
        gsap.to('#mainTitle', {
            opacity: 1,
            y: 0,
            duration: 1.2,
            ease: 'power3.out',
            delay: 0.4
        });

        // Register button animation
        gsap.to('#registerBtn', {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: 'power3.out',
            delay: 0.8
        });

        // Side elements animation
        gsap.to('#sideInfo, #awardInfo', {
            opacity: 1,
            duration: 1.5,
            ease: 'power2.out',
            delay: 1
        });

        // Breadcrumb animation
        gsap.to('#breadcrumb', {
            opacity: 1,
            duration: 1.5,
            ease: 'power2.out',
            delay: 1.2
        });

        // Interactive title parallax effect
        const title = document.querySelector('#mainTitle');
        document.addEventListener('mousemove', function(e) {
            const x = (e.clientX / window.innerWidth) * 100;
            const y = (e.clientY / window.innerHeight) * 100;
            
            gsap.to(title, {
                textShadow: `${(x - 50) * 0.1}px ${(y - 50) * 0.1}px 20px rgba(0, 0, 0, 0.8), 2px 2px 4px rgba(0, 0, 0, 0.5)`,
                duration: 0.3
            });
        });

        // Button hover animations with GSAP
        const registerBtn = document.querySelector('#registerBtn');
        
        registerBtn.addEventListener('mouseenter', function() {
            gsap.to(this, {
                y: -8,
                scale: 1.02,
                boxShadow: '0 6px 20px rgba(18, 187, 253, 0.6)',
                duration: 0.3,
                ease: 'power2.out'
            });
        });
        
        registerBtn.addEventListener('mouseleave', function() {
            gsap.to(this, {
                y: 0,
                scale: 1,
                boxShadow: '0 4px 15px rgba(18, 187, 253, 0.4)',
                duration: 0.3,
                ease: 'power2.out'
            });
        });

        // Video handling
        const video = document.querySelector('#videoBg');
        
        video.addEventListener('error', function() {
            console.log('Video failed to load, using fallback background');
            document.body.style.background = 'linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%)';
        });

        video.addEventListener('canplay', function() {
            video.play().catch(function(error) {
                console.log('Video autoplay failed:', error);
            });
        });

        // Scroll trigger for additional effects (optional enhancement)
        gsap.from('#mainTitle', {
            scrollTrigger: {
                trigger: '#mainTitle',
                start: 'top center',
                toggleActions: 'play none none reverse'
            },
            scale: 0.9,
            duration: 1
        });

        // Responsive adjustments
        if (window.innerWidth <= 768) {
            document.querySelector('#sideInfo').style.transform = 'rotate(0)';
            document.querySelector('#sideInfo').style.bottom = '100px';
            document.querySelector('#sideInfo').style.top = 'auto';
        }
    </script>
</body>
</html>