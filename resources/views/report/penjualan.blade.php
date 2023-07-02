@extends('layouts.app')

@section('content')

<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" id="generate" class="btn btn-primary disabled" onclick="generatePDF()">
        Generate PDF
    </button>
    <h1>Report Penjualan</h1>
    <br><br>
</div>
<div class="container">
    <form name="formReportStok" id="formReportStok">
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Tanggal</label>
            <div class="col-sm-10">
                <div class="input-group mb-3">
                    <input type="date" name="from" id="from" class="form-control" placeholder="From">
                    <span class="input-group-text">-</span>
                    <input type="date" name="to" id="to" class="form-control" placeholder="To">
                </div>
            </div>
        </div>
    </form>
    <button onclick="loadReportStok()" class="btn btn-outline-secondary float-end" type="button" id="button-addon2">Filter</button>
    <br><br>
    <table id="report" class="display" style="display:none;width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </tfoot>
    </table>
</div>

@endsection

@section('script')

<script>
    var tanggal;
    var gudang;

    $(document).ready(function() {});

    function isNumberKeyCheck(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function generatePDF() {
        window.open(
            '/report/penjualan/generate-pdf/' + from + '/' + to + '/' + 2,
            '_blank'
        )
    }

    function loadReportStok() {
        var formData = $('#formReportStok').serializeArray();
        if (formData[0].value == '' || formData[1].value == '')
            alert('Harap Isi Tanggal!');
        else {
            var cekFrom = new Date(formData[0].value);
            var cekTo = new Date(formData[1].value);
            if (cekFrom.setHours(0, 0, 0, 0) > cekTo.setHours(0, 0, 0, 0))
                alert('Tanggal yang diisi salah!');
            else {
                var generate = document.getElementById("generate");
                generate.classList.remove("disabled");
                from = formData[0].value;
                to = formData[1].value;
                var reportStok = document.getElementById('report');
                reportStok.style.display = '';
                $('#report').dataTable().fnDestroy();
                $('#report').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/report/penjualan/datatables',
                        'data': formData
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'tanggal'
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
                            data: 'qty'
                        },
                        {
                            data: 'total'
                        }
                    ],
                    columnDefs: [{
                        target: [4],
                        className: "text-center",
                        render: function(data, type, row) {
                            return fixPrice(data);
                        }
                    }]
                });
            }
        }
    }
</script>

@endsection