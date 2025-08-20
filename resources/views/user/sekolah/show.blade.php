<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayan Soccer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg">
            <!-- Logo Section -->
            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <img src="https://rec-data.kalibrr.com/www.kalibrr.com/logos/GKTW5JUNVCCLNZFJFEE7ESSU26UQKVR5QKN7E65J-64ab6a30.png" 
                        alt="Bayan Logo" 
                        class="w-14 h-18  object-cover">
                    <div>
                        <h2 class="text-lg font-bold text-orange-600">SOCCER CLINIC</h2>
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
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Table Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <input type="text" placeholder="Search" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Original Laravel Content -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Daftar Pemain Bola</h2>
                            @if(isset($sekolah))
                            @endif
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full border border-gray-200 rounded-lg">
                                <thead>
                                    <tr class="bg-gray-100 text-left text-gray-600">
                                        <th class="p-3">Nama</th>
                                        <th class="p-3">Umur</th>
                                        <th class="p-3">Kategori</th>
                                        <th class="p-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($sekolah) && isset($sekolah->pemainBola))
                                        @forelse($sekolah->pemainBola as $pemain)
                                        <tr class="border-t hover:bg-gray-50">
                                            <td class="p-3">{{ $pemain->nama }}</td>
                                            <td class="p-3">{{ $pemain->umur }}</td>
                                            <td class="p-3">{{ $pemain->umur_kategori }}</td>
                                            <td class="p-3 text-center flex justify-center gap-3">
                                                <a href="/user/{{ $sekolah->id }}/edit-pemain/{{ $pemain->id }}" 
                                                   class="text-blue-600 hover:underline">Edit</a>
                                                <form action="/user/{{ $sekolah->id }}/hapus-pemain/{{ $pemain->id }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Yakin hapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-600 hover:underline">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="p-3 text-center text-gray-500">Belum ada pemain</td>
                                        </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="4" class="p-3 text-center text-gray-500">Data sekolah tidak tersedia</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function confirmDelete(nama) {
            return confirm('Yakin ingin menghapus pemain ' + nama + '?');
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[placeholder="Search"]');
            const tableRows = document.querySelectorAll('tbody tr');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    
                    tableRows.forEach(function(row) {
                        const nama = row.querySelector('td:first-child');
                        if (nama) {
                            const namaText = nama.textContent.toLowerCase();
                            if (namaText.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>