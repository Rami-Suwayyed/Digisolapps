<?php


namespace App\Repositories;


use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Resources\SubCategoryResource;
use App\Http\Resources\TeacherSubCategoryResource;
use App\Models\AvailableTimesSubject;
use App\Models\MainCategory;
use App\Models\Subject;
use App\Models\SubCategory;
use App\Rules\CheckIsValidTime;
use App\Rules\CheckSliceTimeIsValid;
use Illuminate\Http\Request;

class TeacherSubCategoryRepository
{

    public function getSubCategoriesByMainCategory($mainCategoryId): array
    {
        $subCategories = SubCategory::selectBuilder()->byMainCategory($mainCategoryId)->ordering()->get();
        if($subCategories->count() == 1){
            $data["type"] = "subjects";
            $subjectRepository = new SubjectRepository();
            $data["content"] = $subjectRepository->getSubjectsBySubCategory($subCategories[0]->id);
            $data["sub_category_id"] = $subCategories[0]->id;
        }else{
            $data[] = TeacherSubCategoryResource::collection($subCategories);
        }
        return $data;

    }

    public function rules(Request $request): array
    {
        return [
            "name_en" => ["required","max:255"],
            "name_ar" => ["required","max:255"],
            "days"          => ["required"],
            "time_from"     => ["required", new CheckIsValidTime()],
            "time_to"       => ["required", new CheckIsValidTime(), "after:time_from", "after:08:00","before_or_equal:23:59"],
            "time_slice"    => ["required", "numeric", "min:30", "max:360", new CheckSliceTimeIsValid($request->time_from, $request->time_to)],
            "times"         => ["required", "array"],
            "sub_category_photo" => ["required","image","max:512"],
        ];
    }


    public function columns(): array
    {
        return [
            "name_en" => "english sub category name",
            "name_ar" => "arabic sub category name",
        ];
    }

    public function store(Request $request){
        $lastCategory = SubCategory::selectBuilder()->byMainCategory($request->main_id)->ordering("desc")->first();
        $category = new SubCategory();
        $category->name_en = $request->name_en;
        $category->name_ar = $request->name_ar;
        $category->main_category_id =  $request->main_id;
        $category->order = $lastCategory ? $lastCategory->order + 1 : 1;
        $category->save();
        $category->saveMedia($request->file("sub_category_photo"));
        $this->saveDaysAvailableSubject($request, $category);
        $this->saveTimesAvailableSubject($request, $category);

    }

    public function update(Request $request, $category){
        $category->name_en = $request->name_en;
        $category->name_ar = $request->name_ar;
        $category->save();
        if($request->hasFile("sub_category_photo")){
            if($category->getFirstMediaFile())
                $category->removeMedia($category->getFirstMediaFile());;
            $category->saveMedia($request->file("sub_category_photo"));
        }
        $this->saveDaysAvailableSubject($request, $category);
        $start = $category->times()->orderBy("start_time", "asc")->first();
        $end = $category->times()->orderBy("end_time", "desc")->first();
        $slice = (strtotime($start->end_time) - strtotime($start->start_time)) / 60;
        if($request->time_slice != $slice || $request->time_from  != date("H:i", strtotime($start->start_time)) || $request->time_to  != date("H:i", strtotime($end->end_time))){
            $category->times()->delete();
            $this->saveTimesAvailableSubject($request, $category);
        }else{
            $times = [];
            foreach ($request->times as $time){
                $times[$time] = true;
            }
            $this->updateTimes($category->times, $times);
        }
    }

    protected function saveDaysAvailableSubject($request, $category){
        $daysAvailable = $category->getDaysAvailableModel();
        $daysAvailable->sunday = isset($request->days["sun"]);
        $daysAvailable->monday = isset($request->days["mon"]);
        $daysAvailable->tuesday = isset($request->days["tue"]);
        $daysAvailable->wednesday = isset($request->days["wed"]);
        $daysAvailable->thursday = isset($request->days["thur"]);
        $daysAvailable->friday = isset($request->days["fri"]);
        $daysAvailable->saturday = isset($request->days["sat"]);
        $daysAvailable->save();
    }


    protected function saveTimesAvailableSubject($request, $category){
        $slice = $request->time_slice / 60;
        $parts = explode(":", $request->time_from, 2);
        $timeFrom = floatval($parts[0] . "." . (intval($parts[1]) * 10 / 60));
        $parts = explode(":", $request->time_to, 2);
        $timeTo = floatval($parts[0] . "." . (intval($parts[1]) * 10 / 60));
        $times = array_flip($request->times);
        for ($start = $timeFrom; $start < $timeTo; $start += $slice){

            $floatingPoint =  ($start - floor($start));
            $startHour = $start - $floatingPoint;
            $startMinutes = ($floatingPoint * 60);

            $end = $start + $slice;
            $floatingPoint =  ($end - floor($end));
            $endHour = $end - $floatingPoint;
            $endMinutes = ($floatingPoint * 60);
            if($endHour == 24){
                $endHour = 23;
                $endMinutes = 59;
            }

            $startMinutes =  $startMinutes < 10 ? "0" . $startMinutes : $startMinutes;
            $startHour =  $startHour < 10 ? "0" . $startHour : $startHour;
            $from = $startHour . ":" . $startMinutes;

            $endMinutes =  $endMinutes < 10 ? "0" . $endMinutes : $endMinutes;
            $endHour =  $endHour < 10 ? "0" . $endHour : $endHour;
            $to = $endHour . ":" . $endMinutes;
            $this->saveTime($from, $to, !isset($times[$from]), $category->id);
        }

    }

    protected function saveTime($from, $to, $is_blocked,$category_id) {
        $timeAvailable = new AvailableTimesSubject();
        $timeAvailable->start_time = $from;
        $timeAvailable->end_time = $to;
        $timeAvailable->category_id = $category_id;
        $timeAvailable->blocked = $is_blocked;
        $timeAvailable->save();
    }

    public function updateTimes($times, $selectedTimes){
        foreach ($times as $time){
            if(isset($selectedTimes[$time->start_time])){
                if($time->blocked){
                    $time->blocked = false;
                    $time->save();
                }
            }else{
                if(!$time->blocked){
                    $time->blocked = true;
                    $time->save();
                }
            }
        }
    }


    public function delete($subCategory){
        if($subCategory->subjects->isNotEmpty())
            foreach ($subCategory->subjects as $subject)
                (new SubjectRepository())->delete($subject);
        $subCategory->removeAllFiles();
        $subCategory->delete();
    }



}
