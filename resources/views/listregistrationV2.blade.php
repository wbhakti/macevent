@extends('sb-admin-2.layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .card-header {
        background-color: #fff;
    }
    .mr-0 {
        margin-right: 0;
    }
    .ml-auto {
        margin-left: auto;
    }
    .d-block {
        display: block;
    }
    .button-group a {
        margin-bottom: 10px;
    }
</style>

<!-- CSS custom -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">

<div class="card shadow mb-4 custom-card-header">
    <div class="card-header py-3">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Report Pendaftaran Peserta Event</h1>
        <p class="mb-4" style="color:black">
            loremipsum loremipsum loremipsum loremipsum loremipsum loremipsum
        </p>
        @if(isset($error))
        <div align="center">
            <text style="color:red">{{ $error }}</text>
        </div>
        @endif
    </div>

    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="GET" action="/getreportevent">
                    @csrf
                    <div class="mb-3">
                        <label for="outlet" class="form-label">Event</label>
                        <select class="form-control" id="event" name="event" required>
                            <option value="" disabled {{ request('event') ? '' : 'selected' }}>Pilih Event</option>
                            @foreach($event as $item)
                            <option value="{{ $item->id_event }}|{{ $item->title_event }}" 
                                {{ request('event') == $item->id_event . '|' . $item->title_event ? 'selected' : '' }}>
                                {{ $item->title_event }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <button type="submit" class="btn btn-primary w-100" name="action" value="report">Lihat</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-success w-100" name="action" value="download">Download</button>
                        </div>
                    </div>                                       
                </form>
            </div>
        </div>

        <hr>
        <div class="table-responsive">
            <table id="datatablesSimple" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No Registrasi</th>
                        <th>Nama Peserta</th>
                        <th>Event</th>
                        <th>Kategori</th>
                        <th>Nomor HP</th>
                        <th>Nama Team</th>
                        <th>Tanggal Daftar</th>
                        <th>Status User</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data) && !$data->isEmpty())
                    @foreach ($data as $index => $item)
                    <tr>
                        <td>{{ $item->id_peserta }}</td>
                        <td>{{ $item->nama_lengkap }}</td>
                        <td>{{ $item->title_event }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td>{{ $item->nomor_hp }}</td>
                        <td>{{ $item->nama_team }}</td>
                        <td>{{ $item->addtime }}</td>
                        <td>{{ $item->status_user }}</td>
                        <td>
                            <div class="button-group">
                                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#detailsModal"
                                    data-rowid="{{ $item->id_peserta }}"
                                    data-idtransaksi="{{ $item->id_transaksi }}"
                                    data-nama="{{ $item->nama_lengkap }}"
                                    data-nomorhp="{{ $item->nomor_hp }}"
                                    data-email="{{ $item->email }}"
                                    data-namateam="{{ $item->nama_team }}"
                                    data-tanggaldaftar="{{ $item->addtime }}"
                                    data-statususer="{{ $item->status_user }}"
                                    data-buktitransfer="{{ $item->foto_bukti_trf }}"
                                    data-imgdepan="{{ $item->foto_depan }}"
                                    data-imgbelakang="{{ $item->foto_belakang }}"
                                    data-imgsamping="{{ $item->foto_samping }}"
                                    data-kota="{{ $item->kota_asal }}"
                                    data-kategori="{{ $item->nama_kategori }}">
                                    Detail
                                </button>
                                <form method="POST" action="/postapproveuser">
                                    @csrf
                                    <input type="hidden" name="rowid" value="{{ $item->id_peserta }}">
                                    <button type="submit" name="proses" value="delete" class="btn btn-danger mb-2">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else

                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Detail Peserta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" method="POST" action="/postapproveuser" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="rowid" id="editRowid">
                    <input type="hidden" name="idTransaksi" id="idTransaksi">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="modalTanggalDaftar"><strong>Tanggal Daftar:</strong></label>
                            <input type="text" class="form-control" id="modalTanggalDaftar" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modalNomorHp"><strong>Nomor HP:</strong></label>
                            <input type="text" class="form-control" id="modalNomorHp" name="nomor_hp" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modalEmail"><strong>Email:</strong></label>
                            <input type="text" class="form-control" id="modalEmail" name="email" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modalNama"><strong>Nama Peserta:</strong></label>
                            <input type="text" class="form-control" id="modalNama" name="nama" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modalAlamat"><strong>Kota:</strong></label>
                            <input type="text" class="form-control" id="modalAlamat" name="" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modalNamaTeam"><strong>Nama Team:</strong></label>
                            <input type="text" class="form-control" id="modalNamaTeam" name="nama_team" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modalkategori"><strong>Kategori:</strong></label>
                            <input type="text" class="form-control" id="modalkategori" name="kategori" readonly>
                        </div>
                        <div class="form-group">
                            <label><strong>Foto Depan:</strong></label>
                            <a id="imageLinkDepan" href="#" target="_blank">
                                <img id="currentImageDepan" src="" alt="Current Image" style="max-width: 100px; max-height: 100px;">
                            </a>
                        </div>
                        <div class="form-group">
                            <label><strong>Foto Belakang:</strong></label>
                            <a id="imageLinkBelakang" href="#" target="_blank">
                                <img id="currentImageBelakang" src="" alt="Current Image" style="max-width: 100px; max-height: 100px;">
                            </a>
                        </div>
                        <div class="form-group">
                            <label><strong>Foto Samping:</strong></label>
                            <a id="imageLinkSamping" href="#" target="_blank">
                                <img id="currentImageSamping" src="" alt="Current Image" style="max-width: 100px; max-height: 100px;">
                            </a>
                        </div>
                        <div class="form-group">
                            <label><strong>Bukti Transfer:</strong></label>
                            <a id="imageLinkTransfer" href="#" target="_blank">
                                <img 
                                    id="currentImageTransfer" 
                                    src="" 
                                    alt="Current Image" 
                                    style="max-width: 100px; max-height: 100px;">
                            </a>
                        </div>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="proses-edit" name="proses" value="approve" class="btn btn-primary">Approve</button>
                        <a href="#" id="proses-edit" class="btn btn-success" target="_blank">Confirmation</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@if (session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
@endif
@if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
@endif

<!-- Page level plugins -->
<script src="{{ asset('vendor/jquery/jquery-3.3.1.min.js')}}"></script>
<script src="{{ asset('vendor/jquery/jquery.validate.min.js')}}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        $('#datatablesSimple').DataTable({
            "lengthMenu": [10, 20, 50, 100],
            "pageLength": 5,
            responsive: true,
            searching: true
        });

        $('#detailsModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var rowid = button.data('rowid');
            var nama = button.data('nama');
            var nomorHp = String(button.data('nomorhp'));
            var email = button.data('email');
            var namaTeam = button.data('namateam');
            var tanggalDaftar = button.data('tanggaldaftar');
            var kategori = button.data('kategori');
            var alamat = button.data('kota');
            var imgdepan = button.data('imgdepan');
            var imgbelakang = button.data('imgbelakang');
            var imgsamping = button.data('imgsamping');
            var statusUser = button.data('statususer');
            var buktiTransfer = button.data('buktitransfer');
            var idtransaksi = button.data('idtransaksi');

            var modal = $(this);
            modal.find('#editRowid').val(rowid);
            modal.find('#modalNama').val(nama);
            modal.find('#modalNomorHp').val(nomorHp);
            modal.find('#modalEmail').val(email);
            modal.find('#modalNamaTeam').val(namaTeam);
            modal.find('#modalTanggalDaftar').val(tanggalDaftar);
            modal.find('#modalkategori').val(kategori);
            modal.find('#modalAlamat').val(alamat);
            modal.find('#idTransaksi').val(idtransaksi);

            modal.find('#currentImageDepan').attr('src', "{{ url('/img') }}/" + imgdepan);
            modal.find('#imageLinkDepan').attr('href', "{{ url('/img') }}/" + imgdepan);

            modal.find('#currentImageBelakang').attr('src', "{{ url('/img') }}/" + imgbelakang);
            modal.find('#imageLinkBelakang').attr('href', "{{ url('/img') }}/" + imgbelakang);

            modal.find('#currentImageSamping').attr('src', "{{ url('/img') }}/" + imgsamping);
            modal.find('#imageLinkSamping').attr('href', "{{ url('/img') }}/" + imgsamping);

            modal.find('#currentImageTransfer').attr('src', "{{ url('/invoice') }}/" + buktiTransfer);
            modal.find('#imageLinkTransfer').attr('href', "{{ url('/invoice') }}/" + buktiTransfer);

            if (!buktiTransfer || !buktiTransfer.includes('.jpg')) {
                const textElement = $('<span>')
                    .text('Belum bayar')
                    .css({
                        color: 'red',
                        fontWeight: 'bold'
                    });

                const parentLink = modal.find('#imageLinkTransfer');
                parentLink.empty().append(textElement);
                parentLink.attr('href', '#');
            }


            if (statusUser === 'PENDING') {
                modal.find('button[name="proses"][value="approve"]').show();
            } else if (statusUser === 'CONFIRMATION') {
                modal.find('button[name="proses"][value="approve"]').show();
                modal.find('a#proses-edit').hide();
            }
            else {
                modal.find('button[name="proses"][value="approve"]').hide();
                modal.find('a#proses-edit').hide();
            }

            //kirim WA
            if (nomorHp.startsWith("0")) {
                nomorHp = "62" + nomorHp.substring(1);
            }
            var waLink = "https://wa.me/" + nomorHp + "?text=Hallo%20Robo%20Racer,,%0aMohon%20konfrmasi%20untuk%20pendftaran%20Robo%20Race%202025%20apakah%20mau%20untuk%20melanjutkan%20registrasi%20kak?%0aRobo%20tunggu%20konfirmasinya%20hari%20ini%20jam%2020:00%20wib%20dengan%20melampirkan%20bukti%20Transfer.%0aTerimakasih,,";
            $(this).find('a#proses-edit').attr('href', waLink).off('click').on('click', function(e) {
                e.preventDefault(); 
                $.ajax({
                    url: '/postapproveuser',
                    type: 'POST',
                    data: {
                        rowid: rowid,
                        proses: 'CONFIRMATION'
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        window.open(waLink, '_blank');
                        location.reload();
                    },
                    error: function(xhr) {
                        console.log('Response Text: ' + xhr.responseText);
                        alert('Gagal update data.');
                    }
                });
            });

        });
    });
</script>

@endsection
