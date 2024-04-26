<?php

namespace App\Http\Controllers\Syncronize;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Syncronize\_Sync;

class Sync extends Controller

    {
        public function __construct(){
            /// $this->middleware('guest')->except('logout');
            session_start();    
            ini_set('max_execution_time', 0);
        } 
        
        public function syncAppointmentList(){
            if(_Sync::syncAppointmentList()){ return response()->json('success'); }
        }

        public function syncAppointmentSettings(){
            if(_Sync::syncAppointmentSettings()){ return response()->json('success'); }
        } 

        public function syncDoctors(){
            if(_Sync::syncDoctors()){ return response()->json('success'); }
        } 

        public function syncDoctorsAppointmentService(){
            if(_Sync::syncDoctorsAppointmentService()){ return response()->json('success'); }
        } 

        public function syncDoctorsComments(){
            if(_Sync::syncDoctorsComments()){ return response()->json('success'); }
        } 

        public function syncDoctorsNotes(){
            if(_Sync::syncDoctorsNotes()){ return response()->json('success'); }
        } 

        public function syncDoctorsNotesCanvas(){
            if(_Sync::syncDoctorsNotesCanvas()){ return response()->json('success'); }
        } 
        
        public function syncDoctorsNotification(){
            if(_Sync::syncDoctorsNotification()){ return response()->json('success'); }
        } 

        public function syncDoctorsPatients(){
            if(_Sync::syncDoctorsPatients()){ return response()->json('success'); }
        } 

        public function syncDoctorsPrescriptions(){
            if(_Sync::syncDoctorsPrescriptions()){ return response()->json('success'); }
        } 

        public function syncDoctorsSpecializationList(){
            if(_Sync::syncDoctorsSpecializationList()){ return response()->json('success'); }
        } 

        public function syncDoctorsTreatmentPlan(){
            if(_Sync::syncDoctorsTreatmentPlan()){ return response()->json('success'); }
        } 

        public function syncEncoder(){
            if(_Sync::syncEncoder()){ return response()->json('success'); }
        } 

        public function syncEncoderPatientBillsRecord(){
            if(_Sync::syncEncoderPatientBillsRecord()){ return response()->json('success'); }
        } 

        public function syncEncoderPatientBillsUnpaid(){
            if(_Sync::syncEncoderPatientBillsUnpaid()){ return response()->json('success'); }
        } 

        public function syncImaging(){
            if(_Sync::syncImaging()){ return response()->json('success'); }
        } 

        public function syncImagingCenter(){
            if(_Sync::syncImagingCenter()){ return response()->json('success'); }
        } 

        public function syncImagingCenterRecord(){
            if(_Sync::syncImagingCenterRecord()){ return response()->json('success'); }
        } 

        public function syncLaboratory(){
            if(_Sync::syncLaboratory()){ return response()->json('success'); }
        } 

        public function syncLaboratoryChemistry(){
            if(_Sync::syncLaboratoryChemistry()){ return response()->json('success'); }
        } 

        public function syncLaboratoryFecal(){
            if(_Sync::syncLaboratoryFecal()){ return response()->json('success'); }
        } 

        public function syncLaboratoryFormheader(){
            if(_Sync::syncLaboratoryFormheader()){ return response()->json('success'); }
        } 

        public function syncLaboratoryHemathology(){
            if(_Sync::syncLaboratoryHemathology()){ return response()->json('success'); }
        } 

        public function syncLaboratoryList(){
            if(_Sync::syncLaboratoryList()){ return response()->json('success'); }
        } 
        
        public function syncLaboratoryMicroscopy(){
            if(_Sync::syncLaboratoryMicroscopy()){ return response()->json('success'); }
        } 

        public function syncLaboratorySorology(){
            if(_Sync::syncLaboratorySorology()){ return response()->json('success'); }
        } 

        public function syncLaboratoryLest(){
            if(_Sync::syncLaboratoryLest()){ return response()->json('success'); }
        }

        public function syncLaboratoryUnsaveOrder(){
            if(_Sync::syncLaboratoryUnsaveOrder()){ return response()->json('success'); }
        }

        public function syncManagement(){
            if(_Sync::syncManagement()){ return response()->json('success'); }
        }

        public function syncManagementMonitoring(){
            if(_Sync::syncManagementMonitoring()){ return response()->json('success'); }
        }

        public function syncMessages(){
            if(_Sync::syncMessages()){ return response()->json('success'); }
        }
        
        
        public function syncMessagesFromUser(){
            if(_Sync::syncMessagesFromUser()){ return response()->json('success'); }
        }

        public function syncPatients(){
            if(_Sync::syncPatients()){ return response()->json('success'); }
        }

        public function syncPatientsAddedBy(){
            if(_Sync::syncPatientsAddedBy()){ return response()->json('success'); }
        }

        
        public function syncPatientsCholesterolHistory(){
            if(_Sync::syncPatientsCholesterolHistory()){ return response()->json('success'); }
        }

        public function syncPatientsCredit(){
            if(_Sync::syncPatientsCredit()){ return response()->json('success'); }
        }

        public function syncPatientsCreditTransaction(){
            if(_Sync::syncPatientsCreditTransaction()){ return response()->json('success'); }
        }

        public function syncPatientsDiagnosis(){
            if(_Sync::syncPatientsDiagnosis()){ return response()->json('success'); }
        }

        public function syncPatientsDiets(){
            if(_Sync::syncPatientsDiets()){ return response()->json('success'); }
        }

        public function syncPatientsDischarged(){
            if(_Sync::syncPatientsDischarged()){ return response()->json('success'); }
        } 

        public function syncPatientsFamilyHistories(){
            if(_Sync::syncPatientsFamilyHistories()){ return response()->json('success'); }
        }

        public function syncPatientsFamilyHistory(){
            if(_Sync::syncPatientsFamilyHistory()){ return response()->json('success'); }
        }

        public function syncPatientsGlucoseHistory(){
            if(_Sync::syncPatientsGlucoseHistory()){ return response()->json('success'); }
        }   
        
        public function syncPatientsHistory(){
            if(_Sync::syncPatientsHistory()){ return response()->json('success'); }
        } 

        public function syncPatientHistoryAttachment(){
            if(_Sync::syncPatientHistoryAttachment()){ return response()->json('success'); }
        } 

        public function syncPatientHistoryCalcium(){
            if(_Sync::syncPatientHistoryCalcium()){ return response()->json('success'); }
        }
        
        public function syncPatientHistoryChloride(){
            if(_Sync::syncPatientHistoryChloride()){ return response()->json('success'); }
        }

        public function syncPatientHistoryCreatinine(){
            if(_Sync::syncPatientHistoryCreatinine()){ return response()->json('success'); }
        }
        
        public function syncPatientHistoryHDL(){
            if(_Sync::syncPatientHistoryHDL()){ return response()->json('success'); }
        }
        
        public function syncPatientHistoryLDL(){
            if(_Sync::syncPatientHistoryLDL()){ return response()->json('success'); }
        }

        public function syncPatientHistoryLithium(){
            if(_Sync::syncPatientHistoryLithium()){ return response()->json('success'); }
        }

        public function syncPatientHistoryMagnessium(){
            if(_Sync::syncPatientHistoryMagnessium()){ return response()->json('success'); }
        }

        public function syncPatientHistoryPotassium(){
            if(_Sync::syncPatientHistoryPotassium()){ return response()->json('success'); }
        }

        public function syncPatientHistoryProtein(){
            if(_Sync::syncPatientHistoryProtein()){ return response()->json('success'); }
        }

        public function syncPatientHistorySoduim(){
            if(_Sync::syncPatientHistorySoduim()){ return response()->json('success'); }
        }

        public function syncPatientHistoryLabBP(){
            if(_Sync::syncPatientHistoryLabBP()){ return response()->json('success'); }
        }

        public function syncPatientPainHistory(){
            if(_Sync::syncPatientPainHistory()){ return response()->json('success'); }
        }
        
        public function syncPatientPermission(){
            if(_Sync::syncPatientPermission()){ return response()->json('success'); }
        }

        public function syncPatientPersonalMedication(){
            if(_Sync::syncPatientPersonalMedication()){ return response()->json('success'); }
        }

        public function syncPatientPulseHistory(){
            if(_Sync::syncPatientPulseHistory()){ return response()->json('success'); }
        }

        public function syncPatientRespiratoryHistory(){
            if(_Sync::syncPatientRespiratoryHistory()){ return response()->json('success'); }
        }
        
        public function syncPatientTempHistory(){
            if(_Sync::syncPatientTempHistory()){ return response()->json('success'); }
        }

        public function syncPatientUricAcidHistory(){
            if(_Sync::syncPatientUricAcidHistory()){ return response()->json('success'); }
        }

        public function syncPatientWeightHistory(){
            if(_Sync::syncPatientWeightHistory()){ return response()->json('success'); }
        }

        public function syncPatientShareImages(){
            if(_Sync::syncPatientShareImages()){ return response()->json('success'); }
        }

        public function syncPharmacy(){
            if(_Sync::syncPharmacy()){ return response()->json('success'); }
        }

        public function syncPharmacyClinicHistory(){
            if(_Sync::syncPharmacyClinicHistory()){ return response()->json('success'); }
        }

        public function syncPharmacyClinicInventory(){
            if(_Sync::syncPharmacyClinicInventory()){ return response()->json('success'); }
        }

        public function syncPharmacyClinicProducts(){
            if(_Sync::syncPharmacyClinicProducts()){ return response()->json('success'); }
        }

        public function syncPharmacyClinicProductsPackage(){
            if(_Sync::syncPharmacyClinicProductsPackage()){ return response()->json('success'); }
        }

        public function syncPharmacyClinicProductsShare(){
            if(_Sync::syncPharmacyClinicProductsShare()){ return response()->json('success'); }
        }

        public function syncPharmacyClinicReceipt(){
            if(_Sync::syncPharmacyClinicReceipt()){ return response()->json('success'); }
        }

        public function syncPharmacyClinicSales(){
            if(_Sync::syncPharmacyClinicSales()){ return response()->json('success'); }
        }

        public function syncPharmacyBranches(){
            if(_Sync::syncPharmacyBranches()){ return response()->json('success'); }
        }

        public function syncRadiologist(){
            if(_Sync::syncRadiologist()){ return response()->json('success'); }
        }

        public function syncTeleRadiologist(){
            if(_Sync::syncTeleRadiologist()){ return response()->json('success'); }
        }

        public function syncTeleRadiologistChat(){
            if(_Sync::syncTeleRadiologistChat()){ return response()->json('success'); }
        }

        public function syncUsers(){
            if(_Sync::syncUsers()){ return response()->json('success'); }
        }

        public function syncUsersGeolocation(){
            if(_Sync::syncUsersGeolocation()){ return response()->json('success'); }
        }

        public function syncUsersSubscription(){
            if(_Sync::syncUsersSubscription()){ return response()->json('success'); }
        }

        public function syncVirtualAppointment(){
            if(_Sync::syncVirtualAppointment()){ return response()->json('success'); }
        }
        
        public function syncVirtualAppointmentNotification(){
            if(_Sync::syncVirtualAppointmentNotification()){ return response()->json('success'); }
        }

        public function syncDoctorsRxHeader(){
            if(_Sync::syncDoctorsRxHeader()){ return response()->json('success'); }
        }
    }
