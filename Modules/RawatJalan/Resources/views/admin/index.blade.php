@extends('adminlte::page')

@section('title', 'Rawat Jalan')

@section('content_header')
    <h1 class="m-0 text-dark">Rawat Jalan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $perawatans->count() }}</h3>
                            <p>Pasien Rawat Jalan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        @can('admin-role')
                            <a href="#" class="small-box-footer" data-toggle="modal" data-target="#createModal">
                                Daftar Rawat Jalan <i class="fas fa-plus-circle"></i>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Tabel Data Rawat Jalan</h3>
                </div>
                <div class="card-body">
                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="example1" class="table table-bordered table-striped dataTable dtr-inline"
                                    role="grid" aria-describedby="example1_info">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode Regis</th>
                                            <th>Tanggal</th>
                                            <th>Poliklinik</th>
                                            <th>Nama Pasien</th>
                                            <th>Dokter</th>
                                            <th>Keluhan</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perawatans as $item)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $item->kode }}</td>
                                                <td>{{ Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                                <td>{{ $item->spesialis }}</td>
                                                <td>{{ $item->pasien->user->name }}</td>
                                                <td>{{ $item->dokter->user->name }}</td>
                                                <td>{{ $item->keluhan }}</td>
                                                <td>
                                                    @if ($item->status == 0)
                                                        <label class="badge badge-danger">Menunggu antrian</label>
                                                    @endif
                                                    @if ($item->status == 1)
                                                        <label class="badge badge-warning">Telah dicek dokter</label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('admin.user.destroy', $item) }}" method="POST">
                                                        @can('admin-role')
                                                            <a class="btn btn-xs btn-warning"
                                                                href="{{ route('admin.rawat-jalan.edit', $item->kode) }}"
                                                                data-toggle="tooltip" title="Edit {{ $item->kode }}"><i
                                                                    class=" fas fa-edit"></i></a>
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-danger"
                                                                data-toggle="tooltip" title="Hapus {{ $item->name }}">
                                                                <i class="fas fa-trash-alt"
                                                                    onclick="return confirm('Are you sure you want to delete this item ?')"></i>
                                                            </button>
                                                        @endcan
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="createModalLabel">Daftar Rawat Jalan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['route' => 'admin.rawat-jalan.store', 'method' => 'POST', 'files' => true]) !!}
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Ada kesalahan input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card card-warning">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="iTanggal">Tanggal Rawat Jalan</label>
                                        {!! Form::date('tanggal', \Carbon\Carbon::now(), ['class' => 'form-control', 'id' => 'iTanggal']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="iSpesialis" class="form-label">Poliklinik</label>
                                        <select name="spesialis" id="spesialis" class="form-control" id='iSpesialis'>
                                            <option value="">Pilih Polikinik</option>
                                            @foreach ($spesialis as $item)
                                                <option value="{{ $item }}">{{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="iSpesialis">Keluhan</label>
                                        {!! Form::textarea('keluhan', null, ['class' => 'form-control', 'id' => 'iSpesialis', 'rows' => '3']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Data Pasien</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="pasien_id" class="form-label">Pasien</label>
                                        <select name="pasien_id" id="pasien_id" class="form-control">
                                            <option value="">Pilih Pasien</option>
                                            @foreach ($pasiens as $item)
                                                <option value="{{ $item->id }}">{{ $item->kode }}
                                                    {{ $item->user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <dl class="row">
                                        <dt class="col-sm-2">Kode</dt>
                                        <dd class="col-sm-10">123123123</dd>
                                        <dt class="col-sm-2">NIK</dt>
                                        <dd class="col-sm-10">1234123412341234</dd>
                                        <dt class="col-sm-2">Nama</dt>
                                        <dd class="col-sm-10">Marwan Dhiaur Rahman</dd>
                                        <dt class="col-sm-2">TTL</dt>
                                        <dd class="col-sm-10">Cirebon, 9 Mei 1998</dd>
                                        <dt class="col-sm-2">Umur</dt>
                                        <dd class="col-sm-10">23 Tahun</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Data Dokter</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="dokter_id" class="form-label">Dokter</label>
                                        <select name="dokter_id" id="dokter_id" class="form-control">
                                            <option value="">Pilih Dokter</option>
                                            @foreach ($dokters as $item)
                                                <option value="{{ $item->id }}">{{ $item->user->name }} (
                                                    {{ $item->spesialis }} )</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <dl class="row">
                                        <dt class="col-sm-2">Kode</dt>
                                        <dd class="col-sm-10">123123123</dd>
                                        <dt class="col-sm-2">NIK</dt>
                                        <dd class="col-sm-10">1234123412341234</dd>
                                        <dt class="col-sm-2">Nama</dt>
                                        <dd class="col-sm-10">Marwan Dhiaur Rahman</dd>
                                        <dt class="col-sm-2">TTL</dt>
                                        <dd class="col-sm-10">Cirebon, 9 Mei 1998</dd>
                                        <dt class="col-sm-2">Umur</dt>
                                        <dd class="col-sm-10">23 Tahun</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('plugins.Datatables', true)

@section('js')
    <script type="text/javascript">
        @if ($errors->any())
            $('#createModal').modal('show');
        @endif
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["excel", "pdf", "print", "colvis"],
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#province_id').on('change', function() {
                $.ajax({
                    url: '{{ route('dependent-dropdown.store') }}',
                    method: 'POST',
                    data: {
                        id: $(this).val()
                    },
                    success: function(response) {
                        $('#city_id').empty();

                        $.each(response, function(id, name) {
                            $('#city_id').append(new Option(name, id))
                        })
                    }
                })
            });
            $('#city_id').on('change', function() {
                $.ajax({
                    url: '{{ route('dependent-dropdown.kecamatan') }}',
                    method: 'POST',
                    data: {
                        id: $(this).val()
                    },
                    success: function(response) {
                        $('#district_id').empty();

                        $.each(response, function(id, name) {
                            $('#district_id').append(new Option(name, id))
                        })
                    }
                })
            });
            $('#district_id').on('change', function() {
                $.ajax({
                    url: '{{ route('dependent-dropdown.desa') }}',
                    method: 'POST',
                    data: {
                        id: $(this).val()
                    },
                    success: function(response) {
                        $('#village_id').empty();

                        $.each(response, function(id, name) {
                            $('#village_id').append(new Option(name, id))
                        })
                    }
                })
            });
        });
    </script>
@endsection
