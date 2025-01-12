@extends('sb-admin-2.layouts.app')

@section('content')


<!-- CSS custom -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">

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

<!-- DataTales Example -->
<div class="card shadow mb-4 custom-card-header">
    <div class="card-header py-3">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Master Kategori</h1>
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
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Harga Kategori</th>
                        <th>Event</th>
                        <th>Addtime</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data) && !$data->isEmpty())
                    @foreach ($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td>{{ $item->harga_kategori }}</td>
                        <td>{{ $item->title_event }}</td>
                        <td>{{ $item->addtime }}</td>
                        <td>
                        <div class="button-group">
                            <form method="POST" action="/postkategori">
                                @csrf
                                <input type="hidden" name="rowid" value="{{ $item->id_kategori }}">
                                <div class="button-group">
                                    <button type="button" class="btn btn-primary mb-2 btn-edit"
                                        data-rowid="{{ $item->id_kategori }}"
                                        data-nama="{{ $item->nama_kategori }}"
                                        data-harga="{{ $item->harga_kategori }}">Edit</button>
                                    <button type="submit" name="proses" value="delete" class="btn btn-danger mb-2">Delete</button>
                                </div>
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

    <hr>
    <div align="center">
        <button id="toggleButton" class="btn btn-success">Add New Kategori</button>
    </div>
    <br>
    <div id="myForm" style="display: none;">
        <div class="col-xl-8 col-lg-7 mx-auto">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="POST" action="/postkategori">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="nama_kategori"><b>Nama Kategori</b></label>
                                <input type="text" name="nama_kategori" class="form-control" required />
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="harga_kategori"><b>Harga Kategori</b></label>
                                <input type="text" name="harga_kategori" class="form-control" required />
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="event"><b>Event</b></label>
                                <select class="form-control" id="event" name="event" required>
                                    @foreach($event as $item)
                                    <option value="{{ $item->id_event }}" {{ request('event') == $item->id_event ? 'selected' : '' }}>
                                        {{ $item->title_event }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div align="center">
                            <button type="submit" name="proses" value="save" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="/postkategori">
                        @csrf
                        <input type="hidden" name="proses" value="edit">
                        <input type="hidden" name="rowid" id="editRowid">

                        <div class="form-group">
                            <label for="editNama"><b>Nama Kategori</b></label>
                            <input type="text" name="nama_kategori" id="editNama" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="editHarga"><b>Harga Kategori</b></label>
                            <input type="text" name="harga_kategori" id="editHarga" class="form-control" required />
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@if(session('success'))
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
    $('#dataTable').dataTable({
        "lengthMenu": [10, 20, 50, 100],
        "pageLength": 5,
        searching: true
    });
});
</script>

<script>
    $(document).ready(function() {
        $("#toggleButton").click(function() {
            $("#myForm").toggle();
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Edit button click event
        $('.btn-edit').on('click', function() {
            var rowid = $(this).data('rowid');
            var nama = $(this).data('nama');
            var harga = $(this).data('harga');

            // Set modal data
            $('#editRowid').val(rowid);
            $('#editNama').val(nama);
            $('#editHarga').val(harga);

            // Show modal
            $('#editModal').modal('show');
        });
    });
</script>

@endsection