<!-- resources/views/home.blade.php -->
@extends('home-page.layouts.app-home')

@section('content')
    <style>
        .custom-border-card {
            border: 2px solid #adb5bd;
            border-radius: 10px;
        }
        .text-link {
        color: #007bff;
        text-decoration: underline;
        cursor: pointer;
        }

        .text-link:hover {
            color: #0056b3;
            text-decoration: none;
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
                                            <label for="qty_slot" class="form-label">Jumlah Slot</label>
                                            <input type="number" name="qty_slot" id="qty_slot" value="" class="form-control" required />
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
                                            <input type="number" name="nomor_hp" id="nomor_hp" class="form-control" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" name="email" id="email" class="form-control" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama_team" class="form-label">Bengkel / Komunitas</label>
                                            <input type="text" name="nama_team" id="nama_team" class="form-control" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto_depan" class="form-label">Foto Kendaraan</label>
                                            <input type="file" class="form-control" name="foto_depan" id="foto_depan" required>
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="syaratKetentuan" name="syaratKetentuan" disabled required>
                                            <label class="form-check-label text-link" for="syaratKetentuan" data-bs-toggle="modal" data-bs-target="#termsModal">
                                                Dengan ini saya menyatakan bahwa semua data yang saya isi adalah benar dan mematuhi semua peraturan contest, keputusan Juri tidak bisa diganggu gugat.
                                            </label>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label"> <strong>⁠Info booking slot ( {{$kontakInfo}} )</strong></label>
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
    
    <!-- Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Ketentuan dan Peraturan Contest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Menampilkan PDF -->
                    <img src="{{ url('macevent/public/img/'.$dataEvent->regulasi_event) }}" alt="Ketentuan dan Peraturan" width="100%" height="auto" style="border: none;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <br>
    @if (session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif

    <script src="{{ asset('vendor/jquery/jquery-3.3.1.min.js')}}"></script>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const syaratKetentuanCheckbox = document.getElementById('syaratKetentuan');
        const termsModal = document.getElementById('termsModal');
        termsModal.addEventListener('hidden.bs.modal', function () {
            syaratKetentuanCheckbox.disabled = false;
        });
    });
</script>



    

@endsection
