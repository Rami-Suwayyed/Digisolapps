<?php

namespace App\Packeges\PdfGenerator;

use Illuminate\Support\Facades\View;

class PdfGenerator
{

    protected IPdf $pdfGenerator;
    public function __construct(IPdf $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    public function createPdf($document, $data, $filename = "doc"){
        $filename = rtrim($filename, ".pdf") . ".pdf";
        $view = "documents::pdf/" . $document;
        $html = View::make($view, $data)->render();
        $this->pdfGenerator->setFileName($filename)->make($html);

    }

    public function viewPdf()
    {
        $this->pdfGenerator->get();
    }
}
