@extends('layouts.app')

@section('content')

<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPenjualan">
        Tambah Data
    </button>
    <h1>Penjualan</h1>
    <br><br>
    <form name="formFilter" id="formFilter">
        <div class="input-group mb-3">
            <div class="input-group mb-3">
                <input type="date" name="from" id="from" class="form-control" placeholder="From">
                <span class="input-group-text">-</span>
                <input type="date" name="to" id="to" class="form-control" placeholder="To">
                <button onclick="filtered()" class="btn btn-outline-secondary" type="button" id="button-addon2">Filter</button>
            </div>
        </div>
    </form>
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
@include('penjualan.edit')

@endsection

@section('script')

<script>
    var harga = 0;
    var stok_max = 0;
    var terjual = 0;
    var total = 0;
    var editId;
    var editBarangId;
    var editHarga = 0;
    var editStok_max = 0;
    var editTerjual = 0;
    var editTotal = 0;

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
                    var result = '<button type="button" onclick="generatePDF(' + data + ')" class="btn btn-success">PDF</button>';
                    result += '<span>   </span>';
                    result += '<button type="button" data-bs-toggle="modal" data-bs-target="#editPenjualan" onclick="editPenjualan(' + data + ')" class="btn btn-warning">Edit</button>';
                    result += '<span>   </span>';
                    result += '<button type="button" onclick="deletePenjualan(' + data + ')" class="btn btn-danger">Delete</button>';

                    return result;
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

        axios.get('/barang/all')
            .then(function(response) {
                response.data.forEach(element => {
                    var gudang = document.getElementById("selectEditBarang");
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

    $('#selectEditBarang').change(function() {
        var data = parseInt($(this).val());
        axios.get('/barang/find/' + data)
            .then(function(response) {
                document.getElementById("editNama").value = response.data.nama;
                document.getElementById("editHarga").value = fixPrice(response.data.harga);
                if (parseInt(data) == editBarangId) {
                    editStok_max = response.data.stok_akhir + editTerjual;
                } else {
                    editStok_max = response.data.stok_akhir;
                }
                document.getElementById("editStok").value = editStok_max;
                editHarga = response.data.harga;
                if (editTerjual >= editStok_max)
                    document.getElementById("editTerjual").value = editStok_max;
                var editTotal = $('#editTerjual').val() * editHarga;
                document.getElementById("editTotal").value = fixPrice(editTotal);
            });
    });

    $('#editTerjual').keyup(function(event) {
        editTerjual = $('#editTerjual').val();
        if (editTerjual >= editStok_max)
            document.getElementById("editTerjual").value = editStok_max;
        var editTotal = $('#editTerjual').val() * editHarga;
        document.getElementById("editTotal").value = fixPrice(editTotal);
    });

    function tambahPenjualan() {
        var formData = new FormData(document.getElementById("formPenjualan"));

        axios({
            method: 'post',
            url: '/penjualan',
            data: formData
        }).then(function(response) {
            window.location.href = '/penjualan';
        }).catch(function(error) {
            console.log(error);
            alert('gagal');
        })
    }

    function generatePDF(val) {
        window.open(
            '/penjualan/generate-pdf/' + val,
            '_blank'
        )
    }

    function editPenjualan(val) {
        editId = val;
        axios.get('/penjualan/show/' + val)
            .then(function(response) {
                editBarangId = response.data.barang_id;
                console.log(response.data);
                document.getElementById('editTanggal').value = response.data.tanggal;
                document.getElementById('editTerjual').value = response.data.terjual;
                document.getElementById('editTotal').value = fixPrice(response.data.terjual * response.data.barang.harga);
                $('#selectEditBarang').val(response.data.barang_id);
                document.getElementById("editNama").value = response.data.barang.nama;
                document.getElementById("editHarga").value = fixPrice(response.data.barang.harga);
                document.getElementById("editStok").value = response.data.barang.stok_akhir + response.data.terjual;
                editTerjual = response.data.terjual;
                editHarga = response.data.barang.harga;
                editStok_max = response.data.barang.stok_akhir + response.data.terjual;
            })
            .catch(function(error) {
                console.log(error);
                alert('Gagal');
            })
    }

    function updatePenjualan() {
        var formData = new FormData(document.getElementById('formEditPenjualan'));
        axios({
            url: '/penjualan/update/' + editId,
            method: 'post',
            data: formData,
        }).then(function(response) {
            if (response.data == 200) {
                alert('Success');
                window.location.href = '/penjualan';
            } else
                alert('Ada kode yang sama')
        }).catch(function(error) {
            alert('Gagal');
        })
    }

    function deletePenjualan(val) {
        if (confirm("Menghapus Data Vendor akan menghapus Data Barang beserta Penjualannya juga, Anda yakin?") == true) {
            penjualanDeleted(val);
        }
    }

    function penjualanDeleted(val) {
        axios.get('/penjualan/destroy/' + val)
            .then(function(response) {
                alert('Success');
                window.location.href = '/penjualan';
            })
            .catch(function(error) {
                alert('Gagal');
            })
    }

    function filtered() {
        var formData = $('#formFilter').serializeArray();
        if (formData[0].value == '' || formData[1].value == '')
            alert('Tolong isi tanggal!')
        else {
            $('#penjualan').dataTable().fnDestroy();
            $('#penjualan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/penjualan/datatables',
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
                        var result = '<button type="button" onclick="generatePDF(' + data + ')" class="btn btn-success">PDF</button>';
                        result += '<span>   </span>';
                        result += '<button type="button" data-bs-toggle="modal" data-bs-target="#editPenjualan" onclick="editPenjualan(' + data + ')" class="btn btn-warning">Edit</button>';
                        result += '<span>   </span>';
                        result += '<button type="button" onclick="deletePenjualan(' + data + ')" class="btn btn-danger">Delete</button>';

                        return result;
                    }
                }]
            });
        }
    }
</script>

@endsection