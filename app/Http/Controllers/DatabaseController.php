<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DatabaseController extends Controller
{
    public function truncateTables(Request $request)
    {
        $tables = $request->tables ?? [];

        if (empty($tables)) {
            return response()->json(['message' => 'No tables selected'], 400);
        }

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            foreach ($tables as $table) {
                DB::table($table)->truncate();
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json(['message' => 'Selected tables truncated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error truncating tables: ' . $e->getMessage()], 500);
        }
    }
}
