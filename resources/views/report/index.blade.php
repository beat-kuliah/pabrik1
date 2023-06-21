@extends('layouts.app')

@section('content')

<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" class="btn btn-primary" onclick="generatePDF()">
        Generate PDF
    </button>
    <h1>Report</h1>
    <br><br>
</div>
<div class="container">
    <table id="report" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok Awal</th>
                <th>Stok AKhir</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Id</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok Awal</th>
                <th>Stok AKhir</th>
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
    var total = 0;


    $(document).ready(function() {
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

        $('#report').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/report/datatables',
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
                    data: 'stok_awal'
                },
                {
                    data: 'stok_akhir'
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
                target: [6],
                className: "text-center",
                render: function(data, type, row) {
                    var result = '<button type="button" onclick="generatePDF(' + data + ')" class="btn btn-success">PDF</button>';
                    result += '<span>   </span>';
                    result += '<button type="button" onclick="editVendor(' + data + ')" class="btn btn-warning">Edit</button>';
                    result += '<span>   </span>';
                    result += '<button type="button" onclick="deleteVendor(' + data + ')" class="btn btn-danger">Delete</button>';

                    return result;
                }
            }]
        });

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

    function generatePDF(val) {
        window.open(
            '/report/generate-pdf',
            '_blank'
        )
    }

    function editVendor(val) {
        alert('Coming Soon');
    }

    function deleteVendor(val) {
        alert('Coming Soon');
    }
</script>

@endsection