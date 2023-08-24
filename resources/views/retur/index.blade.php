@extends('layouts.app')

@section('content')
<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRetur">
        Tambah Data
    </button>
    <h1>Retur Barang</h1>
    <br><br>
    <div class="table-responsive">
        <table id="retur" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>OldId</th>
                    <th>NewId</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Id</th>
                    <th>OldId</th>
                    <th>NewId</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th class="text-center">Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


@include('retur.create')
@include('retur.edit')

@endsection

@section('script')
<script>
    var check;
    var maxRetur = 0;
    var maxStok = 0;
    var maks = 0;
    var penjualanId;
    var editId;

    $(document).ready(function() {
        $('#retur').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/retur/datatables',
            columns: [{
                    data: 'id'
                },
                {
                    data: 'oldId'
                },
                {
                    data: 'total_retur'
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
                        return '@if (Auth::user()->role[0] == "ADMIN")<button type="button" data-bs-toggle="modal" data-bs-target="#updateRetur" onclick="updateRetur(' + data + ')" class="btn btn-warning">update</button><span>   </span><button type="button" onclick="deleteRetur(' + data + ')" class="btn btn-danger">Delete</button>@else noAccess @endif';
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
            ],
        });

        $(document).on('submit', '#createRetur', function() {

            var barangId = document.getElementById('barangId').value;
            penjualanId = barangId;
            axios.get('/retur/penjualan/' + barangId).then((response) => {
                dataAdded(response.data, barangId);
            }).catch((err) => {
                console.log(err);
            });

            return false;
        })
    });

    function dataAdded(temp, barangId) {
        if (temp == '') {
            axios.get('/penjualan/show/' + barangId).then((response) => {
                if (response.data == '') {
                    alert('Penjualan tidak ada');
                    check = false;
                    maxStok = 0;
                    maxRetur = 0;
                    document.getElementById('dataBarang').style.display = 'none';
                    document.getElementById("jmlretur").value = maks;
                } else {
                    console.log(response.data);
                    maxRetur = response.data.terjual;
                    document.getElementById('jumlah').value = response.data.terjual;
                    checkBarang(response.data.barang_id);
                    document.getElementById('dataBarang').style.display = 'block';
                    check = true;
                }
            }).catch((err) => {
                console.log(err);
            });
        } else {
            alert('Penjualan ini sudah diretur');
        }
    }

    function checkBarang(kodeBarang) {
        axios.get('/barang/find/' + kodeBarang).then((response) => {
            console.log(response.data);
            document.getElementById('kode').value = response.data.kode;
            document.getElementById('nama').value = response.data.nama;
            document.getElementById('stok').value = response.data.stok_akhir;
            maxStok = response.data.stok_akhir;
        }).catch((err) => {
            console.log(err)
        });
    }

    $('#jmlretur').keyup(function(event) {
        retur = $('#jmlretur').val();
        if (maxRetur > maxStok)
            maks = maxStok;
        else
            maks = maxRetur;

        if (retur >= maks)
            document.getElementById("jmlretur").value = maks;
    });

    $('#edit_jmlretur').keyup(function(event) {
        retur = $('#edit_jmlretur').val();
        if (maxRetur > maxStok)
            maks = maxStok;
        else
            maks = maxRetur;

        if (retur >= maks)
            document.getElementById("edit_jmlretur").value = maks;
    });

    function tambahRetur() {
        var formData = new FormData(document.getElementById('formRetur'));
        formData.set('barangId', penjualanId);
        if (check) {
            if (formData.get('barangId') == '')
                alert('Harap Isi Id Penjualan');
            else if (formData.get('jmlretur') == '')
                alert('Harap Isi Jumlah Barang yang Akan Diretur')
            else if (formData.get('jmlretur') == 0) {
                alert('Total Retur tidak boleh 0');
            } else {
                axios({
                    url: '/retur',
                    method: 'post',
                    data: formData,
                }).then((response) => {
                    window.location.href = '/retur';
                }).catch((err) => {
                    console.log(err);
                });
            }
        } else
            alert('Isi kode penjualan terlebih dahulu');
    }

    function updateRetur(val) {
        editId = val;
        var barang_id;
        axios.get('/retur/show/' + val).then((response) => {
            barang_id = response.data.penjualan.barang_id;
            setbarang(barang_id, response.data.total_retur);
            document.getElementById('edit_jumlah').value = response.data.penjualan.terjual;
            maxRetur = response.data.penjualan.terjual;
        });

    }

    function setbarang(val, retur) {
        axios.get('/barang/find/' + val).then((response) => {
            document.getElementById('edit_kode').value = response.data.kode;
            document.getElementById('edit_nama').value = response.data.nama;
            document.getElementById('edit_stok').value = response.data.stok_akhir + retur;
            maxStok = response.data.stok_akhir + retur;
        });
    }

    function editRetur() {
        var formData = new FormData();
        var total_retur = document.getElementById("edit_jmlretur").value;
        formData.set('total_retur', total_retur);

        if (total_retur != 0) {
            axios({
                url: '/retur/update/' + editId,
                method: 'post',
                data: formData
            }).then((response) => {
                window.location.href = '/retur';
            }).catch((err) => {
                console.log(err);
            });
        } else {
            alert('Total Retur tidak boleh 0');
        }
    }

    function deleteRetur(val) {
        if (confirm("Anda yakin?") == true) {
            returDeleted(val);
        }
    }

    function returDeleted(val) {
        axios.delete('/retur/' + val).then((response) => {
            window.location.href = '/retur';
        }).catch((err) => {
            console.log(err);
        });
    }
</script>
@endsection