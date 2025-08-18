<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bayan Soccer Clinic - Pendaftaran Pemain</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">
    <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4 text-center">Form Pendaftaran Pemain Bola</h1>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('public.form.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Dropdown Sekolah Bola -->
            <div>
                <label class="block font-medium">Sekolah Bola</label>
                <select name="sekolah_bola_id" class="w-full border rounded p-2">
                    <option value="">-- Pilih Sekolah Bola --</option>
                    @foreach($sekolahBola as $sekolah)
                        <option value="{{ $sekolah->id }}" {{ old('sekolah_bola_id') == $sekolah->id ? 'selected' : '' }}>
                            {{ $sekolah->nama }}
                        </option>
                    @endforeach
                </select>
                @error('sekolah_bola_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Nama Pemain -->
            <div>
                <label class="block font-medium">Nama Pemain</label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border rounded p-2">
                @error('nama') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Umur Kategori (Button Group) -->
            <div>
                <label class="block font-medium mb-2">Kategori Umur</label>
                <div class="flex gap-3">
                    @foreach(['7-8' => '7-8 Tahun', '9-10' => '9-10 Tahun', '11-12' => '11-12 Tahun'] as $key => $label)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="umur_kategori" value="{{ $key }}" 
                                {{ old('umur_kategori') == $key ? 'checked' : '' }} class="hidden peer">
                            <span class="px-4 py-2 border rounded-lg peer-checked:bg-blue-500 peer-checked:text-white">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('umur_kategori') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                Daftar
            </button>
        </form>
    </div>
</body>
</html>
