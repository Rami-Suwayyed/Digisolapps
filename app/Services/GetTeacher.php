<?php

namespace App\Services;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Models\SubjectsTeachers;
use App\Models\Teacher;
use App\Models\Timer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetTeacher
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function ChekTeacher(Request $request)
    {
        $subjects_teachers = SubjectsTeachers::where("subject_id", $request->id)->get();
        $data = [];
        foreach ($subjects_teachers as $index => $teacher) {
            $data[$index]['id'] = $teacher->teacher->user->id;
            $data[$index]['name'] = $teacher->teacher->user->full_name;
//            $data[$index]["email"] = $teacher->teacher->user->email;
//            $data[$index]['phone_number'] = $teacher->teacher->phone_number_1;
//            $data[$index]['phone_number2'] = $teacher->teacher->phone_number_2;
            $data[$index]['ues_price'] = $teacher->teacher->ues_price;
            $data[$index]['rating'] = $teacher->teacher->user->rating->avg("number_rating");
//            $data[$index]["gender"] = $teacher->teacher->user->gender;
//            $data[$index]["birth_date"] = $teacher->teacher->user->birth_date;
//            $data[$index]['experience_years'] = $teacher->teacher->experience_years;
            $data[$index]['image_url'] = $teacher->teacher->user->getFirstMediaFile('profile_photo')->url;
        }
        return JsonResponse::data($data)->send();
    }


    public function ChekLocation(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $subject_id = $request->id;
        $start_time = "09:00:00";
        $end_time = "10:00:00";
        $teacher_id = 319;
        $Jid = 0;
//        dd($subject_id);
//
        $teachers = DB::table('users')
            ->join('teachers', 'users.id', '=', 'teachers.user_id')
            ->join('subjects_teachers', 'teachers.id', '=', 'subjects_teachers.teacher_id')
            ->where('users.type', '=', "t")
            ->where('subjects_teachers.subject_id', '=', $subject_id)
            ->whereNotExists(function($q) use ($Jid){
                $q->select(DB::raw(1))
                    ->from('order_teachers_requests')
                    ->whereRaw('order_teachers_requests.teacher_id = teachers.user_id')
                    ->whereRaw("order_teachers_requests.order_id = {$Jid->id}");
            })
            ->get();

//        $teachers=  User::where("type","t")->select(DB::raw('*, ( 6367 * acos( cos( radians('.$lat.') ) * cos( radians( latitude ) ) * cos(
//   radians( longitude ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( latitude ) ) ) ) AS
//   distance'))
//        ->having('distance', '<', 25)
//        ->orderBy('distance')
//        ->get();

//        dd($teachers);


//
//        $teachers = DB::table('users')
//            ->join('teachers', 'users.id', '=', 'teachers.user_id')
//            ->join('subjects_teachers', 'teachers.id', '=', 'subjects_teachers.teacher_id')
//            ->lJoin('rating', 'users.id', '=', 'rating.user_id')
//            ->where('users.type', '=', "t")
//            ->where('subjects_teachers.subject_id', '=', $subject_id)
//            ->get();


        $data = [];
        foreach ($teachers as $index => $teacher) {
            $timer = new Timer();
            $timer->Jid = $Jid;
            $timer->subject_id = $subject_id;
            $timer->teacher_id = $teacher_id;
            $timer->teacher_name = $teacher->full_name;
            $timer->location = "test";
            $timer->rating = 3;
            $timer->start_time = $start_time;
            $timer->end_time = $end_time;
            $timer->price = 100;
            $timer->ues_price = 1;
            $timer->save();
            $data[$index]['id'] = $teacher->user_id;
            $data[$index]['name'] = $teacher->full_name;
            $data[$index]['ues_price'] = $teacher->ues_price;
//            $data[$index]['rating'] = $teacher->rating->avg("number_rating");
//            $data[$index]['image_url'] = $teacher->getFirstMediaFile('profile_photo')->url;
        }


        return JsonResponse::data($data)->send();
    }


    public function gitallteacher(Request $request)
    {
        $Jid = 0;
        $teachers = Timer::where('Jid', $Jid)->get();
//        dd($teachers);
        $data = [];
        foreach ($teachers as $index => $teacher) {
            $data[$index]['id'] = $teacher->id;
            $data[$index]['teacher_id'] = $teacher->teacher_id;
            $data[$index]['name'] = $teacher->teacher_name;
            $data[$index]['location'] = $teacher->location;
            $data[$index]['rating'] = $teacher->rating;
            $data[$index]['price'] = $teacher->price;
            $data[$index]['ues_price'] = $teacher->ues_price;
            //            $data[$index]['rating'] = $teacher->rating->avg("number_rating");
//            $data['image'] = $teacher->getFirstMediaFile('profile_photo')->url;
        }

        return JsonResponse::data($data)->send();
    }

}
