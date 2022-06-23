<?php

namespace App\Factories\Order\FactoryTypes;

use App\Factories\Order\getOrdersAbstractionWithUserId;
use App\Factories\Order\Status\User\CancelOrder;
use App\Factories\Order\Status\User\Finish;
use App\Factories\Order\Status\User\UnderGuarantee;
use App\Factories\Order\Status\User\UnderProcessing;
use App\Repositories\OrderRepository;

class getOrdersForUserFactory extends getOrdersAbstractionWithUserId
{

    /**
     * @throws \Exception
     */
    protected static function create($type, OrderRepository $repository){
        switch ($type){
            case "under-processing":
                static::$status = new UnderProcessing($repository);
                break;
            case "under-guarantee":
                static::$status = new UnderGuarantee($repository);
                break;
            case "finish":
                static::$status = new Finish($repository);
                break;
            case "cancel_order":
                static::$status = new CancelOrder($repository);
                break;
            default:
                throw new \Exception("", 404);
                break;
        }
    }



}
