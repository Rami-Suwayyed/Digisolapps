<?php

namespace App\Factories\Order\FactoryTypes;

use App\Factories\Order\getOrdersAbstractionWithUserId;
use App\Factories\Order\Status\Teacher\Canceled;
use App\Factories\Order\Status\Teacher\Finish;
use App\Factories\Order\Status\Teacher\Pending;
use App\Factories\Order\Status\Teacher\UnderGuarantee;
use App\Factories\Order\Status\Teacher\UnderProcessing;
use App\Repositories\OrderRepository;

class getOrdersForTeacherFactory extends getOrdersAbstractionWithUserId
{

    /**
     * @throws \Exception
     */
    protected static function create($type, OrderRepository $repository){
        switch ($type){
            case "pending":
                static::$status = new Pending($repository);
                break;
            case "under-processing":
                static::$status = new UnderProcessing($repository);
                break;
            case "canceled":
                static::$status = new Canceled($repository);
                break;
            case "under-guarantee":
                static::$status = new UnderGuarantee($repository);
                break;
            case "finish":
                static::$status = new Finish($repository);
                break;
            default:
                throw new \Exception("", 404);
                break;
        }
    }



}
