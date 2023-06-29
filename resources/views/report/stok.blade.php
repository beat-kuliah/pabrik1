@extends('layouts.app')

@section('content')

<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" id="generate" class="btn btn-primary disabled" onclick="generatePDF()">
        Generate PDF
    </button>
    <h1>Report Stok</h1>
    <br><br>
</div>
<div class="container">
    <form name="formReportStok" id="formReportStok">
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Tanggal</label>
            <div class="col-sm-10">
                <input type="date" name="date" id="date" class="form-control" aria-label="Recipient's username" aria-describedby="button-addon2">
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Gudang</label>
            <div class="col-sm-10">
                <select name="gudang" class="form-select" id="selectGudang">
                    <option value="" selected>Select Gudang</option>
                </select>
            </div>
        </div>
    </form>
    <button onclick="loadReportStok()" class="btn btn-outline-secondary float-end" type="button" id="button-addon2">Filter</button>
    <br><br>
    <table id="report" class="display" style="display:none;width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok Awal</th>
                <th>Penjualan</th>
                <th>Stok Akhir</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Id</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok Awal</th>
                <th>Penjualan</th>
                <th>Stok Akhir</th>
            </tr>
        </tfoot>
    </table>
</div>

@endsection

@section('script')

<script>
    var tanggal;
    var gudang;

    $(document).ready(function() {
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
            '/report/stok/generate-pdf/' + tanggal + '/' + gudang,
            '_blank'
        )
    }

    function loadReportStok() {
        var formData = new FormData(document.getElementById('formReportStok'));
        if (formData.get('date') == '')
            alert('Harap Isi Tanggal!');
        else {
            var generate = document.getElementById("generate");
            generate.classList.remove("disabled");
            tanggal = formData.get('date');
            if (formData.get('gudang') == '')
                gudang = 'null';
            else
                gudang = formData.get('gudang');
            var reportStok = document.getElementById('report');
            reportStok.style.display = '';
            $('#report').dataTable().fnDestroy();
            $('#report').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/report/stok/datatables/' + tanggal + '/' + gudang,
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
                        data: 'penjualan'
                    },
                    {
                        data: 'stok_akhir'
                    }
                ],
                columnDefs: [{
                    target: [3],
                    className: "text-center",
                    render: function(data, type, row) {
                        return fixPrice(data);
                    }
                }]
            });
        }
    }
</script>

@endsection