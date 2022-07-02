<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Notifications\NewOrder;
use App\Repositories\SendEmailRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmail implements ShouldQueue
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
        $SendEmails = (new SendEmailRepository())->index();

        if($SendEmails->isNotEmpty()){
            foreach ($SendEmails as $Email) {
                $is_super= Admin::where("is_super_admin", 1)->where('email_verified_at', '!=' , null)->first();
                $arr = [ 'name' => $is_super->username  ,'title' => $Email->getTitleAttribute(), 'body' => $Email->getBodyAttribute()];
                if($is_super->is_super_admin)
                    $is_super->notify(new NewOrder($arr));
                $admins= Admin::where("is_super_admin", 0)->where('email_verified_at', '!=' , null)->get();
                foreach ($admins as $admin){
                    foreach ($admin->role->permissions as $permission) {
                        if ($permission->id == 18) {
                            $arr['name']=$admin->username;
                            $admin->notify(new NewOrder($arr));
                        }
                    }
                }
                $Email->status = 1;
                $Email->save();
            }
        }

    }
}
