<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayan Soccer Clinic - Pendaftaran</title>
    <link rel="icon" type="ico" href="{{ asset('logo.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ensure CSRF token is accessible */
        body::before {
            content: '';
            display: none;
        }
    </style>
    <style>
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 9999px;
        }
        .badge-info { background-color: #dbeafe; color: #1e40af; }
        .badge-warning { background-color: #fef3c7; color: #d97706; }
        .badge-success { background-color: #d1fae5; color: #059669; }
        
        .alert-slide-down {
            animation: slideDown 0.5s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-100%);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 to-orange-50 min-h-screen py-6 sm:py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <!-- Header dengan Logo -->
        <div class="text-center mb-8 sm:mb-10">
            <img src="/images/logo.png" 
                 alt="Bayan Soccer Clinic" 
                 class="mx-auto w-18 h-20 sm:w-22 sm:h-24">
            <h1 class="mt-3 sm:mt-4 text-3xl sm:text-5xl font-extrabold text-orange-400 tracking-wide drop-shadow-sm">
                Bayan Soccer Clinic
            </h1>
            <p class="text-gray-600 font-semibold mt-1 sm:mt-2 text-sm sm:text-base">Formulir Pendaftaran SSB</p>
        </div>

        <!-- Progress Indicator -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-8">
                <div class="flex flex-col sm:flex-row items-center text-center sm:text-left">
                    <div id="step1-indicator" class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                    <span id="step1-text" class="mt-1 sm:mt-0 sm:ml-2 font-medium text-orange-600 text-sm sm:text-base">Daftar SSB</span>
                </div>
                <div class="hidden sm:block flex-1 h-1 bg-gray-300" id="progress-line"></div>
                <div class="flex flex-col sm:flex-row items-center text-center sm:text-left">
                    <div id="step2-indicator" class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">2</div>
                    <span id="step2-text" class="mt-1 sm:mt-0 sm:ml-2 font-medium text-gray-600 text-sm sm:text-base">Daftar Pemain</span>
                </div>
            </div>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <!-- Form Sekolah Bola -->
        <div id="form-sekolah" class="bg-white shadow-lg rounded-lg p-4 sm:p-8">
            <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-gray-800">Form Pendaftaran SSB</h1>
            
            <form id="sekolah-form" class="space-y-4 sm:space-y-6">
                <div class="grid gap-4 sm:gap-6 md:grid-cols-2">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1 sm:mb-2">Nama SSB *</label>
                        <select id="nama_sekolah" required class="w-full border border-gray-300 rounded-lg p-2 sm:p-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white">
                            <option value="">-- Pilih SSB --</option>
                            <option value="ABABIL">ABABIL</option>
                            <option value="AREBA">AREBA</option>
                            <option value="BINTANG TIMUR">BINTANG TIMUR</option>
                            <option value="GUBAH FS">GUBAH FS</option>
                            <option value="GUNTUR PUTRA">GUNTUR PUTRA</option>
                            <option value="MITRA TERAS">MITRA TERAS</option>
                            <option value="TELKOM">TELKOM</option>
                            <option value="TOTAL">TOTAL</option>
                            <option value="ACADEMY BAS">ACADEMY BAS</option>
                            <option value="BEBANIR">BEBANIR</option>
                            <option value="BINA BOLA">BINA BOLA</option>
                            <option value="CSA">CSA</option>
                            <option value="DOSTEP">DOSTEP</option>
                            <option value="GALAXI">GALAXI</option>
                            <option value="GENERASI">GENERASI</option>
                            <option value="KARIANGAU">KARIANGAU</option>
                            <option value="KUKAYU">KUKAYU</option>
                            <option value="PINISI JUNIOR">PINISI JUNIOR</option>
                            <option value="RAJAWALI PUTRA BORNEO">RAJAWALI PUTRA BORNEO</option>
                            <option value="SAMURAI FC">SAMURAI FC</option>
                            <option value="SATRIA KURMA">SATRIA KURMA</option>
                            <option value="SUPER KIDS">SUPER KIDS</option>
                            <option value="TAMBORA">TAMBORA</option>
                            <option value="TYPHOON">TYPHOON</option>
                            <option value="MS 22">MS 22</option>
                            <option value="FONI SOCCER">FONI SOCCER</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block font-medium text-gray-700 mb-1 sm:mb-2">PIC (Penanggung Jawab) *</label>
                        <input type="text" id="pic" required class="w-full border border-gray-300 rounded-lg p-2 sm:p-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Nama penanggung jawab">
                    </div>
                </div>

                <div class="grid gap-4 sm:gap-6 md:grid-cols-2">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1 sm:mb-2">Email *</label>
                        <input type="email" id="email" required class="w-full border border-gray-300 rounded-lg p-2 sm:p-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="email@example.com">
                    </div>
                    
                    <div>
                        <label class="block font-medium text-gray-700 mb-1 sm:mb-2">No. Telepon yang dapat dihubungi *</label>
                        <input type="tel" id="telepon" required class="w-full border border-gray-300 rounded-lg p-2 sm:p-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 rounded-lg transition duration-300 transform hover:scale-105">
                    Lanjut Daftar Pemain
                </button>
            </form>
        </div>

        <!-- Form Pemain Bola -->
        <div id="form-pemain" class="bg-white shadow-lg rounded-lg p-4 sm:p-8 hidden">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-2 sm:gap-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Form Pendaftaran Pemain</h1>
                <div class="text-sm text-gray-600">
                    <span id="pemain-count">0</span>/<span id="max-pemain-count">0</span> Pemain
                </div>
            </div>

            <!-- Info Sekolah -->
            <div class="bg-orange-50 p-4 rounded-lg mb-6 text-sm sm:text-base">
                <h3 class="font-semibold text-orange-800 mb-2">Informasi SSB:</h3>
                <p class="text-orange-700"><strong>Nama SSB:</strong> <span id="info-nama"></span></p>
                <p class="text-orange-700"><strong>PIC SSB:</strong> <span id="info-pic"></span></p>
                <p class="text-orange-700"><strong>Email:</strong> <span id="info-email"></span></p>
                <p class="text-orange-700"><strong>Telepon:</strong> <span id="info-telepon"></span></p>
            </div>

            <!-- Alert Informasi Kuota -->
            <div id="quotaAlert" class="mb-6 bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200 rounded-lg p-4 sm:p-6 alert-slide-down">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="text-blue-500 text-lg sm:text-xl">ℹ️</i>
                    </div>
                    <div class="ml-2 sm:ml-3 flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-blue-800 mb-2">
                            ⚽ Informasi Kuota SSB <span id="quota-ssb-name"></span>
                        </h3>
                        <div id="quota-content" class="text-blue-700 mb-4">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Tambah Pemain -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Tambah Pemain Baru</h3>
                <form id="pemain-form" class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1 sm:mb-2">Nama Pemain *</label>
                            <input type="text" id="nama_pemain" required class="w-full border border-gray-300 rounded-lg p-2 sm:p-3 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Nama lengkap pemain">
                        </div>
                        
                        <div>
                            <label class="block font-medium text-gray-700 mb-1 sm:mb-2">Umur (tahun) *</label>
                            <input type="number" id="umur_pemain" required min="7" max="12" class="w-full border border-gray-300 rounded-lg p-2 sm:p-3 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Umur pemain">
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1 sm:mb-2">Kategori Umur *</label>
                        <div class="flex flex-wrap gap-3">
                            <label class="flex-1 sm:flex-none flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="umur_kategori" value="7-8" class="hidden peer" required>
                                <span class="w-full text-center px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition duration-300">
                                    7-8 Tahun
                                </span>
                            </label>
                            <label class="flex-1 sm:flex-none flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="umur_kategori" value="9-10" class="hidden peer" required>
                                <span class="w-full text-center px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition duration-300">
                                    9-10 Tahun
                                </span>
                            </label>
                            <label class="flex-1 sm:flex-none flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="umur_kategori" value="11-12" class="hidden peer" required>
                                <span class="w-full text-center px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition duration-300">
                                    11-12 Tahun
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Quota Warning Area -->
                    <div id="quota-warning-area"></div>

                    <button type="submit" id="btn-tambah" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
                        Tambah Pemain
                    </button>
                </form>
            </div>

            <!-- Daftar Pemain -->
            <div id="daftar-pemain" class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Daftar Pemain Terdaftar</h3>
                <div id="pemain-list" class="space-y-2 max-h-64 overflow-y-auto text-sm sm:text-base">
                    <p class="text-gray-500 text-center py-4">Belum ada pemain yang didaftarkan</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="selesaiPendaftaran()" id="btn-selesai" class="w-full sm:flex-1 bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 rounded-lg transition duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                    Selesai Pendaftaran
                </button>
                <button onclick="kembaliKeSekolah()" class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300">
                    Kembali
                </button>
            </div>
        </div>

        <!-- Success Message -->
        <div id="success-message" class="bg-white shadow-lg rounded-lg p-6 sm:p-8 text-center hidden">
            <div class="text-green-600 text-5xl sm:text-6xl mb-4">✓</div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Pendaftaran Berhasil!</h2>
            <p class="text-gray-600 mb-4 text-sm sm:text-base">SSB dan pemain telah berhasil didaftarkan ke sistem Bayan Soccer Clinic.</p>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 text-left">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Informasi Penting:</strong><br>
                            List pendaftar saat ini bersifat sementara. Jumlah kuota yang akan didapatkan akan diinformasikan melalui link website terpisah dan akan diinformasikan melalui nomor telepon PIC terdaftar.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button onclick="exportToPDF()" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 sm:px-8 rounded-lg transition duration-300 transform hover:scale-105">
                    📄 Export ke PDF
                </button>
                <button onclick="resetForm()" class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-6 sm:px-8 rounded-lg transition duration-300 transform hover:scale-105">
                    ➕ Daftar SSB Baru
                </button>
            </div>
        </div>
    </div>

    <footer class="mt-10 sm:mt-12 text-center font-semibold text-gray-600 text-xs sm:text-sm">
        <p>&copy; 2025 ICT Bayan Group. All Rights Reserved.</p>
    </footer>

    <!-- jsPDF Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script>
        let sekolahData = {};
        let pemainList = [];
        let kuotaData = {
            has_quota: false,
            total: 0,
            '7-8': 0,
            '9-10': 0,
            '11-12': 0,
            current_counts: {
                total: 0,
                '7-8': 0,
                '9-10': 0,
                '11-12': 0
            },
            remaining: {
                '7-8': 0,
                '9-10': 0,
                '11-12': 0
            },
            percentage: {
                '7-8': 0,
                '9-10': 0,
                '11-12': 0
            }
        };

        // Hardcoded quota data untuk setiap SSB
        const QUOTA_DATABASE = {
            'ABABIL': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'AREBA': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'BINTANG TIMUR': { '7-8': 2, '9-10': 3, '11-12': 5 },
            'GUBAH FS': { '7-8': 2, '9-10': 3, '11-12': 7 },
            'GUNTUR PUTRA': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'MITRA TERAS': { '7-8': 2, '9-10': 4, '11-12': 7 },
            'TELKOM': { '7-8': 2, '9-10': 3, '11-12': 6 },
            'TOTAL': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'ACADEMY BAS': { '7-8': 2, '9-10': 2, '11-12': 6 },
            'BEBANIR': { '7-8': 2, '9-10': 2, '11-12': 6 },
            'BINA BOLA': { '7-8': 2, '9-10': 3, '11-12': 6 },
            'CSA': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'DOSTEP': { '7-8': 2, '9-10': 3, '11-12': 6 },
            'GALAXI': { '7-8': 1, '9-10': 2, '11-12': 5 },
            'GENERASI': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'KARIANGAU': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'KUKAYU': { '7-8': 2, '9-10': 3, '11-12': 6 },
            'PINISI JUNIOR': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'RAJAWALI PUTRA BORNEO': { '7-8': 1, '9-10': 2, '11-12': 5 },
            'SAMURAI FC': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'SATRIA KURMA': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'SUPER KIDS': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'TAMBORA': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'TYPHOON': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'MS 22': { '7-8': 2, '9-10': 2, '11-12': 5 },
            'FONI SOCCER': { '7-8': 2, '9-10': 2, '11-12': 5 }
        };

        // Fetch quota data from hardcoded database
        function fetchQuotaData(namaSekolah) {
            // Check if quota exists for this SSB
            if (QUOTA_DATABASE[namaSekolah]) {
                const quota = QUOTA_DATABASE[namaSekolah];
                
                kuotaData = {
                    has_quota: true,
                    total: quota['7-8'] + quota['9-10'] + quota['11-12'],
                    '7-8': quota['7-8'],
                    '9-10': quota['9-10'],
                    '11-12': quota['11-12'],
                    current_counts: {
                        total: 0,
                        '7-8': 0,
                        '9-10': 0,
                        '11-12': 0
                    },
                    remaining: {
                        '7-8': quota['7-8'],
                        '9-10': quota['9-10'],
                        '11-12': quota['11-12']
                    },
                    percentage: {
                        '7-8': 0,
                        '9-10': 0,
                        '11-12': 0
                    }
                };
                
                updateQuotaDisplay();
                return true;
            } else {
                // No quota found
                kuotaData.has_quota = false;
                updateQuotaDisplay();
                showAlert('Kuota belum ditetapkan untuk SSB ini. Maksimal 50 pemain.', 'error');
                return false;
            }
        }

        // Update quota display
        function updateQuotaDisplay() {
            const quotaContent = document.getElementById('quota-content');
            const quotaSsbName = document.getElementById('quota-ssb-name');
            const maxPemainCount = document.getElementById('max-pemain-count');
            
            quotaSsbName.textContent = sekolahData.nama;
            
            if (!kuotaData.has_quota) {
                quotaContent.innerHTML = `
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center">
                            <span class="text-xl mr-2">⚠️</span>
                            <div class="text-yellow-800">
                                <strong class="text-sm sm:text-base">Kuota belum ditetapkan</strong>
                                <p class="text-xs sm:text-sm mt-1">Admin belum menetapkan kuota untuk SSB Anda. Anda dapat mendaftarkan maksimal 50 pemain sementara.</p>
                            </div>
                        </div>
                    </div>
                `;
                maxPemainCount.textContent = '50';
                return;
            }

            maxPemainCount.textContent = kuotaData.total;

            // Calculate current counts from pemainList
            updateCurrentCounts();

            quotaContent.innerHTML = `
                <p class="mb-2 text-sm sm:text-base">
                    Kuota yang diberikan: <strong class="text-blue-800">${kuotaData.total} Orang</strong>
                </p>
                <p class="mb-4 text-xs sm:text-sm">
                    Pemain terdaftar: <strong class="text-green-800">${kuotaData.current_counts.total} Orang dari ${kuotaData.total} Orang</strong>
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                    <div class="bg-white rounded-lg p-3 sm:p-4 border border-blue-100 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Kelompok 7-8 Tahun</span>
                            <span class="badge badge-info">${kuotaData['7-8']} kuota</span>
                        </div>
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="text-gray-500">Terdaftar: ${kuotaData.current_counts['7-8']}</span>
                            <span class="text-green-600 font-medium">Sisa: ${kuotaData.remaining['7-8']}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: ${kuotaData.percentage['7-8']}%"></div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-3 sm:p-4 border border-blue-100 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Kelompok 9-10 Tahun</span>
                            <span class="badge badge-warning">${kuotaData['9-10']} kuota</span>
                        </div>
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="text-gray-500">Terdaftar: ${kuotaData.current_counts['9-10']}</span>
                            <span class="text-green-600 font-medium">Sisa: ${kuotaData.remaining['9-10']}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full transition-all duration-300" style="width: ${kuotaData.percentage['9-10']}%"></div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-3 sm:p-4 border border-blue-100 shadow-sm sm:col-span-2 md:col-span-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Kelompok 11-12 Tahun</span>
                            <span class="badge badge-success">${kuotaData['11-12']} kuota</span>
                        </div>
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="text-gray-500">Terdaftar: ${kuotaData.current_counts['11-12']}</span>
                            <span class="text-green-600 font-medium">Sisa: ${kuotaData.remaining['11-12']}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: ${kuotaData.percentage['11-12']}%"></div>
                        </div>
                    </div>
                </div>
                ${generateQuotaWarnings()}
            `;
        }

        // Generate quota warnings
        function generateQuotaWarnings() {
            const alerts = [];
            
            ['7-8', '9-10', '11-12'].forEach(cat => {
                if (kuotaData.percentage[cat] >= 100) {
                    alerts.push(`Kategori ${cat} tahun sudah penuh (${kuotaData.current_counts[cat]}/${kuotaData[cat]})`);
                } else if (kuotaData.percentage[cat] >= 80) {
                    alerts.push(`Kategori ${cat} tahun hampir penuh (${kuotaData.percentage[cat].toFixed(0)}%)`);
                }
            });
            
            if (alerts.length > 0) {
                return `
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <span class="text-xl mr-2">⚠️</span>
                            <div class="text-yellow-800 text-sm flex-1">
                                <strong>Perhatian:</strong>
                                <ul class="list-disc list-inside mt-1">
                                    ${alerts.map(alert => `<li class="text-xs sm:text-sm">${alert}</li>`).join('')}
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            return '';
        }

        // Update current counts from pemainList
        function updateCurrentCounts() {
            kuotaData.current_counts = {
                total: pemainList.length,
                '7-8': pemainList.filter(p => p.kategori === '7-8').length,
                '9-10': pemainList.filter(p => p.kategori === '9-10').length,
                '11-12': pemainList.filter(p => p.kategori === '11-12').length
            };

            kuotaData.remaining = {
                '7-8': Math.max(0, kuotaData['7-8'] - kuotaData.current_counts['7-8']),
                '9-10': Math.max(0, kuotaData['9-10'] - kuotaData.current_counts['9-10']),
                '11-12': Math.max(0, kuotaData['11-12'] - kuotaData.current_counts['11-12'])
            };

            kuotaData.percentage = {
                '7-8': kuotaData['7-8'] > 0 ? (kuotaData.current_counts['7-8'] / kuotaData['7-8']) * 100 : 0,
                '9-10': kuotaData['9-10'] > 0 ? (kuotaData.current_counts['9-10'] / kuotaData['9-10']) * 100 : 0,
                '11-12': kuotaData['11-12'] > 0 ? (kuotaData.current_counts['11-12'] / kuotaData['11-12']) * 100 : 0
            };
        }

        // Validate quota before adding player
        function validateQuota(kategori) {
            if (!kuotaData.has_quota) {
                // No quota set, allow up to 50 players total
                if (pemainList.length >= 50) {
                    return {
                        valid: false,
                        message: 'Maksimal 50 pemain yang dapat didaftarkan!'
                    };
                }
                return { valid: true, message: '' };
            }

            // Check total quota
            if (kuotaData.current_counts.total >= kuotaData.total) {
                return {
                    valid: false,
                    message: 'Kuota total SSB sudah terpenuhi!'
                };
            }

            // Check category quota
            if (kuotaData.current_counts[kategori] >= kuotaData[kategori]) {
                return {
                    valid: false,
                    message: `Kuota kategori ${kategori} tahun sudah penuh (${kuotaData.current_counts[kategori]}/${kuotaData[kategori]})`
                };
            }

            return {
                valid: true,
                message: `Sisa kuota kategori ${kategori}: ${kuotaData.remaining[kategori]} pemain`
            };
        }

        // Show alert function
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
            const icon = type === 'success' ? '✓' : '⚠️';
            
            const alertHTML = `
                <div class="mb-6 ${alertClass} border rounded-lg p-4 alert-slide-down">
                    <div class="flex items-center">
                        <span class="text-xl mr-3">${icon}</span>
                        <span>${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-current hover:opacity-70">
                            ✕
                        </button>
                    </div>
                </div>
            `;
            
            alertContainer.innerHTML = alertHTML;
            
            setTimeout(() => {
                const alert = alertContainer.querySelector('div');
                if (alert) alert.remove();
            }, 5000);
        }

        // Auto-detect kategori umur berdasarkan umur
        document.getElementById('umur_pemain').addEventListener('input', function() {
            const umur = parseInt(this.value);
            const radioButtons = document.querySelectorAll('input[name="umur_kategori"]');
            
            radioButtons.forEach(radio => radio.checked = false);
            
            let selectedKategori = '';
            if (umur >= 7 && umur <= 8) {
                selectedKategori = '7-8';
                document.querySelector('input[value="7-8"]').checked = true;
            } else if (umur >= 9 && umur <= 10) {
                selectedKategori = '9-10';
                document.querySelector('input[value="9-10"]').checked = true;
            } else if (umur >= 11 && umur <= 12) {
                selectedKategori = '11-12';
                document.querySelector('input[value="11-12"]').checked = true;
            }

            // Show quota warning for selected category
            if (selectedKategori && kuotaData.has_quota) {
                const validation = validateQuota(selectedKategori);
                updateQuotaWarning(validation);
            }
        });

        // Update quota warning in form
        function updateQuotaWarning(validation) {
            const warningArea = document.getElementById('quota-warning-area');
            
            if (!validation.valid) {
                warningArea.innerHTML = `
                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-xl mr-2">⚠️</span>
                            <span class="text-red-800 text-sm">${validation.message}</span>
                        </div>
                    </div>
                `;
            } else if (validation.message) {
                warningArea.innerHTML = `
                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-xl mr-2">✓</span>
                            <span class="text-green-800 text-sm">${validation.message}</span>
                        </div>
                    </div>
                `;
            } else {
                warningArea.innerHTML = '';
            }
        }

        // Handle form sekolah
        document.getElementById('sekolah-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            sekolahData = {
                nama: document.getElementById('nama_sekolah').value,
                pic: document.getElementById('pic').value,
                email: document.getElementById('email').value,
                telepon: document.getElementById('telepon').value
            };

            // Update info sekolah
            document.getElementById('info-nama').textContent = sekolahData.nama;
            document.getElementById('info-pic').textContent = sekolahData.pic;
            document.getElementById('info-email').textContent = sekolahData.email;
            document.getElementById('info-telepon').textContent = sekolahData.telepon;

            // Fetch quota data from hardcoded database
            fetchQuotaData(sekolahData.nama);

            // Update progress
            updateProgress(2);

            // Show form pemain
            document.getElementById('form-sekolah').classList.add('hidden');
            document.getElementById('form-pemain').classList.remove('hidden');
        });

        // Handle form pemain
        document.getElementById('pemain-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nama = document.getElementById('nama_pemain').value;
            const umur = document.getElementById('umur_pemain').value;
            const kategoriRadio = document.querySelector('input[name="umur_kategori"]:checked');
            
            if (!kategoriRadio) {
                showAlert('Pilih kategori umur yang sesuai!', 'error');
                return;
            }
            
            const kategori = kategoriRadio.value;

            // Validasi kategori umur sesuai dengan umur
            const umurInt = parseInt(umur);
            if ((kategori === '7-8' && (umurInt < 7 || umurInt > 8)) ||
                (kategori === '9-10' && (umurInt < 9 || umurInt > 10)) ||
                (kategori === '11-12' && (umurInt < 11 || umurInt > 12))) {
                showAlert('Kategori umur tidak sesuai dengan umur pemain!', 'error');
                return;
            }

            // Validate quota
            const validation = validateQuota(kategori);
            if (!validation.valid) {
                showAlert(validation.message, 'error');
                return;
            }

            const pemain = {
                id: Date.now(),
                nama: nama,
                umur: umur,
                kategori: kategori
            };

            pemainList.push(pemain);
            updatePemainList();
            updatePemainCount();
            updateCurrentCounts();
            updateQuotaDisplay();

            // Reset form
            document.getElementById('pemain-form').reset();
            document.getElementById('quota-warning-area').innerHTML = '';

            // Enable selesai button jika ada pemain
            document.getElementById('btn-selesai').disabled = false;

            showAlert(`Pemain ${nama} berhasil ditambahkan!`, 'success');
        });

        function updatePemainList() {
            const container = document.getElementById('pemain-list');
            
            if (pemainList.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">Belum ada pemain yang didaftarkan</p>';
                return;
            }

            container.innerHTML = pemainList.map((pemain, index) => `
                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                    <div class="flex-1">
                        <span class="font-medium">${index + 1}. ${pemain.nama}</span>
                        <span class="text-sm text-gray-600 ml-2">(${pemain.umur} tahun - Kategori ${pemain.kategori})</span>
                    </div>
                    <button onclick="hapusPemain(${pemain.id})" class="text-red-500 hover:text-red-700 font-semibold">
                        Hapus
                    </button>
                </div>
            `).join('');
        }

        function updatePemainCount() {
            document.getElementById('pemain-count').textContent = pemainList.length;
            
            const maxPemain = kuotaData.has_quota ? kuotaData.total : 50;
            const btnTambah = document.getElementById('btn-tambah');
            
            if (pemainList.length >= maxPemain) {
                btnTambah.disabled = true;
                btnTambah.textContent = `Maksimal ${maxPemain} Pemain Tercapai`;
                btnTambah.classList.add('bg-gray-400');
                btnTambah.classList.remove('bg-green-600', 'hover:bg-green-700');
            } else {
                btnTambah.disabled = false;
                btnTambah.textContent = 'Tambah Pemain';
                btnTambah.classList.remove('bg-gray-400');
                btnTambah.classList.add('bg-green-600', 'hover:bg-green-700');
            }
        }

        function hapusPemain(id) {
            pemainList = pemainList.filter(pemain => pemain.id !== id);
            updatePemainList();
            updatePemainCount();
            updateCurrentCounts();
            updateQuotaDisplay();
            
            if (pemainList.length === 0) {
                document.getElementById('btn-selesai').disabled = true;
            }
            
            showAlert('Pemain berhasil dihapus!', 'success');
        }

        function updateProgress(step) {
            if (step === 2) {
                document.getElementById('step1-indicator').classList.remove('bg-orange-600');
                document.getElementById('step1-indicator').classList.add('bg-green-500');
                document.getElementById('step1-text').classList.remove('text-orange-600');
                document.getElementById('step1-text').classList.add('text-green-600');
                
                document.getElementById('step2-indicator').classList.remove('bg-gray-300', 'text-gray-600');
                document.getElementById('step2-indicator').classList.add('bg-orange-600', 'text-white');
                document.getElementById('step2-text').classList.remove('text-gray-600');
                document.getElementById('step2-text').classList.add('text-orange-600');
                
                document.getElementById('progress-line').classList.remove('bg-gray-300');
                document.getElementById('progress-line').classList.add('bg-green-500');
            }
        }

        function kembaliKeSekolah() {
            if (pemainList.length > 0) {
                if (!confirm('Data pemain yang telah ditambahkan akan hilang. Yakin ingin kembali?')) {
                    return;
                }
            }
            
            document.getElementById('form-pemain').classList.add('hidden');
            document.getElementById('form-sekolah').classList.remove('hidden');
            
            // Reset progress
            document.getElementById('step1-indicator').classList.add('bg-orange-600');
            document.getElementById('step1-indicator').classList.remove('bg-green-500');
            document.getElementById('step1-text').classList.add('text-orange-600');
            document.getElementById('step1-text').classList.remove('text-green-600');
            
            document.getElementById('step2-indicator').classList.add('bg-gray-300', 'text-gray-600');
            document.getElementById('step2-indicator').classList.remove('bg-orange-600', 'text-white');
            document.getElementById('step2-text').classList.add('text-gray-600');
            document.getElementById('step2-text').classList.remove('text-orange-600');
            
            document.getElementById('progress-line').classList.add('bg-gray-300');
            document.getElementById('progress-line').classList.remove('bg-green-500');
        }

        async function selesaiPendaftaran() {
            if (pemainList.length === 0) {
                showAlert('Minimal harus mendaftarkan 1 pemain!', 'error');
                return;
            }

            // Validate all players against quota
            if (kuotaData.has_quota) {
                const counts = {
                    '7-8': pemainList.filter(p => p.kategori === '7-8').length,
                    '9-10': pemainList.filter(p => p.kategori === '9-10').length,
                    '11-12': pemainList.filter(p => p.kategori === '11-12').length
                };

                for (let kategori in counts) {
                    if (counts[kategori] > kuotaData[kategori]) {
                        showAlert(`Jumlah pemain kategori ${kategori} melebihi kuota (${counts[kategori]}/${kuotaData[kategori]})`, 'error');
                        return;
                    }
                }
            }

            const btnSelesai = document.getElementById('btn-selesai');
            const originalText = btnSelesai.innerHTML;
            btnSelesai.disabled = true;
            btnSelesai.innerHTML = '⏳ Menyimpan...';

            try {
                const dataToSend = {
                    nama_sekolah: sekolahData.nama,
                    pic: sekolahData.pic,
                    email: sekolahData.email,
                    telepon: sekolahData.telepon,
                    pemain: pemainList.map(p => ({
                        nama: p.nama,
                        umur: parseInt(p.umur),
                        umur_kategori: p.kategori
                    }))
                };

                const response = await fetch('/daftar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('form-pemain').classList.add('hidden');
                    document.getElementById('success-message').classList.remove('hidden');
                    showAlert('Pendaftaran berhasil disimpan!', 'success');
                } else {
                    showAlert('Terjadi kesalahan: ' + result.message, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.', 'error');
            } finally {
                btnSelesai.disabled = false;
                btnSelesai.innerHTML = originalText;
            }
        }

        function resetForm() {
            sekolahData = {};
            pemainList = [];
            kuotaData = {
                has_quota: false,
                total: 0,
                '7-8': 0,
                '9-10': 0,
                '11-12': 0,
                current_counts: { total: 0, '7-8': 0, '9-10': 0, '11-12': 0 },
                remaining: { '7-8': 0, '9-10': 0, '11-12': 0 },
                percentage: { '7-8': 0, '9-10': 0, '11-12': 0 }
            };
            
            document.getElementById('sekolah-form').reset();
            document.getElementById('pemain-form').reset();
            
            document.getElementById('success-message').classList.add('hidden');
            document.getElementById('form-sekolah').classList.remove('hidden');
            
            updatePemainList();
            updatePemainCount();
            
            // Reset progress
            document.getElementById('step1-indicator').classList.add('bg-orange-600');
            document.getElementById('step1-indicator').classList.remove('bg-green-500');
            document.getElementById('step1-text').classList.add('text-orange-600');
            document.getElementById('step1-text').classList.remove('text-green-600');
            
            document.getElementById('step2-indicator').classList.add('bg-gray-300', 'text-gray-600');
            document.getElementById('step2-indicator').classList.remove('bg-orange-600', 'text-white');
            document.getElementById('step2-text').classList.add('text-gray-600');
            document.getElementById('step2-text').classList.remove('text-orange-600');
            
            document.getElementById('progress-line').classList.add('bg-gray-300');
            document.getElementById('progress-line').classList.remove('bg-green-500');
            
            document.getElementById('btn-selesai').disabled = true;
        }

        // Initialize
        updatePemainList();
        updatePemainCount();

        // Export to PDF Function
        async function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const colors = {
                primary: [255, 153, 51],      // Orange
                secondary: [34, 197, 94],     // Green
                dark: [31, 41, 55],           // Dark gray
                light: [249, 250, 251],       // Light gray
                white: [255, 255, 255],
                accent: [59, 130, 246],       // Blue
                lightOrange: [255, 237, 213], // Light orange
                lightGray: [156, 163, 175]    // Light gray
            };
            
            const currentDate = new Date().toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            doc.setFontSize(10);
            doc.text(currentDate, 196, 35, { align: "right" });

            // ====== Config Logo ======
            const logoUrl = "/images/logo.png";
            try {
                const logo = await fetch(logoUrl).then(res => res.blob()).then(blob => {
                    return new Promise(resolve => {
                        const reader = new FileReader();
                        reader.onload = () => resolve(reader.result);
                        reader.readAsDataURL(blob);
                    });
                });
                doc.addImage(logo, "PNG", 14, 5, 20, 20);
            } catch (error) {
                console.log('Logo tidak dapat dimuat');
            }

            // ====== Header ======
            doc.setFillColor(255, 255, 255);
            doc.rect(0, 0, 210, 30, "F");
            doc.setFont("helvetica", "bold");
            doc.setFontSize(16);
            doc.setTextColor(0, 0, 0);
            doc.text("Bayan Soccer Clinic", 105, 18, { align: "center" });

            // ====== Informasi Sekolah ======
            doc.setFontSize(13);
            doc.setFont("helvetica", "bold");
            doc.text("Data Sekolah Sepak Bola", 14, 45);

            doc.setFont("helvetica", "normal");
            doc.setFontSize(11);
            doc.setTextColor(50, 50, 50);
            doc.text(`Nama SSB   : ${sekolahData.nama}`, 14, 55);
            doc.text(`PIC        : ${sekolahData.pic}`, 14, 63);
            doc.text(`Email      : ${sekolahData.email}`, 14, 71);
            doc.text(`Telepon    : ${sekolahData.telepon}`, 14, 79);

            // ====== Informasi Kuota ======
          //  if (kuotaData.has_quota) {
          //      doc.setFontSize(11);
           //     doc.setFont("helvetica", "bold");
           //     doc.text("Kuota yang Ditetapkan:", 14, 89);
           //     doc.setFont("helvetica", "normal");
           //     doc.setFontSize(10);
           //     doc.text(`Total Kuota: ${kuotaData.total} Pemain`, 14, 96);
           //     doc.text(`- Kategori 7-8 tahun: ${kuotaData['7-8']} pemain`, 20, 102);
            //    doc.text(`- Kategori 9-10 tahun: ${kuotaData['9-10']} pemain`, 20, 108);
            //    doc.text(`- Kategori 11-12 tahun: ${kuotaData['11-12']} pemain`, 20, 114);
           // }

            // ====== Grouping Data Per Kategori ======
            const groupedData = {};
            pemainList.forEach(pemain => {
                if (!groupedData[pemain.kategori]) {
                    groupedData[pemain.kategori] = [];
                }
                groupedData[pemain.kategori].push(pemain);
            });

            // ====== Daftar Pemain Per Kategori ======
            let currentY = kuotaData.has_quota ? 125 : 95;
            
            Object.keys(groupedData).forEach((kategori, kategoriIndex) => {
                const pemainKategori = groupedData[kategori];
                
                // Judul kategori
                doc.setFontSize(13);
                doc.setFont("helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text(`Kategori ${kategori} Tahun`, 14, currentY);
                
                // Data tabel untuk kategori ini
                const tableData = pemainKategori.map((p, i) => [
                    i + 1,
                    p.nama,
                    p.umur
                ]);

                doc.autoTable({
                    head: [["No", "Nama Pemain", "Umur"]],
                    body: tableData,
                    startY: currentY + 5,
                    theme: "grid",
                    styles: {
                        halign: "center",
                        valign: "middle",
                        fontSize: 11,
                        font: "helvetica"
                    },
                    headStyles: {
                        fillColor: [0, 102, 204],
                        textColor: [255, 255, 255],
                        fontStyle: "bold"
                    },
                    bodyStyles: {
                        textColor: [60, 60, 60],
                    },
                    alternateRowStyles: { fillColor: [245, 245, 245] },
                    margin: { left: 14, right: 14 },
                });

                currentY = doc.lastAutoTable.finalY + 15;
                
                if (currentY > 250 && kategoriIndex < Object.keys(groupedData).length - 1) {
                    doc.addPage();
                    currentY = 20;
                }
            });

            // ====== Important Notice ======
            const finalY = doc.lastAutoTable.finalY || 150;
            
            doc.setFillColor(255, 248, 220);
            doc.setDrawColor(255, 193, 7);
            doc.setLineWidth(1);
            doc.roundedRect(14, finalY + 10, 182, 35, 3, 3, 'FD');
            
            doc.setFont("helvetica", "bold");
            doc.setFontSize(12);
            doc.setTextColor(180, 83, 9);
            doc.text("INFORMASI PENTING", 20, finalY + 20);
            
            doc.setFont("helvetica", "normal");
            doc.setFontSize(9);
            doc.setTextColor(146, 64, 14);
            const noticeText = "List pendaftar saat ini bersifat sementara. Jumlah kuota yang akan didapatkan akan";
            const noticeText2 = "diinformasikan melalui link website terpisah dan akan diinformasikan melalui nomor";
            const noticeText3 = "telepon PIC terdaftar.";
            
            doc.text(noticeText, 20, finalY + 30);
            doc.text(noticeText2, 20, finalY + 37);
            doc.text(noticeText3, 20, finalY + 44);

            // ====== Footer ======
            const pageHeight = doc.internal.pageSize.height;
            
            doc.setFillColor(...colors.dark);
            doc.rect(0, pageHeight - 25, 210, 25, "F");
            
            doc.setFont("helvetica", "normal");
            doc.setFontSize(9);
            doc.setTextColor(...colors.white);
            doc.text("© 2025 Bayan Group. All Rights Reserved.", 14, pageHeight - 15);
            doc.text(`Dokumen dibuat pada: ${currentDate}`, 14, pageHeight - 8);

            // Save PDF
            doc.save(`Pendaftaran_${sekolahData.nama}.pdf`);
            
            showAlert('PDF berhasil diunduh!', 'success');
        }
    </script>
</body>
</html>