<?php

namespace App\Http\Controllers;

use App\Models\_Doctor;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Doctor extends Controller
{
    public function movePatientToList(Request $request)
    {
        return response()->json(_Doctor::movePatientToList($request));
    }

    public function createRoom(Request $request)
    {
        $result = _Doctor::createRoom($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removeRoom(Request $request)
    {
        $result = _Doctor::removeRoom($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function appointmentDetails(Request $request)
    {
        return response()->json(_Doctor::appointmentDetails($request));
    }

    public function getPatientWebRtcId(Request $request)
    {
        $result = _Doctor::getPatientWebRtcId($request);
        return response()->json($result);
    }

    public function getPersonalInfo(Request $request)
    {
        $result = _Doctor::getPersonalInfo($request);
        return response()->json($result);
    }

    public function getPatients(Request $request)
    {
        $result = _Doctor::getPatients($request);
        return response()->json($result);
    }

    public function getPatientInformation(Request $request)
    {
        $result = _Doctor::getPatientInformation($request);
        return response()->json($result);
    }

    public function getBloodPressure(Request $request)
    {
        $result = _Doctor::getBloodPressure($request);
        return response()->json($result);
    }

    public function getTemperature(Request $request)
    {
        $result = _Doctor::getTemperature($request);
        return response()->json($result);
    }

    public function getGlucose(Request $request)
    {
        $result = _Doctor::getGlucose($request);
        return response()->json($result);
    }

    public function getWeight(Request $request)
    {
        $result = _Doctor::getWeight($request);
        return response()->json($result);
    }

    public function getRespiratory(Request $request)
    {
        $result = _Doctor::getRespiratory($request);
        return response()->json($result);
    }

    public function getPulse(Request $request)
    {
        $result = _Doctor::getPulse($request);
        return response()->json($result);
    }

    public function getCholesterol(Request $request)
    {
        $result = _Doctor::getCholesterol($request);
        return response()->json($result);
    }

    public function getUricacid(Request $request)
    {
        $result = _Doctor::getUricacid($request);
        return response()->json($result);
    }

    public function getChloride(Request $request)
    {
        $result = _Doctor::getChloride($request);
        return response()->json($result);
    }

    public function getCreatinine(Request $request)
    {
        $result = _Doctor::getCreatinine($request);
        return response()->json($result);
    }

    public function getHDL(Request $request)
    {
        $result = _Doctor::getHDL($request);
        return response()->json($result);
    }

    public function getLDL(Request $request)
    {
        $result = _Doctor::getLDL($request);
        return response()->json($result);
    }

    public function getLithium(Request $request)
    {
        $result = _Doctor::getLithium($request);
        return response()->json($result);
    }

    public function getMagnesium(Request $request)
    {
        $result = _Doctor::getMagnesium($request);
        return response()->json($result);
    }

    public function getPotasium(Request $request)
    {
        $result = _Doctor::getPotasium($request);
        return response()->json($result);
    }

    public function getProtein(Request $request)
    {
        $result = _Doctor::getProtein($request);
        return response()->json($result);
    }

    public function getSodium(Request $request)
    {
        $result = _Doctor::getSodium($request);
        return response()->json($result);
    }

    public function newBp(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newBp($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function newWeight(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newWeight($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function newHepatitis(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newHepatitis($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function newTuberculosis(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newTuberculosis($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function newDengue(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newDengue($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function newAllergies(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {return response()->json('pass-invalid');}
        $result = _Doctor::newAllergies($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function newTemp(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newTemp($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function newGlucose(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newGlucose($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function newUricacid(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newUricacid($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function newCholesterol(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newCholesterol($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function newPulse(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newPulse($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function newRespiratory(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newRespiratory($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-inv');
        }
    }

    public function patientHistory(Request $request)
    {
        return response()->json((new _Doctor)::patientHistory($request));
    }

    public function requestPermissionToPatient(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::requestPermissionToPatient($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getProfilePermission(Request $request)
    {
        $result = _Doctor::getProfilePermission($request);
        return response()->json($result);
    }

    public function savePatient(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if (!_Validator::checkEmailInPatient($request->email)) {
                $result = _Doctor::savePatient($request);
                if ($result) {
                    return response()->json('success');
                } else {
                    return response()->json('db-error');
                }
            } else {
                return response()->json('email-exist');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getLocalAppList(Request $request)
    {
        return response()->json((new _Doctor)::getLocalAppList($request));
    }

    public function getIncompleteAppList(Request $request)
    {
        return response()->json((new _Doctor)::getIncompleteAppList($request));
    }

    public function requestCreditSave(Request $request)
    {
        $result = (new _Doctor)::requestCreditSave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getRequestCredit(Request $request)
    {
        return response()->json((new _Doctor)::getRequestCredit($request));
    }

    public function getcommentsForApproval(Request $request)
    {
        return response()->json((new _Doctor)::getcommentsForApproval($request));
    }

    public function approveCommentSave(Request $request)
    {
        $result = _Doctor::approveCommentSave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function forapproveCommentDelete(Request $request)
    {
        $result = _Doctor::forapproveCommentDelete($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAppointmentLocalDetails(Request $request)
    {
        return response()->json((new _Doctor)::getAppointmentLocalDetails($request));
    }

    public function setLocalAppComplete(Request $request)
    {
        $result = _Doctor::setLocalAppComplete($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function setLocalAppRechedule(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::setLocalAppRechedule($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getNotificationsList(Request $request)
    {
        return response()->json((new _Doctor)::getNotificationsList($request));
    }

    public function reschedVirtualAppointment(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::reschedVirtualAppointment($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getPatientContactInfo(Request $request)
    {
        return response()->json((new _Doctor)::getPatientContactInfo($request));
    }

    public function getNotificationsMsg(Request $request)
    {
        return response()->json((new _Doctor)::getNotificationsMsg($request));
    }

    public function newDiagnosis(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newDiagnosis($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getDiagnosis(Request $request)
    {
        return response()->json((new _Doctor)::getDiagnosis($request));
    }

    public function getPatientSharedImages(Request $request)
    {
        return response()->json((new _Doctor)::getPatientSharedImages($request));
    }

    public function getPatientSharedImagesDates(Request $request)
    {
        return response()->json((new _Doctor)::getPatientSharedImagesDates($request));
    }

    public function getPatientSharedImagesDatesDetails(Request $request)
    {
        return response()->json((new _Doctor)::getPatientSharedImagesDatesDetails($request));
    }

    public function newPatientPrelab(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        if (_Doctor::newPatientPrelab($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getFamilyHistories(Request $request)
    {
        return response()->json((new _Doctor)::getFamilyHistories($request));
    }

    public function getPatientFamilyHistory(Request $request)
    {
        return response()->json((new _Doctor)::getPatientFamilyHistory($request));
    }

    public function getRxDoctorsRx(Request $request)
    {
        return response()->json((new _Doctor)::getRxDoctorsRx($request));
    }

    public function getPatientsPrescDetails(Request $request)
    {
        return response()->json((new _Doctor)::getPatientsPrescDetails($request));
    }

    public function newDietSave(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::newDietSave($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function changeAppointmentTypeLocal(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Doctor::changeAppointmentTypeLocal($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getPersonalDiet(Request $request)
    {
        $result = _Doctor::getPersonalDiet($request);
        return response()->json($result);
    }

    public function getPersonalDietByDate(Request $request)
    {
        $result = _Doctor::getPersonalDietByDate($request);
        return response()->json($result);
    }

    public function getSuggestedDiet(Request $request)
    {
        $result = _Doctor::getSuggestedDiet($request);
        return response()->json($result);
    }

    public function getSuggestedDietByDate(Request $request)
    {
        $result = _Doctor::getSuggestedDietByDate($request);
        return response()->json($result);
    }

    public function getAllPatients(Request $request)
    {
        $result = _Doctor::getAllPatients($request);
        return response()->json($result);
    }

    public function getPermissionByPatient(Request $request)
    {
        $result = _Doctor::getPermissionByPatient($request);
        return response()->json($result);
    }

    public function getBodyPain(Request $request)
    {
        $result = _Doctor::getBodyPain($request);
        return response()->json($result);
    }

    public function getLaboratoryTest(Request $request)
    {
        $result = _Doctor::getLaboratoryTest($request);
        return response()->json($result);
    }

    public function getRefDetails(Request $request)
    {
        $result = _Doctor::getRefDetails($request);
        return response()->json($result);
    }

    public function createVcallRoom(Request $request)
    {
        $result = _Doctor::createVcallRoom($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function onlineAppSetAsDone(Request $request)
    {
        $result = _Doctor::onlineAppSetAsDone($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function deleteVcallRoom(Request $request)
    {
        $result = _Doctor::deleteVcallRoom($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function deleteAllVcallRoom(Request $request)
    {
        $result = _Doctor::deleteAllVcallRoom($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    // new laboratorywith laboratory forms

    public function getLabOrderDeptDetails(Request $request)
    {
        $result = _Doctor::getLabOrderDeptDetails($request);
        return response()->json($result);
    }

    public function getUnsaveLabOrder(Request $request)
    {
        $result = _Doctor::getUnsaveLabOrder($request);
        return response()->json($result);
    }

    public function addLabOrderTounsave(Request $request)
    {
        $result = _Doctor::addLabOrderTounsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removeLabOrderFromUnsave(Request $request)
    {
        $result = _Doctor::removeLabOrderFromUnsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function processLabOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = _Doctor::processLabOrder($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function laboratoryUnpaidOrderByPatient(Request $request)
    {
        $result = _Doctor::laboratoryUnpaidOrderByPatient($request);
        return response()->json($result);
    }

    public function laboratoryUnpaidOrderByPatientDetails(Request $request)
    {
        $result = _Doctor::laboratoryUnpaidOrderByPatientDetails($request);
        return response()->json($result);
    }

    public function laboratoryPaidOrderByPatient(Request $request)
    {
        $result = _Doctor::laboratoryPaidOrderByPatient($request);
        return response()->json($result);
    }

    public function laboratoryPaidOrderHemaDetails(Request $request)
    {
        $result = _Doctor::laboratoryPaidOrderHemaDetails($request);
        return response()->json($result);
    }

    public function paidLabOrderDetails(Request $request)
    {
        $result = _Doctor::paidLabOrderDetails($request);
        return response()->json($result);
    }

    public function notificationUnreadOrders(Request $request)
    {
        $result = _Doctor::notificationUnreadOrders($request);
        return response()->json($result);
    }

    public function docNotifUpdate(Request $request)
    {
        $result = (new _Doctor)::docNotifUpdate($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getBillingRecordByDate(Request $request)
    {
        $result = _Doctor::getBillingRecordByDate($request);
        return response()->json($result);
    }

    public function getPrescriptionIncomeReport(Request $request)
    {
        return response()->json(_Doctor::getPrescriptionIncomeReport($request));
    }

    public function getVirtualImagingList(Request $request)
    {
        return response()->json(_Doctor::getVirtualImagingList($request));
    }

    public function getVirtualImagingOrderList(Request $request)
    {
        return response()->json(_Doctor::getVirtualImagingOrderList($request));
    }

    public function imagingGetCount(Request $request)
    {
        return response()->json((new _Imaging)::imagingGetCount($request));
    }

    public function getUnviewNotification(Request $request)
    {
        return response()->json((new _Doctor)::getUnviewNotification($request));
    }

    public function getBillingPrescriptionByDate(Request $request)
    {
        $result = _Doctor::getBillingPrescriptionByDate($request);
        return response()->json($result);
    }

    public function setNotifAsView(Request $request)
    {
        return response()->json(_Doctor::setNotifAsView($request));
    }

    public function getAllUnreadMsgFromPatient(Request $request)
    {
        return response()->json((new _Doctor)::getAllUnreadMsgFromPatient($request));
    }

    public function getDoctorsIncomeReportByYear(Request $request)
    {
        return response()->json((new _Doctor)::getDoctorsIncomeReportByYear($request));
    }

    public function getSidebarHeaderInformation(Request $request)
    {
        return response()->json((new _Doctor)::getSidebarHeaderInformation($request));
    }

    public function getFullcalendarAppointmentListLocal(Request $request)
    {
        return response()->json((new _Doctor)::getFullcalendarAppointmentListLocal($request));
    }

    public function getFullcalendarAppointmentListVirtual(Request $request)
    {
        return response()->json((new _Doctor)::getFullcalendarAppointmentListVirtual($request));
    }

    public function updateFullcalendarAppointment(Request $request)
    {
        $result = (new _Doctor)::updateFullcalendarAppointment($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getLocalAppointmentListByStatus(Request $request)
    {
        return response()->json((new _Doctor)::getLocalAppointmentListByStatus($request));
    }

    public function getVirtualAppointmentListByStatus(Request $request)
    {
        return response()->json((new _Doctor)::getVirtualAppointmentListByStatus($request));
    }

    public function getTodaysAppointmentListLocal(Request $request)
    {
        return response()->json((new _Doctor)::getTodaysAppointmentListLocal($request));
    }

    public function getTodaysAppointmentListVirtual(Request $request)
    {
        return response()->json((new _Doctor)::getTodaysAppointmentListVirtual($request));
    }

    public function getFullcalendarAppointmentCount(Request $request)
    {
        return response()->json((new _Doctor)::getFullcalendarAppointmentCount($request));
    }

    public function getHemathologyGraphData(Request $request)
    {
        return response()->json((new _Doctor)::getHemathologyGraphData($request));
    }

    public function getChemistryGraphData(Request $request)
    {
        return response()->json((new _Doctor)::getChemistryGraphData($request));
    }

    public function getClinicalMicroscopyData(Request $request)
    {
        return response()->json((new _Doctor)::getClinicalMicroscopyData($request));
    }

    public function getLaboratoryPrintHeaderByMng(Request $request)
    {
        return response()->json((new _Doctor)::getLaboratoryPrintHeaderByMng($request));
    }

    public function updateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = (new _Doctor)::updateUsername($request);
            if ($result) {
                return response()->json('success');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function updatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = (new _Doctor)::updatePassword($request);
            if ($result) {
                return response()->json('success');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function personalMedicationListByDate(Request $request)
    {
        $result = _Doctor::personalMedicationListByDate($request);
        return response()->json($result);
    }

    public function sampleUploadBase64Image(Request $request)
    {
        // $result = _Doctor::sampleUploadBase64Image($request);
        // return response()->json($request->ckCsrfToken);
        // $attachment = $request->file('upload');
        // return response()->json($attachment);
        $attachment = $request->file('upload');
        $destinationPath = public_path('../images/sample-upload/');
        $filename = time() . '.' . $attachment->getClientOriginalExtension();
        $attachment->move($destinationPath, $filename);

        return response()->json([
            "fileName" => $filename,
            "uploaded" => 1,
            "url" => "http://192.168.0.106/gtc-hmis/v1/images/sample-upload/" . $filename,
        ]);
    }

    public function imagingAddOrder(Request $request)
    {
        $result = (new _Doctor)::imagingAddOrder($request);
        if ($result) {return response()->json('success');}
    }

    public function imagingAddOrderUnsavelist(Request $request)
    {
        $result = _Doctor::imagingAddOrderUnsavelist($request);
        return response()->json($result);
    }

    public function imagingOrderUnsaveDelete(Request $request)
    {
        $result = (new _Doctor)::imagingOrderUnsaveDelete($request);
        if ($result) {return response()->json('success');}
    }

    public function imagingOrderUnsaveProcess(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }

        $result = (new _Doctor)::imagingOrderUnsaveProcess($request);
        if ($result) {return response()->json('success');}
    }

    public function getQueuingPatients(Request $request)
    {
        $result = _Doctor::getQueuingPatients($request);
        return response()->json($result);
    }

    public function doctorUpdatePersonalInfo(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }

        $result = (new _Doctor)::doctorUpdatePersonalInfo($request);
        if ($result) {return response()->json('success');}
    }

    public function doctorUpdateProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/doctor');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Doctor::doctorUpdateProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function getAllDoctorsServices(Request $request)
    {
        $result = _Doctor::getAllDoctorsServices($request);
        return response()->json($result);
    }

    public function addNewServiceDoctor(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = (new _Doctor)::addNewServiceDoctor($request);
        if ($result) {return response()->json('success');}
    }

    public function updateExistingServiceById(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = (new _Doctor)::updateExistingServiceById($request);
        if ($result) {return response()->json('success');}
    }

    public function getAllServiceBByDoctorId(Request $request)
    {
        $result = _Doctor::getAllServiceBByDoctorId($request);
        return response()->json($result);
    }

    public function getPsycOrderDeptDetails(Request $request)
    {
        $result = _Doctor::getPsycOrderDeptDetails($request);
        return response()->json($result);
    }

    public function removePsyOrderFromUnsave(Request $request)
    {
        $result = _Doctor::removePsyOrderFromUnsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function addPsycOrderTounsave(Request $request)
    {
        $result = _Doctor::addPsycOrderTounsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getUnsavePsycOrder(Request $request)
    {
        return response()->json((new _Doctor)::getUnsavePsycOrder($request));
    }

    public function processPsychologyOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = _Doctor::processPsychologyOrder($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function psychologyUnpaidOrderByPatient(Request $request)
    {
        $result = _Doctor::psychologyUnpaidOrderByPatient($request);
        return response()->json($result);
    }

    public function psychologyPaidOrderByPatient(Request $request)
    {
        return response()->json((new _Doctor)::psychologyPaidOrderByPatient($request));
    }

    public function paidPsychologyOrderDetails(Request $request)
    {
        $result = _Doctor::paidPsychologyOrderDetails($request);
        return response()->json($result);
    }

    public function newPhysicalExam(Request $request)
    {
        $result = _Doctor::newPhysicalExam($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function newPEOrderList(Request $request)
    {
        $result = _Doctor::newPEOrderList($request);
        return response()->json($result);
    }

    public function setPEOrderProcess(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Doctor::setPEOrderProcess($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public static function setMedCertOrderCompleted(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Doctor::setMedCertOrderCompleted($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public function getNewMedCertOrder(Request $request)
    {
        $result = _Doctor::getNewMedCertOrder($request);
        return response()->json($result);
    }

    public static function newPatientMedicalCertificate(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Doctor::newPatientMedicalCertificate($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public function getPatientMedicalCertificateList(Request $request)
    {
        $result = _Doctor::getPatientMedicalCertificateList($request);
        return response()->json($result);
    }

    public static function updateConsultationRate(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Doctor::updateConsultationRate($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public function getNewMedCertOrderAll(Request $request)
    {
        $result = _Doctor::getNewMedCertOrderAll($request);
        return response()->json($result);
    }

    public function getAllServicesMadamada(Request $request)
    {
        $result = _Doctor::getAllServicesMadamada($request);
        return response()->json($result);
    }

    public function getAllSalaryRecord(Request $request)
    {
        $result = _Doctor::getAllSalaryRecord($request);
        return response()->json($result);
    }

    public static function doctorUpdateToReceivedSalaryRecord(Request $request)
    {
        $result = _Doctor::doctorUpdateToReceivedSalaryRecord($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    //01-20-2022
    public function doctorResultAttachment(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $patientprofile = $request->file('share_image');
        $destinationPath = public_path('../images/imaging/doctorattachments');
        $filename = rand(0, 888) . '-' . time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Doctor::doctorResultAttachment($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function getPatientDocAttachImageDates(Request $request)
    {
        return response()->json((new _Doctor)::getPatientDocAttachImageDates($request));
    }

    public function getPatientDocAttachImageDatesDetails(Request $request)
    {
        return response()->json((new _Doctor)::getPatientDocAttachImageDatesDetails($request));
    }

    public function sentPatientToAdmitting(Request $request)
    {
        $result = (new _Doctor)::sentPatientToAdmitting($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function checkPatientToAdmitting(Request $request)
    {
        $check = (new _Doctor)::checkPatientToAdmitting($request);
        if (count($check) > 0) {
            return response()->json([
                "message" => 'patient-exist',
            ]);
        }
    }

    public function getPatientAdmittingRecord(Request $request)
    {
        return response()->json((new _Doctor)::getPatientAdmittingRecord($request));
    }

    public function handleAdmitPatient(Request $request)
    {
        if ((new _Doctor)::handleAdmitPatientCheckIfAdmitted($request)) {
            return response()->json([
                "message" => 'patient-exist',
            ]);
        }

        return response()->json((new _Doctor)::handleAdmitPatient($request));
    }

    public function getAdmittedPatientAssignByMd(Request $request)
    {
        return response()->json((new _Doctor)::getAdmittedPatientAssignByMd($request));
    }

    public function checkPatientForOperation(Request $request)
    {
        $checkRes = (new _Doctor)::checkPatientForOperation($request);
        if (count($checkRes)) {
            return response()->json([
                "message" => 'patient-exist',
            ]);
        }
    }

    public function sentPatientForOperation(Request $request)
    {

        $result = (new _Doctor)::sentPatientForOperation($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }


    public function getAppointmentRecordsAll(Request $request)
    {
        return response()->json((new _Doctor)::getAppointmentRecordsAll($request));
    }
}
