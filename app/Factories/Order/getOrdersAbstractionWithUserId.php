<?php

namespace App\Factories\Order;

use App\Repositories\OrderRepository;

abstract class getOrdersAbstractionWithUserId extends getOrdersAbstraction
{

    public static function getAll($type, OrderRepository $repository, $userId){
        static::create($type, $repository);
        static::$status->setUserId($userId);
        return static::$status->getAll($userId);
    }

    public static function getOneDetails($type, $orderId, OrderRepository $repository, $userId){
        static::create($type, $repository);
        static::$status->setUserId($userId);
        return static::$status->getOneDetails($orderId, $userId);
    }
}
