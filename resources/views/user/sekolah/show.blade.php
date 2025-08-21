<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayan Soccer Dashboard - {{ $sekolah->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="ico" href="{{ asset('logo.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar-active {
            background-color: #f3f4f6;
            border-right: 3px solid #f59e0b;
        }
        
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
        
        .logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
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
        
        .action-btn:hover {
            opacity: 0.8;
            transform: translateY(-1px);
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
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg">
            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <img src="https://rec-data.kalibrr.com/www.kalibrr.com/logos/GKTW5JUNVCCLNZFJFEE7ESSU26UQKVR5QKN7E65J-64ab6a30.png" 
                        alt="Bayan Logo" 
                        class="w-14 h-18 object-cover">
                    <div>
                        <h2 class="text-lg font-bold text-orange-600">SOCCER CLINIC</h2>
                        <p class="text-sm text-gray-500">{{ $sekolah->nama }}</p>
                    </div>
                </div>
            </div>     
            <!-- Navigation -->
            <nav class="mt-4">
                <div class="px-4 py-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Menu</h3>
                </div>
                <ul class="mt-2">
                    <li>
                        <a href="#" class="flex items-center px-6 py-3 text-gray-700 bg-gray-100 border-r-4 border-orange-500 font-medium">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Pemain SSB
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <nav class="flex items-center space-x-2 text-sm font-medium text-orange-600">
                                <span>Daftar Pemain SSB</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <span class="text-orange-600 font-medium">List</span>
                            </nav>
                            <h1 class="text-2xl font-bold text-gray-900 mt-1">Daftar Pemain SSB</h1>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 p-6">
                <!-- Alert Informasi Kuota yang sudah diupdate -->
                <div id="quotaAlert" class="mb-6 bg-gradient-to-r from-orange-50 to-green-50 border border-blue-200 rounded-lg p-6 alert-slide-down">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">
                                <i class="fas fa-futbol mr-2"></i>Informasi Kuota SSB {{ $sekolah->nama }}
                            </h3>
                            <div class="text-blue-700 mb-4">
                                @if($kuotaData['has_quota'])
                                    <p class="mb-2">
                                        Kuota yang diberikan: <strong class="text-blue-800">{{ $kuotaData['total'] }} Orang</strong>
                                    </p>
                                    <p class="mb-4 text-sm">
                                        Pemain terdaftar: <strong class="text-green-800">{{ $kuotaData['current_counts']['total'] }} Orang dari {{ $kuotaData['total'] }} Orang</strong>
                                    </p>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="bg-white rounded-lg p-4 border border-blue-100 shadow-sm">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-gray-600">Kelompok 7-8 Tahun</span>
                                                <span class="badge badge-info">{{ $kuotaData['7-8'] }} kuota</span>
                                            </div>
                                            <div class="flex items-center justify-between text-xs mb-2">
                                                <span class="text-gray-500">Terdaftar: {{ $kuotaData['current_counts']['7-8'] }}</span>
                                                <span class="text-green-600 font-medium">Sisa: {{ $kuotaData['remaining']['7-8'] }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-transparent h-2 rounded-full transition-all duration-300" 
                                                    style="width: {{ $kuotaData['percentage']['7-8'] }}%"></div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="bg-white rounded-lg p-4 border border-blue-100 shadow-sm">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-gray-600">Kelompok 9-10 Tahun</span>
                                                <span class="badge badge-warning">{{ $kuotaData['9-10'] }} kuota</span>
                                            </div>
                                            <div class="flex items-center justify-between text-xs mb-2">
                                                <span class="text-gray-500">Terdaftar: {{ $kuotaData['current_counts']['9-10'] }}</span>
                                                <span class="text-green-600 font-medium">Sisa: {{ $kuotaData['remaining']['9-10'] }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-transparent h-2 rounded-full transition-all duration-300" 
                                                    style="width: {{ $kuotaData['percentage']['9-10'] }}%"></div>
                                            </div>
                                         
                                        </div>
                                        
                                        <div class="bg-white rounded-lg p-4 border border-blue-100 shadow-sm">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-gray-600">Kelompok 11-12 Tahun</span>
                                                <span class="badge badge-success">{{ $kuotaData['11-12'] }} kuota</span>
                                            </div>
                                            <div class="flex items-center justify-between text-xs mb-2">
                                                <span class="text-gray-500">Terdaftar: {{ $kuotaData['current_counts']['11-12'] }}</span>
                                                <span class="text-green-600 font-medium">Sisa: {{ $kuotaData['remaining']['11-12'] }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-transparent h-2 rounded-full transition-all duration-300" 
                                                    style="width: {{ $kuotaData['percentage']['11-12'] }}%"></div>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    
                                    <!-- Alert untuk kategori yang hampir penuh atau penuh -->
                                    @php
                                        $alerts = [];
                                        foreach(['7-8', '9-10', '11-12'] as $cat) {
                                            if($kuotaData['percentage'][$cat] >= 100) {
                                                $alerts[] = "Kategori {$cat} tahun sudah melebih batas";
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
                                                            <li>{{ $alert }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                @else
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                                            <div class="text-yellow-800">
                                                <strong>Kuota belum ditetapkan</strong>
                                                <p class="text-sm mt-1">Admin belum menetapkan kuota untuk SSB Anda. Hubungi admin untuk informasi lebih lanjut.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <p class="mt-3 text-sm">
                                    <i class="fas fa-users-cog mr-1"></i>
                                    Silakan Anda memanajemen pemain SSB Anda dengan bijak sesuai kategori umur yang tersedia.
                                </p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button onclick="refreshQuota()" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                        <i class="fas fa-refresh mr-1"></i>Refresh Data
                                    </button>
                                    @if($kuotaData['has_quota'])
                                        <span class="text-gray-400">•</span>
                                        <span class="text-xs text-gray-500">Diperbarui: {{ $sekolah->kuotaSekolah->updated_at ?? 'Belum diatur' }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                <div id="successAlert" class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 alert-slide-down">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <span class="text-green-800">{{ session('success') }}</span>
                        <button onclick="closeSuccessAlert()" class="ml-auto text-green-500 hover:text-green-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div id="errorAlert" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 alert-slide-down">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <span class="text-red-800">{{ session('error') }}</span>
                        <button onclick="closeErrorAlert()" class="ml-auto text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Table Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <input type="text" id="searchInput" placeholder="Cari nama pemain..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Content -->
                    <div class="bg-white p-6 rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="w-full border border-gray-200 rounded-lg">
                                <thead>
                                    <tr class="bg-gray-100 text-left text-gray-600">
                                        <th class="p-3">No</th>
                                        <th class="p-3">Nama</th>
                                        <th class="p-3">Umur</th>
                                        <th class="p-3">Kategori</th>
                                        <th class="p-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="playerTable">
                                    @forelse($sekolah->pemainBolas as $index => $pemain)
                                    <tr class="border-t hover:bg-gray-50 table-hover">
                                        <td class="p-3">{{ $index + 1 }}</td>
                                        <td class="p-3 font-medium">{{ $pemain->nama }}</td>
                                        <td class="p-3">{{ $pemain->umur }} tahun</td>
                                        <td class="p-3">
                                            <span class="badge 
                                                @if($pemain->umur_kategori == '7-8') badge-info 
                                                @elseif($pemain->umur_kategori == '9-10') badge-warning 
                                                @else badge-success @endif">
                                                {{ $pemain->umur_kategori }} tahun
                                            </span>
                                        </td>
                                        <td class="p-3 text-center">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('user.pemain.edit', [$sekolah->user_token, $pemain->id]) }}" 
                                                   class="action-btn edit">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>
                                                <button onclick="confirmDelete('{{ $pemain->nama }}', '{{ $pemain->id }}')" 
                                                        class="action-btn delete">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr id="emptyRow">
                                        <td colspan="5" class="p-8 text-center text-gray-500">
                                            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-lg font-medium">Belum ada pemain</p>
                                            <p class="text-sm">Mulai tambahkan pemain untuk SSB Anda</p>
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
    </div>

    <!-- Form untuk delete (hidden) -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Tambahkan function refresh quota
            function refreshQuota() {
                // Reload halaman untuk mendapat data terbaru
                location.reload();
            }

            // Function untuk update quota secara realtime (optional)
            function updateQuotaDisplay() {
                fetch(`/api/user/{{ $sekolah->user_token }}/quota`)
                    .then(response => response.json())
                    .then(data => {
                        // Update display dengan data terbaru
                        console.log('Quota data updated:', data);
                    })
                    .catch(error => {
                        console.error('Error fetching quota:', error);
                    });
            }

            // Auto refresh setiap 30 detik (optional)
            setInterval(updateQuotaDisplay, 30000);
        // Close alert functions
        function closeAlert() {
            document.getElementById('quotaAlert').style.display = 'none';
        }

        function closeSuccessAlert() {
            document.getElementById('successAlert').style.display = 'none';
        }

        function closeErrorAlert() {
            document.getElementById('errorAlert').style.display = 'none';
        }

        // Auto close success/error alerts after 5 seconds
        setTimeout(function() {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            if (successAlert) successAlert.style.display = 'none';
            if (errorAlert) errorAlert.style.display = 'none';
        }, 5000);

        // Confirm delete function
        function confirmDelete(nama, pemainId) {
            if (confirm(`Yakin ingin menghapus pemain "${nama}"?\n\nData yang sudah dihapus tidak dapat dikembalikan.`)) {
                const form = document.getElementById('deleteForm');
                form.action = `{{ route('user.pemain.destroy', [$sekolah->user_token, ':id']) }}`.replace(':id', pemainId);
                form.submit();
            }
        }

        // Search functionality
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

        // Add player modal (you can implement this later)
        function addPlayerModal() {
            alert('Fitur tambah pemain akan segera tersedia. Untuk sementara, gunakan form manual atau admin panel.');
        }
    </script>
</body>
</html>