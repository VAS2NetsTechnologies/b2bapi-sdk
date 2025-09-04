<?php
namespace VAS2Nets\B2bSDK\Facades;

use Illuminate\Support\Facades\Facade;
use VAS2Nets\B2bSDK\B2bSDK;

class B2bSDKFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return B2bSDK::class;
    }
}

