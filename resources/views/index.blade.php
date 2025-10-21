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
    </style>
</head>
<body class="bg-black text-white overflow-x-hidden">
    <!-- Preloader -->
    <div id="preloader" class="preloader">
        <div id="preloaderText" class="preloader-text">
            <span id="preloaderWord">BAYAN</span>
        </div>
    </div>

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
        <a href="/daftar-ssb" 
           id="registerBtn"
           class="bg-[#12bbfd] text-white px-8 py-3 md:px-12 md:py-4 lg:px-16 lg:py-5 text-sm md:text-base lg:text-lg font-black tracking-widest uppercase mt-6 md:mt-8 lg:mt-10 transition-all duration-300 hover:bg-[#0ea5e0] hover:shadow-2xl opacity-0 text-center"
           style="box-shadow: 0 4px 15px rgba(18, 187, 253, 0.4);">
           DAFTAR SEKARANG
        </a>
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

    <script>
        // Preloader logic
        const words = [
            "BAYAN",
            "SOCCER CLINIC",
            "WELCOME",
        ];
        
        let currentIndex = 0;
        const preloaderWord = document.getElementById('preloaderWord');
        const preloaderText = document.getElementById('preloaderText');
        const preloader = document.getElementById('preloader');
        
        // Fade in first word
        gsap.to(preloaderText, {
            opacity: 1,
            duration: 0.2
        });
        
        function cycleWords() {
            if (currentIndex >= words.length - 1) {
                // Exit animation
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
                    gsap.to(preloaderWord, {
                        opacity: 1,
                        duration: 0.2
                    });
                }
            });
            
            const delay = currentIndex === 1 ? 1000 : 150;
            setTimeout(cycleWords, delay);
        }
        
        setTimeout(cycleWords, 1000);

        // Main animations
        function initMainAnimations() {
            // Header animation
            gsap.to('#header', {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: 'power3.out',
                delay: 0.2
            });

            // Main title animation
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
            gsap.to('#sideInfo, #sideInfoMobile, #awardInfo', {
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

            // Interactive title parallax effect (desktop only)
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

            // Button hover animations
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
        }

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
    </script>
</body>
</html>