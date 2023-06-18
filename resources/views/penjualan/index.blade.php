@extends('layouts.app')

@section('content')

<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPenjualan">
        Tambah Data
    </button>
    <h1>Penjualan</h1>
    <br><br>
    <table id="penjualan" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Id</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th class="text-center">Action</th>
            </tr>
        </tfoot>
    </table>
</div>

@include('penjualan.create')

@endsection

@section('script')

<script>
    var harga = 0;
    var stok_max = 0;
    var terjual = 0;

    $(document).ready(function() {
        $('#penjualan').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/penjualan/datatables',
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
                    data: 'harga'
                },
                {
                    data: 'jumlah'
                },
                {
                    data: 'total'
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'action'
                }
            ],
            columnDefs: [{
                target: [3],
                className: "text-center",
                render: function(data, type, row) {
                    return fixPrice(data);
                }
            }, {
                target: [5],
                className: "text-center",
                render: function(data, type, row) {
                    return fixPrice(data);
                }
            }, {
                target: [6],
                className: "text-center",
                render: function(data, type, row) {
                    return fixDateOnly(data);
                }
            }, {
                target: [7],
                className: "text-center",
                render: function(data, type, row) {
                    return '<button type="button" onclick="editVendor(' + data + ')" class="btn btn-warning">Edit</button><span>   </span><button type="button" onclick="deleteVendor(' + data + ')" class="btn btn-danger">Delete</button>';
                }
            }]
        });

        axios.get('/barang/all')
            .then(function(response) {
                response.data.forEach(element => {
                    var gudang = document.getElementById("selectBarang");
                    var option = document.createElement("option");
                    option.value = element.id;
                    option.text = element.nama;
                    gudang.add(option);
                });
            })

    });

    function isNumberKeyCheck(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    $('#terjual').keyup(function(event) {
        terjual = $('#terjual').val();
        if (terjual >= stok_max)
            document.getElementById("terjual").value = stok_max;
        var total = $('#terjual').val() * harga;
        document.getElementById("total").value = fixPrice(total);
    });

    $('#selectBarang').change(function() {
        var data = $(this).val();
        axios.get('/barang/find/' + data)
            .then(function(response) {
                document.getElementById("nama").value = response.data.nama;
                document.getElementById("harga").value = fixPrice(response.data.harga);
                document.getElementById("stok").value = response.data.stok_akhir;
                harga = response.data.harga;
                stok_max = response.data.stok_akhir;
            });
    });

    function tambahPenjualan() {
        var formData = new FormData(document.getElementById("formVendor"));

        axios({
            method: 'post',
            url: '/penjualan',
            data: formData
        }).then(function(response) {
            if (response.data.status == 200)
                window.location.href = '/penjualan';
            else
                alert('Gagal - Ada kode yang sama')
        }).catch(function(error) {
            alert('gagal');
        })
    }

    function editVendor(val) {
        alert('Coming Soon');
    }

    function deleteVendor(val) {
        alert('Coming Soon');
    }
</script>

@endsection