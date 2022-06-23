<?php
namespace App\Traits;
use App\Exports\ExeclExport;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

trait Helper{

    public function downloadExcelFile(array $columnsWellRetrived, $model , array $headings = [], $fileName = "excel.xlsx", $formats = []){
        $data = [];
        if($model instanceof \Illuminate\Support\Collection){
            $i=0;
            foreach ($model as $element){
                foreach ($columnsWellRetrived as $key => $column){
                    if($key === "custom"){
                        $data[$i][$column["name"]] = (string) $column["values"][$element->{$column["name"]}];
                    }else{
                        $data[$i][$column] = (string) $element->{$column};
                    }
                }
                $i++;
            }
        }else{
            foreach ($columnsWellRetrived as $key => $column){
                if($key === "custom"){
                    $data[$column["name"]] = (string) $column["values"][$model->$column["name"]];
                }else{
                    $data[$column] = (string) $model->{$column};
                }
            }
        }

       return Excel::download(new ExeclExport($data, $headings , $formats), $fileName);
    }
}
