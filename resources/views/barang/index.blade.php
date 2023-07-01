@extends('layouts.app')

@section('content')

<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBarang">
        Tambah Data
    </button>
    <h1>Barang</h1>
    <br><br>
    <select class="form-select" id="selectGudangFilter" aria-label="Default select example">
        <option value="1" selected>Gudang Utama</option>
        <option value="2">Gudang Siap Jual</option>
    </select>
    <br>
    <div class="table-responsive">
        <table id="barang" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Stok Awal</th>
                    <th>Stok Akhir</th>
                    <th>Harga</th>
                    <th>Vendor</th>
                    <th>Gudang</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tfoot>
        </table>
    </div>
</div>

@include('barang.create')
@include('barang.edit')
@include('barang.update_stok')

@endsection

@section('script')

<script>
    var updateStokId;
    var id;
    $(document).ready(function() {
        $('#barang').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/barang/datatables',
            columns: [{
                    data: 'id'
                },
                {
                    data: 'kode'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'stock_awal'
                },
                {
                    data: 'stock_akhir'
                },
                {
                    data: 'harga'
                },
                {
                    data: 'vendor'
                },
                {
                    data: 'gudang'
                },
                {
                    data: 'action'
                }
            ],
            columnDefs: [{
                target: [5],
                className: "text-center",
                render: function(data, type, row) {
                    return fixPrice(data);
                }
            }, {
                target: [8],
                className: "text-center",
                render: function(data, type, row) {
                    var act = '<button type="button" data-bs-toggle="modal" data-bs-target="#editBarang" onclick="editBarang(' + data + ')" class="btn btn-warning">Edit</button>';
                    act += '<span>   </span>';
                    act += '<button type="button" onclick="formUpdateStok(' + data + ')" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateStok">Stok</button>';
                    act += '<span>   </span>';
                    act += '<button type="button" onclick="deleteBarang(' + data + ')" class="btn btn-danger">Delete</button>';

                    return act;
                }
            }, ]
        });
    });

    $('#selectGudangFilter').change(function() {
        var data = $(this).val();
        $('#barang').dataTable().fnDestroy();
        $('#barang').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/barang/datatables',
                'data': {
                    'gudangFilter': data,
                }
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'kode'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'stock_awal'
                },
                {
                    data: 'stock_akhir'
                },
                {
                    data: 'harga'
                },
                {
                    data: 'vendor'
                },
                {
                    data: 'gudang'
                },
                {
                    data: 'action'
                }
            ],
            columnDefs: [{
                target: [5],
                className: "text-center",
                render: function(data, type, row) {
                    return fixPrice(data);
                }
            }, {
                target: [8],
                className: "text-center",
                render: function(data, type, row) {
                    var act = '<button type="button" data-bs-toggle="modal" data-bs-target="#editBarang" onclick="editBarang(' + data + ')" class="btn btn-warning">Edit</button>';
                    act += '<span>   </span>';
                    act += '<button type="button" onclick="formUpdateStok(' + data + ')" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateStok">Stok</button>';
                    act += '<span>   </span>';
                    act += '<button type="button" onclick="deleteBarang(' + data + ')" class="btn btn-danger">Delete</button>';

                    return act;
                }
            }, ]
        });
    });

    function formUpdateStok(val) {
        updateStokId = val;
    }

    function updateStok() {
        var formData = new FormData(document.getElementById("formStok"));
        axios({
            method: 'post',
            url: '/barang/update-stok/' + updateStokId,
            data: formData
        }).then(function(response) {
            alert('Success');
            window.location.href = '/barang';
        }).catch(function(error) {
            alert('gagal');
        })

    }

    function fixDate(val) {
        var fixDate = new Date(val);
        $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        var result = fixDate.getDate() + ' ' + $bulan[fixDate.getMonth()] + ' ' + fixDate.getFullYear() + ' ' + fixDate.getHours() + ':' + fixDate.getMinutes();

        return result;
    }

    function tambahBarang() {
        var formData = new FormData(document.getElementById("formBarang"));
        axios({
            method: 'post',
            url: '/barang',
            data: formData
        }).then(function(response) {
            console.log(response.data);
            if (response.data.status == 200)
                window.location.href = '/barang';
            else
                alert('Gagal - Ada kode yang sama')
        }).catch(function(error) {
            alert('gagal');
        })
    }

    function editBarang(val) {
        id = val;
        axios.get('/barang/find/' + id)
            .then(function(response) {
                console.log(response.data);
                document.getElementById('kode_barang').value = response.data.kode;
                document.getElementById('nama').value = response.data.nama;
                document.getElementById('harga').value = response.data.harga;
                $('#selectEditGudang').val(response.data.gudang_id);
            })
            .catch(function(error) {
                console.log(error);
                alert('Gagal');
            })
    }

    function updateBarang() {
        var formData = new FormData(document.getElementById('formEditBarang'));
        axios({
            url: '/barang/update/' + id,
            method: 'post',
            data: formData
        }).then(function(response) {
            if (response.data == 200) {
                alert('Success');
                window.location.href = '/barang';
            } else
                alert('Ada kode yang sama')
        }).catch(function(error) {
            alert('Gagal');
        })
    }

    function barangDeleted(val) {
        axios.get('/barang/destroy/' + val)
            .then(function(response) {
                alert('Success');
                window.location.href = '/barang';
            })
            .catch(function(error) {
                console.log(error);
                alert('Gagal');
            })
    }

    function deleteBarang(val) {
        if (confirm("Menghapus Data Barang akan menghapus Data Penjualannya juga, Anda yakin?") == true) {
            barangDeleted(val);
        }
    }
</script>

@endsection