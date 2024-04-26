<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_Appointment;
use App\Models\_Validator;

class Appointment extends Controller
{
    public function doctorsList(Request $request){
        return response()->json((new _Appointment)::doctorsList($request));
    }

    public function doctorsInformation(Request $request){
        return response()->json((new _Appointment)::doctorsInformation($request));
    }

    public function doctorsServices(Request $request){
        return response()->json((new _Appointment)::doctorsServices($request));
    }

    public function requestAppointment(Request $request){

        if(_Validator::verifyAccount($request)){
            if(_Appointment::checkActiveOnlineAppointment($request)){
                return response()->json('appointment-exist'); 
            }

            $filename = '';
            if(empty($request->appointment_attachment)){
                $filename = null;
            }else{
                $image = $request->file('appointment_attachment');
                $filename = time().'.'.$image->getClientOriginalExtension(); 
                $destinationPath = public_path('../images/appointment'); // set folder where to save
                $image->move($destinationPath, $filename); //move uploaded file.
            }

            $result = (new _Appointment)::requestAppointment($request, $filename);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-inv');
        }
    }

    public function getApprovedCount(Request $request){
        return response()->json((new _Appointment)::getApprovedCount($request));
    }
    
    public function getAppointmentList(Request $request){
        return response()->json((new _Appointment)::getAppointmentList($request));
    }

    public function getAppointmentDetail(Request $request){
        return response()->json((new _Appointment)::getAppointmentDetail($request));
    }

    public function getNextAppointment(Request $request){
        return response()->json((new _Appointment)::getNextAppointment($request));
    }

    public function getApproveAppointment(Request $request){
        return response()->json((new _Appointment)::getApproveAppointment($request));
    }

    public function getRequestAppointmentList(Request $request){
        return response()->json((new _Appointment)::getRequestAppointmentList($request));
    }

    public function appointmentAction(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = (new _Appointment)::appointmentAction($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-inv');
        }
    }

    public function sendNotificationMsg(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = (new _Appointment)::sendNotificationMsg($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-inv');
        }
    }

    public function appointmentNotificationMsg(Request $request){
        return response()->json((new _Appointment)::appointmentNotificationMsg($request));
    }

    public function appointmentNotifByPatient(Request $request){
        return response()->json((new _Appointment)::appointmentNotifByPatient($request));
    }

    public function appointmentNotifDetails(Request $request){
        return response()->json((new _Appointment)::appointmentNotifDetails($request));
    }

    public function appointmentNotifByPatientUnread(Request $request){
        return response()->json((new _Appointment)::appointmentNotifByPatientUnread($request));
    }

    public function appointmentNotifByPatientUnreadNew(Request $request){
        return response()->json((new _Appointment)::appointmentNotifByPatientUnreadNew($request));
    }

    public function appointmentCreatedRoom(Request $request){
        return response()->json((new _Appointment)::appointmentCreatedRoom($request));
    }

    public function appointmentSetDone(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = (new _Appointment)::appointmentSetDone($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-inv');
        }
    }

    public function getappointmentByDoctor(Request $request){
        return response()->json((new _Appointment)::getappointmentByDoctor($request));
    }
    
    public function getPatientsLocalRecord(Request $request){
        return response()->json((new _Appointment)::getPatientsLocalRecord($request));
    }

    public function getPatientsVirtualRecord(Request $request){
        return response()->json((new _Appointment)::getPatientsVirtualRecord($request));
    }

    public function getDoctorsLocalAppointment(Request $request){
        return response()->json((new _Appointment)::getDoctorsLocalAppointment($request));
    }

    public function createLocalappointment(Request $request){
        if(_Validator::verifyAccount($request)){
            if((new _Appointment)::checkActiveAppointment($request->patient_id)){
                return response()->json('has-appointment');
            }

            $result =  (new _Appointment)::createLocalappointment($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }


    public function appointmentNofitReply(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = (new _Appointment)::appointmentNofitReply($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getClinicListVirtual(Request $request){
        return response()->json(_Appointment::getClinicListVirtual($request)); 
    }

    public function getClinicDetails(Request $request){
        return response()->json(_Appointment::getClinicDetails($request)); 
    }

    public function sendInquiry(Request $request){ 
        $result = (new _Appointment)::sendInquiry($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function getInquiries(Request $request){
        return response()->json(_Appointment::getInquiries($request)); 
    } 

    public function getInquiryLastMsg(Request $request){
        return response()->json(_Appointment::getInquiryLastMsg($request)); 
    } 
    
    public function getClinicDoctorsList(Request $request){
        return response()->json(_Appointment::getClinicDoctorsList($request)); 
    } 

    public function setAppointmentAsViewByDoctor(Request $request){ 
        if(_Appointment::setAppointmentAsViewByDoctor($request)){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }
}

