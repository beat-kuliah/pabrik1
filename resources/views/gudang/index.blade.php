@extends('layouts.app')

@section('content')

<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGudang">
        Tambah Data
    </button>
    <h1>Gudang</h1>
    <br><br>
    <div class="table-responsive">
        <table id="gudang" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Vendor</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Vendor</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th class="text-center">Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@include('gudang.create')
@include('gudang.edit')

@endsection

@section('script')

<script>
    var id = 0;

    $(document).ready(function() {
        $('#gudang').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/gudang/datatables',
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
                    data: 'vendor'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'action'
                }
            ],
            columnDefs: [{
                    target: [6],
                    className: "text-center",
                    render: function(data, type, row) {
                        return '<button type="button" data-bs-toggle="modal" data-bs-target="#editGudang" onclick="editGudang(' + data + ')" class="btn btn-warning">Edit</button><span>   </span><button type="button" onclick="deleteGudang(' + data + ')" class="btn btn-danger">Delete</button>';
                    }
                },
                {
                    target: [5],
                    className: "text-center",
                    render: function(data, type, row) {
                        return fixDate(data);
                    }
                },
                {
                    target: [4],
                    className: "text-center",
                    render: function(data, type, row) {
                        return fixDate(data);
                    }
                }
            ]
        });

        axios.get('/vendor/all')
            .then(function(response) {
                response.data.forEach(element => {
                    var option = document.createElement("option");
                    option.value = element.id;
                    option.text = element.nama;
                    var vendor = document.getElementById("selectVendor");
                    vendor.add(option);
                });
            }).catch(function(error) {
                console.log(error)
            })

        axios.get('/vendor/all')
            .then(function(response) {
                response.data.forEach(element => {
                    var option = document.createElement("option");
                    option.value = element.id;
                    option.text = element.nama;
                    var editVendor = document.getElementById("selectEditVendor");
                    editVendor.add(option);
                });
            }).catch(function(error) {
                console.log(error)
            })
    });

    function tambahGudang() {
        var formData = new FormData(document.getElementById("formGudang"));
        axios({
            method: 'post',
            url: '/gudang',
            data: formData
        }).then(function(response) {
            if (response.data.status == 200) {
                alert('Success');
                window.location.href = '/gudang';
            } else
                alert('Gagal - Ada kode yang sama')
        }).catch(function(error) {
            alert('gagal');
        })
    }

    function editGudang(val) {
        id = val;
        axios.get('/gudang/show/' + val)
            .then(function(response) {
                console.log(response);
                document.getElementById('kode_barang').value = response.data.gudang.kode;
                document.getElementById('nama').value = response.data.gudang.nama;
                $('#selectEditVendor').val(response.data.vendor_id);
                if (response.data.gudang.alamat.length != 0) {
                    counter = 1;
                    response.data.gudang.alamat.forEach(element => {
                        document.getElementById('alamat' + counter).value = element.alamat;
                        counter += 1;
                    });
                }
            })
            .catch(function(error) {
                console.log(error);
            })
    }

    function updateGudang() {
        var formData = new FormData(document.getElementById('formEditGudang'));
        axios({
            url: '/gudang/update/' + id,
            method: 'post',
            data: formData,
        }).then(function(response) {
            if (response.data == 200) {
                alert('Success');
                window.location.href = '/gudang';
            } else {
                alert('Ada Kode yang sama');
            }
        }).catch(function(error) {
            console.log(error);
            alert('gagal');
        })
    }

    function gudangDeleted(val) {
        axios.get('/gudang/destroy/' + val)
            .then(function(response) {
                alert('Success');
                window.location.href = '/barang';
            })
            .catch(function(error) {
                console.log(error);
                alert('Gagal');
            })
    }

    function deleteGudang(val) {
        if (confirm("Menghapus Data Gudang akan menghapus Data Barang beserta Penjualannya juga, Anda yakin?") == true) {
            gudangDeleted(val);
        }
    }
</script>

@endsection