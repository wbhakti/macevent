<!-- resources/views/home.blade.php -->
@extends('home-page.layouts.app-home')

@section('content')
    <style>
        .custom-border-card {
            border: 2px solid #adb5bd;
            border-radius: 10px;
        }
    </style>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if ($dataEvent)
                    <div class="card custom-border-card">
                        <img src="{{ asset('img/' . $dataEvent->img_event) }}" class="card-img-top img-fluid" alt="Image Card" style="object-fit: cover;">
                        <div class="card-body">
                            <h3 class="card-title text-center mb-4">Pendaftaran {{ $dataEvent->title_event }}</h3>
                            <div class="mb-4">
                                <div class="alert alert-primary" role="alert">
                                    <p>{{ $dataEvent->desc_event }}</p>
                                </div>
                            </div>
                            <form method="POST" action="/postregister" enctype="multipart/form-data">
                                @csrf
                                <input type="text" value="{{ $idEvent }}" name="idEvent" hidden>
                                <input type="text" value="{{ $jasaLayanan }}" name="jasaLayanan" hidden>
                                <div class="row justify-content-center">
                                    <div class="col-md-8 mx-auto">
                                        <div class="mb-3">
                                            <label for="kategori" class="form-label">Kategori</label>
                                            <select class="form-select" id="kategori" name="kategori" required>
                                                @foreach ($listKategori as $kategori)
                                                    <option value="{{ $kategori->id_kategori }}|{{ $kategori->harga_kategori }}|{{ $kategori->nama_kategori }}">
                                                        {{ $kategori->nama_kategori }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="harga_kategori" class="form-label">Harga Kategori</label>
                                            <input type="text" id="harga_kategori" value="" class="form-control" disabled />
                                        </div>                                        
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama" id="nama" class="form-control" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="kota" class="form-label">Kota Asal</label>
                                            <input type="text" name="kota" id="kota" class="form-control" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="nomor_hp" class="form-label">Nomor HP</label>
                                            <input type="text" name="nomor_hp" id="nomor_hp" class="form-control" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" name="email" id="email" class="form-control" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama_team" class="form-label">Nama Team</label>
                                            <input type="text" name="nama_team" id="nama_team" class="form-control" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto_depan" class="form-label">Foto Depan</label>
                                            <input type="file" class="form-control" name="foto_depan" id="foto_depan" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto_belakang" class="form-label">Foto Belakang</label>
                                            <input type="file" class="form-control" name="foto_belakang" id="foto_belakang" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto_samping" class="form-label">Foto Samping</label>
                                            <input type="file" class="form-control" name="foto_samping" id="foto_samping" required>
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="syaratKetentuan" name="syaratKetentuan" required>
                                            <label class="form-check-label" for="syaratKetentuan">
                                                Saya menyetujui aturan yang berlaku.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <br>
                                <button type="submit" class="btn btn-primary w-100">Daftar</button>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                        </div>
                    </div>
                @else
                    <p>Event tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>

    <br>

    @if (session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kategoriSelect = document.getElementById('kategori');
            const hargaKategoriInput = document.getElementById('harga_kategori');
    
            kategoriSelect.addEventListener('change', function () {
                const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
                const hargaKategori = selectedOption.value.split('|')[1];
                hargaKategoriInput.value = `Rp ${parseInt(hargaKategori).toLocaleString('id-ID')}`;
            });
    
            const initialOption = kategoriSelect.options[kategoriSelect.selectedIndex];
            if (initialOption) {
                const initialHarga = initialOption.value.split('|')[1];
                hargaKategoriInput.value = `Rp ${parseInt(initialHarga).toLocaleString('id-ID')}`;
            }
        });
    </script>
    

@endsection
