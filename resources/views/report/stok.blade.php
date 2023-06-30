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
                <div class="input-group mb-3">
                    <input type="date" name="from" id="from" class="form-control" placeholder="From">
                    <span class="input-group-text">-</span>
                    <input type="date" name="to" id="to" class="form-control" placeholder="To">
                </div>
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
    var from;
    var to;
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

    function generatePDF() {
        window.open(
            '/report/stok/generate-pdf/' + from + '/' + to + '/' + gudang,
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
                from = formData[0].value;
                to = formData[1].value;
                var generate = document.getElementById("generate");
                generate.classList.remove("disabled");
                if (formData[2].value == '')
                    gudang = 'null';
                else
                    gudang = formData[2].value;
                var reportStok = document.getElementById('report');
                reportStok.style.display = '';
                $('#report').dataTable().fnDestroy();
                $('#report').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/report/stok/datatables',
                        'data': formData
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
    }
</script>

@endsection