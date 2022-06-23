<?php

namespace App\Packeges\PdfGenerator;

use App\Packeges\PdfGenerator\Adapters\MpdfAdaptor;

abstract class IPdf
{
    protected string $filename = "doc.pdf";

    abstract public function make($html);
    abstract public function get();
    abstract public function setFileName(string $filename): IPdf;

    /**
     * @param string $filename
     */


}
