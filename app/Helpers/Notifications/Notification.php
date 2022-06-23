<?php

namespace App\Helpers\Notifications;

use Illuminate\Support\Facades\Config;

abstract class Notification
{
    protected string $name;
    protected array $titleArgs;
    protected array $bodyArgs;

    public function __construct(string $name){
        $this->name= $name;
        $this->titleArgs["en"] = [];
        $this->titleArgs["ar"] = [];
        $this->bodyArgs["en"] = [];
        $this->bodyArgs["ar"] = [];
    }

    public function setTitleArgs(array $args, $lang = "all"): Notification
    {
       if($lang == "all"){
           $this->titleArgs["en"] = $this->titleArgs["ar"] = $args;
       }else if(in_array($lang,Config::get("app.languages"))){
           $this->titleArgs[$lang] = $args;
       }
       return $this;
    }

    public function setBodyArgs(array $args, $lang = "all"): Notification
    {
        if($lang == "all"){
            $this->bodyArgs["en"] = $this->bodyArgs["ar"] = $args;
        }else if(in_array($lang,Config::get("app.languages"))){
            $this->bodyArgs[$lang] = $args;
        }
        return $this;
    }

    abstract public function send();
}
