<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TomatoPHP\LaravelAgora\Services\Agora;
use Willywes\AgoraSDK\RtcTokenBuilder;
class AgoraController extends Controller
{

    public function test()
    {
        return Agora::make(3)->uId(rand(999, 1999))->channel('custom')->token();
    }

    public static function GetToken(){

        $user_id = 130;
        $appID = "760d1e80b85f47d49269ed88c187286a";
        $appCertificate = "9439125fe2904241924e3a3d28cf6785";
        $channelName = "agora-test";
        $uid = $user_id;
        $uidStr = ($user_id) . '';
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = (new \DateTime("now", new \DateTimeZone('UTC')))->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;



        return RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);

    }



}
