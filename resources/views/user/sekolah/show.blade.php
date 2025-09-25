
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayan Soccer Dashboard - {{ $sekolah->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="ico" href="{{ asset('logo.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .btn-orange {
            background-color: #f59e0b;
        }
        
        .btn-orange:hover {
            background-color: #d97706;
        }
        
        .btn-green {
            background-color: #10b981;
        }
        
        .btn-green:hover {
            background-color: #059669;
        }
        
        .table-hover:hover {
            background-color: #f9fafb;
        }
        
        .action-btn {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .action-btn.view {
            background-color: #6b7280;
            color: white;
        }
        
        .action-btn.edit {
            background-color: #f59e0b;
            color: white;
        }
        
        .action-btn.delete {
            background-color: #ef4444;
            color: white;
        }
        
        .action-btn:hover:not(.disabled) {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        .action-btn.disabled {
            background-color: #9ca3af !important;
            cursor: not-allowed !important;
            opacity: 0.5 !important;
        }

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

        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }

        .loading-spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #f59e0b;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Countdown Styles */
        .countdown-container {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
        }

        .countdown-item {
            text-align: center;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .countdown-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
            line-height: 1;
        }

        .countdown-label {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 0.25rem;
        }

        .navbar-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        /* Untuk desktop */
        @media (min-width: 768px) {
            .navbar-logo {
                width: 60px;
                height: 60px;
            }
        }

        /* Pulse animation for urgent countdown */
        .countdown-urgent {
            animation: pulse-orange 2s infinite;
        }

        @keyframes pulse-orange {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
            }
            50% { 
                transform: scale(1.02);
                box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
            }
        }

        /* Critical countdown animation */
        .countdown-critical {
            animation: pulse-red 1s infinite;
        }

        @keyframes pulse-red {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            50% { 
                transform: scale(1.05);
                box-shadow: 0 0 0 15px rgba(239, 68, 68, 0);
            }
        }

        /* Expired countdown styles */
        .countdown-expired {
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%) !important;
            border: 2px solid #ef4444;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .navbar-logo {
                width: 40px;
                height: 40px;
            }

            .countdown-container {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .countdown-number {
                font-size: 1.5rem;
            }

            .countdown-label {
                font-size: 0.75rem;
            }

            .countdown-item {
                padding: 0.5rem;
            }

            .modal-content {
                margin: 1rem;
                max-width: calc(100% - 2rem);
            }

            .action-btn {
                padding: 6px 8px;
                font-size: 12px;
            }

            .badge {
                padding: 0.2rem 0.5rem;
                font-size: 0.7rem;
            }
        }

        @media (max-width: 640px) {
            .countdown-container {
                padding: 0.75rem;
            }

            .countdown-number {
                font-size: 1.25rem;
            }

            .countdown-label {
                font-size: 0.7rem;
            }

            .countdown-item {
                padding: 0.4rem;
            }

            /* Hide some columns on very small screens */
            .mobile-hide {
                display: none;
            }

            .action-btn {
                padding: 4px 6px;
                font-size: 11px;
            }
        }

        /* Waiting for quota styles */
        .countdown-waiting {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            border: 2px dashed #9ca3af;
        }

        /* Disabled button styles */
        .btn-disabled {
            background-color: #9ca3af !important;
            cursor: not-allowed !important;
            opacity: 0.6 !important;
        }

        .btn-disabled:hover {
            background-color: #9ca3af !important;
            transform: none !important;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Main Content Container -->
    <div class="min-h-screen">
        <!-- Header/Navbar -->
        <header class="bg-white shadow-lg border-b border-gray-200">
            <div class="px-4 md:px-6 py-3 md:py-4">
                <!-- Logo and Brand Section -->
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="flex items-center space-x-2 md:space-x-4">
                        <img src="https://rec-data.kalibrr.com/www.kalibrr.com/logos/GKTW5JUNVCCLNZFJFEE7ESSU26UQKVR5QKN7E65J-64ab6a30.png" 
                            alt="Bayan Logo" 
                            class="navbar-logo">
                        <div>
                            <h2 class="text-sm md:text-xl font-bold text-orange-600">BAYAN SOCCER CLINIC</h2>
                            <p class="text-xs md:text-sm text-gray-600">{{ $sekolah->nama }}</p>
                        </div>
                    </div>
                    
                
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-1 md:space-x-2 text-xs md:text-sm font-medium text-orange-600">
                    <span>Daftar Pemain SSB</span>
                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-orange-600 font-medium">List</span>
                </nav>
            
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 md:p-6">
            <!-- Alert Container -->
            <div id="alertContainer"></div>

            <!-- System Status Alert (shown when deadline has passed) -->
            <div id="systemStatusAlert" class="mb-4 md:mb-6 bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-200 rounded-lg p-4 md:p-6 alert-slide-down hidden">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lock text-red-600 text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-2 md:ml-3 flex-1">
                        <h3 class="text-base md:text-lg font-bold text-red-800 mb-2">
                            <i class="fas fa-exclamation-triangle mr-1 md:mr-2"></i>Sistem Telah Dikunci
                        </h3>
                        <div class="text-red-700 mb-4">
                            <p class="mb-2 text-sm md:text-base">
                                Batas waktu pendaftaran telah berakhir pada <strong id="systemStatusDeadline">Loading...</strong>
                            </p>
                            <p class="mb-4 text-xs md:text-sm">
                                Semua fitur edit dan hapus telah dinonaktifkan. Data yang tersimpan sekarang bersifat <strong>final dan permanen</strong>.
                            </p>
                            <div class="bg-red-100 border border-red-300 rounded-lg p-3 mt-3">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-red-600 mr-2"></i>
                                    <div class="text-red-800 text-xs md:text-sm">
                                        <strong>Catatan:</strong> Jika Anda memerlukan perubahan data setelah batas waktu, silakan hubungi administrator sistem.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Countdown Timer -->
            <div class="countdown-container" id="countdownContainer">
                <div id="countdownContent">
                    <!-- This will be populated by JavaScript based on quota status -->
                </div>
            </div>

            <!-- Alert Informasi Kuota yang sudah diupdate -->
            <div id="quotaAlert" class="mb-4 md:mb-6 bg-gradient-to-r from-orange-50 to-green-50 border border-blue-200 rounded-lg p-4 md:p-6 alert-slide-down">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-2 md:ml-3 flex-1">
                        <h3 class="text-base md:text-lg font-semibold text-blue-800 mb-2">
                            <i class="fas fa-futbol mr-1 md:mr-2"></i>Informasi Kuota SSB {{ $sekolah->nama }}
                        </h3>
                        <div class="text-blue-700 mb-4">
                            @if($kuotaData['has_quota'])
                                <p class="mb-2 text-sm md:text-base">
                                    Kuota yang diberikan: <strong class="text-blue-800">{{ $kuotaData['total'] }} Orang</strong>
                                </p>
                                <p class="mb-4 text-xs md:text-sm">
                                    Pemain terdaftar: <strong class="text-green-800">{{ $kuotaData['current_counts']['total'] }} Orang dari {{ $kuotaData['total'] }} Orang</strong>
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                                    <div class="bg-white rounded-lg p-3 md:p-4 border border-blue-100 shadow-sm">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs md:text-sm font-medium text-gray-600">Kelompok 7-8 Tahun</span>
                                            <span class="badge badge-info">{{ $kuotaData['7-8'] }} kuota</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs mb-2">
                                            <span class="text-gray-500">Terdaftar: {{ $kuotaData['current_counts']['7-8'] }}</span>
                                            <span class="text-green-600 font-medium">Sisa: {{ $kuotaData['remaining']['7-8'] }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
                                               ></div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-3 md:p-4 border border-blue-100 shadow-sm">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs md:text-sm font-medium text-gray-600">Kelompok 9-10 Tahun</span>
                                            <span class="badge badge-warning">{{ $kuotaData['9-10'] }} kuota</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs mb-2">
                                            <span class="text-gray-500">Terdaftar: {{ $kuotaData['current_counts']['9-10'] }}</span>
                                            <span class="text-green-600 font-medium">Sisa: {{ $kuotaData['remaining']['9-10'] }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-500 h-2 rounded-full transition-all duration-300" 
                                            ></div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-3 md:p-4 border border-blue-100 shadow-sm sm:col-span-2 md:col-span-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs md:text-sm font-medium text-gray-600">Kelompok 11-12 Tahun</span>
                                            <span class="badge badge-success">{{ $kuotaData['11-12'] }} kuota</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs mb-2">
                                            <span class="text-gray-500">Terdaftar: {{ $kuotaData['current_counts']['11-12'] }}</span>
                                            <span class="text-green-600 font-medium">Sisa: {{ $kuotaData['remaining']['11-12'] }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                                ></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Alert untuk kategori yang hampir penuh atau penuh -->
                                @php
                                    $alerts = [];
                                    foreach(['7-8', '9-10', '11-12'] as $cat) {
                                        if($kuotaData['percentage'][$cat] >= 100) {
                                            $alerts[] = "Kategori {$cat} tahun sudah melebihi batas";
                                        } elseif($kuotaData['percentage'][$cat] >= 80) {
                                            $alerts[] = "Kategori {$cat} tahun hampir penuh ({$kuotaData['percentage'][$cat]}%)";
                                        }
                                    }
                                @endphp
                                
                                @if(count($alerts) > 0)
                                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                            <div class="text-yellow-800 text-sm">
                                                <strong>Perhatian:</strong>
                                                <ul class="list-disc list-inside mt-1">
                                                    @foreach($alerts as $alert)
                                                        <li class="text-xs md:text-sm">{{ $alert }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 md:p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 md:mr-3"></i>
                                        <div class="text-yellow-800">
                                            <strong class="text-sm md:text-base">Kuota belum ditetapkan</strong>
                                            <p class="text-xs md:text-sm mt-1">Admin belum menetapkan kuota untuk SSB Anda. Hubungi admin untuk informasi lebih lanjut.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <p class="mt-3 text-xs md:text-sm">
                                <i class="fas fa-users-cog mr-1"></i>
                                Silakan Anda memanajemen pemain SSB Anda dengan bijak sesuai kategori umur yang tersedia.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                            <div class="flex items-center space-x-2">
                                <button onclick="refreshQuota()" class="text-blue-500 hover:text-blue-700 text-xs md:text-sm font-medium">
                                    <i class="fas fa-refresh mr-1"></i>Refresh Data
                                </button>
                                @if($kuotaData['has_quota'])
                                    <span class="text-gray-400 hidden sm:inline">•</span>
                                    <span class="text-xs text-gray-500">Diperbarui: {{ $sekolah->kuotaSekolah->updated_at ?? 'Belum diatur' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
            <div id="successAlert" class="mb-4 md:mb-6 bg-green-50 border border-green-200 rounded-lg p-4 alert-slide-down">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2 md:mr-3"></i>
                    <span class="text-green-800 text-sm md:text-base">{{ session('success') }}</span>
                    <button onclick="closeSuccessAlert()" class="ml-auto text-green-500 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div id="errorAlert" class="mb-4 md:mb-6 bg-red-50 border border-red-200 rounded-lg p-4 alert-slide-down">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2 md:mr-3"></i>
                    <span class="text-red-800 text-sm md:text-base">{{ session('error') }}</span>
                    <button onclick="closeErrorAlert()" class="ml-auto text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Table Header -->
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center space-x-4 w-full sm:w-auto">
                            <div class="relative flex-1 sm:flex-none">
                                <input type="text" id="searchInput" placeholder="Cari nama pemain..." class="w-full sm:w-64 pl-8 md:pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-400 absolute left-2 md:left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <!-- Tambah Pemain Button -->
                            <button type="button" onclick="openAddModal()" class="ml-2 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200 flex items-center" id="addPlayerBtn">
                                <i class="fas fa-plus mr-2"></i>Tambah Pemain
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="bg-white p-3 md:p-6 rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="bg-gray-100 text-left text-gray-600">
                                    <th class="p-2 md:p-3 text-xs md:text-sm">No</th>
                                    <th class="p-2 md:p-3 text-xs md:text-sm">Nama</th>
                                    <th class="p-2 md:p-3 text-xs md:text-sm mobile-hide">Umur</th>
                                    <th class="p-2 md:p-3 text-xs md:text-sm">Kategori</th>
                                    <th class="p-2 md:p-3 text-center text-xs md:text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="playerTable">
                                @forelse($sekolah->pemainBolas as $index => $pemain)
                                <tr class="border-t hover:bg-gray-50 table-hover" data-player-id="{{ $pemain->id }}">
                                    <td class="p-2 md:p-3 text-xs md:text-sm">{{ $index + 1 }}</td>
                                    <td class="p-2 md:p-3 font-medium text-xs md:text-sm">{{ $pemain->nama }}</td>
                                    <td class="p-2 md:p-3 text-xs md:text-sm mobile-hide">{{ $pemain->umur }} tahun</td>
                                    <td class="p-2 md:p-3">
                                        <span class="badge 
                                            @if($pemain->umur_kategori == '7-8') badge-info 
                                            @elseif($pemain->umur_kategori == '9-10') badge-warning 
                                            @else badge-success @endif">
                                            {{ $pemain->umur_kategori }} th
                                        </span>
                                    </td>
                                    <td class="p-2 md:p-3 text-center">
                                        <div class="flex justify-center gap-1 md:gap-2">
                                            <button onclick="editPlayer({{ $pemain->id }})" class="action-btn edit" data-edit-btn>
                                                <i class="fas fa-edit mr-1"></i><span class="hidden sm:inline">Edit</span>
                                            </button>
                                            <button onclick="deletePlayer({{ $pemain->id }}, '{{ $pemain->nama }}')" class="action-btn delete" data-delete-btn>
                                                <i class="fas fa-trash mr-1"></i><span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr id="emptyRow">
                                    <td colspan="5" class="p-6 md:p-8 text-center text-gray-500">
                                        <i class="fas fa-users text-3xl md:text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-base md:text-lg font-medium">Belum ada pemain</p>
                                        <p class="text-xs md:text-sm">Mulai tambahkan pemain untuk SSB Anda</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Player Modal -->
    <div id="editPlayerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-overlay">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md modal-content">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Data Pemain</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="editPlayerForm" class="p-6">
                    <div class="mb-4">
                        <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Pemain</label>
                        <input type="text" id="edit_nama" name="nama" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div class="mb-4">
                        <label for="edit_umur" class="block text-sm font-medium text-gray-700 mb-2">Umur (tahun)</label>
                        <input type="number" id="edit_umur" name="umur" required min="7" max="12"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Umur</label>
                        <div class="flex flex-wrap gap-2">
                            <label class="flex-1 flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="edit_umur_kategori" value="7-8" class="hidden peer" required>
                                <span class="w-full text-center text-xs px-2 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-300">
                                    7-8 Tahun
                                </span>
                            </label>
                            <label class="flex-1 flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="edit_umur_kategori" value="9-10" class="hidden peer" required>
                                <span class="w-full text-center text-xs px-2 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-300">
                                    9-10 Tahun
                                </span>
                            </label>
                            <label class="flex-1 flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="edit_umur_kategori" value="11-12" class="hidden peer" required>
                                <span class="w-full text-center text-xs px-2 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-300">
                                    11-12 Tahun
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200">
                            Batal
                        </button>
                        <button type="submit" id="updatePlayerBtn"
                                class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200">
                            <span class="loading-text">Update Pemain</span>
                            <span class="loading-spinner-container hidden">
                                <div class="loading-spinner inline-block"></div>
                                <span class="ml-2">Memperbarui...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Player Modal -->
    <div id="addPlayerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-overlay">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md modal-content">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Tambah Pemain Baru</h3>
                    <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="addPlayerForm" class="p-6">
                    <div class="mb-4">
                        <label for="add_nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Pemain</label>
                        <input type="text" id="add_nama" name="nama" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div class="mb-4">
                        <label for="add_umur" class="block text-sm font-medium text-gray-700 mb-2">Umur (tahun)</label>
                        <input type="number" id="add_umur" name="umur" required min="7" max="12"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Umur</label>
                        <div class="flex flex-wrap gap-2">
                            <label class="flex-1 flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="add_umur_kategori" value="7-8" class="hidden peer" required>
                                <span class="w-full text-center text-xs px-2 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-300">
                                    7-8 Tahun
                                </span>
                            </label>
                            <label class="flex-1 flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="add_umur_kategori" value="9-10" class="hidden peer" required>
                                <span class="w-full text-center text-xs px-2 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-300">
                                    9-10 Tahun
                                </span>
                            </label>
                            <label class="flex-1 flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="add_umur_kategori" value="11-12" class="hidden peer" required>
                                <span class="w-full text-center text-xs px-2 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-300">
                                    11-12 Tahun
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAddModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200">
                            Batal
                        </button>
                        <button type="submit" id="addPlayerBtnModal"
                                class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200">
                            <span class="loading-text">Tambah Pemain</span>
                            <span class="loading-spinner-container hidden">
                                <div class="loading-spinner inline-block"></div>
                                <span class="ml-2">Menambahkan...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentEditingPlayerId = null;
        const userToken = '{{ $sekolah->user_token }}';
        let countdownInterval;
        let isDeadlinePassed = false;
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Fixed deadline: September 30, 2025 at 23:59:59 (customizable)
        const REGISTRATION_DEADLINE = new Date('2025-10-15T23:59:59+08:00'); // UTC+7 for Indonesia
        
        // Check if quota is available from server-side data
        const hasQuota = @json($kuotaData['has_quota']);

        // ===== IMPROVED COUNTDOWN SYSTEM =====
        
        // Initialize countdown based on quota status
        function initCountdown() {
            const countdownContent = document.getElementById('countdownContent');
            
            if (!hasQuota) {
                // Show waiting message when no quota is set
                showWaitingForQuota();
                return;
            }

            // Check if deadline has already passed
            const now = new Date();
            if (now > REGISTRATION_DEADLINE) {
                handleExpiredDeadline();
                return;
            }
            
            // Setup active countdown
            setupActiveCountdown();
            
            // Start countdown timer
            updateCountdown();
            countdownInterval = setInterval(updateCountdown, 1000);
        }

        function showWaitingForQuota() {
            const countdownContent = document.getElementById('countdownContent');
            document.getElementById('countdownContainer').classList.add('countdown-waiting');
            countdownContent.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-hourglass-half text-4xl md:text-6xl mb-4 opacity-50"></i>
                    <h3 class="text-lg md:text-2xl font-bold mb-2">Menunggu Kuota dari Admin</h3>
                    <p class="text-sm md:text-lg opacity-90">Countdown akan dimulai setelah admin menetapkan kuota SSB</p>
                    <p class="text-xs md:text-sm opacity-75 mt-2">Hubungi admin untuk informasi lebih lanjut</p>
                    <div class="mt-4">
                        <button onclick="refreshQuota()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                            <i class="fas fa-refresh mr-2"></i>Refresh Status
                        </button>
                    </div>
                </div>
            `;
        }

        function setupActiveCountdown() {
            const countdownContent = document.getElementById('countdownContent');
            
            // Show active countdown when quota is available
            document.getElementById('countdownContainer').classList.remove('countdown-waiting');
            countdownContent.innerHTML = `
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4 gap-2">
                    <div>
                        <h3 class="text-base md:text-lg font-bold flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            Waktu Pendaftaran Tersisa
                        </h3>
                        <p class="text-xs md:text-sm opacity-90">Sistem akan dikunci setelah batas waktu berakhir</p>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-xs opacity-75" id="endDate">Berakhir: ${formatDeadlineDate()}</p>
                        <p class="text-xs opacity-60 mt-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Fitur edit/hapus akan dinonaktifkan
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4" id="countdownGrid">
                    <div class="countdown-item">
                        <span class="countdown-number" id="days">00</span>
                        <span class="countdown-label">Hari</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="hours">00</span>
                        <span class="countdown-label">Jam</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="minutes">00</span>
                        <span class="countdown-label">Menit</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="seconds">00</span>
                        <span class="countdown-label">Detik</span>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-3 md:mt-4">
                    <div class="flex justify-between text-xs opacity-75 mb-1">
                        <span>Waktu Berlalu</span>
                        <span id="progressPercent">0%</span>
                    </div>
                    <div class="w-full bg-black bg-opacity-20 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full transition-all duration-1000" id="progressBar" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Countdown Status Info -->
                <div class="mt-4 bg-white bg-opacity-20 rounded-lg p-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="opacity-90">
                            <i class="fas fa-info-circle mr-2"></i>Status Sistem
                        </span>
                        <span class="font-medium" id="countdownStatus">Aktif</span>
                    </div>
                </div>
            `;
        }

        function formatDeadlineDate() {
            return REGISTRATION_DEADLINE.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                timeZoneName: 'short'
            });
        }

        // Update countdown display
        function updateCountdown() {
            // Only run if quota is available
            if (!hasQuota) return;
            
            const now = new Date();
            const timeLeft = REGISTRATION_DEADLINE.getTime() - now.getTime();

            // Check if deadline has passed
            if (timeLeft <= 0) {
                handleExpiredDeadline();
                return;
            }

            // Calculate time units
            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            // Update display
            const daysElement = document.getElementById('days');
            const hoursElement = document.getElementById('hours');
            const minutesElement = document.getElementById('minutes');
            const secondsElement = document.getElementById('seconds');

            if (daysElement) daysElement.textContent = days.toString().padStart(2, '0');
            if (hoursElement) hoursElement.textContent = hours.toString().padStart(2, '0');
            if (minutesElement) minutesElement.textContent = minutes.toString().padStart(2, '0');
            if (secondsElement) secondsElement.textContent = seconds.toString().padStart(2, '0');

            // Update progress bar (assuming start date is 1 month ago for demo)
            const startDate = new Date(REGISTRATION_DEADLINE.getTime() - (30 * 24 * 60 * 60 * 1000));
            const totalDuration = REGISTRATION_DEADLINE.getTime() - startDate.getTime();
            const elapsed = now.getTime() - startDate.getTime();
            const progressPercent = Math.max(0, Math.min((elapsed / totalDuration) * 100, 100));
            
            const progressPercentElement = document.getElementById('progressPercent');
            const progressBarElement = document.getElementById('progressBar');
            
            if (progressPercentElement) progressPercentElement.textContent = `${Math.round(progressPercent)}%`;
            if (progressBarElement) progressBarElement.style.width = `${progressPercent}%`;

            // Update countdown styling based on time left
            updateCountdownStyling(days, hours, minutes);
            
            // Update status
            const statusElement = document.getElementById('countdownStatus');
            if (statusElement) {
                if (days <= 1) {
                    statusElement.textContent = 'Kritis!';
                    statusElement.className = 'font-bold text-red-200';
                } else if (days <= 7) {
                    statusElement.textContent = 'Mendesak';
                    statusElement.className = 'font-medium text-yellow-200';
                } else {
                    statusElement.textContent = 'Aktif';
                    statusElement.className = 'font-medium';
                }
            }
        }

        function updateCountdownStyling(days, hours, minutes) {
            const countdownContainer = document.getElementById('countdownContainer');
            
            // Remove existing classes
            countdownContainer.classList.remove('countdown-urgent', 'countdown-critical');
            
            if (days === 0 && hours <= 1) {
                // Last hour - critical
                countdownContainer.classList.add('countdown-critical');
                countdownContainer.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
            } else if (days <= 1) {
                // Last day - urgent
                countdownContainer.classList.add('countdown-urgent');
                countdownContainer.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
            } else if (days <= 7) {
                // Last week - warning
                countdownContainer.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
            } else {
                // Normal state
                countdownContainer.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
            }
        }

        function handleExpiredDeadline() {
            isDeadlinePassed = true;
            
            // Stop countdown timer
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            
            // Update countdown display
            const countdownContainer = document.getElementById('countdownContainer');
            const countdownContent = document.getElementById('countdownContent');
            
            countdownContainer.classList.add('countdown-expired');
            countdownContent.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-lock text-4xl md:text-6xl mb-4 opacity-60"></i>
                    <h3 class="text-lg md:text-2xl font-bold mb-2">Sistem Telah Dikunci</h3>
                    <p class="text-sm md:text-lg opacity-90 mb-2">
                        Batas waktu pendaftaran berakhir pada
                    </p>
                    <p class="text-xs md:text-sm font-mono bg-black bg-opacity-20 rounded-lg px-3 py-2 mb-4">
                        ${formatDeadlineDate()}
                    </p>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <p class="text-xs md:text-sm opacity-75">
                            <i class="fas fa-info-circle mr-2"></i>
                            Semua data sekarang bersifat <strong>final dan permanen</strong>
                        </p>
                    </div>
                </div>
            `;
            
            // Show system status alert
            const systemStatusAlert = document.getElementById('systemStatusAlert');
            const systemStatusDeadline = document.getElementById('systemStatusDeadline');
            if (systemStatusAlert && systemStatusDeadline) {
                systemStatusDeadline.textContent = formatDeadlineDate();
                systemStatusAlert.classList.remove('hidden');
            }
            
            // Disable all edit/delete functionality
            disableEditDeleteFeatures();
        }

        function disableEditDeleteFeatures() {
            // Disable add button
            const addPlayerBtns = document.querySelectorAll('button[onclick="openAddModal()"]');
            addPlayerBtns.forEach(btn => {
                btn.classList.add('btn-disabled');
                btn.disabled = true;
                btn.onclick = function() {
                    showAlert('Fitur ini telah dinonaktifkan setelah batas waktu berakhir', 'error');
                };
                btn.innerHTML = '<i class="fas fa-lock mr-2"></i>Sistem Dikunci';
            });
            
            // Disable all edit/delete buttons
            const editButtons = document.querySelectorAll('[data-edit-btn]');
            const deleteButtons = document.querySelectorAll('[data-delete-btn]');
            
            editButtons.forEach(btn => {
                btn.classList.add('action-btn', 'disabled');
                btn.disabled = true;
                const originalOnclick = btn.onclick;
                btn.onclick = function() {
                    showAlert('Fitur edit telah dinonaktifkan setelah batas waktu berakhir', 'error');
                };
                btn.title = 'Fitur dikunci setelah deadline';
            });
            
            deleteButtons.forEach(btn => {
                btn.classList.add('action-btn', 'disabled');
                btn.disabled = true;
                const originalOnclick = btn.onclick;
                btn.onclick = function() {
                    showAlert('Fitur hapus telah dinonaktifkan setelah batas waktu berakhir', 'error');
                };
                btn.title = 'Fitur dikunci setelah deadline';
            });
        }

        // Show alert function
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
            const iconClass = type === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500';
            
            const alertHTML = `
                <div class="mb-6 ${alertClass} border rounded-lg p-4 alert-slide-down">
                    <div class="flex items-center">
                        <i class="${iconClass} mr-3"></i>
                        <span>${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-current hover:opacity-70">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            alertContainer.innerHTML = alertHTML;
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                const alert = alertContainer.querySelector('div');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        // Auto-detect kategori umur berdasarkan umur
        function setupUmurAutoDetect(umurInputId, radioName) {
            const umurInput = document.getElementById(umurInputId);
            if (umurInput) {
                umurInput.addEventListener('input', function() {
                    const umur = parseInt(this.value);
                    const radioButtons = document.querySelectorAll(`input[name="${radioName}"]`);
                    
                    // Reset all radio buttons
                    radioButtons.forEach(radio => radio.checked = false);
                    
                    // Auto select kategori berdasarkan umur
                    if (umur >= 7 && umur <= 8) {
                        const radio7_8 = document.querySelector(`input[name="${radioName}"][value="7-8"]`);
                        if (radio7_8) radio7_8.checked = true;
                    } else if (umur >= 9 && umur <= 10) {
                        const radio9_10 = document.querySelector(`input[name="${radioName}"][value="9-10"]`);
                        if (radio9_10) radio9_10.checked = true;
                    } else if (umur >= 11 && umur <= 12) {
                        const radio11_12 = document.querySelector(`input[name="${radioName}"][value="11-12"]`);
                        if (radio11_12) radio11_12.checked = true;
                    }
                });
            }
        }

        // Open add modal
        function openAddModal() {
            if (isDeadlinePassed) {
                showAlert('Tidak dapat menambah pemain setelah batas waktu berakhir', 'error');
                return;
            }
            document.getElementById('addPlayerModal').classList.remove('hidden');
            document.getElementById('add_nama').focus();
            // Setup auto-detect kategori umur untuk tambah pemain
            setupUmurAutoDetect('add_umur', 'add_umur_kategori');
        }

        // Close add modal
        function closeAddModal() {
            document.getElementById('addPlayerModal').classList.add('hidden');
            document.getElementById('addPlayerForm').reset();
        }

        // Open edit modal
        async function editPlayer(playerId) {
            if (isDeadlinePassed) {
                showAlert('Tidak dapat mengedit pemain setelah batas waktu berakhir', 'error');
                return;
            }
            
            currentEditingPlayerId = playerId;
            
            try {
                const response = await fetch(`/user/${userToken}/edit-pemain/${playerId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('edit_nama').value = data.data.nama;
                    document.getElementById('edit_umur').value = data.data.umur;
                    
                    // Auto select kategori radio button berdasarkan umur
                    const kategori = data.data.umur_kategori;
                    const radioButtons = document.querySelectorAll('input[name="edit_umur_kategori"]');
                    radioButtons.forEach(radio => {
                        radio.checked = radio.value === kategori;
                    });
                    
                    document.getElementById('editPlayerModal').classList.remove('hidden');
                    document.getElementById('edit_nama').focus();
                    
                    // Setup auto-detect untuk edit modal
                    setupUmurAutoDetect('edit_umur', 'edit_umur_kategori');
                } else {
                    showAlert('Gagal mengambil data pemain', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat mengambil data', 'error');
            }
        }

        // Close edit modal
        function closeEditModal() {
            document.getElementById('editPlayerModal').classList.add('hidden');
            document.getElementById('editPlayerForm').reset();
            currentEditingPlayerId = null;
        }

        // Delete player
        async function deletePlayer(playerId, playerName) {
            if (isDeadlinePassed) {
                showAlert('Tidak dapat menghapus pemain setelah batas waktu berakhir', 'error');
                return;
            }
            
            if (confirm(`Yakin ingin menghapus pemain "${playerName}"?\n\nData yang sudah dihapus tidak dapat dikembalikan.`)) {
                try {
                    const response = await fetch(`/user/${userToken}/hapus-pemain/${playerId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showAlert(data.message, 'success');
                        // Remove row from table
                        const row = document.querySelector(`tr[data-player-id="${playerId}"]`);
                        if (row) {
                            row.remove();
                        }
                        // Refresh page after delay to update quota display
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showAlert(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat menghapus data', 'error');
                }
            }
        }

        // Handle add player form submission
        document.getElementById('addPlayerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (isDeadlinePassed) {
                showAlert('Tidak dapat menambah pemain setelah batas waktu berakhir', 'error');
                return;
            }
            
            const submitBtn = document.getElementById('addPlayerBtnModal');
            const loadingText = submitBtn.querySelector('.loading-text');
            const loadingSpinner = submitBtn.querySelector('.loading-spinner-container');
            
            // Show loading state
            loadingText.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');
            submitBtn.disabled = true;
            
            // Get form data
            const nama = document.getElementById('add_nama').value;
            const umur = document.getElementById('add_umur').value;
            // Ambil kategori dari radio button
            const kategoriRadio = document.querySelector('input[name="add_umur_kategori"]:checked');
            const kategori = kategoriRadio ? kategoriRadio.value : '';
            
            if (!kategori) {
                showAlert('Pilih kategori umur yang sesuai!', 'error');
                loadingText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
                submitBtn.disabled = false;
                return;
            }
            
            const formData = new FormData();
            formData.append('nama', nama);
            formData.append('umur', umur);
            formData.append('umur_kategori', kategori);
            
            try {
                const response = await fetch(`/user/${userToken}/tambah-pemain`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert(data.message, 'success');
                    closeAddModal();
                    // Refresh page to show new data and update quota
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(data.message || 'Gagal menambahkan pemain', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menambahkan data', 'error');
            } finally {
                // Reset loading state
                loadingText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
                submitBtn.disabled = false;
            }
        });

        // Handle edit player form submission
        document.getElementById('editPlayerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!currentEditingPlayerId) return;
            
            const submitBtn = document.getElementById('updatePlayerBtn');
            const loadingText = submitBtn.querySelector('.loading-text');
            const loadingSpinner = submitBtn.querySelector('.loading-spinner-container');
            
            // Show loading state
            loadingText.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');
            submitBtn.disabled = true;
            
            // Get form data
            const nama = document.getElementById('edit_nama').value;
            const umur = document.getElementById('edit_umur').value;
            
            // Validasi kategori umur sesuai dengan umur
            const umurInt = parseInt(umur);
            let kategori = '';
            if (umurInt >= 7 && umurInt <= 8) {
                kategori = '7-8';
            } else if (umurInt >= 9 && umurInt <= 10) {
                kategori = '9-10';
            } else if (umurInt >= 11 && umurInt <= 12) {
                kategori = '11-12';
            }
            
            if (!kategori) {
                showAlert('Umur tidak valid! Harus antara 7-12 tahun', 'error');
                loadingText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
                submitBtn.disabled = false;
                return;
            }
            
            const formData = new FormData();
            formData.append('nama', nama);
            formData.append('umur', umur);
            
            try {
                const response = await fetch(`/user/${userToken}/update-pemain/${currentEditingPlayerId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert(data.message, 'success');
                    closeEditModal();
                    // Refresh page to show updated data and quota
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(data.message || 'Gagal memperbarui pemain', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat memperbarui data', 'error');
            } finally {
                // Reset loading state
                loadingText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
                submitBtn.disabled = false;
            }
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#playerTable tr');
            let visibleCount = 0;

            rows.forEach(function(row) {
                if (row.id === 'emptyRow') return;
                
                const nama = row.querySelector('td:nth-child(2)');
                if (nama) {
                    const namaText = nama.textContent.toLowerCase();
                    if (namaText.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            // Show/hide empty state
            const emptyRow = document.getElementById('emptyRow');
            if (emptyRow) {
                emptyRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        });

        // Refresh quota function
        function refreshQuota() {
            window.location.reload();
        }

        // Close alert functions
        function closeSuccessAlert() {
            document.getElementById('successAlert').remove();
        }

        function closeErrorAlert() {
            document.getElementById('errorAlert').remove();
        }

        // Close modal when clicking outside
        document.getElementById('editPlayerModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        document.getElementById('addPlayerModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
                closeAddModal();
            }
        });

        // ===== INITIALIZATION AND MONITORING =====
        
        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initCountdown();
            
            // Check deadline status every minute to handle edge cases
            setInterval(() => {
                const now = new Date();
                if (now > REGISTRATION_DEADLINE && !isDeadlinePassed) {
                    handleExpiredDeadline();
                }
            }, 60000); // Check every minute
        });

        // Handle page visibility change (when user switches tabs)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Page became visible, check if deadline passed while away
                const now = new Date();
                if (now > REGISTRATION_DEADLINE && !isDeadlinePassed) {
                    handleExpiredDeadline();
                }
            }
        });

        // Periodic sync check (every 5 minutes)
        setInterval(() => {
            const now = new Date();
            if (now > REGISTRATION_DEADLINE && !isDeadlinePassed) {
                handleExpiredDeadline();
            }
        }, 300000); // Check every 5 minutes
    </script>
</body>
</html>