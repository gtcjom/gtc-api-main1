<?php

namespace App\Http\Controllers;

use App\_Chat;
use Illuminate\Http\Request;

class Chat extends Controller
{
    //
    public static function getManagementUser(Request $request)
    {
        return response()->json(_Chat::getManagementUser($request));
    }

    public function getConversation(Request $request)
    {
        return response()->json(_Chat::getConversation($request));
    }

    public function newMessageConversation(Request $request)
    {
        $result = (new _Chat)::newMessageConversation($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function updateMessageToRead(Request $request)
    {
        $result = (new _Chat)::updateMessageToRead($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function getUnreadMsgCountBySenderId(Request $request)
    {
        return response()->json(_Chat::getUnreadMsgCountBySenderId($request));
    }
}
