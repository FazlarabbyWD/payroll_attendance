<?php

namespace App\Http\Controllers\Test;

use App\Models\Device;
use Illuminate\Http\Request;
use Jmrashed\Zkteco\Lib\ZKTeco;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index(){

        $deviceIp = Device::get('ip_address');
        $devicePort = Device::get('port');

        $zk = new ZKTeco($deviceIp, $devicePort);

        $connected = $zk->connect();

        dd($connected); // This will output the connection status
    }
}
