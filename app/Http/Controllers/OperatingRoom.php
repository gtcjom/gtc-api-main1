<?php

namespace App\Http\Controllers;

use App\Models\_OperatingRoom;
use App\Models\_Validator;
use Illuminate\Http\Request;

class OperatingRoom extends Controller
{
    public function newOperatingRoomService(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _OperatingRoom::newOperatingRoomService($request);
            if ($result) {
                return response()->json([
                    "message" => "success",
                ]);
            } else {
                return response()->json([
                    "message" => "db-error",
                ]);
            }
        } else {
            return response()->json([
                "message" => "pass-invalid",
            ]);
        }
    }

    public function getAllOperatingRoomService(Request $request)
    {
        return response()->json((new _OperatingRoom)::getAllOperatingRoomService($request));
    }

    public function getAllPatientForOR(Request $request)
    {
        return response()->json((new _OperatingRoom)::getAllPatientForOR($request));
    }

    public function getOrHeaderInfo(Request $request)
    {
        return response()->json((new _OperatingRoom)::getOrHeaderInfo($request));
    }

    public function getOrSpecRole(Request $request)
    {
        return response()->json((new _OperatingRoom)::getOrSpecRole($request));
    }

    public function getPhamacyListByTypeGroupById(Request $request)
    {
        return response()->json((new _OperatingRoom)::getPhamacyListByTypeGroupById($request));
    }

    public function getPharmaProductList(Request $request)
    {
        return response()->json((new _OperatingRoom)::getPharmaProductList($request));
    }

    public function getServiceDetailsById(Request $request)
    {
        return response()->json((new _OperatingRoom)::getServiceDetailsById($request));
    }

    public function addItemToUsave(Request $request)
    {
        $result = _OperatingRoom::addItemToUsave($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getItemFromUsave(Request $request)
    {
        return response()->json((new _OperatingRoom)::getItemFromUsave($request));
    }

    public function removeItemFromUsave(Request $request)
    {
        $result = _OperatingRoom::removeItemFromUsave($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function processUnsaveOrder(Request $request)
    {
        $result = (new _OperatingRoom)::processUnsaveOrder($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getProcessedOrderItems(Request $request)
    {
        return response()->json((new _OperatingRoom)::getProcessedOrderItems($request));
    }

    public function getAllPatientInRoom(Request $request)
    {
        return response()->json((new _OperatingRoom)::getAllPatientInRoom($request));
    }

    public function sentPatientToRoomAndStartOp(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => "pass-invalid",
            ]);
        }

        $result = (new _OperatingRoom)::sentPatientToRoomAndStartOp($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function setOperationComplete(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => "pass-invalid",
            ]);
        }

        $result = (new _OperatingRoom)::setOperationComplete($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getAllCompletedOperation(Request $request)
    {
        return response()->json((new _OperatingRoom)::getAllCompletedOperation($request));
    }

    public function orAccountUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/or');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _OperatingRoom::orAccountUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function orAccountUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _OperatingRoom::orAccountUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getOrInfoById(Request $request)
    {
        return response()->json((new _OperatingRoom)::getOrInfoById($request));
    }

    public function getAllPatientsOROrders(Request $request)
    {
        return response()->json((new _OperatingRoom)::getAllPatientsOROrders($request));
    }

    public function getOrderDetails(Request $request)
    {
        return response()->json((new _OperatingRoom)::getOrderDetails($request));
    }

    public function updateConfirmedOrderUsage(Request $request)
    {
        if ((new _OperatingRoom)::updateConfirmedOrderUsage($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAllDoctorList(Request $request)
    {
        return response()->json((new _OperatingRoom)::getAllDoctorList($request));
    }

    public function insertSendPatientToBilling(Request $request)
    {
        if ((new _OperatingRoom)::insertSendPatientToBilling($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getDoctorsListByManagement(Request $request)
    {
        return response()->json(_OperatingRoom::getDoctorsListByManagement($request));
    }

    public function newChartFrontPage(Request $request)
    {
        $result = _OperatingRoom::newChartFrontPage($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getChartFrontPage(Request $request)
    {
        return response()->json(_OperatingRoom::getChartFrontPage($request));
    }

    public function newChartInformationSheet(Request $request)
    {
        $result = _OperatingRoom::newChartInformationSheet($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getChartInformationSheet(Request $request)
    {
        return response()->json(_OperatingRoom::getChartInformationSheet($request));
    }

    public function newSurgeryContent(Request $request)
    {
        $result = _OperatingRoom::newSurgeryContent($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getSurgeryContent(Request $request)
    {
        return response()->json(_OperatingRoom::getSurgeryContent($request));
    }

    public function newCardioPulmononary(Request $request)
    {
        $result = _OperatingRoom::newCardioPulmononary($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getCardioPulmononary(Request $request)
    {
        return response()->json(_OperatingRoom::getCardioPulmononary($request));
    }

    public function newChartBilling(Request $request)
    {
        $result = _OperatingRoom::newChartBilling($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getChartBilling(Request $request)
    {
        return response()->json(_OperatingRoom::getChartBilling($request));
    }

    public function newBedsideNotes(Request $request)
    {
        $result = _OperatingRoom::newBedsideNotes($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getBedsideNotes(Request $request)
    {
        return response()->json(_OperatingRoom::getBedsideNotes($request));
    }

    public function getBedsideNotesTable(Request $request)
    {
        return response()->json(_OperatingRoom::getBedsideNotesTable($request));
    }

    public function newMedicalAbstract(Request $request)
    {
        $result = _OperatingRoom::newMedicalAbstract($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getMedicalAbstract(Request $request)
    {
        return response()->json(_OperatingRoom::getMedicalAbstract($request));
    }

    public function newClinicalSummary(Request $request)
    {
        $result = _OperatingRoom::newClinicalSummary($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getClinicalSummary(Request $request)
    {
        return response()->json(_OperatingRoom::getClinicalSummary($request));
    }

    //dennis
    public function getChartLaboratory(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartLaboratory($request));
    }

    public function updateChartLaboratory(Request $request)
    {
        if ((new _OperatingRoom)::updateChartLaboratory($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getChartPeriOperative(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartPeriOperative($request));
    }

    public function updateChartPeriOperative(Request $request)
    {
        if ((new _OperatingRoom)::updateChartPeriOperative($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getChartPostOperative(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartPostOperative($request));
    }

    public function updateChartPostOperative(Request $request)
    {
        if ((new _OperatingRoom)::updateChartPostOperative($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getChartSurgicalMember(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartSurgicalMember($request));
    }

    public function updateChartSurgicalMember(Request $request)
    {
        if ((new _OperatingRoom)::updateChartSurgicalMember($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getChartDoctorConsultation(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartDoctorConsultation($request));
    }

    public function getChartDoctorConsultationTable(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartDoctorConsultationTable($request));
    }

    public function updateChartDoctorConsultation(Request $request)
    {
        if ((new _OperatingRoom)::updateChartDoctorConsultation($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removeChartDoctorConsultation(Request $request)
    {
        if ((new _OperatingRoom)::removeChartDoctorConsultation($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getChartOperativeRecord(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartOperativeRecord($request));
    }

    public function updateChartOperativeRecord(Request $request)
    {
        if ((new _OperatingRoom)::updateChartOperativeRecord($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getChartDoctorsOrder(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartDoctorsOrder($request));
    }

    public function getChartDoctorsOrderTable(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartDoctorsOrderTable($request));
    }

    public function updateChartDoctorsOrder(Request $request)
    {
        if ((new _OperatingRoom)::updateChartDoctorsOrder($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removeChartDoctorsOrder(Request $request)
    {
        if ((new _OperatingRoom)::removeChartDoctorsOrder($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getChartPostAnesthesiaCareUnit(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartPostAnesthesiaCareUnit($request));
    }

    public function getChartPostAnesthesiaCareUnitTable(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartPostAnesthesiaCareUnitTable($request));
    }

    public function updateChartPostAnesthesiaCareUnit(Request $request)
    {
        if ((new _OperatingRoom)::updateChartPostAnesthesiaCareUnit($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removeChartPostAnesthesiaCareUnit(Request $request)
    {
        if ((new _OperatingRoom)::removeChartPostAnesthesiaCareUnit($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getChartJPDrainMonitoring(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartJPDrainMonitoring($request));
    }

    public function getChartJPDrainMonitoringTable(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartJPDrainMonitoringTable($request));
    }

    public function updateChartJPDrainMonitoring(Request $request)
    {
        if ((new _OperatingRoom)::updateChartJPDrainMonitoring($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removeChartJPDrainMonitoring(Request $request)
    {
        if ((new _OperatingRoom)::removeChartJPDrainMonitoring($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getChartAttendanceSheet(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartAttendanceSheet($request));
    }

    public function updateChartAttendanceSheet(Request $request)
    {
        if ((new _OperatingRoom)::updateChartAttendanceSheet($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAllTraceNoList(Request $request)
    {
        return response()->json((new _OperatingRoom)::getAllTraceNoList($request));
    }

    public function getAllCaseRecordList(Request $request)
    {
        return response()->json((new _OperatingRoom)::getAllCaseRecordList($request));
    }

    public function getAllCaseRecordListDetails(Request $request)
    {
        return response()->json((new _OperatingRoom)::getAllCaseRecordListDetails($request));
    }

    public function createChartCaseRecord(Request $request)
    {
        if ((new _OperatingRoom)::createChartCaseRecord($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    //jhomar
    public function getDischargedIns(Request $request)
    {
        return response()->json((new _OperatingRoom)::getDischargedIns($request));
    }
    public function newDischargedIns(Request $request)
    {
        if ((new _OperatingRoom)::newDischargedIns($request)) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }
    public function getAddressoGraph(Request $request)
    {
        return response()->json((new _OperatingRoom)::getAddressoGraph($request));
    }
    public function newAddressoGraph(Request $request)
    {
        if ((new _OperatingRoom)::newAddressoGraph($request)) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }
    public function sentPatientToDocu(Request $request)
    {
        if ((new _OperatingRoom)::sentPatientToDocu($request)) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function editChartDoctorConsultation(Request $request)
    {
        if ((new _OperatingRoom)::editChartDoctorConsultation($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editChartDoctorsOrder(Request $request)
    {
        if ((new _OperatingRoom)::editChartDoctorsOrder($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editChartPostAnesthesiaCareUnit(Request $request)
    {
        if ((new _OperatingRoom)::editChartPostAnesthesiaCareUnit($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editChartJPDrainMonitoring(Request $request)
    {
        if ((new _OperatingRoom)::editChartJPDrainMonitoring($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removeChartBedSideNotes(Request $request)
    {
        if ((new _OperatingRoom)::removeChartBedSideNotes($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editChartBedSideNotes(Request $request)
    {
        if ((new _OperatingRoom)::editChartBedSideNotes($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function createChartBedsideNotes(Request $request)
    {
        if ((new _OperatingRoom)::createChartBedsideNotes($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function createDoctorsConsultationTable(Request $request)
    {
        if ((new _OperatingRoom)::createDoctorsConsultationTable($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function createDoctorsOrderTable(Request $request)
    {
        if ((new _OperatingRoom)::createDoctorsOrderTable($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function createPostAnethesiaCareUnitTable(Request $request)
    {
        if ((new _OperatingRoom)::createPostAnethesiaCareUnitTable($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function createJPDrainMonitoringTable(Request $request)
    {
        if ((new _OperatingRoom)::createJPDrainMonitoringTable($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function createBedSideNotesTable(Request $request)
    {
        if ((new _OperatingRoom)::createBedSideNotesTable($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getMedicalSheetChart(Request $request)
    {
        return response()->json((new _OperatingRoom)::getMedicalSheetChart($request));
    }
    public function createMedicalSheet(Request $request)
    {
        if ((new _OperatingRoom)::createMedicalSheet($request)) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    // 03-07-2022
    public function getChartCovid19Checklist(Request $request)
    {
        return response()->json((new _OperatingRoom)::getChartCovid19Checklist($request));
    }

    public function createCovid19Checklist(Request $request)
    {
        if ((new _OperatingRoom)::createCovid19Checklist($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function updateChartTempAndPulse(Request $request)
    {
        if ((new _OperatingRoom)::updateChartTempAndPulse($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getMedicalSheetStatChart(Request $request)
    {
        return response()->json((new _OperatingRoom)::getMedicalSheetStatChart($request));
    }

    public function createMedicalSheetStat(Request $request)
    {
        if ((new _OperatingRoom)::createMedicalSheetStat($request)) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    } 

    public function getMedicalSheetPRNChart(Request $request)
    {
        return response()->json((new _OperatingRoom)::getMedicalSheetPRNChart($request));
    }

    public function createMedicalSheetPRN(Request $request)
    {
        if ((new _OperatingRoom)::createMedicalSheetPRN($request)) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    } 
    
}
