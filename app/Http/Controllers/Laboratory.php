<?php

namespace App\Http\Controllers;

use App\Models\_Laboratory;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Laboratory extends Controller
{
    public function hislabGetHeaderInfo(Request $request)
    {
        return response()->json((new _Laboratory)::hislabGetHeaderInfo($request));
    }

    public function laboratoryCounts(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryCounts($request->patient_id));
    }

    public function laboratoryWithResult(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryWithResult($request->patient_id));
    }

    public function laboratoryOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryOrderDetails($request->lab_id));
    }

    public function laboratoryOngoing(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryOngoing($request->patient_id));
    }

    public function laboratoryUnprocess(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryUnprocess($request->patient_id));
    }

    public function laboratoryPending(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryPending($request->patient_id));
    }

    public function laboratoryDetails(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryDetails($request->management_id));
    }

    public function createOrder(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::createOrder($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function addLabOrder(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::addLabOrder($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function cancelLabOrder(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::cancelLabOrder($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getNewOrder(Request $request)
    {
        $laboratoryid = (new _Laboratory)::getLaboratoryId($request->user_id);
        return response()->json((new _Laboratory)::getNewOrder($laboratoryid));
    }

    public function getPendingOrder(Request $request)
    {
        $laboratoryid = (new _Laboratory)::getLaboratoryId($request->user_id);
        return response()->json((new _Laboratory)::getPendingOrder($laboratoryid));
    }

    public function getProcessingOrder(Request $request)
    {
        $laboratoryid = (new _Laboratory)::getLaboratoryId($request->user_id);
        return response()->json((new _Laboratory)::getProcessingOrder($laboratoryid));
    }

    public function orderSetProcess(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::orderSetProcess($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function orderSetPending(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::orderSetPending($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function getLaboratoryByPatient(Request $request)
    {
        return response()->json((new _Laboratory)::getLaboratoryByPatient($request));
    }

    public function addResult(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $filename = '';
            if (!empty($request->attachment)) {
                $attachment = $request->file('attachment');
                $destinationPath = public_path('../images/laboratory');
                $filename = time() . '.' . $attachment->getClientOriginalExtension();
                $attachment->move($destinationPath, $filename);
            }
            if ((new _Laboratory)::addResult($request, $filename)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function getCounts(Request $request)
    {
        $laboratoryid = (new _Laboratory)::getLaboratoryId($request->user_id);
        return response()->json((new _Laboratory)::getCounts($laboratoryid));
    }

    public function getAllRecords(Request $request)
    {
        $laboratoryid = (new _Laboratory)::getLaboratoryId($request->user_id);
        return response()->json((new _Laboratory)::getAllRecords($laboratoryid));
    }

    public function getAllTest(Request $request)
    {
        return response()->json((new _Laboratory)::getAllTest($request));
    }

    public function saveNewTest(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::saveNewTest($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function editTest(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::editTest($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getUnpaidLabOrder(Request $request)
    {
        return response()->json((new _Laboratory)::getUnpaidLabOrder($request));
    }

    // hemarthology laboratory

    public function getOrderHemathologyNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderHemathologyNew($request));
    }

    public function getOrderHemathologyNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderHemathologyNewDetails($request));
    }

    public function saveHemaOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveHemaOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function setHemaOrderPending(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::setHemaOrderPending($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function setHemaOrderProcessed(Request $request)
    {
        $result = (new _Laboratory)::setHemaOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json('reagent-error');
        }
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getCompleteHemathologyOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteHemathologyOrderDetails($request));
    }

    // sorology order
    public function getOrderSorologyNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderSorologyNew($request));
    }

    public function getOrderSorologyNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderSorologyNewDetails($request));
    }

    public function saveSorologyOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveSorologyOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function setSorologyOrderProcessed(Request $request)
    {
        $result = (new _Laboratory)::setSorologyOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json('reagent-error');
        }
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function setSorologyOrderPending(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::setSorologyOrderPending($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getCompleteSoroOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteSoroOrderDetails($request));
    }

    // clinical microscopy
    public function getOrderClinicalMicroscopyNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderClinicalMicroscopyNew($request));
    }

    public function getOrderClinicalMicroscopyNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderClinicalMicroscopyNewDetails($request));
    }

    public function setClinicMicrosopyOrderProcessed(Request $request)
    {
        $result = (new _Laboratory)::setClinicMicrosopyOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json('reagent-error');
        }
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function setClinicMicrosopyOrderPending(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::setClinicMicrosopyOrderPending($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function saveClinicalMicroscopyOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveClinicalMicroscopyOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getCompleteClinicalMicroscopyOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteClinicalMicroscopyOrderDetails($request));
    }

    // fecal analysis
    public function getOrderFecalAnalysisNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderFecalAnalysisNew($request));
    }

    public function getOrderFecalAnalysisNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderFecalAnalysisNewDetails($request));
    }

    public function setFecalAnalysisOrderPending(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::setFecalAnalysisOrderPending($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function setFecalAnalysisOrderProcessed(Request $request)
    {
        $result = (new _Laboratory)::setFecalAnalysisOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json('reagent-error');
        }

        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function saveFecalAnalysisOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveFecalAnalysisOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getCompleteFecalAnalysisOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteFecalAnalysisOrderDetails($request));
    }

    //clinical chemistry
    public function getOrderClinicalChemistryNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderClinicalChemistryNew($request));
    }

    public function getOrderClinicalChemistryNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderClinicalChemistryNewDetails($request));
    }

    public function setClinicChemistryOrderProcessed(Request $request)
    {
        $result = (new _Laboratory)::setClinicChemistryOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json('reagent-error');
        }
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    public function setClinicChemistryOrderPending(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::setClinicChemistryOrderPending($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }
    public function saveClinicalChemistryOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveClinicalChemistryOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    public function getCompleteChemOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteChemOrderDetails($request));
    }
    public function getNewOrderCountByDept(Request $request)
    {
        return response()->json((new _Laboratory)::getNewOrderCountByDept($request));
    }

    public function getLaboratoryCompletedReport(Request $request)
    {
        return response()->json((new _Laboratory)::getLaboratoryCompletedReport($request));
    }

    public function getLabPatientsWithNewOrder(Request $request)
    {
        $result = (new _Laboratory)::getLabPatientsWithNewOrder($request);
        return response()->json($result);
    }

    public function getLaboratoryByPatientTest(Request $request)
    {
        return response()->json((new _Laboratory)::getLaboratoryByPatientTest($request));
    }

    public function getLabFormHeader(Request $request)
    {
        return response()->json((new _Laboratory)::getLabFormHeader($request));
    }

    public function hislabGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Laboratory)::hislabGetPersonalInfoById($request));
    }

    public function hislabUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Laboratory::hislabUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hislabUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/laboratory');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Laboratory::hislabUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hislabUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Laboratory::hislabUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hislabUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Laboratory::hislabUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hislabGetLabFormHeader(Request $request)
    {
        return response()->json((new _Laboratory)::hislabGetLabFormHeader($request));
    }

    // new laboratory

    public function newLaboratoryItem(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::newLaboratoryItem($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'pass-invalid',
        ]);
    }

    public function laboratoryItemList(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryItemList($request));
    }

    public function laboratoryItemListByBatches(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryItemListByBatches($request));
    }

    public function laboratoryItemDeliveryTemp(Request $request)
    {
        $result = _Laboratory::laboratoryItemDeliveryTemp($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'pass-invalid',
        ]);
    }

    public function laboratoryItemDeliveryTempList(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryItemDeliveryTempList($request));
    }

    public function laboratoryItemDeliveryTempRemove(Request $request)
    {
        $result = _Laboratory::laboratoryItemDeliveryTempRemove($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function laboratoryItemDeliveryTempProcess(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryItemDeliveryTempProcess($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getLaboratoryItemsInventory(Request $request)
    {
        return response()->json((new _Laboratory)::getLaboratoryItemsInventory($request));
    }

    public static function getItemMonitoring(Request $request)
    {
        return response()->json((new _Laboratory)::getItemMonitoring($request));
    }

    public function saveTempOrderNoItem(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::saveTempOrderNoItem($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getLabOrderTemp(Request $request)
    {
        return response()->json((new _Laboratory)::getLabOrderTemp($request));
    }

    public static function getOrdersItems(Request $request)
    {
        return response()->json((new _Laboratory)::getOrdersItems($request));
    }

    public function saveOrderItem(Request $request)
    {

        $result = _Laboratory::saveOrderItem($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function processTempOrderWithItems(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::processTempOrderWithItems($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getLaboratoryOrderList(Request $request)
    {
        return response()->json((new _Laboratory)::getLaboratoryOrderList($request));
    }

    public static function getLaboratoryOrderListItems(Request $request)
    {
        return response()->json((new _Laboratory)::getLaboratoryOrderListItems($request));
    }

    public static function laboratoryHemathologyOrderProcessed(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryHemathologyOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function laboratorySerologyOrderProcessed(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratorySerologyOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function laboratoryMicroscopyOrderProcessed(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryMicroscopyOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function laboratoryFecalAnalysisOrderProcessed(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryFecalAnalysisOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function laboratoryOrderUsesThisItem(Request $request)
    {
        return response()->json((new _Laboratory)::laboratoryOrderUsesThisItem($request));
    }

    public static function laboratoryChemistryOrderProcessed(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryChemistryOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }


    // stooltest order start
    public function getOrderStoolTestNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderStoolTestNew($request));
    }

    public function getOrderStoolTestNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderStoolTestNewDetails($request));
    }

    public function saveStoolTestOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveStoolTestOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function setStoolTestOrderProcessed(Request $request)
    {
        $result = (new _Laboratory)::setStoolTestOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json('reagent-error');
        }
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function setStoolTestOrderPending(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _Laboratory)::setStoolTestOrderPending($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getCompleteStoolTestOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteStoolTestOrderDetails($request));
    }

    public static function laboratoryStooltestOrderProcessed(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryStooltestOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }
    // stooltest order end


    //papsmear
    public function getOrderPapsmearTestNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderPapsmearTestNew($request));
    }
    public function getOrderPapsmearTestNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderPapsmearTestNewDetails($request));
    }
    public static function laboratoryPapsmeartestOrderProcessed(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryPapsmeartestOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function savePapsmearTestOrderResult(Request $request)
    {
        if ((new _Laboratory)::savePapsmearTestOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getCompletePapsmearOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompletePapsmearOrderDetails($request));
    }

    //urinalysis
    public function getOrderUrinalysis(Request $request){
        return response()->json((new _Laboratory)::getOrderUrinalysis($request));
    }
    public function getOrderUrinalysisDetails(Request $request){
        return response()->json((new _Laboratory)::getOrderUrinalysisDetails($request));
    }
    public function saveUrinalysisOrderResult(Request $request){
        if ((new _Laboratory)::saveUrinalysisOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    public static function setUrinalysisOrderProcessed(Request $request){
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Laboratory::setUrinalysisOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }
    public function getCompleteUrinalysisOrderDetails(Request $request){
        return response()->json((new _Laboratory)::getCompleteUrinalysisOrderDetails($request));
    }

    //ecg
    public function getOrderEcg(Request $request){
        return response()->json((new _Laboratory)::getOrderEcg($request));
    }
    public function getOrderEcgDetails(Request $request){
        return response()->json((new _Laboratory)::getOrderEcgDetails($request));
    }
    public static function setEcgOrderProcessed(Request $request){
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Laboratory::setEcgOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }
    public function saveEcgOrderResult(Request $request){
        $filename = "";
        $attachment = $request->file('images');

        if ($attachment) {
            $destinationPath = public_path('../images/laboratory');
            $filename = time() . '.' . $attachment->getClientOriginalExtension();
            $attachment->move($destinationPath, $filename);
        }

        $result = (new _Laboratory)::saveEcgOrderResult($request, $filename);

        if ($result) {
            return response()->json([
                'message' => 'success',
            ]);
        }
        return response()->json([
            'message' => 'db-error',
        ]);
    }
    public function getCompleteEcgOrderDetails(Request $request){
        return response()->json((new _Laboratory)::getCompleteEcgOrderDetails($request));
    }

    //medical
    public function getOrderMedicalExam(Request $request){
        return response()->json((new _Laboratory)::getOrderMedicalExam($request));
    }
    public function getOrderMedicalExamDetails(Request $request){
        return response()->json((new _Laboratory)::getOrderMedicalExamDetails($request));
    }
    public static function setMedicalExamOrderProcessed(Request $request){
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Laboratory::setMedicalExamOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }
    public function saveMedicalExamOrderResult(Request $request){
        $result = (new _Laboratory)::saveMedicalExamOrderResult($request);
        if ($result) {
            return response()->json([
                'message' => 'success',
            ]);
        }

        return response()->json([
            'message' => 'db-error',
        ]);
    }
    public function getCompleteMedicalExamOrderDetails(Request $request){
        return response()->json((new _Laboratory)::getCompleteMedicalExamOrderDetails($request));
    }
    
    //oral glucose
    public function getOrderOralGlucoseTestNew(Request $request){
        return response()->json((new _Laboratory)::getOrderOralGlucoseTestNew($request));
    }
    public function getOralGlucoseTestNewDetails(Request $request){
        return response()->json((new _Laboratory)::getOralGlucoseTestNewDetails($request));
    }
    public static function laboratoryOralGlucosetestOrderProcessed(Request $request){
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryOralGlucosetestOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }
    
    public function saveOralGlucosetestOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveOralGlucosetestOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getCompleteOralGlucoseOrderDetails(Request $request){
        return response()->json((new _Laboratory)::getCompleteOralGlucoseOrderDetails($request));
    }
    
    //thyroid profile
    public function getOrderThyroidProfileTestNew(Request $request){
        return response()->json((new _Laboratory)::getOrderThyroidProfileTestNew($request));
    }
    public function getThyroidProfileTestNewDetails(Request $request){
        return response()->json((new _Laboratory)::getThyroidProfileTestNewDetails($request));
    }
    public static function laboratoryThyroidProfiletestOrderProcessed(Request $request){
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryThyroidProfiletestOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }
    public function saveThyroidProfileTestOrderResult(Request $request){
        if ((new _Laboratory)::saveThyroidProfileTestOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    public function getCompleteThyroidProfileOrderDetails(Request $request){
        return response()->json((new _Laboratory)::getCompleteThyroidProfileOrderDetails($request));
    }
    
    //immunology
    public function getOrderImmunologyTestNew(Request $request){
        return response()->json((new _Laboratory)::getOrderImmunologyTestNew($request));
    }
    public function getImmunologyTestNewDetails(Request $request){
        return response()->json((new _Laboratory)::getImmunologyTestNewDetails($request));
    }
    public static function laboratoryImmunologytestOrderProcessed(Request $request){
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryImmunologytestOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }
    public function saveImmunologyTestOrderResult(Request $request){
        if ((new _Laboratory)::saveImmunologyTestOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    public function getCompleteImmunologyOrderDetails(Request $request){
        return response()->json((new _Laboratory)::getCompleteImmunologyOrderDetails($request));
    }

    //miscellaneous
    public function getOrderMiscellaneousTestNew(Request $request){
        return response()->json((new _Laboratory)::getOrderMiscellaneousTestNew($request));
    }
    public function getMiscellaneousTestNewDetails(Request $request){
        return response()->json((new _Laboratory)::getMiscellaneousTestNewDetails($request));
    }
    public static function laboratoryMiscellaneoustestOrderProcessed(Request $request){
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryMiscellaneoustestOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }
    
    public function saveMiscellaneousOrderResult(Request $request)
    {
        $result = (new _Laboratory)::saveMiscellaneousOrderResult($request);

        if ($result) {
            return response()->json([
                'message' => 'success',
            ]);
        }

        return response()->json([
            'message' => 'db-error',
        ]);
    }

    public function getCompleteMiscellaneousOrderDetails(Request $request){
        return response()->json((new _Laboratory)::getCompleteMiscellaneousOrderDetails($request));
    }

    //hepatitis
    public function getOrderHepatitisProfileTestNew(Request $request){
        return response()->json((new _Laboratory)::getOrderHepatitisProfileTestNew($request));
    }
    public function getHepatitisProfileTestNewDetails(Request $request){
        return response()->json((new _Laboratory)::getHepatitisProfileTestNewDetails($request));
    }
    public static function laboratoryHepatitisProfiletestOrderProcessed(Request $request){
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryHepatitisProfiletestOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }
    public function saveHepatitisProfileOrderResult(Request $request)
    {
        $result = (new _Laboratory)::saveHepatitisProfileOrderResult($request);

        if ($result) {
            return response()->json([
                'message' => 'success',
            ]);
        }

        return response()->json([
            'message' => 'db-error',
        ]);
    }

    public function getCompleteHepatitisProfileOrderDetails(Request $request){
        return response()->json((new _Laboratory)::getCompleteHepatitisProfileOrderDetails($request));
    }

    public function getItemMonitoringBatches(Request $request)
    {
        return response()->json((new _Laboratory)::getItemMonitoringBatches($request));
    }
    
    public function getSpecimenList(Request $request)
    {
        return response()->json((new _Laboratory)::getSpecimenList($request));
    }

    public function getOrderCBCNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderCBCNew($request));
    }

    public function getOrderCBCNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderCBCNewDetails($request));
    }

    public static function laboratoryCBCOrderProcessed(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryCBCOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function saveCBCOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveCBCOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getCompleteCBCOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteCBCOrderDetails($request));
    }

    public function getOrderCovidTestNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderCovidTestNew($request));
    }

    public function getOrderCovidTestNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderCovidTestNewDetails($request));
    }

    public static function laboratoryCovid19OrderProcessed(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryCovid19OrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function saveCovid19TestOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveCovid19TestOrderResult($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function getCompleteCovid19OrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteCovid19OrderDetails($request));
    }

    public function getOrderTumorMakerTestNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderTumorMakerTestNew($request));
    }

    public function getOrderTumorMakerTestNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderTumorMakerTestNewDetails($request));
    }

    public static function laboratoryTumorMakerOrderProcessed(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryTumorMakerOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function saveTumorMakerOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveTumorMakerOrderResult($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function getCompleteTumorMakerOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteTumorMakerOrderDetails($request));
    }

    public function getOrderDrugTestTestNew(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderDrugTestTestNew($request));
    }

    public function getOrderDrugTestTestNewDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderDrugTestTestNewDetails($request));
    }

    public static function laboratoryDrugTestOrderProcessed(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Laboratory::laboratoryDrugTestOrderProcessed($request);
        if ($result == 'order-cannot-process-reagent-notfound') {
            return response()->json([
                "message" => 'reagent-error',
            ]);
        }

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function saveDrugTestOrderResult(Request $request)
    {
        if ((new _Laboratory)::saveDrugTestOrderResult($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function getCompleteDrugTestOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteDrugTestOrderDetails($request));
    }

    public function getLabItemProductDescriptions(Request $request)
    {
        return response()->json((new _Laboratory)::getLabItemProductDescriptions($request));
    }

    public function getLabItemProducts(Request $request)
    {
        return response()->json((new _Laboratory)::getLabItemProducts($request));
    }

    public function editResultPrintLayout(Request $request)
    {
        $result = _Laboratory::editResultPrintLayout($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    
    public function getCurrentFormInformationResult(Request $request){
        return response()->json((new _Laboratory)::getCurrentFormInformationResult($request));
    }
    
    public function getAllLaboratoryReport(Request $request){
        return response()->json((new _Laboratory)::getAllLaboratoryReport($request));
    }

    public function getAllTestByTracerNumber(Request $request){
        return response()->json((new _Laboratory)::getAllTestByTracerNumber($request));
    }

    public function getAllSpecTestByDepartment(Request $request){
        return response()->json((new _Laboratory)::getAllSpecTestByDepartment($request));
    }

    public function getLaboratoryGetToEditResult(Request $request){
        return response()->json((new _Laboratory)::getLaboratoryGetToEditResult($request));
    }

    public function editResultHemaCBCConfirm(Request $request)
    {
        $result = _Laboratory::editResultHemaCBCConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultSerologyConfirm(Request $request)
    {
        $result = _Laboratory::editResultSerologyConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    
    public function editResultHemaConfirm(Request $request)
    {
        $result = _Laboratory::editResultHemaConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultClinicalMicroConfirm(Request $request)
    {
        $result = _Laboratory::editResultClinicalMicroConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultClinicalChemistryConfirm(Request $request)
    {
        $result = _Laboratory::editResultClinicalChemistryConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultStoolConfirm(Request $request)
    {
        $result = _Laboratory::editResultStoolConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultUrinalysisConfirm(Request $request)
    {
        $result = _Laboratory::editResultUrinalysisConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultThyroidProfConfirm(Request $request)
    {
        $result = _Laboratory::editResultThyroidProfConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultMiscellaneousConfirm(Request $request)
    {
        $result = _Laboratory::editResultMiscellaneousConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultHepatitisProfileConfirm(Request $request)
    {
        $result = _Laboratory::editResultHepatitisProfileConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultCovid19Confirm(Request $request)
    {
        $result = _Laboratory::editResultCovid19Confirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultTumorMakerConfirm(Request $request)
    {
        $result = _Laboratory::editResultTumorMakerConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editResultDrugTestConfirm(Request $request)
    {
        $result = _Laboratory::editResultDrugTestConfirm($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAllLaboratoryReportFilter(Request $request){
        return response()->json((new _Laboratory)::getAllLaboratoryReportFilter($request));
    }

    public function getCompleteMedCertOrderDetails(Request $request){
        return response()->json((new _Laboratory)::getCompleteMedCertOrderDetails($request));
    }

    public function getCompleteSarsCovOrderDetails(Request $request)
    {
        return response()->json((new _Laboratory)::getCompleteSarsCovOrderDetails($request));
    }

    public function getItemReagents(Request $request)
    {
        return response()->json((new _Laboratory)::getItemReagents($request));
    }
    
    public function createRequestItemReagents(Request $request)
    {
        $result = _Laboratory::createRequestItemReagents($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getItemRequestTemp(Request $request)
    {
        return response()->json((new _Laboratory)::getItemRequestTemp($request));
    }
    
    public function removeRequestItemReagents(Request $request)
    {
        $result = _Laboratory::removeRequestItemReagents($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function confirmRequestItemReagents(Request $request)
    {
        $result = _Laboratory::confirmRequestItemReagents($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getItemRequestConfirm(Request $request)
    {
        return response()->json((new _Laboratory)::getItemRequestConfirm($request));
    }

    //electrolytes
    public function getOrderClinicalChemistryNewElectro(Request $request)
    {
        return response()->json((new _Laboratory)::getOrderClinicalChemistryNewElectro($request));
    }
    
}
