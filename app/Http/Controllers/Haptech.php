<?php

namespace App\Http\Controllers;

use App\Models\_Haptech;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Haptech extends Controller
{
    public function hisHaptechHeaderInfo(Request $request)
    {
        return response()->json((new _Haptech)::hisHaptechHeaderInfo($request));
    }

    public function hisHaptechGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Haptech)::hisHaptechGetPersonalInfoById($request));
    }

    public function hisHaptechUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/haptech');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Haptech::hisHaptechUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisHaptechUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Haptech::hisHaptechUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisHaptechUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Haptech::hisHaptechUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisHaptechUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Haptech::hisHaptechUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    // new route for haptech - 7 -31 - 2021
    public static function drApprovedByHaptech(Request $request)
    {
        $result = _Haptech::drApprovedByHaptech($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getForApprovalDr(Request $request)
    {
        return response()->json((new _Haptech)::getForApprovalDr($request));
    }

    public static function getForApprovalDrDetails(Request $request)
    {
        return response()->json((new _Haptech)::getForApprovalDrDetails($request));
    }

    public static function invoiceApprovedByHaptech(Request $request)
    {
        $result = _Accounting::invoiceApprovedByHaptech($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);

    }

    public static function getInvoiceProducts(Request $request)
    {
        return response()->json((new _Haptech)::getInvoiceProducts($request));
    }


    public static function hisWarehouseGetRole(Request $request)
    {
        return response()->json((new _Haptech)::hisWarehouseGetRole($request));
    }


    public static function getPurchaseOrderProducts(Request $request)
    {
        return response()->json((new _Haptech)::getPurchaseOrderProducts($request));
    }
}
