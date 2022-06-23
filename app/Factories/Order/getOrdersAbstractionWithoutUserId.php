<?php

namespace App\Factories\Order;

use App\Repositories\OrderRepository;

abstract class getOrdersAbstractionWithoutUserId extends getOrdersAbstraction
{
    public static function getAll($type, OrderRepository $repository){
        static::create($type, $repository);
        return static::$status->getAll();
    }

    public static function getOneDetails($type, $orderId, OrderRepository $repository){
        static::create($type, $repository);
        return static::$status->getOneDetails($orderId);
    }
}
