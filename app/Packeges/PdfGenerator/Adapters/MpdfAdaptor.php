<?php

namespace App\Packeges\PdfGenerator\Adapters;
use App\Packeges\PdfGenerator\IPdf;
//ob_end_clean();

class MpdfAdaptor extends IPdf
{
    protected \Mpdf\Mpdf $mpdf;


    public function __construct()
    {
        $this->mpdf = new \Mpdf\Mpdf();
    }

    /**
     * @param string $filename
     */
    public function setFileName(string $filename): MpdfAdaptor
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @throws \Mpdf\MpdfException
     */
    public function make($html)
    {
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->writeHtml($html);
    }

    /**
     * @throws \Mpdf\MpdfException
     */
    public function get()
    {
        $this->mpdf->Output($this->filename, "I");
    }
}
