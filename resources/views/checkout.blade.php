<!-- resources/views/checkout.blade.php -->
@extends('home-page.layouts.app-home')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-dark shadow-sm">
                    <div class="card-header bg-info text-white text-center">
                        <h4 class="mb-0">Pemesanan Berhasil</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Terima kasih, {{ $nama_lengkap }}!</h5>
                        <p class="text-muted text-center mb-4">Proses pemesanan anda telah sukses, Segera lakukan pembayaran</p>
                        <p class="text-muted text-center mb-4">Berikut adalah detail pendaftaran dan informasi pembayaran Anda:</p>
                        
                         <!-- Ringkasan Pendaftaran -->
                        <div class="text-center">
                            <div class="mb-4">
                                <h6 class="card-subtitle mb-3 text-primary">Ringkasan Pendaftaran:</h6>
                                <ul class="list-group mb-4 d-inline-block text-start">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">ID Transaksi:</span>
                                        <span class="ms-2 text-secondary">{{ $idTransaksi }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Email:</span>
                                        <span class="ms-2 text-secondary">{{ $email }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Nomor HP:</span>
                                        <span class="ms-2 text-secondary">{{ $nomor_hp }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Nama Lengkap:</span>
                                        <span class="ms-2 text-secondary">{{ $nama_lengkap }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Bengkel atau Komunitas:</span>
                                        <span class="ms-2 text-secondary">{{ $nama_team }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Kategori:</span>
                                        <span class="ms-2 text-secondary">{{ $kategori }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Jumlah Slot:</span>
                                        <span class="ms-2 text-secondary">{{ $qty_slot }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Informasi Pembayaran -->
                        <div class="mb-4">
                            <h4 class="mb-2">Rincian Pembayaran:</h4>
                            <div class="p-3 border border-success rounded bg-light">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Biaya Registrasi:</span>
                                        <span class="ms-2 text-secondary">Rp {{ number_format(($harga_kategori * $qty_slot), 0, ',', '.') }} ( {{$qty_slot}} Slot )</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Biaya Layanan:</span>
                                        <span class="ms-2 text-secondary">Rp {{ number_format(($jasa_layanan * $qty_slot), 0, ',', '.') }} ( {{$qty_slot}} Slot )</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Kode Unik:</span>
                                        <span class="ms-2 text-secondary">Rp {{ number_format($kode_unik, 0, ',', '.') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Total :</span>
                                        <span class="fw-bold">Rp {{ number_format($total_bayar, 0, ',', '.') }}</span>
                                    </li>
                                </ul>
                                <!-- Informasi Rekening -->
                                <div class="mt-3">
                                    <h6 class="text-primary">Informasi Rekening:</h6>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between border-0 p-0">
                                            <span class="fw-bold">Bank:</span>
                                            <span class="text-secondary">{{ $nama_bank }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between border-0 p-0">
                                            <span class="fw-bold">Nomor Rekening:</span>
                                            <span class="text-secondary">{{ $nomor_rek }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between border-0 p-0">
                                            <span class="fw-bold">Atas Nama:</span>
                                            <span class="text-secondary">{{ $nama_rek }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Status Pembayaran -->
                        <div class="mb-4">
                            <h4 class="mb-2">Status Pembayaran:</h4>
                            <div class="p-3 border rounded bg-light">
                                @if ($status_pembayaran === 'PENDING')
                                    <p class="text-danger fw-bold">Belum Dibayar</p>
                                    <p class="text-muted">Silakan lakukan pembayaran sesuai dengan informasi rekening di atas.</p>
                                @elseif ($status_pembayaran === 'CONFIRMATION')
                                    <p class="text-warning fw-bold">Menunggu Konfirmasi</p>
                                    <p class="text-muted">Bukti transfer Anda sedang diperiksa. Harap tunggu beberapa saat.</p>
                                @elseif ($status_pembayaran === 'APPROVED')
                                    <p class="text-success fw-bold">Lunas</p>
                                    <p class="text-muted">Pembayaran Anda telah diterima. Terima kasih!</p>
                                @else
                                    <p class="text-muted">Status tidak diketahui. Harap hubungi admin.</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Form Upload Bukti Transfer -->
                        @if ($status_pembayaran !== 'APPROVED')
                        <div class="text-center">
                            <form method="POST" action="/postbuktitransfer" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 text-start">
                                    <label for="bukti_transfer" class="form-label"><b>Bukti Transfer</b></label>
                                    <input type="file" name="bukti_transfer" id="bukti_transfer" class="form-control" required>
                                    <input type="text" name="nohp" value="{{ $nomor_hp }}" hidden>
                                    <input type="text" name="email" value="{{ $email }}" hidden>
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim Bukti Transfer</button>
                            </form>
                        </div>
                        @endif
                        
                    </div>
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

@endsection
