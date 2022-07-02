<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Models\User;
use App\Notifications\NewOrder;
use App\Repositories\RegisteredRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Registered implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $SendEmails = (new RegisteredRepository())->index();

    if($SendEmails->isNotEmpty()){
        foreach ($SendEmails as $Email) {
            if($Email->media_type == 'admin'){
                $user= Admin::where("id", $Email->type_id)->first();
            }elseif ($Email->media_type == 'teacher'){
                $user= User::where("id", $Email->type_id)->first();
            }
            $arr = [ 'name' => $Email->full_name  ,'url'=>$Email->url,'username' => $Email->username  ,'Password' => $Email->password ,'email' => $Email->email ];
            $user->notify(new \App\Notifications\Registered($arr));
            $Email->status = 1;
            $Email->save();
            }
        }
    }
}
