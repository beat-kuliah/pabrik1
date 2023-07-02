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
        width: 128px;
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
        <h1>Penjualan Report</h1>
    </center>
    <h3>Tanggal : {{ $tanggal }}</h3>
    <h3>Dibuat : {{ $dibuat }}</h3>
    <table>
        <tr>
            <th>
                <h3>No</h3>
            </th>
            <th>
                <h3>Tanggal</h3>
            </th>
            <th>
                <h3>Kode</h3>
            </th>
            <th>
                <h3>Nama</h3>
            </th>
            <th>
                <h3>Harga</h3>
            </th>
            <th>
                <h3>Qty</h3>
            </th>
            <th>
                <h3>Total</h3>
            </th>
        </tr>
        @foreach ($penjualan as $p)
        <tr>
            <td>
                <h3>{{ $p['id'] }}</h3>
            </td>
            <td>
                <h3>{{ $p['tanggal'] }}</h3>
            </td>
            <td>
                <h3>{{ $p['kode'] }}</h3>
            </td>
            <td>
                <h3>{{ $p['nama'] }}</h3>
            </td>
            <td>
                <h3>{{ $p['harga'] }}</h3>
            </td>
            <td>
                <h3>{{ $p['qty'] }}</h3>
            </td>
            <td>
                <h3>{{ $p['total'] }}</h3>
            </td>
        </tr>

        @endforeach
    </table>
    <hr>
    <table>
        <tr>
            <td>
                <h3></h3>
            </td>
            <td>
                <h3></h3>
            </td>
            <td>
                <h3></h3>
            </td>
            <td>
                <h3></h3>
            </td>
            <td>
                <h3></h3>
            </td>
            <td>
                <h3>Total</h3>
            </td>
            <td>
                <h3>{{ $full }}</h3>
            </td>
        </tr>
    </table>
</body>

</html>