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
        <h1 class="h3 mb-2 text-gray-800">Data Master Event</h1>
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
                        <th>Title Event</th>
                        <th>Desc Event</th>
                        <th>Image Event</th>
                        <th>Status Event</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($event) && !$event->isEmpty())
                    @foreach ($event as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->title_event }}</td>
                        <td>{{ $item->desc_event }}</td>
                        <td>
                            <img src="{{ asset('img/' . $item->img_event) }}" alt="Thumbnail" style="max-width: 100px; max-height: 100px;">
                        </td>
                        <td>{{ $item->is_active }}</td>
                        <td>
                        <div class="button-group">
                            <form method="POST" action="/postevent">
                                @csrf
                                <input type="hidden" name="rowid" value="{{ $item->id_event }}">
                                <input type="hidden" name="old_img_event" value="{{ $item->img_event }}">
                                <div class="button-group">
                                    <button type="button" class="btn btn-primary mb-2 btn-edit"
                                        data-rowid="{{ $item->id_event }}"
                                        data-title="{{ $item->title_event }}"
                                        data-desc="{{ $item->desc_event }}"
                                        data-img="{{ $item->img_event }}"
                                        data-bank="{{ $item->nama_bank }}"
                                        data-norek="{{ $item->nomor_rekening }}"
                                        data-namarek="{{ $item->nama_rekening }}"
                                        data-isactive="{{ $item->is_active }}">Edit</button>
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
        <button id="toggleButton" class="btn btn-success">Add New Event</button>
    </div>
    <br>
    <div id="myForm" style="display: none;">
        <div class="col-xl-8 col-lg-7 mx-auto">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="POST" action="/postevent" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="title_event"><b>Title Event</b></label>
                                <input type="text" name="title_event" class="form-control" required />
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="regulasi_event"><b>Regulasi Event</b></label>
                                <input type="file" name="img_snk" class="form-control" accept="image/*" />
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="desc_event"><b>Desc Event</b></label>
                                <textarea name="desc_event" class="form-control" rows="4" required></textarea>
                            </div>                            
                            <div class="form-group col-sm-6">
                                <label for="img_event"><b>Image Event (900x400)</b></label>
                                <input type="file" name="img_event" class="form-control" accept="image/*" />
                            </div>                            
                        </div>
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="nama_bank"><b>Nama Bank</b></label>
                                <input type="text" name="nama_bank" class="form-control" required />
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="no_rekening"><b>Nomor Rekening</b></label>
                                <input type="text" name="no_rekening" class="form-control" required />
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="nama_rekening"><b>Nama Rekening</b></label>
                                <input type="text" name="nama_rekening" class="form-control" required />
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="jasa_layanan"><b>Jasa Layanan</b></label>
                                <input type="text" name="jasa_layanan" class="form-control" required />
                            </div>
                        </div>
                        <br />
                        <div align="center">
                            <button type="submit" name="proses" value="save" class="btn btn-success">Save Event</button>
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
                    <h5 class="modal-title" id="editModalLabel">Edit Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="/postevent" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="proses" value="edit">
                        <input type="hidden" name="rowid" id="editRowid">
                        <input type="hidden" name="old_img_event" id="editOldImgEvent">

                        <div class="form-group">
                            <label for="editIsActive"><b>Is Active</b></label>
                            <select name="is_active" id="editIsActive" class="form-control">
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editTitleEvent"><b>Title Event</b></label>
                            <input type="text" name="title_event" id="editTitleEvent" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="editDescEvent"><b>Desc Event</b></label>
                            <textarea name="desc_event" id="editDescEvent" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editImgEvent"><b>Image Event</b></label>
                            <input type="file" name="img_event" id="editImgEvent" class="form-control" accept="image/*" />
                        </div>

                        <div class="form-group">
                            <label for="editNamaBank"><b>Nama BANK</b></label>
                            <input type="text" name="nama_bank" id="editNamaBank" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="editNorek"><b>Nomor Rekening</b></label>
                            <input type="text" name="nomor_rekening" id="editNorek" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="editNamarek"><b>Nama Rekening</b></label>
                            <input type="text" name="nama_rekening" id="editNamarek" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label><b>Current Image</b></label>
                            <img id="currentImage" src="" alt="Current Image" style="max-width: 100px; max-height: 100px;">
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
            var title = $(this).data('title');
            var desc = $(this).data('desc');
            var img = $(this).data('img');
            var isactive = $(this).data('isactive');
            var bank = $(this).data('bank');
            var norek = $(this).data('norek');
            var namarek = $(this).data('namarek');

            // Set modal data
            $('#editRowid').val(rowid);
            $('#editTitleEvent').val(title);
            $('#editDescEvent').val(desc);
            $('#editOldImgEvent').val(img);
            $('#editIsActive').val(isactive);
            $('#currentImage').attr('src', "{{ asset('img/') }}/" + img);
            $('#editNamaBank').val(bank);
            $('#editNorek').val(norek);
            $('#editNamarek').val(namarek);

            // Show modal
            $('#editModal').modal('show');
        });
    });
</script>

@endsection