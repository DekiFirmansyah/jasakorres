<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Agenda Surat Bulan {{ date('F Y', strtotime($month)) }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-
    J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-
  Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-
  wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="text-center mt-2">
                <h5>PT JASAMARGA PANDAAN TOL</h5>
                <h5>LAPORAN AGENDA SURAT KELUAR</h5>
                <h5>Bulan {{ date('F Y', strtotime($month)) }}</h5>
            </div>
        </div>
    </div>
    <br><br>
    <div class="container-fluid">
        <table class="table table-bordered">
            <caption>Tabel Laporan Agenda Surat Keluar</caption>
            <tr class="text-center">
                <th>No</th>
                <th>Tanggal Surat</th>
                <th>No. Surat</th>
                <th>Perihal Surat</th>
                <th>Dikirim Kepada</th>
                <th>Tanggal Dikirim</th>
                <th>Keterangan</th>
            </tr>

            @foreach($notebooks as $key=>$notebook)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $notebook->letter->updated_at->format('d M Y') }}</td>
                <td>{{ $notebook->letter->document->letter_code }}</td>
                <td>{{ $notebook->letter->title }}</td>
                <td>{{ $notebook->destination_name }} - {{ $notebook->destination_address }}</td>
                <td>{{ $notebook->date_sent->format('d M Y') }}</td>
                <td class="text-center">{{ empty($notebook->description) ? '-' : $notebook->description }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</body>

</html>