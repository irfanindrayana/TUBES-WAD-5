<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Barang</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
</head>

<body>
<div class="container mt-5">
    <h2>Stock Bahan</h2>
    <h4>(Inventory)</h4>
    <div class="data-tables datatable-dark">
        <table id="datatablesSimple" class="table table-striped">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Deskripsi</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($homes as $index)
                <tr>
                    <td>{{ $index->id }}</td>
                    <td>{{ $index->namaBarang }}</td>
                    <td>{{ $index->deskripsi }}</td>
                    <td>{{ $index->stok }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Tambahkan tombol kembali di sini -->
    <div class="mt-3 text-left">
        <a href="{{ route('home.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Home
        </a>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

<!-- DataTables Initialization -->
<script>
$(document).ready(function() {
    $('#datatablesSimple').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>

</body>
</html>