<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;

class PDFController extends Controller
{
    public function generatePDF() 
    {
        $data = [
            'title' => 'Detalle Comprobante',
            'date' => date('m/d/Y')
        ];

        $pdf = PDF::loadView('myPDF', $data);

        return $pdf->download('Detalle_comprobante.pdf');
    }
}
