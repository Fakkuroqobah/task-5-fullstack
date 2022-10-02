<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function res($code = 200, $msg = 'Berhasil', $data = [])
    {
        return response()->json([
            'message' => $msg,
            'data' => $data
        ], $code);
    }
    
    protected function errorFk()
    {
        return $this->res(422, 'Gagal', 'Tidak dapat menghapus, data ini digunakan oleh data lain');
    }
}
