@extends('layouts.app')

@section('content')

<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createVendor">
        Tambah Data
    </button>
    <h1>Vendor</h1>
    <br><br>
    <div class="table-responsive">
        <table id="vendor" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Kode</th>
                    <th>Nama</th>
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
                    <th>Created</th>
                    <th>Updated</th>
                    <th class="text-center">Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@include('vendor.create')
@include('vendor.edit')

@endsection

@section('script')

<script>
    var id = 0;

    $(document).ready(function() {
        $('#vendor').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/vendor/datatables',
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
                    target: [5],
                    className: "text-center",
                    render: function(data, type, row) {
                        return '<button type="button" data-bs-toggle="modal" data-bs-target="#editVendor" onclick="editVendor(' + data + ')" class="btn btn-warning">Edit</button><span>   </span><button type="button" onclick="deleteVendor(' + data + ')" class="btn btn-danger">Delete</button>';
                    }
                },
                {
                    target: [4],
                    className: "text-center",
                    render: function(data, type, row) {
                        return fixDate(data);
                    }
                },
                {
                    target: [3],
                    className: "text-center",
                    render: function(data, type, row) {
                        return fixDate(data);
                    }
                }
            ]
        });
    });

    function tambahVendor() {
        var formData = new FormData(document.getElementById("formVendor"));
        console.log(formData.get("kode_barang"));
        console.log(formData.get("nama"));
        axios({
            method: 'post',
            url: '/vendor',
            data: formData
        }).then(function(response) {
            if (response.data.status == 200)
                window.location.href = '/vendor';
            else
                alert('Gagal - Ada kode yang sama')
        }).catch(function(error) {
            alert('gagal');
        })
    }

    function editVendor(val) {
        id = val;
        axios.get('/vendor/show/' + val)
            .then(function(response) {
                document.getElementById('kode_barang').value = response.data.vendor.kode;
                document.getElementById('nama').value = response.data.vendor.nama;
            })
            .catch(function(error) {
                console.log(error);
            })
    }

    function updateVendor() {
        var formData = new FormData(document.getElementById('formEditVendor'));
        axios({
            url: '/vendor/update/' + id,
            method: 'post',
            data: formData
        }).then(function(response) {
            if (response.data == 200) {
                alert('Success');
                window.location.href = '/vendor';
            } else {
                alert('Ada Kode yang sama');
            }
        }).catch(function(error) {
            alert('gagal');
        })
    }

    function vendorDeleted(val) {
        axios.get('/vendor/destroy/' + val)
            .then(function(response) {
                alert('Success');
                window.location.href = '/barang';
            })
            .catch(function(error) {
                console.log(error);
                alert('Gagal');
            })
    }

    function deleteVendor(val) {
        if (confirm("Menghapus Data Vendor akan menghapus Data Barang beserta Penjualannya juga, Anda yakin?") == true) {
            vendorDeleted(val);
        }
    }
</script>

@endsection