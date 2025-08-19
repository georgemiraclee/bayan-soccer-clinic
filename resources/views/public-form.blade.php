<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bayan Soccer Clinic - Pendaftaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-8">
                <div class="flex items-center">
                    <div id="step1-indicator" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                    <span id="step1-text" class="ml-2 font-medium text-blue-600">Daftar Sekolah Bola</span>
                </div>
                <div class="flex-1 h-1 bg-gray-300" id="progress-line"></div>
                <div class="flex items-center">
                    <div id="step2-indicator" class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">2</div>
                    <span id="step2-text" class="ml-2 font-medium text-gray-600">Daftar Pemain</span>
                </div>
            </div>
        </div>

        <!-- Form Sekolah Bola -->
        <div id="form-sekolah" class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Form Pendaftaran Sekolah Bola</h1>
            
            <form id="sekolah-form" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Nama Sekolah Bola *</label>
                        <input type="text" id="nama_sekolah" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan nama sekolah bola">
                    </div>
                    
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">PIC (Penanggung Jawab) *</label>
                        <input type="text" id="pic" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nama penanggung jawab">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" id="email" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="email@example.com">
                    </div>
                    
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">No. Telepon *</label>
                        <input type="tel" id="telepon" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300 transform hover:scale-105">
                    Lanjut ke Pendaftaran Pemain
                </button>
            </form>
        </div>

        <!-- Form Pemain Bola -->
        <div id="form-pemain" class="bg-white shadow-lg rounded-lg p-8 hidden">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Form Pendaftaran Pemain</h1>
                <div class="text-sm text-gray-600">
                    <span id="pemain-count">0</span>/50 Pemain
                </div>
            </div>

            <!-- Info Sekolah -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <h3 class="font-semibold text-blue-800 mb-2">Informasi Sekolah Bola:</h3>
                <p class="text-blue-700"><strong>Nama:</strong> <span id="info-nama"></span></p>
                <p class="text-blue-700"><strong>PIC:</strong> <span id="info-pic"></span></p>
                <p class="text-blue-700"><strong>Email:</strong> <span id="info-email"></span></p>
                <p class="text-blue-700"><strong>Telepon:</strong> <span id="info-telepon"></span></p>
            </div>

            <!-- Form Tambah Pemain -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Tambah Pemain Baru</h3>
                <form id="pemain-form" class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-gray-700 mb-2">Nama Pemain *</label>
                            <input type="text" id="nama_pemain" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Nama lengkap pemain">
                        </div>
                        
                        <div>
                            <label class="block font-medium text-gray-700 mb-2">Umur (tahun) *</label>
                            <input type="number" id="umur_pemain" required min="7" max="12" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Umur pemain">
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Kategori Umur *</label>
                        <div class="flex gap-3">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="umur_kategori" value="7-8" class="hidden peer" required>
                                <span class="px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition duration-300">
                                    7-8 Tahun
                                </span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="umur_kategori" value="9-10" class="hidden peer" required>
                                <span class="px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition duration-300">
                                    9-10 Tahun
                                </span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="umur_kategori" value="11-12" class="hidden peer" required>
                                <span class="px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition duration-300">
                                    11-12 Tahun
                                </span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" id="btn-tambah" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
                        Tambah Pemain
                    </button>
                </form>
            </div>

            <!-- Daftar Pemain -->
            <div id="daftar-pemain" class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Daftar Pemain Terdaftar</h3>
                <div id="pemain-list" class="space-y-2 max-h-64 overflow-y-auto">
                    <!-- Pemain akan ditampilkan di sini -->
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button onclick="kembaliKeSekolah()" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300">
                    Kembali
                </button>
                <button onclick="selesaiPendaftaran()" id="btn-selesai" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                    Selesai Pendaftaran
                </button>
            </div>
        </div>

        <!-- Success Message -->
        <div id="success-message" class="bg-white shadow-lg rounded-lg p-8 text-center hidden">
            <div class="text-green-600 text-6xl mb-4">✓</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Pendaftaran Berhasil!</h2>
            <p class="text-gray-600 mb-6">Sekolah bola dan pemain telah berhasil didaftarkan ke sistem Bayan Soccer Clinic.</p>
            <button onclick="resetForm()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300">
                Daftar Sekolah Baru
            </button>
        </div>
    </div>

    <script>
        let sekolahData = {};
        let pemainList = [];

        // Auto-detect kategori umur berdasarkan umur
        document.getElementById('umur_pemain').addEventListener('input', function() {
            const umur = parseInt(this.value);
            const radioButtons = document.querySelectorAll('input[name="umur_kategori"]');
            
            radioButtons.forEach(radio => radio.checked = false);
            
            if (umur >= 7 && umur <= 8) {
                document.querySelector('input[value="7-8"]').checked = true;
            } else if (umur >= 9 && umur <= 10) {
                document.querySelector('input[value="9-10"]').checked = true;
            } else if (umur >= 11 && umur <= 12) {
                document.querySelector('input[value="11-12"]').checked = true;
            }
        });

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

            // Update progress
            updateProgress(2);

            // Show form pemain
            document.getElementById('form-sekolah').classList.add('hidden');
            document.getElementById('form-pemain').classList.remove('hidden');
        });

        // Handle form pemain
        document.getElementById('pemain-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (pemainList.length >= 50) {
                alert('Maksimal 50 pemain yang dapat didaftarkan!');
                return;
            }

            const nama = document.getElementById('nama_pemain').value;
            const umur = document.getElementById('umur_pemain').value;
            const kategori = document.querySelector('input[name="umur_kategori"]:checked').value;

            // Validasi kategori umur sesuai dengan umur
            const umurInt = parseInt(umur);
            if ((kategori === '7-8' && (umurInt < 7 || umurInt > 8)) ||
                (kategori === '9-10' && (umurInt < 9 || umurInt > 10)) ||
                (kategori === '11-12' && (umurInt < 11 || umurInt > 12))) {
                alert('Kategori umur tidak sesuai dengan umur pemain!');
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

            // Reset form
            document.getElementById('pemain-form').reset();

            // Enable selesai button jika ada pemain
            document.getElementById('btn-selesai').disabled = false;
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
            
            if (pemainList.length >= 50) {
                document.getElementById('btn-tambah').disabled = true;
                document.getElementById('btn-tambah').textContent = 'Maksimal 50 Pemain Tercapai';
                document.getElementById('btn-tambah').classList.add('bg-gray-400');
            } else {
                document.getElementById('btn-tambah').disabled = false;
                document.getElementById('btn-tambah').textContent = 'Tambah Pemain';
                document.getElementById('btn-tambah').classList.remove('bg-gray-400');
            }
        }

        function hapusPemain(id) {
            pemainList = pemainList.filter(pemain => pemain.id !== id);
            updatePemainList();
            updatePemainCount();
            
            if (pemainList.length === 0) {
                document.getElementById('btn-selesai').disabled = true;
            }
        }

        function updateProgress(step) {
            if (step === 2) {
                document.getElementById('step1-indicator').classList.remove('bg-blue-600');
                document.getElementById('step1-indicator').classList.add('bg-green-500');
                document.getElementById('step1-text').classList.remove('text-blue-600');
                document.getElementById('step1-text').classList.add('text-green-600');
                
                document.getElementById('step2-indicator').classList.remove('bg-gray-300', 'text-gray-600');
                document.getElementById('step2-indicator').classList.add('bg-blue-600', 'text-white');
                document.getElementById('step2-text').classList.remove('text-gray-600');
                document.getElementById('step2-text').classList.add('text-blue-600');
                
                document.getElementById('progress-line').classList.remove('bg-gray-300');
                document.getElementById('progress-line').classList.add('bg-green-500');
            }
        }

        function kembaliKeSekolah() {
            document.getElementById('form-pemain').classList.add('hidden');
            document.getElementById('form-sekolah').classList.remove('hidden');
            
            // Reset progress
            document.getElementById('step1-indicator').classList.add('bg-blue-600');
            document.getElementById('step1-indicator').classList.remove('bg-green-500');
            document.getElementById('step1-text').classList.add('text-blue-600');
            document.getElementById('step1-text').classList.remove('text-green-600');
            
            document.getElementById('step2-indicator').classList.add('bg-gray-300', 'text-gray-600');
            document.getElementById('step2-indicator').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('step2-text').classList.add('text-gray-600');
            document.getElementById('step2-text').classList.remove('text-blue-600');
            
            document.getElementById('progress-line').classList.add('bg-gray-300');
            document.getElementById('progress-line').classList.remove('bg-green-500');
        }

        async function selesaiPendaftaran() {
            if (pemainList.length === 0) {
                alert('Minimal harus mendaftarkan 1 pemain!');
                return;
            }

            // Disable button dan show loading
            const btnSelesai = document.getElementById('btn-selesai');
            const originalText = btnSelesai.innerHTML;
            btnSelesai.disabled = true;
            btnSelesai.innerHTML = 'Menyimpan...';

            try {
                // Prepare data untuk dikirim ke server
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

                // Kirim data ke server Laravel
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
                    // Show success message with admin link
                    document.getElementById('form-pemain').classList.add('hidden');
                    document.getElementById('success-message').classList.remove('hidden');
                    
                    // Update success message dengan link ke admin
                    document.querySelector('#success-message p').innerHTML = `
                        Sekolah bola dan ${result.data.jumlah_pemain} pemain telah berhasil didaftarkan ke sistem Bayan Soccer Clinic.<br>
                        <a href="${result.data.redirect_url}" class="text-blue-600 hover:text-blue-800 underline font-medium mt-2 inline-block">
                            Lihat di Admin Panel →
                        </a>
                    `;
                } else {
                    alert('Terjadi kesalahan: ' + result.message);
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
            } finally {
                // Re-enable button
                btnSelesai.disabled = false;
                btnSelesai.innerHTML = originalText;
            }
        }

        function resetForm() {
            // Reset semua data
            sekolahData = {};
            pemainList = [];
            
            // Reset forms
            document.getElementById('sekolah-form').reset();
            document.getElementById('pemain-form').reset();
            
            // Reset displays
            document.getElementById('success-message').classList.add('hidden');
            document.getElementById('form-sekolah').classList.remove('hidden');
            
            updatePemainList();
            updatePemainCount();
            
            // Reset progress
            document.getElementById('step1-indicator').classList.add('bg-blue-600');
            document.getElementById('step1-indicator').classList.remove('bg-green-500');
            document.getElementById('step1-text').classList.add('text-blue-600');
            document.getElementById('step1-text').classList.remove('text-green-600');
            
            document.getElementById('step2-indicator').classList.add('bg-gray-300', 'text-gray-600');
            document.getElementById('step2-indicator').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('step2-text').classList.add('text-gray-600');
            document.getElementById('step2-text').classList.remove('text-blue-600');
            
            document.getElementById('progress-line').classList.add('bg-gray-300');
            document.getElementById('progress-line').classList.remove('bg-green-500');
            
            document.getElementById('btn-selesai').disabled = true;
        }

        // Initialize
        updatePemainList();
        updatePemainCount();
    </script>
</body>
</html>