<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class DownloadController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @return Response|string
     */
    public function downloadSample()
    {
        try {
            $file = Storage::disk('public')->get('excel/product-upload-pos.xlsx');
            return (new Response($file, 200))
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } catch (\Exception $ex) {
            return 'Error while downloading file.' . $ex;
        }
    }
}
