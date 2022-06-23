<?php

namespace App\Exports;

use App\Models\ItemsList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;


class ExeclExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected  $data;
    protected $heading;
    protected $formats;


    public function headings(): array {
        return $this->heading;
    }

    public function columnFormats(): array {
        return $this->formats;
    }

    public function __construct( $data, array $heading = [], array $formats = [])
    {
        $this->data = $data;
        $this->heading =$heading;
        $this->formats =$formats;

    }


    public function collection()
    {
        return new Collection($this->data);
    }
}
