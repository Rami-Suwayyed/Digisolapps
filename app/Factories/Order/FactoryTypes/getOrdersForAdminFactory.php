<?php

namespace App\Factories\Order\FactoryTypes;

use App\Factories\Order\getOrdersAbstractionWithoutUserId;
use App\Factories\Order\getOrdersAbstractionWithUserId;
use App\Factories\Order\Status\Admin\All;
use App\Factories\Order\Status\Admin\Canceled;
use App\Factories\Order\Status\Admin\Pending;
use App\Factories\Order\Status\Admin\Finish;
use App\Factories\Order\Status\Admin\UnderGuarantee;
use App\Factories\Order\Status\Admin\UnderProcessing;
use App\Repositories\OrderRepository;

class getOrdersForAdminFactory extends getOrdersAbstractionWithoutUserId
{

    /**
     * @throws \Exception
     */
    protected static function create($type, OrderRepository $repository){
        switch ($type){
            case "all":
                static::$status = new All($repository);
                break;
            case "pending":
                static::$status = new Pending($repository);
                break;
            case "under-processing":
                static::$status = new UnderProcessing($repository);
                break;
            case "canceled":
                static::$status = new Canceled($repository);
                break;
            case "finish":
                static::$status = new Finish($repository);
                break;
            case "under-guarantee":
                static::$status = new UnderGuarantee($repository);
                break;
            default:
                throw new \Exception("", 404);
                break;
        }
    }
    public static function getAll($type, OrderRepository $repository, $activation = 'all'){
        static::create($type, $repository);
        return static::$status->getAll($activation);
    }
}
