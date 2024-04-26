<?php

namespace App\Http\Controllers;

use App\Models\_Van;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Van extends Controller
{
    public static function getInformation(Request $request)
    {
        return response()->json((new _Van)::getInformation($request));
    }

    public function hisVanGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Van)::hisVanGetPersonalInfoById($request));
    }

    public function hisVanEndtUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/accounting');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Van::hisVanEndtUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisVanEndtUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Van::hisVanEndtUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisVanNewPatient(Request $request)
    {
        $filename = '';
        // if (!_Validator::verifyAccount($request)) {
        //     return response()->json('pass-invalid');
        // }
        if (!empty($request->email)) {
            if (_Validator::checkEmailInPatient($request->email)) {
                return response()->json('email-exist');
            }
        }
        if($request->image != 'no-image.jpg'){
            if (str_contains($request->image, 'data:image/png;base64')) {
                $destinationPath = public_path('../images/patients/'); // set folder where to save
                $img = $request->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = time() . '.jpeg';
    
                $file = $destinationPath . $filename;
                $success = file_put_contents($file, $data);
            }
            if (_Van::hisVanNewPatient($request, $filename)) {
                return response()->json('success');
            }
        }else{
            $filename = $request->image;
            if (_Van::hisVanNewPatient($request, $filename)) {
                return response()->json('success');
            }
        }
        return response()->json('db-error');
    }
    
    public function vanGetPatientInformation(Request $request)
    {
        return response()->json(_Van::vanGetPatientInformation($request));
    }

    public function vanEditPatientVital(Request $request)
    {
        if (_Van::vanEditPatientVital($request)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function savePackageOrderTemp(Request $request){
        if (_Van::savePackageOrderTemp($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    function vanBillingSetAsPaid(Request $request){ 
        if( ! _Validator::verifyAccount($request) ){
            return response()->json('pass-invalid');
        } 
        if(_Van::vanBillingSetAsPaid($request)){ 
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function vanPatientListQueuing(Request $request)
    {
        return response()->json(_Van::vanPatientListQueuing($request));
    }
    
    public function getVanUrineTest(Request $request){
        return response()->json((new _Van)::getVanUrineTest($request));
    }

    public function getVanStoolTest(Request $request){
        return response()->json((new _Van)::getVanStoolTest($request));
    }

    function updateProcessVanUrineTest(Request $request){ 
        if(_Van::updateProcessVanUrineTest($request)){ 
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    function updateProcessVanStoolTest(Request $request){ 
        if(_Van::updateProcessVanStoolTest($request)){ 
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function saveUrinalysisOrderResult(Request $request){
        if ((new _Van)::saveUrinalysisOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function saveStoolOrderResult(Request $request){
        if ((new _Van)::saveStoolOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function vanPatientListToPrintResult(Request $request)
    {
        return response()->json(_Van::vanPatientListToPrintResult($request));
    }
    

    public function getMobileVanPatientsWithNewOrder(Request $request)
    {
        $result = (new _Van)::getMobileVanPatientsWithNewOrder($request);
        return response()->json($result);
    }

    public function getMobileVanPatientNewPEOrder(Request $request)
    {
        $result = (new _Van)::getMobileVanPatientNewPEOrder($request);
        return response()->json($result);
    }

    public function getMobileVanPatientNewXRAYOrder(Request $request)
    {
        $result = (new _Van)::getMobileVanPatientNewXRAYOrder($request);
        return response()->json($result);
    }

    public function getMobileVanPatientNewXRAYOrderAddResult(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if($request->imaging_type == 'xray'){
                $image = '';
                if (!empty($request->image)) {
                    $attachment = $request->file('image');
                    $attachmentname = [];
                    $count = 0;
                    $destinationPath = public_path('../images/imaging');
    
                    foreach ($attachment as $file) {
                        $fname = date('Y') . '-' . rand(0, 9999) . '-' . time();
                        $attachmentname[] = $fname . '.' . $file->getClientOriginalExtension();
                        $newfname = $attachmentname[$count++];
    
                        $file->move($destinationPath, $newfname);
                    }
                }
                $result = _Van::getMobileVanPatientNewXRAYOrderAddResult($request, implode(',', $attachmentname));
                if ($result) {
                    return response()->json('success');
                } else {
                    return response()->json('db-error');
                }
            }else{
                $result = _Van::getMobileVanPatientNewXRAYOrderAddResult($request, null);
                if ($result) {
                    return response()->json('success');
                } else {
                    return response()->json('db-error');
                }
            }
        } else {
            return response()->json('pass-invalid');
        }
    }
    
    public function getMobileVanPatientNewMedCertOrder(Request $request)
    {
        $result = (new _Van)::getMobileVanPatientNewMedCertOrder($request);
        return response()->json($result);
    }

    public function getAllPatientRecordImagingForPrintVan(Request $request)
    {
        $result = (new _Van)::getAllPatientRecordImagingForPrintVan($request);
        return response()->json($result);
    }

    public function vanGetPatientList(Request $request)
    {
        return response()->json(_Van::vanGetPatientList($request));
    }

    public function addLabOrderTounsave(Request $request){
        $result = _Van::addLabOrderTounsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    function vanBillingCancel(Request $request){ 
        if( ! _Validator::verifyAccount($request) ){
            return response()->json('pass-invalid');
        } 
        if(_Van::vanBillingCancel($request)){ 
            return response()->json('success');
        }
        return response()->json('db-error');
    }
    
    public function imagingOrderList(Request $request)
    {
        return response()->json(_Van::imagingOrderList($request));
    }

    public function addImagingOrderTounsave(Request $request){
        $result = _Van::addImagingOrderTounsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getPsychologyList(Request $request)
    {
        return response()->json((new _Van)::getPsychologyList($request));
    }
    
    public function addPsychologyOrderTounsave(Request $request){
        $result = _Van::addPsychologyOrderTounsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getVanPatientNewMedCertOrder(Request $request)
    {
        $result = (new _Van)::getVanPatientNewMedCertOrder($request);
        return response()->json($result);
    }

    //01-21-2022
    public function getOtherList(Request $request)
    {
        return response()->json((new _Van)::getOtherList($request));
    }
    
    public function addOtherOrderToUnsave(Request $request){
        $result = _Van::addOtherOrderToUnsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getMobileVanPatientNewMedCertOrderFirstDesc(Request $request)
    {
        $result = (new _Van)::getMobileVanPatientNewMedCertOrderFirstDesc($request);
        return response()->json($result);
    }

    public function getMedicalTechVanByBranch(Request $request)
    {
        $result = (new _Van)::getMedicalTechVanByBranch($request);
        return response()->json($result);
    }

    public function getRadiologistVanByBranch(Request $request)
    {
        $result = (new _Van)::getRadiologistVanByBranch($request);
        return response()->json($result);
    }

    public function getAllDoctorList(Request $request)
    {
        $result = (new _Van)::getAllDoctorList($request);
        return response()->json($result);
    }

    public function vanClinicalSummaryPatientListQueuing(Request $request)
    {
        return response()->json(_Van::vanClinicalSummaryPatientListQueuing($request));
    }
    
    public function vanSaveMedicalExamOrderResult(Request $request){
        $result = (new _Van)::vanSaveMedicalExamOrderResult($request);
        if ($result) {
            return response()->json([
                'message' => 'success',
            ]);
        }

        return response()->json([
            'message' => 'db-error',
        ]);
    }

    public static function vanSetMedCertOrderCompleted(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Van::vanSetMedCertOrderCompleted($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public function getMobileVanPatientNewECGOrder(Request $request)
    {
        $result = (new _Van)::getMobileVanPatientNewECGOrder($request);
        return response()->json($result);
    }

    public function saveECGOrderResult(Request $request){
        if ((new _Van)::saveECGOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    
    public function getVanSarsCovTest(Request $request){
        return response()->json((new _Van)::getVanSarsCovTest($request));
    }

    public function saveSarsCovOrderResult(Request $request){
        if ((new _Van)::saveSarsCovOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    
}
