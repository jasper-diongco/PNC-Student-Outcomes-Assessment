@extends('layout.app', ['active' => 'maintenance'])

@section('title', 'Application Maintenance')

@section('content')
    

    <div class="card">
        <div class="card-body py-3">
            <h1 class="page-header mb-0"><i class="fa fa-cog"></i> Application Maintenance</h1>
        </div>
    </div>


    <div class="card mt-4">
        <div class="card-body py-3">
            <h4>Backup</h4>
            <p class="text-info">Create backup of the current database</p>
            <form action="http://localhost/backup_restore/backup.php" method="GET">
                <button class="btn btn-info">Backup now <i class="fa fa-download"></i></button>
            </form>
        </div>
    </div>


    <div class="card mt-4">
        <div class="card-body py-3">
            <h4>Restore</h4>
            <p class="text-info">Upload the sql file and restore the application.</p>
            <form action="http://localhost/backup_restore/restore.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" required>
                <button class="btn btn-info">Restore now <i class="fa fa-upload"></i></button>
            </form>
        </div>
    </div>
@endsection