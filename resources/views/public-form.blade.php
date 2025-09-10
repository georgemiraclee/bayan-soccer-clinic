<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayan Run 2025 - Pendaftaran Coaching Clinic</title>
    <link rel="icon" type="ico" href="{{ asset('logo.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-200 to-red-300 min-h-screen py-6 sm:py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        <!-- Header dengan Logo -->
        <div class="text-center mb-8 sm:mb-10">
           <img src="{{ asset('images/bayanrun.png') }}" 
                alt="Bayan Run 2025" 
                class="mx-auto w-28 h-30 sm:w-33 sm:h-33 object-contain">
            <h1 class="mt-3 sm:mt-4 text-3xl sm:text-5xl font-extrabold text-blue-800 italic tracking-wide drop-shadow-sm">
                BAYAN RUN 2025 
            </h1>
            <p class="text-red-600 font-bold mt-1 sm:mt-2 text-sm sm:text-base uppercase">Formulir Pendaftaran Coaching Clinic</p>
        </div>

        <!-- Form Pendaftaran Event Lari -->
        <div id="form-pendaftaran" class="bg-white shadow-lg rounded-lg p-6 sm:p-8">
            <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center text-gray-800">
                <span class="inline-block mr-2">🏃‍♂️</span>
                Form Pendaftaran
            </h2>
            
            <form id="pendaftaran-form" class="space-y-6">
                <div>
                    <label class="block font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <span class="inline-block mr-2">👤</span>
                            Nama Lengkap *
                        </span>
                    </label>
                    <input type="text" 
                           id="nama_lengkap" 
                           required 
                           class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                           placeholder="Masukkan nama lengkap Anda">
                    <div class="text-red-500 text-sm mt-1 hidden" id="error-nama_lengkap"></div>
                </div>
                
                <div>
                    <label class="block font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <span class="inline-block mr-2">🏃‍♀️</span>
                            Kategori Lari *
                        </span>
                    </label>
                    <input type="text" 
                           id="kategori_lari" 
                           required 
                           class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                           placeholder="Contoh: 5K, 10K, Half Marathon, dll">
                    <div class="text-red-500 text-sm mt-1 hidden" id="error-kategori_lari"></div>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <span class="inline-block mr-2">📧</span>
                            Email *
                        </span>
                    </label>
                    <input type="email" 
                           id="email" 
                           required 
                           class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                           placeholder="email@example.com">
                    <div class="text-red-500 text-sm mt-1 hidden" id="error-email"></div>
                </div>
                
                <div>
                    <label class="block font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <span class="inline-block mr-2">📱</span>
                            Nomor Telepon *
                        </span>
                    </label>
                    <input type="text" 
                           id="telepon" 
                           required 
                           class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                           placeholder="08xxxxxxxxxx">
                    <div class="text-red-500 text-sm mt-1 hidden" id="error-telepon"></div>
                    <p class="text-sm text-gray-600 mt-1">
                        <span class="inline-block mr-1">💬</span>
                        Nomor WhatsApp aktif untuk menerima konfirmasi pendaftaran
                    </p>
                </div>

                <!-- Info Section -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-blue-400 text-xl">ℹ️</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Informasi:</strong><br>
                                Setelah pendaftaran berhasil, Anda akan menerima konfirmasi dan QR Code melalui WhatsApp sebagai bukti registrasi.
                            </p>
                        </div>
                    </div>
                </div>

                <button type="submit" 
                        id="btn-daftar" 
                        class="w-full bg-gradient-to-r from-blue-600 to-red-500 hover:from-blue-700 hover:to-red-600 text-white font-semibold py-4 rounded-lg transition-all duration-300 transform hover:scale-105 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none">
                    <span id="btn-text">
                        <span class="inline-block mr-2">🚀</span>
                        Daftar Event Lari
                    </span>
                </button>
            </form>
        </div>

        <!-- Success Message -->
        <div id="success-message" class="bg-white shadow-lg rounded-lg p-6 sm:p-8 text-center hidden">
            <div class="text-green-600 text-6xl mb-4">✅</div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Pendaftaran Berhasil!</h2>
            <p class="text-gray-600 mb-6 text-sm sm:text-base">
                Pendaftaran Anda berhasil! Terima kasih sudah mendaftar pada event lari ini. 
                Detail pendaftaran sudah kami kirimkan melalui WhatsApp.
            </p>
            
            <!-- Peserta Info -->
            <div id="peserta-info" class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <h3 class="font-bold text-lg mb-3 text-center text-gray-800">
                    <span class="inline-block mr-2">🏃‍♂️</span>
                    Detail Pendaftaran
                </h3>
                <div class="grid gap-2 text-sm">
                    <div class="flex justify-between">
                        <span class="font-medium">Nama:</span>
                        <span id="success-nama"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Kategori Lari:</span>
                        <span id="success-kategori"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Nomor BIB:</span>
                        <span id="success-bib" class="font-bold text-blue-600"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Email:</span>
                        <span id="success-email"></span>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Info -->
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 text-left">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-green-400 text-xl">💬</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            <strong>Cek WhatsApp Anda!</strong><br>
                            Kami telah mengirimkan konfirmasi pendaftaran dan QR Code bukti registrasi ke nomor WhatsApp Anda.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- QR Code Preview -->
            <div id="qr-preview" class="mb-6 hidden">
                <h4 class="font-bold text-gray-800 mb-2">QR Code Anda:</h4>
                <div class="flex justify-center">
                    <img id="qr-image" src="" alt="QR Code" class="w-40 h-40 border rounded-lg">
                </div>
            </div>
            
            <button onclick="resetForm()" 
                    class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-red-500 hover:from-blue-700 hover:to-red-600 text-white font-semibold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105">
                <span class="inline-block mr-2">➕</span>
                Daftar Peserta Baru
            </button>
        </div>

    </div>

    <!-- Footer -->
    <footer class="mt-10 sm:mt-12 text-center font-semibold text-gray-600 text-xs sm:text-sm">
        <p>&copy; 2025 ICT Bayan Group. All Rights Reserved.</p>
    </footer>

    <script>
        // Form handler
        document.getElementById('pendaftaran-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Reset error messages
            clearErrors();
            
            // Get form data
            const formData = {
                nama_lengkap: document.getElementById('nama_lengkap').value.trim(),
                kategori_lari: document.getElementById('kategori_lari').value.trim(),
                email: document.getElementById('email').value.trim(),
                telepon: document.getElementById('telepon').value.trim()
            };
            
            // Basic validation
            if (!validateForm(formData)) {
                return;
            }

            // Disable button and show loading
            const btnDaftar = document.getElementById('btn-daftar');
            const btnText = document.getElementById('btn-text');
            const originalText = btnText.innerHTML;
            
            btnDaftar.disabled = true;
            btnText.innerHTML = '<span class="inline-block mr-2">⏳</span>Mendaftar...';

            try {
                const response = await fetch('/daftar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    showSuccessMessage(result.data);
                } else {
                    if (response.status === 422 && result.errors) {
                        showValidationErrors(result.errors);
                    } else {
                        alert('Terjadi kesalahan: ' + result.message);
                    }
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
            } finally {
                btnDaftar.disabled = false;
                btnText.innerHTML = originalText;
            }
        });

        function validateForm(data) {
            let isValid = true;
            
            if (!data.nama_lengkap) {
                showError('nama_lengkap', 'Nama lengkap harus diisi');
                isValid = false;
            }
            
            if (!data.kategori_lari) {
                showError('kategori_lari', 'Kategori lari harus diisi');
                isValid = false;
            }
            
            if (!data.email) {
                showError('email', 'Email harus diisi');
                isValid = false;
            } else if (!isValidEmail(data.email)) {
                showError('email', 'Format email tidak valid');
                isValid = false;
            }
            
            if (!data.telepon) {
                showError('telepon', 'Nomor telepon harus diisi');
                isValid = false;
            }
            
            return isValid;
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showError(field, message) {
            const errorDiv = document.getElementById('error-' + field);
            const inputField = document.getElementById(field);
            
            if (errorDiv && inputField) {
                errorDiv.textContent = message;
                errorDiv.classList.remove('hidden');
                inputField.classList.add('border-red-500');
            }
        }

        function clearErrors() {
            const errorDivs = document.querySelectorAll('[id^="error-"]');
            const inputFields = document.querySelectorAll('input');
            
            errorDivs.forEach(div => {
                div.textContent = '';
                div.classList.add('hidden');
            });
            
            inputFields.forEach(input => {
                input.classList.remove('border-red-500');
            });
        }

        function showValidationErrors(errors) {
            Object.keys(errors).forEach(field => {
                if (errors[field] && errors[field].length > 0) {
                    showError(field, errors[field][0]);
                }
            });
        }

        function showSuccessMessage(data) {
            // Hide form and show success message
            document.getElementById('form-pendaftaran').classList.add('hidden');
            document.getElementById('success-message').classList.remove('hidden');
            
            // Update success info
            document.getElementById('success-nama').textContent = data.nama;
            document.getElementById('success-kategori').textContent = data.kategori;
            document.getElementById('success-bib').textContent = data.nomor_bib;
            document.getElementById('success-email').textContent = document.getElementById('email').value;
            
            // Show QR Code if available
            if (data.qr_code_url) {
                document.getElementById('qr-image').src = data.qr_code_url;
                document.getElementById('qr-preview').classList.remove('hidden');
            }
            
            // Scroll to top
            window.scrollTo(0, 0);
        }

        function resetForm() {
            // Reset form
            document.getElementById('pendaftaran-form').reset();
            clearErrors();
            
            // Hide success message and show form
            document.getElementById('success-message').classList.add('hidden');
            document.getElementById('form-pendaftaran').classList.remove('hidden');
            
            // Hide QR preview
            document.getElementById('qr-preview').classList.add('hidden');
            
            // Scroll to top
            window.scrollTo(0, 0);
        }

        // Auto-format phone number
        document.getElementById('telepon').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, ''); // Remove non-digits
            
            // Add formatting for Indonesian phone numbers
            if (value.startsWith('62')) {
                // Already has country code
            } else if (value.startsWith('0')) {
                // Local format, keep as is
            } else if (value.startsWith('8')) {
                // Add 0 prefix
                value = '0' + value;
            }
            
            this.value = value;
        });

        // Real-time validation feedback
        document.querySelectorAll('input[required]').forEach(input => {
            input.addEventListener('blur', function() {
                const field = this.id;
                const value = this.value.trim();
                
                clearErrors();
                
                if (!value) {
                    showError(field, 'Field ini harus diisi');
                } else if (field === 'email' && !isValidEmail(value)) {
                    showError(field, 'Format email tidak valid');
                }
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('border-red-500')) {
                    this.classList.remove('border-red-500');
                    document.getElementById('error-' + this.id).classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>