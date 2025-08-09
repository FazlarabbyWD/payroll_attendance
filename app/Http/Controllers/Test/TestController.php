<?php
namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Jmrashed\Zkteco\Lib\ZKTeco;

class TestController extends Controller
{
    public function index()
    {

        $deviceIp = Device::where('status', 1)->value('ip_address');

        $zk = new ZKTeco($deviceIp);

        $connected = $zk->connect();

        $users = $zk->getUser();

        dd($users);

        // $uid = 167;

        // $removedUser = $zk->removeUser($uid);

        // dd($removedUser);

        // $uid=167;
        // $userid = 155;
        // $name = 'Test User';
        // $password=null;
        // $role=0;
        // $cardno='0000000000';

        // $setUserResult = $zk->setUser($uid, $userid, $name, $password, $role, $cardno);
        // dd($setUserResult);
    }
}
