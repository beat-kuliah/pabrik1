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
    <table>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
        <tr>
            <td>1</td>
            <td>{{ $kode }}</td>
            <td>{{ $nama }}</td>
            <td>{{ $harga }}</td>
            <td>{{ $terjual }}</td>
            <td>{{ $total }}</td>
        </tr>
    </table>
    <div style="height: 300px;"></div>
    <hr>
    <table>
        <tr class="total">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total DP:</td>
            <td>{{ $total }}</td>
        </tr>
    </table>
    <br><br><br>
    <table class="ttd-only">
        <tr>
            <td>
                <h3>Counter</h3>
            </td>
            <td>
                <h3>Warehouse</h3>
            </td>
            <td>
                <h3>Diterima Oleh</h3>
            </td>
        </tr>
    </table>
    <div style="height: 100px;"></div>
    <table class="ttd">
        <tr>
            <td>
                <h3>(</h3>
            </td>
            <td>
                <h3>(</h3>
            </td>
            <td>
                <h3>)(</h3>
            </td>
            <td>
                <h3>)</h3>
            </td>
        </tr>
    </table>
</body>

</html>