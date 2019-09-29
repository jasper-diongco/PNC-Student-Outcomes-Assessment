<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BackupRestore;
use Gate;

class BackupRestoreController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth');
    }


    public function index() {
        if(!Gate::check('isSAdmin')) {
            return abort(404, 'Page not found');
        }

        return view('maintenances.index');
    }

    public function backup() {
        $backup_restore = new BackupRestore();

        $backup_restore->EXPORT_DATABASE("localhost","root","","pnc_soa_final");
    }
}
