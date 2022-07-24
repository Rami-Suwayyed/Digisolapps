<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;

class Settings
{
    protected static Settings $instance;
    protected $general;
    protected $zoneConfiguration;
    protected $paymentMethods;

    protected function __construct(){
        $this->general = GeneralSettings::first();
    }
    protected function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if(!isset(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * @return mixed
     */
    public function getGeneral()
    {
        return $this->general;
    }

    /**
     * @return mixed
     */
    public function getZoneConfiguration()
    {
        return $this->zoneConfiguration;
    }

    /**
     * @return PaymentMethod[]|Collection
     */
    public function getPaymentMethods()
    {
        return $this->paymentMethods;
    }


}
