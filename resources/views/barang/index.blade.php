@extends('layouts.app')

@section('content')

<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBarang">
        Tambah Data
    </button>
    <h1>Barang</h1>
    <br><br>
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

@include('barang.create')
@include('barang.update_stok')

@endsection

@section('script')

<script>
    var updateStokId;

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
                target: [8],
                className: "text-center",
                render: function(data, type, row) {
                    var act = '<button type="button" onclick="editBarang(' + data + ')" class="btn btn-warning">Edit</button>';
                    act += '<span>   </span>';
                    act += '<button type="button" onclick="formUpdateStok(' + data + ')" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateStok">Stok</button>';
                    act += '<span>   </span>';
                    act += '<button type="button" onclick="deleteBarang(' + data + ')" class="btn btn-danger">Delete</button>';

                    return act;
                }
            }, ]
        });

        axios.get('/gudang/all')
            .then(function(response) {
                response.data.forEach(element => {
                    var gudang = document.getElementById("selectGudang");
                    var option = document.createElement("option");
                    option.value = element.id;
                    option.text = element.nama;
                    gudang.add(option);
                });

            })

        axios.get('/vendor/all')
            .then(function(response) {
                response.data.forEach(element => {
                    var gudang = document.getElementById("selectVendor");
                    var option = document.createElement("option");
                    option.value = element.id;
                    option.text = element.nama;
                    gudang.add(option);
                });

            })
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

    function tambahVendor() {
        var formData = new FormData(document.getElementById("formGudang"));
        axios({
            method: 'post',
            url: '/gudang',
            data: formData
        }).then(function(response) {
            if (response.data.status == 200)
                window.location.href = '/gudang';
            else
                alert('Gagal - Ada kode yang sama')
        }).catch(function(error) {
            alert('gagal');
        })
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

    function editVendor(val) {
        alert('Coming Soon');
    }

    function deleteGudang(val) {
        alert('Coming Soon');
    }
</script>

@endsection