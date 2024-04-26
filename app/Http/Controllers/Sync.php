<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_Sync;

class Sync extends Controller

    {
        public function __construct(){
            /// $this->middleware('guest')->except('logout');
            //session_start();
           // ini_set('max_execution_time', 0);
        } 

        public function syncAccountingAccount(Request $request){
            if(_Sync::syncAccountingAccount($request)){ return response()->json('success'); }
        }

        public function syncAdmissionAccount(Request $request){
            if(_Sync::syncAdmissionAccount($request)){ return response()->json('success'); }
        }
        
        public function syncAppointmentList(Request $request){
            if(_Sync::syncAppointmentList($request)){ return response()->json('success'); }
        }

        public function syncCashier(Request $request){
            if(_Sync::syncCashier($request)){ return response()->json('success'); }
        }

        public function syncCashierPatientBillsRecord(Request $request){
            if(_Sync::syncCashierPatientBillsRecord($request)){ return response()->json('success'); }
        }
        public function syncCashierSalesExpenses(Request $request){
            if(_Sync::syncCashierSalesExpenses($request)){ return response()->json('success'); }
        }
        public function syncCashierStatementOfAccount(Request $request){
            if(_Sync::syncCashierStatementOfAccount($request)){ return response()->json('success'); }
        }
        public function syncClinicBank(Request $request){
            if(_Sync::syncClinicBank($request)){ return response()->json('success'); }
        }
        public function syncClinicBankContacts(Request $request){
            if(_Sync::syncClinicBankContacts($request)){ return response()->json('success'); }
        }
        public function syncClinicBankTransaction(Request $request){
            if(_Sync::syncClinicBankTransaction($request)){ return response()->json('success'); }
        }
        public function syncClinicExpensesList(Request $request){
            if(_Sync::syncClinicExpensesList($request)){ return response()->json('success'); }
        }
        public function syncClinicLeaveApplication(Request $request){
            if(_Sync::syncClinicLeaveApplication($request)){ return response()->json('success'); }
        }
        public function syncClinicResignationType(Request $request){
            if(_Sync::syncClinicResignationType($request)){ return response()->json('success'); }
        }

        public function syncDoctors(Request $request){
            if(_Sync::syncDoctors($request)){ return response()->json('success'); }
        } 

        public function syncDoctorsAppointmentServices(Request $request){
            if(_Sync::syncDoctorsAppointmentServices($request)){ return response()->json('success'); }
        } 
        
        public function syncDoctorsComments(){
            if(_Sync::syncDoctorsComments()){ return response()->json('success'); }
        } 

        public function syncDoctorsMedicalCertificateOrdered(Request $request){
            if(_Sync::syncDoctorsMedicalCertificateOrdered($request)){ return response()->json('success'); }
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

        public function syncDoctorsPatients(Request $request){
            if(_Sync::syncDoctorsPatients($request)){ return response()->json('success'); }
        } 

        public function syncDoctorsPrescriptions(Request $request){
            if(_Sync::syncDoctorsPrescriptions($request)){ return response()->json('success'); }
        } 

        public function syncDoctorsRxHeader(){
            if(_Sync::syncDoctorsRxHeader()){ return response()->json('success'); }
        }

        public function syncDoctorsSpecializationList(){
            if(_Sync::syncDoctorsSpecializationList()){ return response()->json('success'); }
        } 

        public function syncDoctorsTreatmentPlan(Request $request){
            if(_Sync::syncDoctorsTreatmentPlan($request)){ return response()->json('success'); }
        } 

        public function syncDoctorSalaryRecord(Request $request){
            if(_Sync::syncDoctorSalaryRecord($request)){ return response()->json('success'); }
        } 

        public function syncEncoder(Request $request){
            if(_Sync::syncEncoder($request)){ return response()->json('success'); }
        } 

        public function syncEncoderPatientBillsRecord(Request $request){
            if(_Sync::syncEncoderPatientBillsRecord($request)){ return response()->json('success'); }
        } 

        public function syncEndorsementAccount(Request $request){
            if(_Sync::syncEndorsementAccount($request)){ return response()->json('success'); }
        } 

        public function syncFormFooterHeaderInformation(Request $request){
            if(_Sync::syncFormFooterHeaderInformation($request)){ return response()->json('success'); }
        } 

        public function syncGeneralManagement(){
            if(_Sync::syncGeneralManagement()){ return response()->json('success'); }
        }

        public function syncGeneralManagementBranches(){
            if(_Sync::syncGeneralManagementBranches()){ return response()->json('success'); }
        }

        public function syncHaptechAccount(Request $request){
            if(_Sync::syncHaptechAccount($request)){ return response()->json('success'); }
        }

        public function syncHMOList(Request $request){
            if(_Sync::syncHMOList($request)){ return response()->json('success'); }
        }

        public function syncHospitalBillingAccount(Request $request){
            if(_Sync::syncHospitalBillingAccount($request)){ return response()->json('success'); }
        }

        public function syncHospitalDTRLogs(Request $request){
            if(_Sync::syncHospitalDTRLogs($request)){ return response()->json('success'); }
        }

        public function syncHospitalEmployeeDetails(Request $request){
            if(_Sync::syncHospitalEmployeeDetails($request)){ return response()->json('success'); }
        }

        public function syncHospitalEmployeePayrollAdd(Request $request){
            if(_Sync::syncHospitalEmployeePayrollAdd($request)){ return response()->json('success'); }
        }

        public function syncHrAccount(Request $request){
            if(_Sync::syncHrAccount($request)){ return response()->json('success'); }
        }

        public function syncImaging(Request $request){
            if(_Sync::syncImaging($request)){ return response()->json('success'); }
        } 

        public function syncImagingCenter(){
            if(_Sync::syncImagingCenter()){ return response()->json('success'); }
        } 

        public function syncImagingCenterRecord(){
            if(_Sync::syncImagingCenterRecord()){ return response()->json('success'); }
        } 

        public function syncImagingFormHeader(Request $request){
            if(_Sync::syncImagingFormHeader($request)){ return response()->json('success'); }
        } 

        public function syncImagingOrderMenu(Request $request){
            if(_Sync::syncImagingOrderMenu($request)){ return response()->json('success'); }
        } 

        public function syncLaboratory(){
            if(_Sync::syncLaboratory()){ return response()->json('success'); }
        } 

        public function syncLaboratoryCBC(){
            if(_Sync::syncLaboratoryCBC()){ return response()->json('success'); }
        } 

        public function syncLaboratoryChemistry(){
            if(_Sync::syncLaboratoryChemistry()){ return response()->json('success'); }
        } 

        public function syncLaboratoryCovid19Test(){
            if(_Sync::syncLaboratoryCovid19Test()){ return response()->json('success'); }
        } 

        public function syncLaboratoryDrugTest(){
            if(_Sync::syncLaboratoryDrugTest()){ return response()->json('success'); }
        } 

        public function syncLaboratoryECG(){
            if(_Sync::syncLaboratoryECG()){ return response()->json('success'); }
        } 

        public function syncLaboratoryFecal(){
            if(_Sync::syncLaboratoryFecal()){ return response()->json('success'); }
        } 

        public function syncLaboratoryFormheader(Request $request){
            if(_Sync::syncLaboratoryFormheader($request)){ return response()->json('success'); }
        } 

        public function syncLaboratoryHemathology(){
            if(_Sync::syncLaboratoryHemathology()){ return response()->json('success'); }
        } 

        public function syncLaboratoryHepatitisProfile(){
            if(_Sync::syncLaboratoryHepatitisProfile()){ return response()->json('success'); }
        }

        public function syncLaboratoryImmunology(){
            if(_Sync::syncLaboratoryImmunology()){ return response()->json('success'); }
        }

        public function syncLaboratoryItems(Request $request){
            if(_Sync::syncLaboratoryItems($request)){ return response()->json('success'); }
        } 

        public function syncLaboratoryItemsOrder(Request $request){
            if(_Sync::syncLaboratoryItemsOrder($request)){ return response()->json('success'); }
        } 

        public function syncLaboratoryItemsMonitoring(Request $request){
            if(_Sync::syncLaboratoryItemsMonitoring($request)){ return response()->json('success'); }
        } 

        public function syncLaboratoryList(Request $request){
            if(_Sync::syncLaboratoryList($request)){ return response()->json('success'); }
        } 

        public function syncLaboratoryMedicalExam(Request $request){
            if(_Sync::syncLaboratoryMedicalExam($request)){ return response()->json('success'); }
        } 
        
        public function syncLaboratoryMicroscopy(){
            if(_Sync::syncLaboratoryMicroscopy()){ return response()->json('success'); }
        } 

        public function syncLaboratoryMiscellaneous(){
            if(_Sync::syncLaboratoryMiscellaneous()){ return response()->json('success'); }
        } 

        public function syncLaboratoryOralGlucose(){
            if(_Sync::syncLaboratoryOralGlucose()){ return response()->json('success'); }
        } 

        public function syncLaboratoryPapsmear(){
            if(_Sync::syncLaboratoryPapsmear()){ return response()->json('success'); }
        } 

        public function syncLaboratoryRequestItem(){
            if(_Sync::syncLaboratoryRequestItem()){ return response()->json('success'); }
        } 
        
        public function syncLaboratorySorology(){
            if(_Sync::syncLaboratorySorology()){ return response()->json('success'); }
        } 

        public function syncLaboratoryStoolTest(){
            if(_Sync::syncLaboratoryStoolTest()){ return response()->json('success'); }
        } 

        public function syncLaboratoryTest(){
            if(_Sync::syncLaboratoryTest()){ return response()->json('success'); }
        }

        public function syncLaboratoryThyroidProfile(){
            if(_Sync::syncLaboratoryThyroidProfile()){ return response()->json('success'); }
        }

        public function syncLaboratoryTumorMaker(){
            if(_Sync::syncLaboratoryTumorMaker()){ return response()->json('success'); }
        }

        public function syncLaboratoryUrinalysis(){
            if(_Sync::syncLaboratoryUrinalysis()){ return response()->json('success'); }
        }

        public function syncManagement(Request $request){
            if(_Sync::syncManagement($request)){ return response()->json('success'); }
        }

        public function syncManagementAccreditedCompanies(Request $request){
            if(_Sync::syncManagementAccreditedCompanies($request)){ return response()->json('success'); }
        }

        public function syncManagementAccreditedCompanyHMO(Request $request){
            if(_Sync::syncManagementAccreditedCompanyHMO($request)){ return response()->json('success'); }
        }

        public function syncMedicalExaminationTest(Request $request){
            if(_Sync::syncMedicalExaminationTest($request)){ return response()->json('success'); }
        }

        // public function syncMobileVanQueue(Request $request){
        //     if(_Sync::syncMobileVanQueue($request)){ return response()->json('success'); }
        // }
        
        public function syncNurseAccount(Request $request){
            if(_Sync::syncNurseAccount($request)){ return response()->json('success'); }
        } 

        public function syncOperationManagerAccount(Request $request){
            if(_Sync::syncOperationManagerAccount($request)){ return response()->json('success'); }
        } 

        public function syncOtherAccount(Request $request){
            if(_Sync::syncOtherAccount($request)){ return response()->json('success'); }
        } 

        public function syncOtherOrderTest(Request $request){
            if(_Sync::syncOtherOrderTest($request)){ return response()->json('success'); }
        } 

        public function syncPackagesCharge(Request $request){
            if(_Sync::syncPackagesCharge($request)){ return response()->json('success'); }
        }

        public function syncPackagesOrderList(Request $request){
            if(_Sync::syncPackagesOrderList($request)){ return response()->json('success'); }
        }

        public function syncPatients(Request $request){
            if(_Sync::syncPatients($request)){ return response()->json('success'); }
        }
        
        public function syncPatientsCholesterolHistory(){
            if(_Sync::syncPatientsCholesterolHistory()){ return response()->json('success'); }
        }

        public function syncPatientsContactTracing(){
            if(_Sync::syncPatientsContactTracing()){ return response()->json('success'); }
        }

        public function syncPatientsDiagnosis(){
            if(_Sync::syncPatientsDiagnosis()){ return response()->json('success'); }
        }

        public function syncPatientsDiets(){
            if(_Sync::syncPatientsDiets()){ return response()->json('success'); }
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

        // public function syncPatientQueue(){
        //     if(_Sync::syncPatientQueue()){ return response()->json('success'); }
        // }

        public function syncPatientShareImages(){
            if(_Sync::syncPatientShareImages()){ return response()->json('success'); }
        }

        public function syncPayrollHeader(Request $request){
            if(_Sync::syncPayrollHeader($request)){ return response()->json('success'); }
        }

        public function pharmacyclinicWarehoseDRAccounts(Request $request){
            if(_Sync::pharmacyclinicWarehoseDRAccounts($request)){ return response()->json('success'); }
        }

        public function pharmacyclinicWarehoseDraccountsAgent(Request $request){
            if(_Sync::pharmacyclinicWarehoseDraccountsAgent($request)){ return response()->json('success'); }
        }
        
        public function pharmacyclinicWarehoseProducts(Request $request){
            if(_Sync::pharmacyclinicWarehoseProducts($request)){ return response()->json('success'); }
        }

        public function pharmacyclinicWarehouseBrand(Request $request){
            if(_Sync::pharmacyclinicWarehouseBrand($request)){ return response()->json('success'); }
        }

        public function pharmacyclinicWarehouseCategory(Request $request){
            if(_Sync::pharmacyclinicWarehouseCategory($request)){ return response()->json('success'); }
        }

        public function pharmacyclinicWarehouseInventory(Request $request){
            if(_Sync::pharmacyclinicWarehouseInventory($request)){ return response()->json('success'); }
        }

        public function pharmacyclinicWarehouseInventoryForapproval(Request $request){
            if(_Sync::pharmacyclinicWarehouseInventoryForapproval($request)){ return response()->json('success'); }
        }

        public function pharmacyclinicWarehouseInventoryTempExclusive(Request $request){
            if(_Sync::pharmacyclinicWarehouseInventoryTempExclusive($request)){ return response()->json('success'); }
        }

        public function pharmacyclinicWarehouseInventoryTemp(Request $request){
            if(_Sync::pharmacyclinicWarehouseInventoryTemp($request)){ return response()->json('success'); }
        }

        public function pharmacyclinicWarehousePOInvoiceTemp(Request $request){
            if(_Sync::pharmacyclinicWarehousePOInvoiceTemp($request)){ return response()->json('success'); }
        }

        // public function syncPharmacy(Request $request){
        //     if(_Sync::syncPharmacy($request)){ return response()->json('success'); }
        // }

        // public function syncPharmacyHospitalHistory(Request $request){
        //     if(_Sync::syncPharmacyHospitalHistory($request)){ return response()->json('success'); }
        // }

        // public function syncPharmacyHospitalInventory(Request $request){
        //     if(_Sync::syncPharmacyHospitalInventory($request)){ return response()->json('success'); }
        // }

        // public function syncPharmacyHospitalProducts(Request $request){
        //     if(_Sync::syncPharmacyHospitalProducts($request)){ return response()->json('success'); }
        // }

        // public function syncPharmacyHospitalReceipt(Request $request){
        //     if(_Sync::syncPharmacyHospitalReceipt($request)){ return response()->json('success'); }
        // }

        // public function syncPharmacyHospitalSales(Request $request){
        //     if(_Sync::syncPharmacyHospitalSales($request)){ return response()->json('success'); }
        // }

        // public function syncPharmacyBranches(Request $request){
        //     if(_Sync::syncPharmacyBranches($request)){ return response()->json('success'); }
        // }

        public function syncPsychologyAccount(Request $request){
            if(_Sync::syncPsychologyAccount($request)){ return response()->json('success'); }
        } 
        
        public function syncPsychologyAudiometry(Request $request){
            if(_Sync::syncPsychologyAudiometry($request)){ return response()->json('success'); }
        }
        public function syncPsychologyIshihara(Request $request){
            if(_Sync::syncPsychologyIshihara($request)){ return response()->json('success'); }
        }
        public function syncPsychologyNeuroexam(Request $request){
            if(_Sync::syncPsychologyNeuroexam($request)){ return response()->json('success'); }
        }

        public function syncPsychologyTest(Request $request){
            if(_Sync::syncPsychologyTest($request)){ return response()->json('success'); }
        }

        public function syncPsychologyTestOrders(Request $request){
            if(_Sync::syncPsychologyTestOrders($request)){ return response()->json('success'); }
        }
        
        public function syncRadiologist(Request $request){
            if(_Sync::syncRadiologist($request)){ return response()->json('success'); }
        } 

        public function syncReceivingAccount(Request $request){
            if(_Sync::syncReceivingAccount($request)){ return response()->json('success'); }
        }

        public function syncReceivingSpecimen(Request $request){
            if(_Sync::syncReceivingSpecimen($request)){ return response()->json('success'); }
        }

        public function stockroomAcccount(Request $request){
            if(_Sync::stockroomAcccount($request)){ return response()->json('success'); }
        }

        public function stockroomAcccountProducts(Request $request){
            if(_Sync::stockroomAcccountProducts($request)){ return response()->json('success'); }
        }

        public function syncTriageAccount(Request $request){
            if(_Sync::syncTriageAccount($request)){ return response()->json('success'); }
        }

        public function syncUsers(Request $request){
            if(_Sync::syncUsers($request)){ return response()->json('success'); }
        }

        public function syncUsersGeolocation(){
            if(_Sync::syncUsersGeolocation()){ return response()->json('success'); }
        }        

        public function syncWarehouseAccounts(Request $request){
            if(_Sync::syncWarehouseAccounts($request)){ return response()->json('success'); }
        }

        public function syncLabSarsCov(Request $request){
            if(_Sync::syncLabSarsCov($request)){ return response()->json('success'); }
        }

        public function syncClinicAllHistoryPE(Request $request){
            if(_Sync::syncClinicAllHistoryPE($request)){ return response()->json('success'); }
        }
 
        
    }
