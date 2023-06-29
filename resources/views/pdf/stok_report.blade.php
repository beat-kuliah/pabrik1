<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body {
        border: 1px solid;
        padding: 20px;
    }

    th,
    td {
        width: 150px;
        text-align: center;
    }

    tr th {
        border-bottom: 1px solid
    }

    table.ttd-only tr th,
    table.ttd-only tr td {
        width: 300px;
    }

    table.ttd tr th,
    table.ttd tr td {
        width: 300px;
        text-align: justify;
    }
</style>

<body>
    <center>
        <h1>Stock Report</h1>
    </center>
    <h3>Gudang : {{ $gudang->nama }}</h3>
    <h3>Tanggal : {{ $tanggal }}</h3>
    <h3>Dibuat : {{ $dibuat }}</h3>
    <table>
        <tr>
            <th>
                <h3>No</h3>
            </th>
            <th>
                <h3>Kode</h3>
            </th>
            <th>
                <h3>Nama</h3>
            </th>
            <th>
                <h3>Stok Awal</h3>
            </th>
            <th>
                <h3>Terjual</h3>
            </th>
            <th>
                <h3>Stok Akhir</h3>
            </th>
        </tr>
        @foreach ($barang as $b)
        <tr>
            <td>
                <h3>{{ $b['id'] }}</h3>
            </td>
            <td>
                <h3>{{ $b['kode'] }}</h3>
            </td>
            <td>
                <h3>{{ $b['nama'] }}</h3>
            </td>
            <td>
                <h3>{{ $b['stok_awal'] }}</h3>
            </td>
            <td>
                <h3>{{ $b['penjualan'] }}</h3>
            </td>
            <td>
                <h3>{{ $b['stok_akhir'] }}</h3>
            </td>
        </tr>

        @endforeach
    </table>
    <hr>
    <center>
        <h2>Akhir Laporan - DIcetak oleh : Operational</h2>
    </center>
    <hr>
</body>

</html>