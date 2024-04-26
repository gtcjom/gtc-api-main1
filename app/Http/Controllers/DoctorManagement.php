<?php

namespace App\Http\Controllers;

use App\_DoctorManagement;
use Illuminate\Http\Request;

class DoctorManagement extends Controller
{
    public function getManagementListByMainId(Request $request)
    {
        return response()->json(_DoctorManagement::getManagementListByMainId($request));
    }

    public function getDoctorsPharmacyList(Request $request)
    {
        return response()->json(_DoctorManagement::getDoctorsPharmacyList($request));
    }

    public function hispharmacyGetStockList(Request $request)
    {
        return response()->json(_DoctorManagement::hispharmacyGetStockList($request));
    }

    public function hispharmacyGetSalesReport(Request $request)
    {
        return response()->json(_DoctorManagement::hispharmacyGetSalesReport($request));
    }

    public function hispharmacyGetFilterByDate(Request $request)
    {
        return response()->json(_DoctorManagement::hispharmacyGetFilterByDate($request));
    }

    public function getBillingRecords(Request $request)
    {
        return response()->json(_DoctorManagement::getBillingRecords($request));
    }

    public function getBillingRecordsDetails(Request $request)
    {
        return response()->json(_DoctorManagement::getBillingRecordsDetails($request));
    }

    public function getBillingRecordsByDate(Request $request)
    {
        return response()->json(_DoctorManagement::getBillingRecordsByDate($request));
    }
}
