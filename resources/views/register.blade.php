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
                                            <label for="nama" class="form-label">Jasa Layanan</label>
                                            <input type="text" value="Rp {{ number_format($jasaLayanan, 0, ',', '.') }}" class="form-control" disabled/>
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
                                            <label class="form-check-label" for="syaratKetentuan" data-bs-toggle="modal" data-bs-target="#termsModal">
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

    <!-- Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Ketentuan dan Peraturan Contest</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ol>
                    <li>Untuk zona CAR dan DIESEL, panitia menyediakan tenda.</li>
                    <li>Batas space/slot display adalah:
                        <ul>
                            <li>2,5 x 4 m untuk CAR dan DIESEL.</li>
                            <li>3 x 5 m untuk truck.</li>
                        </ul>
                        Apabila melebihi slot, harap menghubungi panitia.
                    </li>
                    <li>Peserta yang menggunakan listrik bisa menghubungi panitia dengan biaya Rp 400.000,-/2 ampere.</li>
                    <li>Peserta wajib registrasi ulang sebelum loading IN untuk mendapatkan nomor scrut dan tiket ID selama acara berlangsung.</li>
                    <li>Peserta yang mendirikan special display dimohon koordinasi dengan panitia paling lambat H-2 sebelum loading IN.</li>
                    <li>Panitia berhak menolak atau membongkar apabila di luar ketentuan.</li>
                    <li>Loading IN dimulai:
                        <ul>
                            <li>21 Februari 2025, jam 20.00 s/d 22 Februari 2025, jam 02.00 WIB.</li>
                        </ul>
                        Keterlambatan loading akan dikenakan pengurangan poin.
                    </li>
                    <li>Waktu penjurian dibagi menjadi 2 sesi:
                        <ul>
                            <li>Hari pertama sesi 1: Jam 11.00 s/d selesai.</li>
                            <li>Hari kedua sesi 2: Jam 11.00 s/d selesai.</li>
                        </ul>
                        Pemilik kendaraan atau yang mewakili wajib mendampingi atau berada di dekat kendaraan ketika penjurian berlangsung untuk mempresentasikan kepada juri.
                    </li>
                    <li>Kendaraan peserta tidak diperbolehkan meninggalkan lokasi sebelum acara selesai.</li>
                    <li>Syarat, regulasi, dan keputusan juri bersifat mutlak dan tidak bisa diganggu gugat.</li>
                    <li>Prosedur Protes:
                        <ul>
                            <li>Protes dilakukan secara tertulis dengan form yang disediakan panitia.</li>
                            <li>Menyertakan uang jaminan Rp 2.000.000,- sebagai administrasi protes.</li>
                            <li>Protes hanya diajukan oleh pemilik kendaraan atau pendampingnya.</li>
                            <li>Hanya peserta yang masuk NOMINE yang mempunyai hak protes.</li>
                        </ul>
                    </li>
                    <li>Kepada seluruh peserta diharapkan menjaga kebersihan dan ketertiban selama acara berlangsung sampai dengan loading OUT demi kenyamanan kita bersama. Panitia dan pihak keamanan berhak menegur peserta yang dianggap mengganggu kenyamanan.</li>
                </ol>
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
