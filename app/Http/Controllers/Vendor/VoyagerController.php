<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VoyagerController extends Controller
{
    public function sort(Request $request, $table = null, $column = null)
    {
        if ($request->isMethod('post')) {
            foreach (request()->get('order') as $key => $order) {
                $item = DB::table($table)->where('id', $order)->update([$column => 1]);
            }
            return 'Sıralandı.';
        } else {
            if ($table) {
                $type = 'sorting';
                $table_name = $table;
                $module = DB::table('data_types')->where('name', $table_name)->first();
                $module_name = $module->display_name_plural;
                $sortables = DB::table($table)->orderBy($column, 'asc')->get();
            } else {

                $type = 'dbselect';
                $table_name = '';
                $module = [];
                $module_name = 'Sıralamak için içerik seçiniz.';
                $tablename = 'Tables_in_' . ENV('DB_DATABASE', 'table');
                $sortables = [];
                $tables = DB::select('SHOW TABLES');
                foreach ($tables as $key => $table) {
                    $table = $table->{$tablename};
                    //$sortables[$table] = DB::select($table)->get();
                    $sortables[$table] = DB::getSchemaBuilder()->getColumnListing($table);
                }
            }
            return view('/vendor/voyager/sort', compact('sortables', 'type', 'table_name', 'module', 'module_name', 'column'));
        }

    }
}
