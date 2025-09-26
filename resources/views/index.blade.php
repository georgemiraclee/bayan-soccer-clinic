<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayan Soccer Clinic</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial Black', Arial, sans-serif;
            background: #000;
            color: #fff;
            overflow-x: hidden;
            height: 100vh;
        }

        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -2;
            object-fit: cover;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        .container {
            position: relative;
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
        }

        .header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            z-index: 100;
        }

        .logo {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 2px;
            color: #fff;
        }

        .get-in-touch {
            background: transparent;
            border: 2px solid #fff;
            color: #fff;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .get-in-touch:hover {
            background: #fff;
            color: #000;
        }

        .menu-button {
            background: none;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
            margin-left: 20px;
        }

        .year-tag {
            position: absolute;
            top: 40px;
            left: 50px;
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 2px;
            color: #fff;
            opacity: 0.8;
        }

        .main-title {
            text-align: center;
            font-size: clamp(80px, 12vw, 200px);
            font-weight: 900;
            line-height: 0.85;
            letter-spacing: -0.02em;
            text-transform: uppercase;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-top: 60px;
        }

        .subtitle {
            position: absolute;
            top: 50px;
            left: 50px;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #fff;
            opacity: 0.9;
        }

        .side-info {
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%) rotate(90deg);
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 2px;
            color: #fff;
            opacity: 0.8;
            transform-origin: center;
        }

        .award-info {
            position: absolute;
            bottom: 40px;
            right: 40px;
            background: #12bbfdff;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            letter-spacing: 1px;
        }

        .award-text {
            font-size: 12px;
            margin-top: 4px;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }
            
            .logo {
                font-size: 20px;
            }
            
            .get-in-touch {
                padding: 10px 16px;
                font-size: 12px;
            }
            
            .main-title {
                font-size: clamp(60px, 15vw, 120px);
                margin-top: 40px;
            }
            
            .year-tag,
            .subtitle {
                left: 20px;
                font-size: 14px;
            }
            
            .side-info {
                right: 20px;
                top: auto;
                bottom: 100px;
                transform: rotate(0);
                font-size: 12px;
            }
            
            .award-info {
                bottom: 20px;
                right: 20px;
                padding: 8px 12px;
                font-size: 12px;
            }
        }

        /* Animations */
        .main-title {
            animation: fadeInUp 1.2s ease-out;
        }

        .header {
            animation: fadeInDown 1s ease-out;
        }

        .side-info,
        .award-info {
            animation: fadeIn 1.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <video class="video-background" autoplay muted loop>
        <source src="https://res.cloudinary.com/dgcedsrzf/video/upload/v1758859505/dji_fly_20241027_081323_0_1730259840479_video_cache_nycykv.mov" type="video/mp4">
        <!-- Fallback for browsers that don't support the video format -->
    </video>
    
    <div class="overlay"></div>

    <header class="header">
     <div class="logo">
            <img src="{{ asset('images/white.png') }}" alt="BAYAN SC Logo" style="height: 50px; width: auto;">
        </div>
        <div style="display: flex; align-items: center;">
            <a href="https://wa.me/6282251752711" class="get-in-touch">CONTACT ↗</a>
        </div>
    </header>

    <div class="container">
        <h1 class="main-title">
            BAYAN<br>
            SOCCER<br>
            CLINIC
        </h1>
    </div>

    
    <div class="side-info">BALIKPAPAN STADION BATAKAN</div>
    
    <div class="award-info">
        <div>8 - 9</div>
        <div class="award-text">NOVEMBER 2025</div>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('mousemove', function(e) {
            const title = document.querySelector('.main-title');
            const x = (e.clientX / window.innerWidth) * 100;
            const y = (e.clientY / window.innerHeight) * 100;
            
            title.style.textShadow = `
                ${(x - 50) * 0.1}px ${(y - 50) * 0.1}px 20px rgba(0, 0, 0, 0.8),
                2px 2px 4px rgba(0, 0, 0, 0.5)
            `;
        });

        // Button hover effects
        const getInTouchBtn = document.querySelector('.get-in-touch');
        getInTouchBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        getInTouchBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });

        // Handle video loading error
        const video = document.querySelector('.video-background');
        video.addEventListener('error', function() {
            console.log('Video failed to load, using fallback background');
            document.body.style.background = 'linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%)';
        });

        // Ensure video plays
        video.addEventListener('canplay', function() {
            video.play().catch(function(error) {
                console.log('Video autoplay failed:', error);
            });
        });
    </script>
</body>
</html>