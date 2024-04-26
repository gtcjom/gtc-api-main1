<?php

use App\Http\Controllers\Accounting;
use App\Http\Controllers\Administrator;
use App\Http\Controllers\Admission;
use App\Http\Controllers\Admitting;
use App\Http\Controllers\Appointment;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Billing;
use App\Http\Controllers\Cashier;
use App\Http\Controllers\Doctor;
use App\Http\Controllers\Documentation;
use App\Http\Controllers\DTRLogs;
use App\Http\Controllers\Encoder;
use App\Http\Controllers\Endorsement;
use App\Http\Controllers\Haptech;
use App\Http\Controllers\HMIS;
use App\Http\Controllers\HR;
use App\Http\Controllers\Imaging;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\Ishihara;
use App\Http\Controllers\Laboratory;
use App\Http\Controllers\Notes;
use App\Http\Controllers\Nurse;
use App\Http\Controllers\OM;
use App\Http\Controllers\OperatingRoom;
use App\Http\Controllers\Other;
use App\Http\Controllers\Pharmacy;
use App\Http\Controllers\Prescription;
use App\Http\Controllers\Psychology;
use App\Http\Controllers\Radiologist;
use App\Http\Controllers\Receiving;
use App\Http\Controllers\StockRoom;
use App\Http\Controllers\Sync;
use App\Http\Controllers\TreatmentPlan;
use App\Http\Controllers\Triage;
use App\Http\Controllers\Van;
use App\Http\Controllers\Warehouse;
use Illuminate\Support\Facades\Route;

// Route::post('auth/login', 'AuthController@login');
// Route::post('auth/logout', 'AuthController@logout');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login', [AuthController::class, 'login']);

//LOGS
Route::post("qr/insert-in-logs", [DTRLogs::class, 'getInsertInLogs']);
Route::post("qr/insert-out-logs", [DTRLogs::class, 'getInsertOutLogs']);

//SYNC DATA TO CLOUD
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-accounting-account", [Sync::class, 'syncAccountingAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-admission-account", [Sync::class, 'syncAdmissionAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-appointment-list", [Sync::class, 'syncAppointmentList']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-cashier", [Sync::class, 'syncCashier']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-cashier-patientbills-record", [Sync::class, 'syncCashierPatientBillsRecord']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-cashier-sales-expenses", [Sync::class, 'syncCashierSalesExpenses']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-cashier-statement-of-account", [Sync::class, 'syncCashierStatementOfAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-clinic-bank", [Sync::class, 'syncClinicBank']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-clinic-bank-contacts", [Sync::class, 'syncClinicBankContacts']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-clinic-bank-transaction", [Sync::class, 'syncClinicBankTransaction']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-clinic-expenses-list", [Sync::class, 'syncClinicExpensesList']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-clinic-leave-application", [Sync::class, 'syncClinicLeaveApplication']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-clinic-resignation-type", [Sync::class, 'syncClinicResignationType']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-endorsement-account", [Sync::class, 'syncEndorsementAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-form-footer-header-information", [Sync::class, 'syncFormFooterHeaderInformation']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors", [Sync::class, 'syncDoctors']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-appointment-services", [Sync::class, 'syncDoctorsAppointmentServices']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-comments", [Sync::class, 'syncDoctorsComments']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-medical-certificate-ordered", [Sync::class, 'syncDoctorsMedicalCertificateOrdered']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-notes", [Sync::class, 'syncDoctorsNotes']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-notes-canvas", [Sync::class, 'syncDoctorsNotesCanvas']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-notification", [Sync::class, 'syncDoctorsNotification']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-patients", [Sync::class, 'syncDoctorsPatients']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-prescriptions", [Sync::class, 'syncDoctorsPrescriptions']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-rx-header", [Sync::class, 'syncDoctorsRxHeader']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-specialization", [Sync::class, 'syncDoctorsSpecializationList']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctors-treatmentplan", [Sync::class, 'syncDoctorsTreatmentPlan']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-doctor-salary-record", [Sync::class, 'syncDoctorSalaryRecord']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-encoder", [Sync::class, 'syncEncoder']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-encoder-patientbills-record", [Sync::class, 'syncEncoderPatientBillsRecord']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-general-management", [Sync::class, 'syncGeneralManagement']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-general-management-branches", [Sync::class, 'syncGeneralManagementBranches']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-haptech-account", [Sync::class, 'syncHaptechAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-hmo-list", [Sync::class, 'syncHMOList']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-hospital-billing-account", [Sync::class, 'syncHospitalBillingAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-hospital-dtr-logs", [Sync::class, 'syncHospitalDTRLogs']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-hospital_employee_details", [Sync::class, 'syncHospitalEmployeeDetails']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-hospital-employee-payroll-add", [Sync::class, 'syncHospitalEmployeePayrollAdd']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-hr-account", [Sync::class, 'syncHrAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-imaging", [Sync::class, 'syncImaging']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-imaging-center", [Sync::class, 'syncImagingCenter']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-imaging-centerrecord", [Sync::class, 'syncImagingCenterRecord']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-imaging-formheader", [Sync::class, 'syncImagingFormHeader']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-imaging-order-menu", [Sync::class, 'syncImagingOrderMenu']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory", [Sync::class, 'syncLaboratory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-cbc", [Sync::class, 'syncLaboratoryCBC']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-chemistry", [Sync::class, 'syncLaboratoryChemistry']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-covid19-test", [Sync::class, 'syncLaboratoryCovid19Test']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-drug-test", [Sync::class, 'syncLaboratoryDrugTest']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-ecg", [Sync::class, 'syncLaboratoryECG']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-fecal", [Sync::class, 'syncLaboratoryFecal']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-formheader", [Sync::class, 'syncLaboratoryFormheader']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-hemathology", [Sync::class, 'syncLaboratoryHemathology']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-hepatitis-profile", [Sync::class, 'syncLaboratoryHepatitisProfile']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-immunology", [Sync::class, 'syncLaboratoryImmunology']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-items", [Sync::class, 'syncLaboratoryItems']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-items-laborder", [Sync::class, 'syncLaboratoryItemsOrder']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-items-monitoring", [Sync::class, 'syncLaboratoryItemsMonitoring']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-list", [Sync::class, 'syncLaboratoryList']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-medical-exam", [Sync::class, 'syncLaboratoryMedicalExam']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-microscopy", [Sync::class, 'syncLaboratoryMicroscopy']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-miscellaneous", [Sync::class, 'syncLaboratoryMiscellaneous']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-oral-glucose", [Sync::class, 'syncLaboratoryOralGlucose']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-papsmear", [Sync::class, 'syncLaboratoryPapsmear']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-request-item", [Sync::class, 'syncLaboratoryRequestItem']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-sorology", [Sync::class, 'syncLaboratorySorology']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-stooltest", [Sync::class, 'syncLaboratoryStoolTest']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-test", [Sync::class, 'syncLaboratoryTest']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-thyroid-profile", [Sync::class, 'syncLaboratoryThyroidProfile']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-tumor-maker", [Sync::class, 'syncLaboratoryTumorMaker']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-urinalysis", [Sync::class, 'syncLaboratoryUrinalysis']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-management", [Sync::class, 'syncManagement']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-management-accredited-companies", [Sync::class, 'syncManagementAccreditedCompanies']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-management-accredited-company-hmo", [Sync::class, 'syncManagementAccreditedCompanyHMO']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-medical-examination-test", [Sync::class, 'syncMedicalExaminationTest']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-nurses", [Sync::class, 'syncNurseAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-operation-manager-account", [Sync::class, 'syncOperationManagerAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-other-account", [Sync::class, 'syncOtherAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-other-order-test", [Sync::class, 'syncOtherOrderTest']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-packages-charge", [Sync::class, 'syncPackagesCharge']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-packages-order-list", [Sync::class, 'syncPackagesOrderList']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients", [Sync::class, 'syncPatients']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-cholesterol-history", [Sync::class, 'syncPatientsCholesterolHistory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-contacttracing", [Sync::class, 'syncPatientsContactTracing']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-diagnosis", [Sync::class, 'syncPatientsDiagnosis']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-diets", [Sync::class, 'syncPatientsDiets']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-familyhistories", [Sync::class, 'syncPatientsFamilyHistories']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-familyhistory", [Sync::class, 'syncPatientsFamilyHistory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-glucosehistory", [Sync::class, 'syncPatientsGlucoseHistory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-history", [Sync::class, 'syncPatientsHistory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-attachmenthistory", [Sync::class, 'syncPatientHistoryAttachment']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-calciumhistory", [Sync::class, 'syncPatientHistoryCalcium']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-chloridehistory", [Sync::class, 'syncPatientHistoryChloride']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-creatininehistory", [Sync::class, 'syncPatientHistoryCreatinine']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-HDLhistory", [Sync::class, 'syncPatientHistoryHDL']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-LDLhistory", [Sync::class, 'syncPatientHistoryLDL']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-lithiumhistory", [Sync::class, 'syncPatientHistoryLithium']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-magnessiumhistory", [Sync::class, 'syncPatientHistoryMagnessium']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-potassiumhistory", [Sync::class, 'syncPatientHistoryPotassium']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-protienhistory", [Sync::class, 'syncPatientHistoryProtein']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-sodiumhistory", [Sync::class, 'syncPatientHistorySoduim']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-labbphistory", [Sync::class, 'syncPatientHistoryLabBP']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-painhistory", [Sync::class, 'syncPatientPainHistory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-permission", [Sync::class, 'syncPatientPermission']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-personal-medication", [Sync::class, 'syncPatientPersonalMedication']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-pulse-history", [Sync::class, 'syncPatientPulseHistory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-respiratory-history", [Sync::class, 'syncPatientRespiratoryHistory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-temp-history", [Sync::class, 'syncPatientTempHistory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-uricacid-history", [Sync::class, 'syncPatientUricAcidHistory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-weight-history", [Sync::class, 'syncPatientWeightHistory']);
// Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patient-queue", [Sync::class, 'syncPatientQueue']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-patients-sharedimages", [Sync::class, 'syncPatientShareImages']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-payroll-header", [Sync::class, 'syncPayrollHeader']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyclinic-warehose-draccounts", [Sync::class, 'pharmacyclinicWarehoseDRAccounts']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyclinic-warehose-draccounts-agent", [Sync::class, 'pharmacyclinicWarehoseDraccountsAgent']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyclinic-warehose-products", [Sync::class, 'pharmacyclinicWarehoseProducts']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyclinic-warehouse-brand", [Sync::class, 'pharmacyclinicWarehouseBrand']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyclinic-warehouse-category", [Sync::class, 'pharmacyclinicWarehouseCategory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyclinic-warehouse-inventory", [Sync::class, 'pharmacyclinicWarehouseInventory']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyclinic-warehouse-inventory-forapproval", [Sync::class, 'pharmacyclinicWarehouseInventoryForapproval']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyclinic-warehouse-inventory-temp-exclusive", [Sync::class, 'pharmacyclinicWarehouseInventoryTempExclusive']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyclinic-warehouse-inventory-temp", [Sync::class, 'pharmacyclinicWarehouseInventoryTemp']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyclinic-warehouse-po-invoice-temp", [Sync::class, 'pharmacyclinicWarehousePOInvoiceTemp']);
// Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacy", [Sync::class, 'syncPharmacy']);
// Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyhospital-history", [Sync::class, 'syncPharmacyHospitalHistory']);
// Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyhospital-inventory", [Sync::class, 'syncPharmacyHospitalInventory']);
// Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyhospital-products", [Sync::class, 'syncPharmacyHospitalProducts']);
// Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyhospital-receipt", [Sync::class, 'syncPharmacyHospitalReceipt']);
// Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacyhospital-sales", [Sync::class, 'syncPharmacyHospitalSales']);
// Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-pharmacy-branches", [Sync::class, 'syncPharmacyBranches']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-psychology-account", [Sync::class, 'syncPsychologyAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-psychology-audiometry", [Sync::class, 'syncPsychologyAudiometry']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-psychology-ishihara", [Sync::class, 'syncPsychologyIshihara']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-psychology-neuroexam", [Sync::class, 'syncPsychologyNeuroexam']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-psychology-test", [Sync::class, 'syncPsychologyTest']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-psychology-test-orders", [Sync::class, 'syncPsychologyTestOrders']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-radiologist", [Sync::class, 'syncRadiologist']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-receiving-account", [Sync::class, 'syncReceivingAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-receiving-specimen", [Sync::class, 'syncReceivingSpecimen']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-stockroom-acccount", [Sync::class, 'stockroomAcccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-stockroom-account-products", [Sync::class, 'stockroomAcccountProducts']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-triage-account", [Sync::class, 'syncTriageAccount']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-users", [Sync::class, 'syncUsers']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-users-geolocation", [Sync::class, 'syncUsersGeolocation']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-warehouse-accounts", [Sync::class, 'syncWarehouseAccounts']);

// newly added 1/25/2022

Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-laboratory-sars-cov", [Sync::class, 'syncLabSarsCov']);
Route::get("ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/ddf1f5asdf5/sycn-clinic-all-history-pe", [Sync::class, 'syncClinicAllHistoryPE']);

Route::group([
    'middleware' => 'auth:api',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    Route::post("auth/logout", [AuthController::class, 'logout']);
    Route::get("my/information", [InformationController::class, 'myinfo']);

    //NEWS
    Route::get("gtc/news/featured-list", [Administrator::class, 'getGTCFeaturedNews']);
    Route::get("gtc/news/featured-list-more", [Administrator::class, 'getGTCFeaturedNewsMore']);

    // news in dialog
    Route::get("gtc/news/featured/dialog-list", [Administrator::class, 'getGTCDialogList']);

    //HMIS
    Route::get("hmis/sidebar/header-infomartion", [HMIS::class, 'hmisGetHeaderInfo']);
    Route::get("hmis/dashboard/get-all-income", [HMIS::class, 'hmisGetAllIncome']);
    Route::get("hims/laboratory/test/getAllTest", [HMIS::class, 'hmisGetAllLabTest']);
    Route::post("hims/laboratory/test/newtest-save", [HMIS::class, 'himsSaveNewTest']);
    Route::get('hims/laboratory/sales-result', [HMIS::class, 'himsSalesResultLab']);
    Route::get('hims/laboratory/pending-patient', [HMIS::class, 'himsPendingPatientsLab']);
    Route::get('hims/imaging/sales-result', [HMIS::class, 'himsSalesResultImg']);
    Route::get('hims/imaging/pending-patient', [HMIS::class, 'himsPendingPatientsImg']);
    Route::get('hmis/get-all-or/kwqebwqetkjuiAFNFINafjafnf', [HMIS::class, 'himsGetAllORByBillFrom']);
    Route::get('hmis/get-receipt-info-print', [HMIS::class, 'himsGetReceiptInfoPrint']);
    Route::get('hmis/get-all-active', [HMIS::class, 'himsGetAllActive']);
    Route::get('hims/get-all/account-list', [HMIS::class, 'himsGetAllAccountList']);
    Route::get('hims/get-all/active-not-yet', [HMIS::class, 'himsGetAllAccountActive']);
    Route::get("hims/get-personal-info-by-id", [HMIS::class, 'hismGetPersonalInfoById']);
    Route::post("hims/information/personal-uploadnewprofile", [HMIS::class, 'himsUploadProfile']);
    Route::post("hims/information/personal-update", [HMIS::class, 'himsUpdatePersonalInfo']);
    Route::post("hmis/update-username", [HMIS::class, 'himsUpdateUsername']);
    Route::post("hims/update-password", [HMIS::class, 'himsUpdatePassword']);
    Route::get('hims/get/all-users-account', [HMIS::class, 'himsGetAllUsersAccount']);
    Route::post("hims/add/new-account", [HMIS::class, 'himsAddNewDepartmentAccount']);
    Route::post("hmis/update/account-inactive", [HMIS::class, 'himsUpdateAccountToInactive']);
    Route::get('hmis/income/report/bymonth', [HMIS::class, 'himsGetIncomeReportByYear']);
    Route::get('hmis/get-all-employee', [HMIS::class, 'himsGetAllEmployee']);

    //new
    Route::get('hmis/filter-with-date/get-all-employee', [HMIS::class, 'himsGetAllEmployeeWithDate']);
    Route::get('hmis/get/employee-info', [HMIS::class, 'hmisGetEmployeeInfoPayroll']);
    Route::get('hmis/payroll/payslip/report-bydate', [HMIS::class, 'hmisGetPayrollReportByDate']);

    //RADIOLOGIST
    Route::get('radiologist/patients/getpatient-forreview', [Radiologist::class, 'getPatientForReview']);
    Route::get('radiologist/patients/getpatients-read', [Radiologist::class, 'getPatientReviewed']);
    Route::post('radiologist/patients/order/patients-orderdetails', [Radiologist::class, 'getOrderDetails']);
    Route::post('radiologist/patients/order/order-saveresult', [Radiologist::class, 'saveOrderResult']);
    Route::get('radiologist/sidebar/header-infomartion', [Radiologist::class, 'radiologistGetHeaderInfo']);
    Route::get("radiologist/get-personal-info-by-id", [Radiologist::class, 'hisradGetPersonalInfoById']);
    Route::post("radiologist/information/personal-uploadnewprofile", [Radiologist::class, 'hisradUploadProfile']);
    Route::post("radiologist/information/personal-update", [Radiologist::class, 'hisradUpdatePersonalInfo']);
    Route::post("radiologist/update-username", [Radiologist::class, 'hisradUpdateUsername']);
    Route::post("radiologist/update-password", [Radiologist::class, 'hisradUpdatePassword']);

    //PHARMACY
    Route::get("pharmacy/sidebar/header-infomartion", [Pharmacy::class, 'hispharmacyGetHeaderInfo']);
    Route::get("pharmacy/get-spec-role", [Pharmacy::class, 'hispharmacyGetRole']);
    Route::get("pharmacy/get-product-list", [Pharmacy::class, 'hispharmacyGetInventoryList']);
    Route::get("pharmacy/get-purchase-list", [Pharmacy::class, 'hispharmacyGetPuchaseList']);
    Route::get("pharmacy/get-brand-list", [Pharmacy::class, 'hispharmacyGetBrandList']);
    Route::get("pharmacy/get-batch-list", [Pharmacy::class, 'hispharmacyGetBatchList']);
    Route::get("pharmacy/get-batch-info", [Pharmacy::class, 'hispharmacyGetBatchInfo']);
    Route::post("pharmacy/confirm-payment", [Pharmacy::class, 'hispharmacyConfirmPaymentPurchase']);
    Route::post("pharmacy/new-product-save", [Pharmacy::class, 'hispharmacyNewProductSave']);
    Route::get("pharmacy/get-batches-by-product-id", [Pharmacy::class, 'hispharmacyGetBatchesByProdId']);
    Route::post("pharmacy/add-batch-by-id", [Pharmacy::class, 'hispharmacyAddNewStockByProdId']);
    Route::post("pharmacy/add-qty-by-specific", [Pharmacy::class, 'hispharmacyAddQtyBySpecificBatch']);
    Route::post("pharmacy/del-qty-by-specific", [Pharmacy::class, 'hispharmacyDelQtyBySpecificBatch']);
    Route::post("pharmacy/add-purchase", [Pharmacy::class, 'hispharmacyAddPuchase']);
    Route::get("pharmacy/get-receipt-list", [Pharmacy::class, 'hispharmacyGetReceiptList']);
    Route::get("pharmacy/get-receipt-info-print", [Pharmacy::class, 'hispharmacyPrintForTransaction']);
    Route::get("pharmacy/get-stock-list", [Pharmacy::class, 'hispharmacyGetStockList']);
    Route::get("pharmacy/get-log-list", [Pharmacy::class, 'hispharmacyGetLogAct']);
    Route::get("pharmacy/get-sales-list", [Pharmacy::class, 'hispharmacyGetSalesReport']);
    Route::post("pharmacy/get-filter-by-date", [Pharmacy::class, 'hispharmacyGetFilterByDate']);
    Route::get("pharmacy/get-filter-by-date", [Pharmacy::class, 'hispharmacyGetFilterByDate']);
    Route::post("pharmacy/delete-purchase-id", [Pharmacy::class, 'hispharmacyDeletePurchaseById']);
    Route::get("pharmacy/get-personal-info-by-id", [Pharmacy::class, 'hispharmacyGetPersonalInfoById']);
    Route::post("pharmacy/information/personal-uploadnewprofile", [Pharmacy::class, 'hispharmacyUploadProfile']);
    Route::post("pharmacy/information/personal-update", [Pharmacy::class, 'hispharmacyUpdatePersonalInfo']);
    Route::post("pharmacy/update-username", [Pharmacy::class, 'hispharmacyUpdateUsername']);
    Route::post("pharmacy/update-password", [Pharmacy::class, 'hispharmacyUpdatePassword']);
    Route::get("pharmacy/get-prescription-list", [Pharmacy::class, 'hispharmacyGetPrescriptionList']);
    Route::get("pharmacy/get-prescription-details", [Pharmacy::class, 'hispharmacyGetPrescriptionDetails']);
    Route::get("pharmacy/rx/doctorsdetails", [Pharmacy::class, 'hispharmacyGetRxDoctorsRx']);
    Route::get("pharmacy/patient-information", [Pharmacy::class, 'hispharmacyGetPatientInformation']);
    Route::get("pharmacy/rx/prescriptiondetails", [Pharmacy::class, 'hispharmacyGetPrescription']);
    Route::post("pharmacy/get-prescription-update-qty", [Pharmacy::class, 'hispharmacyUpdateQtyPrescription']);
    Route::post("pharmacy/process-payment-prescription", [Pharmacy::class, 'hispharmacyProcessPaymentPresc']);
    Route::get("pharmacy/get/all-unclaimed-pres", [Pharmacy::class, 'getAllUnClaimedPres']);

    //CASHIER
    Route::get("cashier/sidebar/header-infomartion", [Cashier::class, 'hiscashierGetHeaderInfo']);
    Route::get("cashier/patient/billing", [Cashier::class, 'hiscashierGetPatientsBillings']);
    Route::get("cashier/pateint/billing/details", [Cashier::class, 'hiscashierGetPatientsBillingsDetails']);
    Route::post("cashier/billing/cancel-bill", [Cashier::class, 'hiscashierBillingCancel']);
    Route::post("cashier/billing/setaspaid-bill", [Cashier::class, 'hiscashierBillingSetAsPaid']);
    Route::get("cashier/billing/records/list", [Cashier::class, 'hiscashierGetBillingRecords']);
    Route::get("cashier/billing/records/refund-list", [Cashier::class, 'hiscashierRefundOrderList']);
    Route::get("cashier/billing/records/details/by-orderid", [Cashier::class, 'hiscashierGetBillingRecordsDetails']);
    Route::post("cashier/billing/records/refund-orderbyid", [Cashier::class, 'hiscashierRefundOrder']);
    Route::get("cashier/get-personal-info-by-id", [Cashier::class, 'hiscashierGetPersonalInfoById']);
    Route::post("cashier/information/personal-uploadnewprofile", [Cashier::class, 'hiscashierUploadProfile']);
    Route::post("cashier/information/personal-update", [Cashier::class, 'hiscashierUpdatePersonalInfo']);
    Route::post("cashier/update-username", [Cashier::class, 'hiscashierUpdateUsername']);
    Route::post("cashier/update-password", [Cashier::class, 'hiscashierUpdatePassword']);
    Route::get("cashier/patient/billing/receipt/details", [Cashier::class, 'hiscashierReceiptDetails']);

    //ADMISSION
    Route::get('admission/sidebar/header-infomartion', [Admission::class, 'hisadmissionGetHeaderInfo']);
    Route::get('admission/get-personal-info-by-id', [Admission::class, 'hisadmissionGetPersonalInfoById']);
    Route::post("admission/information/personal-uploadnewprofile", [Admission::class, 'hisadmissionUploadProfile']);
    Route::post("admission/information/personal-update", [Admission::class, 'hisadmissionUpdatePersonalInfo']);
    Route::post("admission/update-username", [Admission::class, 'hisadmissionUpdateUsername']);
    Route::post("admission/update-password", [Admission::class, 'hisadmissionUpdatePassword']);
    Route::get('admission/patients/getpatient-list', [Admission::class, 'hisadmissionGetPatientList']);
    Route::get('admission/get-all-doctors', [Admission::class, 'hisadmissionGetAllDoctors']);
    Route::post("admission/patients/newpatient-save", [Admission::class, 'hisadmissionNewPatient']);
    Route::get('admission/patient/patient-information', [Admission::class, 'hisadmissionGetPatientInformation']);
    Route::get('admission/patient-triage/patient-information', [Admission::class, 'hisadmissionGetPatientInformationTriage']);
    Route::get('admission/patients/getpatient-info', [Admission::class, 'hisadmissionGetPatientInfo']);
    Route::post("admission/patients/edit-patient", [Admission::class, 'hisadmissionUpdatePatientInfo']);
    Route::get('admission/patients/contacttracing/record', [Admission::class, 'getContactTracingRecord']);
    Route::get('admission/imaging/imaging-details', [Admission::class, 'getImagingDetails']);
    Route::get('admission/imaging/local/imaging-orderlist', [Admission::class, 'imagingOrderList']);
    Route::get('admission/imaging/local/imaging-orderlist/details', [Admission::class, 'imagingOrderSelectedDetails']);
    Route::get('admission/imaging/local/imaging-orderlist/unsave', [Admission::class, 'imagingAddOrderUnsavelist']);
    Route::post('admission/imaging/local/order-add', [Admission::class, 'imagingAddOrder']);
    Route::post("admission/order/local/unsave/process-order", [Admission::class, 'imagingOrderUnsaveProcess']);
    Route::get('admission/order/local/getimaging-list', [Admission::class, 'getImagingOrderList']);
    Route::get('admission/laboratory/new/order/unsave-orderlist', [Admission::class, 'getUnsaveLabOrder']);
    Route::post('admission/laboratory/new/order/order-addtounsave', [Admission::class, 'addLabOrderTounsave']);
    Route::post('admission/laboratory/new/order/process-laborder', [Admission::class, 'processLabOrder']);
    Route::post('admission/patient/laboratory/order/paid-list', [Admission::class, 'laboratoryPaidOrderByPatient']);
    Route::post('admission/patient/laboratory/order/unpaid-list', [Admission::class, 'laboratoryUnpaidOrderByPatient']);
    Route::post('admission/patient/laboratory/order/unpaid-listdetails', [Admission::class, 'laboratoryUnpaidOrderByPatientDetails']);

    Route::get('admission/patients/triage/getpatient-list', [Admission::class, 'hisadmissionGetPatientListQueue']);
    Route::post("admission/patients/edit-contact-tracing", [Admission::class, 'hisadmissionUpdatePatientContactTracing']);
    Route::post("admission/patient/create-appointment", [Admission::class, 'hisAdmissionCreateAppointment']);
    Route::post("admission/patient/appointment/reschedule", [Admission::class, 'hisAdmissionRescheduleAppointment']);

    // new route ishihara tests old
    Route::get("admission/patients/ishihara/test-getlist", [Admission::class, 'getIshiharaTestList']);
    Route::post("admission/patients/ishihara/new-order", [Admission::class, 'newIshiharaOrder']);
    Route::get('admission/patients/ishihara/order-list', [Admission::class, 'getOrderList']);

    //psychology new
    Route::get("admission/patients/psychology/test-getlist", [Admission::class, 'getPsychologyTestList']);
    Route::post("admission/patients/psychology/new-order", [Admission::class, 'newPsychologyOrder']);
    Route::get('admission/patients/psychology/order-list', [Admission::class, 'getPsychologyOrderList']);

    //TRIAGE
    Route::get('triage/sidebar/header-infomartion', [Triage::class, 'histriageGetHeaderInfo']);
    Route::get("triage/get-personal-info-by-id", [Triage::class, 'histriageGetPersonalInfoById']);
    Route::post("triage/information/personal-uploadnewprofile", [Triage::class, 'histriageUploadProfile']);
    Route::post("triage/information/personal-update", [Triage::class, 'histriageUpdatePersonalInfo']);
    Route::post("triage/update-username", [Triage::class, 'histriageUpdateUsername']);
    Route::post("triage/update-password", [Triage::class, 'histriageUpdatePassword']);
    Route::post("triage/patients/newpatient-save", [Triage::class, 'histriageNewPatient']);
    Route::get('triage/patients/getpatient-list', [Triage::class, 'histriageGetIncompleteList']);
    Route::get('triage/patient/patient-information', [Triage::class, 'histriageGetPatientInformation']);
    Route::post("triage/patients/edit-patient", [Triage::class, 'histriageUpdatePatientInfo']);
    Route::post("triage/add/contact-tracing/reviews", [Triage::class, 'histriageAddNewContactTracing']);

    //SECRETARY or ENCODER
    Route::get('encoder/sidebar/header-infomartion', [Encoder::class, 'hisSecretaryGetHeaderInfo']);
    Route::post('encoder/patients/newpatient-save', [Encoder::class, 'hisSecretaryNewPatient']);
    Route::get('encoder/get-personal-info-by-id', [Encoder::class, 'hisSecretaryGetPersonalInfoById']);
    Route::post("encoder/information/personal-update", [Encoder::class, 'hisSecretaryUpdatePersonalInfo']);
    Route::post("encoder/information/personal-uploadnewprofile", [Encoder::class, 'hisSecretaryUploadProfile']);
    Route::post("encoder/update-username", [Encoder::class, 'hisSecretaryUpdateUsername']);
    Route::post("encoder/update-password", [Encoder::class, 'hisSecretaryUpdatePassword']);
    Route::get("encoder/map/patient/patient-information", [Encoder::class, 'hisSecretaryPatientInfo']);
    Route::get("encoder/patients/appointment/local/list", [Encoder::class, 'hisSecretaryGetAppointmentLocal']);
    Route::get("encoder/patients/getpatient-info", [Encoder::class, 'hisSecretaryGetPatientInfo']);
    Route::post("encoder/patients/edit-patient", [Encoder::class, 'hisSecretaryUpdatePatientInfo']);
    Route::post("encoder/patient/create-appointment", [Encoder::class, 'hisSecretaryCreateAppointment']);
    Route::post("encoder/patient/appointment/reschedule", [Encoder::class, 'hisSecretaryRescheduleAppointment']);
    Route::get("encoder/pateint/billing", [Encoder::class, 'hisSecretaryGetPatientsBillings']);
    Route::get("encoder/pateint/billing/details", [Encoder::class, 'hisSecretaryGetPatientsBillingsDetails']);
    Route::post("encoder/billing/cancel-bill", [Encoder::class, 'hisSecretaryBillingCancel']);
    Route::post("encoder/billing/setaspaid-bill", [Encoder::class, 'hisSecretaryBillingSetAsPaid']);
    Route::get("encoder/receipt/printable/doctorsdetails", [Encoder::class, 'hisSecretaryGetReceiptHeader']);
    Route::get("encoder/pateint/billing/receipt/details", [Encoder::class, 'hisSecretaryReceiptDetails']);
    Route::get("encoder/billing/records/list", [Encoder::class, 'hisSecretaryGetBillingRecords']);
    Route::get("encoder/billing/records/refund-list", [Encoder::class, 'hisSecretaryRefundOrderList']);
    Route::get("encoder/billing/records/details/by-orderid", [Encoder::class, 'hisSecretaryGetBillingRecordsDetails']);
    Route::post("encoder/billing/records/refund-orderbyid", [Encoder::class, 'hisSecretaryRefundOrder']);

    //IMAGING
    Route::get('imaging/sidebar/header-infomartion', [Imaging::class, 'hisimagingGetHeaderInfo']);
    Route::get('imaging/get-personal-info-by-id', [Imaging::class, 'hisimagingGetPersonalInfoById']);
    Route::post("imaging/information/personal-uploadnewprofile", [Imaging::class, 'hisimagingUploadProfile']);
    Route::post("imaging/information/personal-update", [Imaging::class, 'hisimagingUpdatePersonalInfo']);
    Route::post("imaging/update-username", [Imaging::class, 'hisimagingUpdateUsername']);
    Route::post("imaging/update-password", [Imaging::class, 'hisimagingUpdatePassword']);
    Route::get('imaging/test/getAllTest', [Imaging::class, 'hisimagingGetAllTest']);
    Route::post("imaging/test/newtest-save", [Imaging::class, 'hisimagingSaveNewTest']);
    Route::post('imaging/test/edit-test', [Imaging::class, 'hisimagingEditTest']);
    Route::get('imaging/get/patient/xray/forimaging', [Imaging::class, 'hisimagingGetPatientForImaging']);
    Route::get('imaging/patients/information', [Imaging::class, 'hisimagingGetPatientInformation']);
    Route::post('imaging/patient/order/addresult', [Imaging::class, 'hisimagingOrderAddResult']);
    Route::post('imaging/upload-imaging-attachment', [Imaging::class, 'hisimagingUploadImagingAttach']);
    Route::get('imaging/get-all-localrad-list', [Imaging::class, 'hisimagingGetAllLocalRadiologist']);
    Route::get('imaging/get-all-telerad-list', [Imaging::class, 'hisimagingGetAllTeleRadiologist']);
    Route::get('imaging/virtual/get/patient/forimaging', [Imaging::class, 'hisimagingGetNewOrder']);
    Route::get('imaging/virtual/order/reports', [Imaging::class, 'hisimagingGetImagingOrderReports']);
    Route::get('imaging/virtual/order/reports-details', [Imaging::class, 'hisimagingGetReportDetails']);
    Route::post('imaging/virtual/order/reports-bydate', [Imaging::class, 'getImagingOrderReportsByDate']);

    Route::get('imaging/virtual/get/patient/patient-information', [Imaging::class, 'getPatientInformation']);
    Route::get('imaging/virtual/get/order/order-information', [Imaging::class, 'getImagingOrderInformation']);
    Route::get('imaging/get/radiologist/list', [Imaging::class, 'getRadiologist']);
    Route::get('imaging/virtual/get/tele-radiologist/list', [Imaging::class, 'getTeleradiologistList']);
    Route::post('imaging/virtual/order/addresults', [Imaging::class, 'setImagingOrderRadiologist']);

    Route::post('imaging/create-order', [Imaging::class, 'createOrder']);

    //LABORATORY
    Route::get("laboratory/sidebar/header-infomartion", [Laboratory::class, 'hislabGetHeaderInfo']);
    Route::get('laboratory/order/getpatientswith-neworder', [Laboratory::class, 'getLabPatientsWithNewOrder']);
    Route::get('laboratory/order/completed/report', [Laboratory::class, 'getLaboratoryCompletedReport']);
    Route::get('laboratory/test/getAllTest', [Laboratory::class, 'getAllTest']);
    Route::post('laboratory/test/edit-test', [Laboratory::class, 'editTest']);
    Route::get("laboratory/get-personal-info-by-id", [Laboratory::class, 'hislabGetPersonalInfoById']);
    Route::post("laboratory/information/personal-update", [Laboratory::class, 'hislabUpdatePersonalInfo']);
    Route::post("laboratory/information/personal-uploadnewprofile", [Laboratory::class, 'hislabUploadProfile']);
    Route::post("laboratory/update-username", [Laboratory::class, 'hislabUpdateUsername']);
    Route::post("laboratory/update-password", [Laboratory::class, 'hislabUpdatePassword']);
    Route::get("laboratory/order/formheader-details", [Laboratory::class, 'getLabFormHeader']);

    Route::post("laboratory/test/items/new-item", [Laboratory::class, 'newLaboratoryItem']);
    Route::get("laboratory/test/items/get-itemlist", [Laboratory::class, 'laboratoryItemList']);
    Route::get("laboratory/test/items/get-itemlist/batches", [Laboratory::class, 'laboratoryItemListByBatches']);
    Route::post("laboratory/test/items/delivery/new-tempdr", [Laboratory::class, 'laboratoryItemDeliveryTemp']);
    Route::get("laboratory/test/items/delivery-templist", [Laboratory::class, 'laboratoryItemDeliveryTempList']);
    Route::post("laboratory/test/items/delivery-tempremove", [Laboratory::class, 'laboratoryItemDeliveryTempRemove']);
    Route::post("laboratory/test/items/delivery-tempprocess", [Laboratory::class, 'laboratoryItemDeliveryTempProcess']);

    Route::get("laboratory/test/items/inventory/list-inventory", [Laboratory::class, 'getLaboratoryItemsInventory']);
    Route::get("laboratory/test/items/monitoring/monitoring-list", [Laboratory::class, 'getItemMonitoring']);

    Route::post('laboratory/items/laborder/create-tempordernoitem', [Laboratory::class, 'saveTempOrderNoItem']);
    Route::get('laboratory/items/laborder/list-tempnoitem', [Laboratory::class, 'getLabOrderTemp']);
    Route::get('laboratory/items/laborder/list-tempitems', [Laboratory::class, 'getOrdersItems']);

    Route::post("laboratory/test/items/orderagent-save", [Laboratory::class, "saveOrderItem"]);
    Route::post("laboratory/items/laborder/temporder-withitems-processed", [Laboratory::class, "processTempOrderWithItems"]);
    Route::get("laboratory/items/laborder/list-available", [Laboratory::class, "getLaboratoryOrderList"]);
    Route::get("laboratory/items/laborder/list-available-items", [Laboratory::class, "getLaboratoryOrderListItems"]);

    Route::post("laboratory/test/order/temp-removeorder", [Laboratory::class, "laboratoryRemoveOrder"]);
    Route::post("laboratory/test/order/temp-removeorder-item", [Laboratory::class, "laboratoryRemoveItemInOrder"]);

    Route::post("laboratory/test/items/remove-item", [Accounting::class, "laboratoryItemRemove"]);
    Route::post("laboratory/test/items/orderusesthisitem-list", [Laboratory::class, "laboratoryOrderUsesThisItem"]);

    //unseen
    Route::post('laboratory/laboratory-details', [Laboratory::class, 'laboratoryDetails']);
    Route::post('laboratory/create-order', [Laboratory::class, 'createOrder']);
    Route::post('doctor/laboratory/add-order', [Laboratory::class, 'addLabOrder']);
    Route::post('doctor/patient/laboratory-unpaidorder', [Laboratory::class, 'getUnpaidLabOrder']);
    Route::post('doctor/laboratory/cancel-order', [Laboratory::class, 'cancelLabOrder']);
    Route::post('laboratory/doctor/patient/laboratory-counts', [Laboratory::class, 'laboratoryCounts']);
    Route::post('laboratory/order/order-new', [Laboratory::class, 'getNewOrder']);
    Route::post('laboratory/order/order-pending', [Laboratory::class, 'getPendingOrder']);
    Route::post('laboratory/order/order-processing', [Laboratory::class, 'getProcessingOrder']);
    Route::post('laboratory/order/set-process', [Laboratory::class, 'orderSetProcess']);
    Route::post('laboratory/order/set-pending', [Laboratory::class, 'orderSetPending']);
    Route::post('laboratory/order/set-addresult', [Laboratory::class, 'addResult']);
    Route::post('laboratory/order/ordernew-count', [Laboratory::class, 'getCounts']);
    Route::post('laboratory/order/allrecords', [Laboratory::class, 'getAllRecords']);
    Route::post('doctor/patient/laboratory/laboratory-withresult', [Laboratory::class, 'laboratoryWithResult']);
    Route::post('doctor/patient/laboratory/laboratory-order-details', [Laboratory::class, 'laboratoryOrderDetails']);
    Route::post('doctor/patient/laboratory/laboratory-ongoing', [Laboratory::class, 'laboratoryOngoing']);
    Route::post('doctor/patient/laboratory/laboratory-pending', [Laboratory::class, 'laboratoryPending']);
    Route::post('doctor/patient/laboratory/laboratory-unprocess', [Laboratory::class, 'laboratoryUnprocess']);
    Route::post('laboratory/patient/patient-laboratory', [Laboratory::class, 'getLaboratoryByPatient']);
    Route::post('laboratory/patient/patient-laboratory-details', [Laboratory::class, 'laboratoryOrderDetails']);
    Route::post('laboratory/test/newtest-save', [Laboratory::class, 'saveNewTest']);

    // hemathology
    Route::post('laboratory/order/ordernew-hemathology', [Laboratory::class, 'getOrderHemathologyNew']);
    Route::get('laboratory/order/ordernew-hemathology/details', [Laboratory::class, 'getOrderHemathologyNewDetails']);
    Route::post('laboratory/order/ordernew-hemathology/save-process-result', [Laboratory::class, 'saveHemaOrderResult']);
    Route::post('laboratory/order/ordernew-hemathology/save-setpending', [Laboratory::class, 'setHemaOrderPending']);
    Route::post('laboratory/order/ordernew-hemathology/save-setprocessing', [Laboratory::class, 'setHemaOrderProcessed']);
    Route::get('laboratory/order/ordernew-hemathology/complete/details-print', [Laboratory::class, 'getCompleteHemathologyOrderDetails']);

    // sorology
    Route::post('laboratory/order/ordernew-sorology', [Laboratory::class, 'getOrderSorologyNew']);
    Route::get('laboratory/order/ordernew-sorology/details', [Laboratory::class, 'getOrderSorologyNewDetails']);
    Route::post('laboratory/order/ordernew-sorology/save-process-result', [Laboratory::class, 'saveSorologyOrderResult']);
    Route::post('laboratory/order/ordernew-sorology/save-setprocessing', [Laboratory::class, 'setSorologyOrderProcessed']);
    Route::post('laboratory/order/ordernew-sorology/save-setpending', [Laboratory::class, 'setSorologyOrderPending']);
    Route::get('laboratory/order/ordernew-sorology/complete/details-print', [Laboratory::class, 'getCompleteSoroOrderDetails']);

    // clinicalmicroscopy
    Route::post('laboratory/order/ordernew-clinicalmicroscopy', [Laboratory::class, 'getOrderClinicalMicroscopyNew']);
    Route::get('laboratory/order/ordernew-clinicalmicroscopy/details', [Laboratory::class, 'getOrderClinicalMicroscopyNewDetails']);
    Route::post('laboratory/order/ordernew-clinicalmicroscopy/save-setprocessing', [Laboratory::class, 'setClinicMicrosopyOrderProcessed']);
    Route::post('laboratory/order/ordernew-clinicmicroscopy/save-setpending', [Laboratory::class, 'setClinicMicrosopyOrderPending']);
    Route::post('laboratory/order/ordernew-clinicalmicroscopy/save-process-result', [Laboratory::class, 'saveClinicalMicroscopyOrderResult']);
    Route::get('laboratory/order/ordernew-clinicalmicroscopy/complete/details-print', [Laboratory::class, 'getCompleteClinicalMicroscopyOrderDetails']);

    // fecal analyis
    Route::post('laboratory/order/ordernew-fecalanalysis', [Laboratory::class, 'getOrderFecalAnalysisNew']);
    Route::get('laboratory/order/ordernew-fecalanalysis/details', [Laboratory::class, 'getOrderFecalAnalysisNewDetails']);
    Route::post('laboratory/order/ordernew-fecalanalysis/save-setpending', [Laboratory::class, 'setFecalAnalysisOrderPending']);
    Route::post('laboratory/order/ordernew-fecalanalysis/save-setprocessing', [Laboratory::class, 'setFecalAnalysisOrderProcessed']);
    Route::post('laboratory/order/ordernew-fecalanalysis/save-process-result', [Laboratory::class, 'saveFecalAnalysisOrderResult']);
    Route::get('laboratory/order/ordernew-fecalanalysis/complete/details-print', [Laboratory::class, 'getCompleteFecalAnalysisOrderDetails']);

    //clinicalchemistry
    Route::post('laboratory/order/ordernew-clinicalchemistry', [Laboratory::class, 'getOrderClinicalChemistryNew']);
    Route::get('laboratory/order/ordernew-clinicalchemistry/details', [Laboratory::class, 'getOrderClinicalChemistryNewDetails']);
    Route::post('laboratory/order/ordernew-clinicalchemistry/save-setprocessing', [Laboratory::class, 'setClinicChemistryOrderProcessed']);
    Route::post('laboratory/order/ordernew-clinicalchemistry/save-setpending', [Laboratory::class, 'setClinicChemistryOrderPending']);
    Route::post('laboratory/order/ordernew-clinicalchemistry/save-process-result', [Laboratory::class, 'saveClinicalChemistryOrderResult']);
    Route::get('laboratory/order/ordernew-chemistry/complete/details-print', [Laboratory::class, 'getCompleteChemOrderDetails']);
    Route::post('laboratory/order/newordercount-bydept', [Laboratory::class, 'getNewOrderCountByDept']);

    //electrolytes
    Route::post('laboratory/order/ordernew-clinicalchemistry-electro', [Laboratory::class, 'getOrderClinicalChemistryNewElectro']);

    //DOCTOR
    //  doctors routes
    Route::post('doctor/online/checkup/create-room', [Doctor::class, 'createRoom']);
    Route::post('doctor/online/checkup/remove-room', [Doctor::class, 'removeRoom']);
    Route::post('doctor/online/checkup/appointment-details', [Doctor::class, 'appointmentDetails']);
    Route::post('doctor/online/checkup/patient-web-rtc-id', [Doctor::class, 'getPatientWebRtcId']);
    Route::post('doctor/information/personal-info', [Doctor::class, 'getPersonalInfo']);
    Route::post('doctor/patient/patients-list', [Doctor::class, 'getPatients']);
    Route::get('doctor/patient/patient-information', [Doctor::class, 'getPatientInformation']);
    Route::post('doctor/patient/vitals/graph/get-bloodpressure', [Doctor::class, 'getBloodPressure']);
    Route::post('doctor/patient/vitals/graph/get-temperature', [Doctor::class, 'getTemperature']);
    Route::post('doctor/patient/vitals/graph/get-glucose', [Doctor::class, 'getGlucose']);
    Route::post('doctor/patient/vitals/graph/get-respiratory', [Doctor::class, 'getRespiratory']);
    Route::post('doctor/patient/vitals/graph/get-pulse', [Doctor::class, 'getPulse']);
    Route::post('doctor/patient/vitals/graph/get-cholesterol', [Doctor::class, 'getCholesterol']);
    Route::post('doctor/patient/vitals/graph/get-uricacid', [Doctor::class, 'getUricacid']);
    Route::post('doctor/patient/vitals/graph/get-weight', [Doctor::class, 'getWeight']);
    Route::post('doctor/patient/vitals/graph/get-chloride', [Doctor::class, 'getChloride']);
    Route::post('doctor/patient/vitals/graph/get-creatinine', [Doctor::class, 'getCreatinine']);
    Route::post('doctor/patient/vitals/graph/get-hdl', [Doctor::class, 'getHDL']);
    Route::post('doctor/patient/vitals/graph/get-ldl', [Doctor::class, 'getLDL']);
    Route::post('doctor/patient/vitals/graph/get-lithium', [Doctor::class, 'getLithium']);
    Route::post('doctor/patient/vitals/graph/get-magnesium', [Doctor::class, 'getMagnesium']);
    Route::post('doctor/patient/vitals/graph/get-potasium', [Doctor::class, 'getPotasium']);
    Route::post('doctor/patient/vitals/graph/get-protein', [Doctor::class, 'getProtein']);
    Route::post('doctor/patient/vitals/graph/get-sodium', [Doctor::class, 'getSodium']);
    Route::post('doctor/patient/vitals/graph/new-bp', [Doctor::class, 'newBp']);
    Route::post('doctor/patient/vitals/graph/new-temp', [Doctor::class, 'newTemp']);
    Route::post('doctor/patient/vitals/graph/new-weight', [Doctor::class, 'newWeight']);
    Route::post('doctor/patient/vitals/graph/new-glucose', [Doctor::class, 'newGlucose']);
    Route::post('doctor/patient/vitals/graph/new-uricacid', [Doctor::class, 'newUricacid']);
    Route::post('doctor/patient/vitals/graph/new-cholesterol', [Doctor::class, 'newCholesterol']);
    Route::post('doctor/patient/vitals/graph/new-pulse', [Doctor::class, 'newPulse']);
    Route::post('doctor/patient/vitals/graph/new-hepatitis', [Doctor::class, 'newHepatitis']);
    Route::post('doctor/patient/vitals/graph/new-tuberculosis', [Doctor::class, 'newTuberculosis']);
    Route::post('doctor/patient/vitals/graph/new-dengue', [Doctor::class, 'newDengue']);
    Route::post('doctor/patient/vitals/graph/get-bodypain', [Doctor::class, 'getBodyPain']);
    Route::post('doctor/patient/vitals/graph/new-respiratory', [Doctor::class, 'newRespiratory']);
    Route::post('doctor/patient/history/patient-history', [Doctor::class, 'patientHistory']);
    Route::post('doctor/online/checkup/request-permissiontopatient', [Doctor::class, 'requestPermissionToPatient']);
    Route::post('doctor/online/checkup/check-profilepermission', [Doctor::class, 'getProfilePermission']);
    Route::post('doctor/patient/patient-new-allergies', [Doctor::class, 'newAllergies']);
    Route::post('doctor/patient/prelaboratory/newprelaboratory', [Doctor::class, 'newPatientPrelab']);
    Route::post('doctors/appointment/local/change-appointmenttype', [Doctor::class, 'changeAppointmentTypeLocal']);
    Route::post('doctor/patients/moveto-list', [Doctor::class, 'movePatientToList']);
    Route::post('doctor/patient/save-patientlocal', [Doctor::class, 'savePatient']);
    Route::post('doctors/appointment/local/applocal-list', [Doctor::class, 'getLocalAppList']);
    Route::post('doctors/appointment/local/incomplete-list', [Doctor::class, 'getIncompleteAppList']);
    Route::post('doctor/patient/diagnosis/newdiagnosis', [Doctor::class, 'newDiagnosis']);
    Route::post('doctor/patient/diagnosis/getdiagnosis-list', [Doctor::class, 'getDiagnosis']);
    Route::post('doctor/patient/history/familyhistory-list', [Doctor::class, 'getFamilyHistories']);
    Route::post('doctor/patient/diet/monitoring/new-diet', [Doctor::class, 'newDietSave']);
    Route::post('doctor/patient/diet/monitoring/personaldiet-list', [Doctor::class, 'getPersonalDiet']);
    Route::post('doctor/patient/diet/monitoring/personaldiet-listbydate', [Doctor::class, 'getPersonalDietByDate']);
    Route::post('doctor/patient/diet/monitoring/suggesteddiet-list', [Doctor::class, 'getSuggestedDiet']);
    Route::post('doctor/patient/diet/monitoring/suggesteddiet-listbydate', [Doctor::class, 'getSuggestedDietByDate']);
    Route::post('doctor/imaging/imaging-details/sharedimages', [Doctor::class, 'getPatientSharedImages']);
    Route::post('doctor/imaging/imaging-details/shared-dateingroupby', [Doctor::class, 'getPatientSharedImagesDates']);
    Route::post('doctor/imaging/imaging-details/shared-dateingroupby-details', [Doctor::class, 'getPatientSharedImagesDatesDetails']);
    Route::post('doctor/patient/information/personal/family-historybyId', [Doctor::class, 'getPatientFamilyHistory']);
    Route::post('doctor/patients/permission/get-permission', [Doctor::class, 'getPermissionByPatient']);
    Route::post('doctor/patient/laboratory/order/unpaid-list', [Doctor::class, 'laboratoryUnpaidOrderByPatient']);
    Route::post('doctor/patient/laboratory/order/unpaid-listdetails', [Doctor::class, 'laboratoryUnpaidOrderByPatientDetails']);
    Route::post('doctor/patient/laboratory/order/paid-list', [Doctor::class, 'laboratoryPaidOrderByPatient']);
    Route::post('doctor/patient/laboratory/order/paid/hema-details', [Doctor::class, 'laboratoryPaidOrderHemaDetails']);
    Route::post('doctor/patient/notification-unread-orders', [Doctor::class, 'notificationUnreadOrders']);
    Route::post('doctor/patient/doc-notif-update', [Doctor::class, 'docNotifUpdate']);
    Route::post('doctor/billing/records/list-bydate', [Doctor::class, 'getBillingRecordByDate']);
    Route::post('doctor/billing/records/prescription/list-bydate', [Doctor::class, 'getPrescriptionIncomeReport']);
    Route::post('doctor/imaging/virtual/imaging-virtuallist', [Doctor::class, 'getVirtualImagingList']);
    Route::post('doctor/imaging/virtual/imaging-orderlist', [Doctor::class, 'getVirtualImagingOrderList']);
    Route::post('doctor/billing/records/presc-by-date', [Doctor::class, 'getBillingPrescriptionByDate']);
    Route::post('doctor/get/all-unread-msg', [Doctor::class, 'getAllUnreadMsgFromPatient']);
    Route::get('doctor/unview/notification-unview-orders', [Doctor::class, 'getUnviewNotification']);
    Route::post('/doctor/setnotification/as-read', [Doctor::class, 'setNotifAsView']);
    Route::get('doctor/sidebar/header-infomartion', [Doctor::class, 'getSidebarHeaderInformation']);
    Route::get('doctor/income/report/bymonth', [Doctor::class, 'getDoctorsIncomeReportByYear']);
    Route::post('doctors/fullcalendar/appointment/listof-localappointment', [Doctor::class, 'getFullcalendarAppointmentListLocal']);
    Route::post('doctors/fullcalendar/appointment/listof-virtualappointment', [Doctor::class, 'getFullcalendarAppointmentListVirtual']);
    Route::post('doctor/appointment/fullcalendar/virtual/appointment-details', [Doctor::class, 'appointmentDetails']);
    Route::post('doctor/appointment/local/applocal-details', [Doctor::class, 'getAppointmentLocalDetails']);
    Route::post('doctors/appointment/fullcalendar/reschedule', [Doctor::class, 'updateFullcalendarAppointment']);
    Route::post('doctor/appointment/local/list-withstatus', [Doctor::class, 'getLocalAppointmentListByStatus']);
    Route::post('doctor/appointment/virtual/list-withstatus', [Doctor::class, 'getVirtualAppointmentListByStatus']);
    Route::get('doctors/fullcalendar/appointment/local-dailylist', [Doctor::class, 'getTodaysAppointmentListLocal']);
    Route::get('doctors/fullcalendar/appointment/virtual-dailylist', [Doctor::class, 'getTodaysAppointmentListVirtual']);
    Route::get('doctors/fullcalendar/appointment/counts-bystatus', [Doctor::class, 'getFullcalendarAppointmentCount']);
    Route::post('doctor/patient/laboratory/hemathology/graph/get-hemathologydata', [Doctor::class, 'getHemathologyGraphData']);
    Route::post('doctor/patient/laboratory/chemistry/graph/get-chemistrydata', [Doctor::class, 'getChemistryGraphData']);
    Route::post('doctor/patient/laboratory/clinicalmicroscopy/graph/get-clinicalmicroscopydata', [Doctor::class, 'getClinicalMicroscopyData']);
    Route::get('doctor/laboratory/order/printable/printheader', [Doctor::class, 'getLaboratoryPrintHeaderByMng']);
    Route::post('doctor/account/change-password', [Doctor::class, 'updatePassword']);
    Route::post('doctor/account/change-username', [Doctor::class, 'updateUsername']);
    Route::get('doctor/comments/forapproval-list', [Doctor::class, 'getcommentsForApproval']);
    Route::post('doctor/laboratory/new/order/department-details', [Doctor::class, 'getLabOrderDeptDetails']);
    Route::post('doctor/laboratory/new/order/order-addtounsave', [Doctor::class, 'addLabOrderTounsave']);
    Route::post('doctor/laboratory/new/order/unsave-orderlist', [Doctor::class, 'getUnsaveLabOrder']);
    Route::post('doctor/laboratory/new/order/order-cancel', [Doctor::class, 'removeLabOrderFromUnsave']);
    Route::post('doctor/laboratory/new/order/process-laborder', [Doctor::class, 'processLabOrder']);
    Route::post('doctors/patient/information/getpatient-information', [Doctor::class, 'getPatientInformation']);
    Route::post('patient/medication/monitoring/list-medicationbydate', [Doctor::class, 'personalMedicationListByDate']);
    Route::post('doctor/patient/laboratory/order/paid-detailsbytable', [Doctor::class, 'paidLabOrderDetails']);
    Route::post('doctor/imaging/local/order-add', [Doctor::class, 'imagingAddOrder']);
    Route::post('doctor/imaging/local/imaging-orderlist/unsave', [Doctor::class, 'imagingAddOrderUnsavelist']);
    Route::post('imaging/order/local/unsave/delete-order', [Doctor::class, 'imagingOrderUnsaveDelete']);
    Route::post('imaging/order/local/unsave/process-order', [Doctor::class, 'imagingOrderUnsaveProcess']);

    Route::post('appointment/doctors/request-appointment-list', [Appointment::class, 'getRequestAppointmentList']);
    Route::post('doctors/appointment/patients/appointmentrecord-local', [Appointment::class, 'getPatientsLocalRecord']);
    Route::post('doctors/appointment/patients/appointmentrecord-virtual', [Appointment::class, 'getPatientsVirtualRecord']);
    Route::post('doctors/appointment/getappointmentlist', [Appointment::class, 'getDoctorsLocalAppointment']);
    Route::get('appointment/doctors/doctors-services', [Appointment::class, 'doctorsServices']);
    Route::post('appointment/doctors/local/createapppointment', [Appointment::class, 'createLocalappointment']);

    //imaging doctor
    Route::post('doctor/patient/imaging/imaging-unprocess', [Imaging::class, 'imagingUnprocess']);
    Route::post('doctor/patient/imaging/imaging-pending', [Imaging::class, 'imagingPending']);
    Route::post('doctor/patient/imaging/imaging-processed', [Imaging::class, 'imagingProcessed']);
    Route::post('doctor/patient/imaging/imaging-ongoing', [Imaging::class, 'imagingOngoing']);
    Route::post('doctor/patient/imaging/imaging-order-details', [Imaging::class, 'imagingOrderDetails']);
    Route::post('doctor/patient/imaging-counts', [Imaging::class, 'imagingCounts']);
    Route::post('imaging/imaging-details', [Imaging::class, 'imagingDetails']);
    Route::post('doctor/imaging/local/imaging-orderlist', [Imaging::class, 'imagingOrderList']);
    Route::post('doctor/imaging/local/imaging-orderlist/details', [Imaging::class, 'imagingOrderSelectedDetails']);
    Route::post('imaging/doctor/patient/imaging-ongoingorder', [Imaging::class, 'getOngoingOrder']);
    Route::post('imaging/telerad/order-readbytelerad', [Imaging::class, 'getImagingOrderReadByTelerad']);
    Route::post('imaging/telerad/get-teleradlist', [Imaging::class, 'getTeleradist']);
    Route::post('imaging/telerad/message/get-conversation', [Imaging::class, 'getTeleradConversation']);
    Route::post('imaging/telerad/message/send-message', [Imaging::class, 'sendMessage']);
    Route::post('imaging/order/validate-order', [Imaging::class, 'validateOrder']);
    Route::post('imaging/order/send-toradiologist', [Imaging::class, 'sentToRadiologist']);

    //  notes routes
    Route::post('doctor/patient/notes/notes-list', [Notes::class, 'getNotes']);
    Route::post('doctor/patient/notes/new-notes', [Notes::class, 'newNotes']);
    Route::post('doctor/patient/notes/edit-notes', [Notes::class, 'editNotes']);
    Route::post('doctor/patient/notes/delete-notes', [Notes::class, 'deleteNotes']);
    Route::post('doctor/patient/notes/savecanvas-note', [Notes::class, 'newNotesCanvas']);
    Route::post('doctor/patient/notes/patient-canvasnoteslist', [Notes::class, 'getCanvasNotesList']);

    //  treatment plan routes
    Route::post('doctor/patient/patient-treatmentplan', [TreatmentPlan::class, 'getTreatmentPlan']);
    Route::post('doctor/patient/patient-treatmentplansave', [TreatmentPlan::class, 'saveTreatmentPlan']);
    Route::post('doctor/patient/patient-treatmentplanupdate', [TreatmentPlan::class, 'updateTreatmentPlan']);
    Route::post('doctor/patient/patient-treatmentplandelete', [TreatmentPlan::class, 'deleteTreatmentPlan']);
    Route::post('doctor/patient/patient-treatmentplancanvas', [TreatmentPlan::class, 'canvasTreatmentPlan']);

    // prescription route
    Route::post('prescription/local/product-list', [Prescription::class, 'getProduct']);
    Route::post('prescription/local/product-details', [Prescription::class, 'getProductDetails']);
    Route::post('prescription/local/product-add', [Prescription::class, 'addProduct']);
    Route::post('prescription/local/product-unsave-count', [Prescription::class, 'unsaveProductCount']);
    Route::post('prescription/local/product-unsave', [Prescription::class, 'unsaveProduct']);
    Route::post('prescription/local/product-unsave-remove', [Prescription::class, 'removeUnsave']);
    Route::post('prescription/local/getLocalPharmacy', [Prescription::class, 'getLocalPharmacy']);
    Route::post('prescription/unsave/get-countby-presctype', [Prescription::class, 'getUnsaveCountByPresc']);
    Route::post('prescription/doctor/local/prescriptionlist', [Prescription::class, 'getprescriptionList']);
    Route::post('prescription/doctor/local/prescriptionsaveallUnsave', [Prescription::class, 'pescriptionSaveallUnsave']);
    Route::post('prescription/doctor/local/prescriptiondetailslist', [Prescription::class, 'getPrescriptionDetails']);
    Route::post('prescription/doctor/virtual/virtuallist', [Prescription::class, 'getvirtualPharmacy']);
    Route::post('prescription/virtual/pharmacyproducts-list', [Prescription::class, 'getvirtualPharmacyProducts']);
    Route::post('prescription/virtual/pharmacyproductsdetails-list', [Prescription::class, 'getvirtualPharmacyProductsDetails']);
    Route::post('prescription/virtual/prescription-viruatladd', [Prescription::class, 'addVirtualPrescription']);
    Route::post('doctor/patient/medication/getmedication-list', [Prescription::class, 'getMedication']);
    Route::post('doctor/patient/medication/getmedication-details', [Prescription::class, 'getMedicationDetails']);
    Route::post('doctor/patient/medication/getmedication-list', [Prescription::class, 'getMedication']);
    Route::post('doctor/patient/medication/getmedication-details', [Prescription::class, 'getMedicationDetails']);

    // Route::post('refresh', 'AuthController@refresh');
    // Route::post('me', 'AuthController@me');

    // radiologist newroute
    Route::post('radiologist/patients/getpatients-read/bydate', [Radiologist::class, 'getPatientReviewedByDate']);

    // imaging newroute
    Route::post('imaging/patients/getpatients-recorded', [Imaging::class, 'getPatientReviewed']);

    // pharmacy
    Route::get('pharmacy/get/all-unclaimed-pres/details/byclaimid', [Pharmacy::class, 'getClaimIdDetails']);
    Route::post('/pharmacy/prescriptiom/new-ordered', [Pharmacy::class, 'prescriptionNewQtyOrdered']);
    Route::post('/pharmacy/prescription/process/payment', [Pharmacy::class, 'prescriptionPaymentProcess']);
    Route::post('/pharmacy/prescription/process/add-to-billing', [Pharmacy::class, 'prescriptionAddToBilling']);

    Route::get('/pharmacy/prescriptiom/product/batches-list', [Pharmacy::class, 'prescriptionProductBatches']);
    Route::get('/pharmacy/prescriptiom/product/batch-avsqty', [Pharmacy::class, 'prescriptionProductBatchesAvsQty']);

    Route::post('patients/rx/printable/prescriptiondetails', [Prescription::class, 'getPrescriptionDetails']);
    Route::get('patients/rx/printable/doctorsdetails', [Doctor::class, 'getRxDoctorsRx']);

    //new api 26-04-2021
    Route::get('/cashier/receipt/information/header', [Cashier::class, 'hiscashierGetHeaderInfo']);

    Route::get('/cashier/receipt/information/header-receipt', [Cashier::class, 'hiscashierGetHeaderReceipt']);

    Route::get('cashier/billing/records/refund-list', [Cashier::class, 'refundOrderList']);
    // Route::get('cashier/billing/records/details/by-orderid', [Cashier::class, 'getBillingRecordsDetails']);
    Route::get('cashier/billing/records/list', [Cashier::class, 'getBillingRecords']);

    // accounting
    Route::get("accounting/sidebar/header-infomartion", [Accounting::class, 'hisAccountingGetHeaderInfo']);
    Route::get("accounting/get-personal-info-by-id", [Accounting::class, 'hisAccountingGetPersonalInfoById']);
    Route::post("accounting/information/personal-uploadnewprofile", [Accounting::class, 'hisAccountingUploadProfile']);
    Route::post("accounting/information/personal-update", [Accounting::class, 'hisAccountingUpdatePersonalInfo']);

    Route::post("accounting/update-username", [Accounting::class, 'hisAccountingUpdateUsername']);
    Route::post("accounting/update-password", [Accounting::class, 'hisAccountingUpdatePassword']);
    Route::get("accounting/test/items/delivery-templist", [Accounting::class, 'accountingItemDeliveryTempList']);
    Route::post("accounting/test/items/delivery-tempremove", [Accounting::class, 'accountingItemDeliveryTempRemove']);
    Route::get("accounting/test/items/get-itemlist", [Accounting::class, 'accountingItemList']);
    Route::get("accounting/test/items/get-itemlist/batches", [Accounting::class, 'accountingItemListByBatches']);
    Route::post("accounting/test/items/delivery-tempprocess", [Accounting::class, 'accountingItemDeliveryTempProcess']);

    Route::get("accounting/test/items/inventory/list-inventory", [Accounting::class, 'getAccountingItemsInventory']);
    Route::get("accounting/test/items/monitoring/monitoring-list", [Accounting::class, 'getItemMonitoring']);

    Route::get('accounting/items/laborder/list-tempnoitem', [Accounting::class, 'getLabOrderTemp']);
    Route::get('accounting/items/laborder/list-available', [Accounting::class, 'getAccountingOrderList']);
    Route::get("accounting/items/laborder/list-available-items", [Accounting::class, "getAccountingOrderListItems"]);
    Route::post('accounting/items/laborder/create-tempordernoitem', [Accounting::class, 'saveTempOrderNoItem']);

    Route::post("accounting/test/order/temp-removeorder", [Accounting::class, "laboratoryRemoveOrder"]);
    Route::post("accounting/test/order/temp-removeorder-item", [Accounting::class, "laboratoryRemoveItemInOrder"]);

    Route::post("accounting/test/items/remove-item", [Accounting::class, "laboratoryItemRemove"]);
    Route::post("accounting/test/items/orderusesthisitem-list", [Accounting::class, "laboratoryOrderUsesThisItem"]);
    Route::get('accounting/items/laborder/list-tempitems', [Accounting::class, 'getOrdersItems']);

    Route::post("accounting/test/items/orderagent-save", [Accounting::class, "saveOrderItem"]);
    Route::post("accounting/items/laborder/temporder-withitems-processed", [Accounting::class, "processTempOrderWithItems"]);

    Route::get("accounting/imaging/test-person-list", [Accounting::class, "getImagingTestPerson"]);
    Route::get("accounting/imaging/sales/report-bydate", [Accounting::class, "getImagingSalesByDate"]);

    Route::get("accounting/laboratory/sales", [Accounting::class, "getLaboratorySalesReport"]);
    Route::get("accounting/laboratory/sales/report-bydate", [Accounting::class, "getLaboratorySalesReportByDate"]);

    Route::get("accounting/get/by/label/items-into-package", [Accounting::class, "getOrderByDepartment"]);
    Route::post("accounting/charge/add-package", [Accounting::class, "addPackage"]);
    Route::post("accounting/charge/remove-package", [Accounting::class, "removePackage"]);
    Route::get("accounting/get/all/unsave-package", [Accounting::class, "getAllUnsavePackage"]);
    Route::post("accounting/finalized/confirm-package", [Accounting::class, "confirmPackage"]);
    Route::get("accounting/get/all/confirmed-package", [Accounting::class, "getAllConfirmedPackages"]);
    Route::get("accounting/get/details/package/by-id", [Accounting::class, "getDetailsPackageById"]);

    Route::post("accounting/test/items/new-item", [Accounting::class, 'newLaboratoryItem']);
    Route::post("accounting/test/items/delivery/new-tempdr", [Accounting::class, 'laboratoryItemDeliveryTemp']);
    Route::post("accounting/test/items/delivery-tempprocess", [Accounting::class, 'laboratoryItemDeliveryTempProcess']);

    // billing
    Route::get("billing/sidebar/header-infomartion", [Billing::class, "hisBillingHeaderInfo"]);
    Route::get("billing/get-personal-info-by-id", [Billing::class, 'hisBillingGetPersonalInfoById']);
    Route::post("billing/information/personal-update", [Billing::class, 'hisBillingUpdatePersonalInfo']);

    Route::post("billing/update-password", [Billing::class, 'hisBillingUpdatePassword']);
    Route::post("billing/update-username", [Billing::class, 'hisBillingUpdateUsername']);
    Route::post("billing/information/personal-uploadnewprofile", [Billing::class, 'hisBillingUploadProfile']);

    Route::get("billing/soa/get-management-patients", [Billing::class, 'getSoaManagmentPatient']);
    Route::get("billing/soa/get-management-patients-info", [Billing::class, 'getSoaManagmentPatientInfo']);
    Route::get("billing/soa/get-patients-transactions", [Billing::class, 'getSoaManagementPatientTransactions']);

    Route::get("billing/soa/get-management-companies", [Billing::class, "getManagementCompanies"]);
    Route::get("billing/soa/get-companies-transactions", [Billing::class, "getCompaniesTransaction"]);

    // doctor new route
    Route::get('doctor/patient/queuing-list', [Doctor::class, 'getQueuingPatients']);

    // warehouse routes
    Route::get('warehouse/sidebar/header-infomartion', [Warehouse::class, 'hisWarehouseHeaderInfo']);
    Route::post('pharmacy/warehouse/products/new-product', [Warehouse::class, 'newWarehouseProducts']);
    Route::get('pharmacy/warehouse/get-warehouseproducts', [Warehouse::class, 'getProductListInWarehouse']);
    Route::get('pharmacy/warehouse/unavailable/get-warehouseproducts', [Warehouse::class, 'getProductListUnAvailable']);
    Route::get('pharmacy/warehouse/new-invoice/get-productlist', [Warehouse::class, 'getProducsListForNewInvoice']);
    Route::post('pharmacy/warehouse/inventory/newinvoice-savetotemp', [Warehouse::class, 'saveProductToTemp']);
    Route::get('pharmacy/warehouse/inventory/temp-productlist', [Warehouse::class, 'getProductTempList']);
    Route::post("pharmacy/warehouse/inventory/newinvoice-removeItem", [Warehouse::class, 'removeItemFromUnsaveList']);

    Route::post("pharmacy/warehouse/inventory/process-unsaveitem", [Warehouse::class, 'processUnsaveProduct']);
    Route::get("pharmacy/warehouse/inventory/getinventory-list", [Warehouse::class, "getProductInventory"]);
    Route::get("pharmacy/warehouse/inventory/product-details", [Warehouse::class, "getProductDetails"]);
    Route::get("pharmacy/warehouse/account/getaccount-list", [Warehouse::class, "getAccountList"]);
    Route::post("pharmacy/warehouse/account/saveaccount", [Warehouse::class, "accountSave"]);
    Route::post("pharmacy/warehouse/account/removeaccount", [Warehouse::class, "removeItem"]);
    Route::get("pharmacy/warehouse/account/getProductBatchesDetails", [Warehouse::class, "getProductBatchDetails"]);
    Route::post("pharmacy/warehouse/account/deliver/add-producttotemp", [Warehouse::class, "saveProductDrtoTemp"]);
    Route::get("pharmacy/warehouse/account/deliver/list-producttotemp", [Warehouse::class, "getProductToDrList"]);
    Route::post("pharmacy/warehouse/inventory/product/item-removeintemp", [Warehouse::class, "removeItemFromTempList"]);
    Route::post("pharmacy/warehouse/inventory/product/process-tempproducts", [Warehouse::class, "processDrItemsInTemp"]);
    Route::get("pharmacy/warehouse/delivered/drproduct-list", [Warehouse::class, "getDrProducts"]);
    Route::get("pharmacy/warehouse/delivered/drnumber-details", [Warehouse::class, "getDrNumberDetails"]);
    Route::get("pharmacy/warehouse/inventory/monitoring-list", [Warehouse::class, "getMonitoringList"]);

    Route::get("warehouse/get-personal-info-by-id", [Warehouse::class, 'warehouseGetPersonalInfoById']);
    Route::post("warehouse/information/personal-uploadnewprofile", [Warehouse::class, 'warehouseUploadProfile']);
    Route::post("warehouse/information/personal-update", [Warehouse::class, 'warehouseUpdatePersonalInfo']);
    Route::post("warehouse/update-password", [Warehouse::class, 'warehouseUpdatePassword']);
    Route::post("warehouse/update-username", [Warehouse::class, 'warehouseUpdateUsername']);

    // inhihara
    Route::get('ishihara/sidebar/header-infomartion', [Ishihara::class, "getHeaderInfo"]);
    Route::get('ishihara/sidebar/get-newpatients', [Ishihara::class, "getNewPatients"]);
    Route::post('ishihara/test/new-test', [Ishihara::class, "newIshiharaTest"]);
    Route::get('ishihara/test/new-get', [Ishihara::class, "getIshiharaTest"]);
    Route::get('ishihara/patient/new-order', [Ishihara::class, "getPatientWithOrder"]);

    //dennis
    Route::get("ishihara/get-personal-info-by-id", [Ishihara::class, 'hisishiharaGetPersonalInfoById']);
    Route::post("ishihara/information/personal-update", [Ishihara::class, 'hisishiharaUpdatePersonalInfo']);
    Route::post("ishihara/information/personal-uploadnewprofile", [Ishihara::class, 'hisishiharaUploadProfile']);
    Route::post("ishihara/update-password", [Ishihara::class, 'hisishiharaUpdatePassword']);
    Route::post("ishihara/update-username", [Ishihara::class, 'hisishiharaUpdateUsername']);

    // laboratory new route
    Route::post('laboratory/hemathology/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryHemathologyOrderProcessed']);
    Route::post('laboratory/serology/order-setprocess/custom-qty', [Laboratory::class, 'laboratorySerologyOrderProcessed']);
    Route::post('laboratory/microscopy/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryMicroscopyOrderProcessed']);
    Route::post('laboratory/fecalanalysis/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryFecalAnalysisOrderProcessed']);
    Route::post('laboratory/chemistry/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryChemistryOrderProcessed']);

    Route::get('admission/walkin/package-list', [Admission::class, 'getPackagesList']);
    Route::post('admission/walkin/package-savetemp', [Admission::class, 'savePackageOrderTemp']);
    Route::get('admission/walkin/unpaid-bills', [Admission::class, 'getUnpaidListByPatientId']);
    Route::post('admission/walkin/unpaid-delete', [Admission::class, 'deleteOrder']);
    Route::post("admission/walkin/save-processorder", [Admission::class, 'saveOrderProcess']);
    Route::get('/admission/walk-in/unpiad-orderlist', [Admission::class, 'getUnpaidOrderList']);
    Route::get('/admission/walk-in/paid-orderlist', [Admission::class, 'getPaidOrderList']);

    //7-05-2021
    Route::post('hims/laboratory/filter-by-date/sales-result', [HMIS::class, 'laboratorySalesFilterByDate']);
    Route::post('hims/imaging/filter-by-date/sales-result', [HMIS::class, 'imagingSalesFilterByDate']);
    Route::post("hmis/update/account-rate-class", [HMIS::class, 'himsUpdateAccountRateClass']);
    Route::post('doctor/get/update/personal-info', [Doctor::class, 'doctorUpdatePersonalInfo']);
    Route::post('doctor/information/personal-uploadnewprofile', [Doctor::class, 'doctorUpdateProfile']);

    //jhomar gwapo 7/7/2021
    Route::get('warehouse/invoice/for-approval', [Warehouse::class, 'getForApprovalInvoice']);
    Route::get('warehouse/invoice/for-approval-details', [Warehouse::class, 'getForApprovalInvoiceDetails']);
    Route::get('warehouse/dr/for-approval', [Warehouse::class, 'getForApprovalDr']);
    Route::get('warehouse/dr/for-approval-details', [Warehouse::class, 'getForApprovalDrDetails']);
    Route::get('accounting/invoice/for-approval', [Accounting::class, 'getForApprovalInvoice']);
    Route::get('accounting/invoice/for-approval-details', [Accounting::class, 'getForApprovalInvoiceDetails']);
    Route::get('accounting/dr/for-approval', [Accounting::class, 'getForApprovalDr']);
    Route::get('accounting/dr/for-approval-details', [Accounting::class, 'getForApprovalDrDetails']);
    Route::post("accounting/dr/approved-byaccounting", [Accounting::class, 'drApprovedByAccounting']);
    Route::post("accounting/invoice/approved-byaccounting", [Accounting::class, 'invoiceApprovedByAccounting']);
    Route::post('accounting/warehouse/products/new-product', [Accounting::class, 'newWarehouseProducts']);
    Route::get('accounting/warehouse/get-warehouseproducts', [Accounting::class, 'getProductListInWarehouse']);
    Route::get("accounting/warehouse/inventory/monitoring-list", [Accounting::class, "getMonitoringList"]);
    Route::get("billing/soa/get-companies-transactions-patients", [Billing::class, "getCompaniesTrasactionByPatients"]);
    Route::get('general/management/get-branches', [HMIS::class, "getGeneralManagementBranches"]);
    Route::get('accounting/management/accredited/company-list', [Accounting::class, 'getCompanyAccreditedList']);
    Route::post('accounting/management/accredited/company-save', [Accounting::class, 'saveCompanyAccredited']);
    Route::post("accounting/management/accredited/company-remove", [Accounting::class, "removeCompanyAccredited"]);
    Route::post('accounting/management/accredited/company-edit', [Accounting::class, 'editCompanyAccredited']);
    Route::get('admission/accredited/company/get-list', [Admission::class, 'getCompanyAccreditedList']);

    //09-07-2021
    Route::get('doctor/get/all/services', [Doctor::class, 'getAllDoctorsServices']);
    Route::post('doctor/add-new-service', [Doctor::class, 'addNewServiceDoctor']);
    Route::post('doctor/update/service', [Doctor::class, 'updateExistingServiceById']);
    Route::get('doctor/get/service/by-id', [Doctor::class, 'getAllServiceBByDoctorId']);

    Route::get('billing/accredited/company/get-details', [Billing::class, 'getAccCompanyInfo']);

    Route::post('doctors/appointment/local/set-complete', [Doctor::class, 'setLocalAppComplete']);

    // stockrooom

    Route::get('stockroom/sidebar/header-infomartion', [StockRoom::class, 'hmisGetHeaderInfo']);
    Route::get('stockroom/get-personal-info-by-id', [StockRoom::class, 'hisAccountingGetPersonalInfoById']);
    Route::post('stockroom/information/personal-uploadnewprofile', [StockRoom::class, 'hisStockroomUploadProfile']);
    Route::post('stockroom/information/personal-update', [StockRoom::class, 'hisStockroomUpdatePersonalInfo']);
    Route::get('stockroom/product/get-from-warehouse', [StockRoom::class, 'warehouseProductList']);
    Route::post('stockroom/product/warehouse/save-totemp', [StockRoom::class, 'stockRoomTempProductsSave']);
    Route::get('stockroom/product/dr/temp-list', [StockRoom::class, 'getStockRoomTempDrProducts']);
    Route::post('stockroom/product/dr/temp-remove', [StockRoom::class, 'removeStockroomTempProducts']);
    Route::post('stockroom/product/dr/product-in', [StockRoom::class, 'processInProduct']);
    Route::get('stockroom/product/monitoring/get-list', [StockRoom::class, 'getStockroomMonitoring']);
    Route::get("stockroom/product/inventory-list", [StockRoom::class, "getInventoryStockroom"]);
    Route::get("stockroom/product/inventory-list-details", [StockRoom::class, "getInventoryStockroomDetails"]);
    Route::post('stockroom/product/dr/product-out', [StockRoom::class, 'processOutProduct']);

    //07-12-2021
    Route::post('cashier/add-ons/update/billing', [Cashier::class, 'cashierAddNewAddOns']);
    Route::get("haptech/sidebar/header-infomartion", [Haptech::class, "hisHaptechHeaderInfo"]);
    Route::get("haptech/get-personal-info-by-id", [Haptech::class, 'hisHaptechGetPersonalInfoById']);
    Route::post("haptech/information/personal-update", [Haptech::class, 'hisHaptechUpdatePersonalInfo']);
    Route::post("haptech/update-password", [Haptech::class, 'hisHaptechUpdatePassword']);
    Route::post("haptech/update-username", [Haptech::class, 'hisHaptechUpdateUsername']);
    Route::post("haptech/information/personal-uploadnewprofile", [Haptech::class, 'hisHaptechUploadProfile']);

    Route::get("hr/sidebar/header-infomartion", [HR::class, "hisHRHeaderInfo"]);
    Route::get("hr/get-personal-info-by-id", [HR::class, 'hisHRGetPersonalInfoById']);
    Route::post("hr/information/personal-update", [HR::class, 'hisHRUpdatePersonalInfo']);
    Route::post("hr/update-password", [HR::class, 'hisHRUpdatePassword']);
    Route::post("hr/update-username", [HR::class, 'hisHRUpdateUsername']);
    Route::post("hr/information/personal-uploadnewprofile", [HR::class, 'hisHRUploadProfile']);
    Route::get("hr/get/all-users/for-summary", [HR::class, 'hisHRGetAllUsersForSummary']);
    Route::get("hr/payroll/payslip/report/get-deductions", [HR::class, 'hisHRGetPayslipDeductionByPeriod']);
    Route::get("hr/payroll/payslip/report/get-bonus", [HR::class, 'hisHRGetPayslipBonusByPeriod']);
    Route::get("hr/payroll/header-list", [HR::class, 'hisHRGetPayrollHeaderList']);
    Route::get("hr/payroll/header-list-bracket", [HR::class, 'hisHRGetPayrollHeaderListByBracket']);
    Route::post("hr/payroll/new-header", [HR::class, 'hisHRNewPayrollHeader']);
    Route::post("hr/payroll/payslip/report/add", [HR::class, 'hisHRAddPayslip']);
    Route::get("hr/employee/get-summary", [HR::class, 'getEmpPayrollSummary']);
    Route::get("hr/payroll/send-to-email", [HR::class, 'hisHRPayrollSendToEmail']);

    //psychology
    Route::get('psychology/sidebar/header-infomartion', [Psychology::class, "getHeaderInfo"]);
    Route::get('psychology/sidebar/get-newpatients', [Psychology::class, "getNewPatients"]);
    Route::post('psychology/test/new-test', [Psychology::class, "newPsychologyTest"]);
    Route::get('psychology/test/new-get', [Psychology::class, "getPsychologyTest"]);
    Route::get('psychology/patient/new-order', [Psychology::class, "getPatientWithOrder"]);
    Route::get('psychology/patient/van/new-order', [Psychology::class, "getPatientWithOrderVan"]);

    Route::get("psychology/get-personal-info-by-id", [Psychology::class, 'hisPsychologyGetPersonalInfoById']);
    Route::post("psychology/information/personal-update", [Psychology::class, 'hisPsychologyUpdatePersonalInfo']);
    Route::post("psychology/information/personal-uploadnewprofile", [Psychology::class, 'hisPsychologyUploadProfile']);
    Route::post("psychology/update-password", [Psychology::class, 'hisPsychologyUpdatePassword']);
    Route::post("psychology/update-username", [Psychology::class, 'hisPsychologyUpdateUsername']);

    // 07-22-2021
    Route::get('accounting/management/get-total-resources', [Accounting::class, "getTotalResources"]);
    Route::post("accounting/add_new_bank_account", [Accounting::class, 'addNewBankAccount']);
    Route::get('accounting/get/bank-list', [Accounting::class, "getBankAccountList"]);
    Route::get('accounting/get/contact-list', [Accounting::class, "getContactList"]);
    Route::post("accounting/edit/contact-info", [Accounting::class, 'editContactInfo']);
    Route::post("accounting/edit/bank-info", [Accounting::class, 'editBankInfo']);
    Route::post("accounting/remove/bank-account", [Accounting::class, 'removeBankAccount']);
    Route::get('accounting/get/bank-details-by-id', [Accounting::class, "getBankDetailsById"]);

    //07-23-2021
    Route::post("accounting/add/new-deposit", [Accounting::class, 'addNewDeposit']);
    Route::post("accounting/add/new-withdrawal", [Accounting::class, 'addNewWithdrawal']);
    Route::post("accounting/add/new-expense", [Accounting::class, 'addNewExpense']);

    Route::get('accounting/get/deposit-list', [Accounting::class, "getDepositList"]);
    Route::get('accounting/get/withdrawal-list', [Accounting::class, "getWithdrawalList"]);
    Route::get('accounting/get/expense-list', [Accounting::class, "getExpenseList"]);
    Route::post('accounting/get/filter-search', [Accounting::class, "getFilterSearch"]);
    Route::get('accounting/get/receivable-list', [Accounting::class, "getReceivableList"]);

    // new route stooltest
    Route::post('laboratory/order/ordernew-stooltest', [Laboratory::class, 'getOrderStoolTestNew']);
    Route::get('laboratory/order/ordernew-stooltest/details', [Laboratory::class, 'getOrderStoolTestNewDetails']);
    Route::post('laboratory/order/ordernew-stooltest/save-process-result', [Laboratory::class, 'saveStoolTestOrderResult']);
    Route::post('laboratory/order/ordernew-stooltest/save-setprocessing', [Laboratory::class, 'setStoolTestOrderProcessed']);
    Route::post('laboratory/order/ordernew-stooltest/save-setpending', [Laboratory::class, 'setStoolTestOrderPending']);
    Route::get('laboratory/order/ordernew-stooltest/complete/details-print', [Laboratory::class, 'getCompleteStoolTestOrderDetails']);
    Route::post('laboratory/stooltest/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryStooltestOrderProcessed']);

    //07-*23-*2021
    Route::get('accounting/product/inventory-list', [Accounting::class, "getProductInventory"]);

    // branches stockroom inventory
    Route::get('accounting/branch/inventory/stockroom', [Accounting::class, "getBranchStockroomInventory"]);
    Route::get('accounting/branch/inventory/laboratory', [Accounting::class, "getBranchLaboratoryInventory"]);

    // branches imaging inventory
    Route::get('accounting/branch/sales', [Accounting::class, "getBranchSales"]);
    Route::get('accounting/payable/list', [Accounting::class, "getPayableList"]);
    Route::post('accounting/set-as-paid/soa-by-company', [Accounting::class, "setAsPaidByCompany"]);

    Route::post("accounting/payable/invoice-payment", [Accounting::class, "payableInvoicePayment"]);

    //7-26/2021
    Route::get('hr/get/all-branches', [HR::class, "getAllBraches"]);
    Route::post('hr/edit/existing-branch', [HR::class, "getEditExistingBranch"]);
    Route::post('hr/add/new-branch', [HR::class, "addNewBranch"]);
    Route::get('accounting/get/collection-list', [Accounting::class, "getCollectionList"]);
    Route::get('accounting/management/get-branches', [Accounting::class, "getAllBraches"]);

    // jhomar 07/27/2021
    Route::get('accounting/grand/sales-totalamount', [Accounting::class, "getSalesGrandTotalAmount"]);
    Route::get('accounting/grand/laboratory/inventory-totalamount', [Accounting::class, "getInventoryGrandTotalAmountLaboratory"]);
    Route::get('accounting/grand/stockroom/inventory-totalamount', [Accounting::class, "getInventoryGrandTotalAmountStockroom"]);
    Route::get('accounting/grand/payable-totalamount', [Accounting::class, "getPayableGrandTotalAmount"]);
    Route::get('accounting/grand/recievable-totalamount', [Accounting::class, "getRecievableGrandTotalAmount"]);
    Route::get('accounting/grand/expense-totalamount', [Accounting::class, "getExpensesGrandTotalAmount"]);
    Route::get('accounting/grand/bank-totalamount', [Accounting::class, "getBankGrandTotalAmount"]);
    Route::get('accounting/grand/collection-totalamount', [Accounting::class, "getCollectionGrandTotalAmount"]);
    Route::get('accounting/branch/laboratory/inventory-totalamount', [Accounting::class, "getBranchLaboratoryInventoryTotal"]);
    Route::get('accounting/branch/stockroom/inventory-totalamount', [Accounting::class, "getBranchStockroomInventoryTotal"]);
    Route::get('accounting/branch/sales-totalamount', [Accounting::class, "getBranchSalesTotal"]);
    Route::get('accounting/branch/recievable-totalamount', [Accounting::class, "getBranchReceivableTotal"]);
    Route::get('accounting/branch/collection-totalamount', [Accounting::class, "getBranchCollectionTotal"]);
    Route::get('accounting/branch/expense-totalamount', [Accounting::class, "getBranchExpenseTotal"]);

    // 7-29-2021
    Route::get('cashier/get/patients-list', [Cashier::class, "getPatientsList"]);
    Route::get('cashier/patient/patient-information', [Cashier::class, 'getPatientInformation']);
    Route::get('cashier/get/package-list', [Cashier::class, 'getPackageList']);
    Route::get('cashier/get/unpaid-bills', [Cashier::class, 'getUnpaidListByPatientId']);
    Route::post('cashier/add/package-savetemp', [Cashier::class, 'savePackageOrderTemp']);
    Route::post('cashier/delete/unpaid-package', [Cashier::class, 'deleteOrder']);
    Route::post("cashier/save/processorder", [Cashier::class, 'saveOrderProcess']);
    Route::get('cashier/get/unpaid-orderlist', [Cashier::class, 'getUnpaidOrderList']);
    Route::get('cashier/get/paid-orderlist', [Cashier::class, 'getPaidOrderList']);
    Route::get('cashier/get/hmo-by-company-id', [Cashier::class, 'getAllHmoList']);
    Route::post('billing/edit/form-print-info', [Billing::class, 'editFormInfo']);
    Route::get('billing/get/form-infomartion', [Billing::class, 'getCurrentFormInformation']);

    //7-31-2021
    Route::get('accounting/get/temp-save-expense', [Accounting::class, 'getTempSaveExpense']);
    Route::post('accounting/add/new-temp-expense', [Accounting::class, 'addNewTempExpense']);
    Route::post('accounting/remove/temp-expense', [Accounting::class, 'removeTempExpense']);

    Route::post('accounting/save/confirm-expense', [Accounting::class, 'saveConfirmExpense']);
    Route::get('accounting/get/expense-all-noo-group', [Accounting::class, 'getExpenseAllNoGroup']);
    Route::get('accounting/get/expense-details-by-id', [Accounting::class, 'getExpenseDetailsById']);
    Route::get('accounting/get/expense/form-information', [Accounting::class, 'getCurrentFormInformationExpense']);

    Route::post('accounting/edit/expense-print-info', [Accounting::class, 'editExpensePrintInfo']);
    Route::post('accounting/save/leave-application', [Accounting::class, 'saveLeaveApplication']);

    //papsmear
    Route::post('laboratory/order/ordernew-papsmeartest', [Laboratory::class, 'getOrderPapsmearTestNew']);
    Route::get('laboratory/order/ordernew-papsmeartest/details', [Laboratory::class, 'getOrderPapsmearTestNewDetails']);
    Route::post('laboratory/papsmeartest/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryPapsmeartestOrderProcessed']);
    Route::post('laboratory/order/ordernew-papsmear/save-process-result', [Laboratory::class, 'savePapsmearTestOrderResult']);
    Route::get('laboratory/order/ordernew-papsmear/complete/details-print', [Laboratory::class, 'getCompletePapsmearOrderDetails']);

    //08-04-2021
    Route::get('accounting/get/leave-for-approval', [Accounting::class, 'getForLeaveApproval']);
    Route::post('accounting/save/leave-decision', [Accounting::class, 'saveLeaveDecision']);

    // urinalysis
    // new route urinalysis
    Route::post('laboratory/order/ordernew-urinalysis', [Laboratory::class, 'getOrderUrinalysis']);
    Route::get('laboratory/order/ordernew-urinalysis/details', [Laboratory::class, 'getOrderUrinalysisDetails']);
    Route::post('laboratory/urinalysis/order-setprocess/custom-qty', [Laboratory::class, 'setUrinalysisOrderProcessed']);
    Route::post('laboratory/order/ordernew-urinalysis/save-process-result', [Laboratory::class, 'saveUrinalysisOrderResult']);
    Route::get('laboratory/order/ordernew-urinalysis/complete/details-print', [Laboratory::class, 'getCompleteUrinalysisOrderDetails']);

    Route::post('laboratory/order/ordernew-urinalysis/save-setpending', [Laboratory::class, 'setUrinalysisOrderPending']);

    // new route for haptech - 7 -31 - 2021
    Route::post("haptech/dr/approved-byhaptech", [Haptech::class, 'drApprovedByHaptech']);
    Route::get('haptech/dr/for-approval-details', [Haptech::class, 'getForApprovalDrDetails']);
    Route::get('haptech/dr/for-approval', [Haptech::class, 'getForApprovalDr']);
    Route::post("haptech/invoice/approved-byhaptech", [Haptech::class, 'invoiceApprovedByHaptech']);
    Route::get("haptech/invoiced/invoice-list", [Haptech::class, 'getInvoiceProducts']);

    // new ecg route
    Route::post("laboratory/order/ordernew-ecg", [Laboratory::class, 'getOrderEcg']);
    Route::get("laboratory/order/ordernew-ecg/details", [Laboratory::class, 'getOrderEcgDetails']);
    Route::post('laboratory/ecg/order-setprocess/custom-qty', [Laboratory::class, 'setEcgOrderProcessed']);
    Route::post('laboratory/order/ordernew-ecg/save-process-result', [Laboratory::class, 'saveEcgOrderResult']);
    Route::get('laboratory/order/ordernew-ecg/complete/details-print', [Laboratory::class, 'getCompleteEcgOrderDetails']);

    // new medical exam route
    Route::post("laboratory/order/ordernew-medicalexam", [Laboratory::class, 'getOrderMedicalExam']);
    Route::get("laboratory/order/ordernew-medicalexam/details", [Laboratory::class, 'getOrderMedicalExamDetails']);
    Route::post('laboratory/medicalexam/order-setprocess/custom-qty', [Laboratory::class, 'setMedicalExamOrderProcessed']);
    Route::post('laboratory/order/ordernew-medicalexam/save-process-result', [Laboratory::class, 'saveMedicalExamOrderResult']);
    Route::get('laboratory/order/ordernew-medicalexam/complete/details-print', [Laboratory::class, 'getCompleteMedicalExamOrderDetails']);

    //08-07-2021
    Route::get('admission/order/local/get-unpaid-list', [Admission::class, 'getUnpaidImagingOrder']);

    Route::get("documentation/sidebar/header-infomartion", [Documentation::class, 'hisDocumentationGetHeaderInfo']);
    Route::get("documentation/get-personal-info-by-id", [Documentation::class, 'hisDocumentationGetPersonalInfoById']);
    Route::post("documentation/information/personal-uploadnewprofile", [Documentation::class, 'hisDocumentationUploadProfile']);
    Route::post("documentation/information/personal-update", [Documentation::class, 'hisDocumentationUpdatePersonalInfo']);
    Route::post("documentation/update-username", [Documentation::class, 'hisDocumentationUpdateUsername']);
    Route::post("documentation/update-password", [Documentation::class, 'hisDocumentationUpdatePassword']);

    Route::get("om/sidebar/header-infomartion", [OM::class, 'hisOMHeaderInfo']);
    Route::get("om/get-personal-info-by-id", [OM::class, 'hisOMGetPersonalInfoById']);
    Route::post("om/information/personal-uploadnewprofile", [OM::class, 'hisOMUploadProfile']);
    Route::post("om/update-username", [OM::class, 'hisOMUpdateUsername']);
    Route::post("om/update-password", [OM::class, 'hisOMUpdatePassword']);
    Route::post("om/information/personal-update", [OM::class, 'hisOMUpdatePersonalInfo']);

    Route::get("other/sidebar/header-infomartion", [Other::class, 'hisOtherHeaderInfo']);
    Route::get("other/get-personal-info-by-id", [Other::class, 'hisOtherGetPersonalInfoById']);
    Route::post("other/information/personal-uploadnewprofile", [Other::class, 'hisOtherUploadProfile']);
    Route::post("other/update-username", [Other::class, 'hisOtherUpdateUsername']);
    Route::post("other/update-password", [Other::class, 'hisOtherUpdatePassword']);
    Route::post("other/information/personal-update", [Other::class, 'hisOtherUpdatePersonalInfo']);

    Route::get('hmis/get/leave-for-approval', [HMIS::class, 'getForLeaveApproval']);
    Route::post('hmis/save/leave-decision', [HMIS::class, 'saveLeaveDecision']);

    Route::get('hmis/get/doctor-list', [HMIS::class, 'getHIMSDoctorList']);
    Route::get('hmis/get/doctor-id/services', [HMIS::class, 'getHIMSServicesByDocId']);
    Route::get('hims/doctor/sales-result', [HMIS::class, 'getHIMSDoctorSales']);
    Route::post('hims/doctor/filter-by-date/sales-result', [HMIS::class, 'doctorSalesFilterByDate']);

    Route::get('hims/psychology/sales-result', [HMIS::class, 'getHIMSPsychologySales']);
    Route::post('hims/psychology/filter-by-date/sales-result', [HMIS::class, 'psychologySalesFilterByDate']);

    //oral glucose
    Route::post('laboratory/order/ordernew-oralglucosetest', [Laboratory::class, 'getOrderOralGlucoseTestNew']);
    Route::get('laboratory/order/ordernew-oralglucosetest/details', [Laboratory::class, 'getOralGlucoseTestNewDetails']);
    Route::post('laboratory/oralglucosetest/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryOralGlucosetestOrderProcessed']);
    Route::post('laboratory/order/ordernew-oralglucosetest/save-process-result', [Laboratory::class, 'saveOralGlucosetestOrderResult']);
    Route::get('laboratory/order/ordernew-oralglucose/complete/details-print', [Laboratory::class, 'getCompleteOralGlucoseOrderDetails']);

    //thyroid profile
    Route::post('laboratory/order/ordernew-thyroidprofile', [Laboratory::class, 'getOrderThyroidProfileTestNew']);
    Route::get('laboratory/order/ordernew-thyroidprofiletest/details', [Laboratory::class, 'getThyroidProfileTestNewDetails']);
    Route::post('laboratory/thyroidprofiletest/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryThyroidProfiletestOrderProcessed']);
    Route::post('laboratory/order/ordernew-thyroidprofile/save-process-result', [Laboratory::class, 'saveThyroidProfileTestOrderResult']);
    Route::get('laboratory/order/ordernew-thyroidprofile/complete/details-print', [Laboratory::class, 'getCompleteThyroidProfileOrderDetails']);

    //immunology
    Route::post('laboratory/order/ordernew-immunology', [Laboratory::class, 'getOrderImmunologyTestNew']);
    Route::get('laboratory/order/ordernew-immunologytest/details', [Laboratory::class, 'getImmunologyTestNewDetails']);
    Route::post('laboratory/immunologytest/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryImmunologytestOrderProcessed']);
    Route::post('laboratory/order/ordernew-immunology/save-process-result', [Laboratory::class, 'saveImmunologyTestOrderResult']);
    Route::get('laboratory/order/ordernew-immunology/complete/details-print', [Laboratory::class, 'getCompleteImmunologyOrderDetails']);

    //miscellaneous
    Route::post('laboratory/order/ordernew-miscellaneous', [Laboratory::class, 'getOrderMiscellaneousTestNew']);
    Route::get('laboratory/order/ordernew-miscellaneoustest/details', [Laboratory::class, 'getMiscellaneousTestNewDetails']);
    Route::post('laboratory/miscellaneoustest/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryMiscellaneoustestOrderProcessed']);
    Route::post('laboratory/order/ordernew-miscellaneous/save-process-result', [Laboratory::class, 'saveMiscellaneousOrderResult']);
    Route::get('laboratory/order/ordernew-miscellaneous/complete/details-print', [Laboratory::class, 'getCompleteMiscellaneousOrderDetails']);

    //hepatitis
    Route::post('laboratory/order/ordernew-hepatitisprofile', [Laboratory::class, 'getOrderHepatitisProfileTestNew']);
    Route::get('laboratory/order/ordernew-hepatitisprofiletest/details', [Laboratory::class, 'getHepatitisProfileTestNewDetails']);
    Route::post('laboratory/hepatitisprofiletest/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryHepatitisProfiletestOrderProcessed']);
    Route::post('laboratory/order/ordernew-hepatitisprofiletest/save-process-result', [Laboratory::class, 'saveHepatitisProfileOrderResult']);
    Route::get('laboratory/order/ordernew-hepatitisprofile/complete/details-print', [Laboratory::class, 'getCompleteHepatitisProfileOrderDetails']);

    Route::get('warehouse/get-spec-role', [Haptech::class, 'hisWarehouseGetRole']);
    Route::post('warehouse/draccount/new-agent', [Warehouse::class, 'newDrAccountAgent']);
    Route::get('warehouse/draccount/agent/list', [Warehouse::class, 'getDrAccountAgentList']);
    Route::get('warehouse/draccount/product/delivered-list', [Warehouse::class, 'getDrAccountProductDelivered']);
    Route::get('warehouse/draccount/agent/agentdraccount-list', [Warehouse::class, 'getAgentAccounts']);
    Route::get('warehouse/products/product-monitoring', [Warehouse::class, 'getProductMonitoringDetails']);

    Route::get('documentation/get/online/result-to-edit', [Documentation::class, 'getResultToEditOnline']);
    Route::get('documentation/get/local/result-to-edit', [Documentation::class, 'getResultToEditLocal']);

    // using telerad and local radiologist
    Route::post('documentation/save/edited-result', [Documentation::class, 'saveEditedResult']);

    Route::post('documentation/save/local/edited-result', [Documentation::class, 'saveEditedResultLocal']);

    Route::get("warehouse/draccount/product/delivered/list-byreport", [Warehouse::class, 'getDrAccountProductDeliveredByReport']);

    Route::get("admission/get/all-census", [Admission::class, 'getAllCensus']);
    Route::get("admission/get/filter-by-date", [Admission::class, 'getAllCensusFilterByDate']);

    //8/17/2021
    Route::post('accounting/items/psycorder/create-order', [Accounting::class, 'saveTempPsychologyOrder']);
    Route::get("accounting/get/psychology-test", [Accounting::class, 'getAllPsychology']);
    Route::post("doctor/psychology/new/order/department-details", [Doctor::class, 'getPsycOrderDeptDetails']);
    Route::get('admission/psychology/new/order/unsave-orderlist', [Admission::class, 'getUnsavePsycOrder']);
    Route::post('admission/psychology/new/order/order-addtounsave', [Admission::class, 'addPsycOrderTounsave']);
    Route::post('doctor/psychology/new/order/order-cancel', [Doctor::class, 'removePsyOrderFromUnsave']);

    Route::post('admission/psychology/new/order/process-laborder', [Admission::class, 'processPsychologyOrder']);
    Route::post('admission/patient/psychology/order/unpaid-list', [Admission::class, 'psychologyUnpaidOrderByPatient']);
    Route::post('admission/patient/psychology/order/paid-list', [Admission::class, 'psychologyPaidOrderByPatient']);

    //audiometric report
    Route::post('psychology/order/ordernew-audiometry', [Psychology::class, 'getOrderAudiometryNew']);
    Route::get('psychology/order/ordernew-audiometry/details', [Psychology::class, 'getOrderAudiometryNewDetails']);
    Route::post('psychology/audiometry/order-setprocess', [Psychology::class, 'psychologyAudiometryOrderProcessed']);
    Route::post('psychology/order/ordernew-audiology/save-process-result', [Psychology::class, 'saveAudiometryOrderResult']);
    Route::get('psychology/order/ordernew-audiology/complete/details-print', [Psychology::class, 'getCompleteAudiometryOrderDetails']);

    //ishihara test
    Route::post('psychology/order/ordernew-ishihara', [Psychology::class, 'getOrderIshiharaNew']);
    Route::get('psychology/order/ordernew-ishihara/details', [Psychology::class, 'getOrderIshiharaNewDetails']);
    Route::post('psychology/ishihara/order-setprocess', [Psychology::class, 'psychologyIshiharaOrderProcessed']);
    Route::post('psychology/order/ordernew-ishihara/save-process-result', [Psychology::class, 'saveIshiharaOrderResult']);
    Route::get('psychology/order/ordernew-ishihara/complete/details-print', [Psychology::class, 'getCompleteIshiharaOrderDetails']);

    //neurology
    Route::post('psychology/order/ordernew-neurology', [Psychology::class, 'getOrderNeurologyNew']);
    Route::get('psychology/order/ordernew-neurology/details', [Psychology::class, 'getOrderNeurologyNewDetails']);
    Route::post('psychology/neurology/order-setprocess', [Psychology::class, 'psychologyNeurologyOrderProcessed']);
    Route::post('psychology/order/ordernew-neurology/save-process-result', [Psychology::class, 'saveNeurologyOrderResult']);
    Route::get('psychology/order/ordernew-neurology/complete/details-print', [Psychology::class, 'getCompleteNeurologyOrderDetails']);

    Route::get('psychology/order/completed/report', [Psychology::class, 'getPsychologyCompletedReport']);
    Route::post("admission/patients/newcensus-save", [Admission::class, 'admissionAddNewContactTracing']);
    Route::post('admission/patient/psychology/order/paid-detailsbytable', [Admission::class, 'paidPsychologyOrderDetails']);

    Route::get("nurse/sidebar/header-infomartion", [Nurse::class, 'hisNurseGetHeaderInfo']);
    Route::get("nurse/get-personal-info-by-id", [Nurse::class, 'hisNurseGetPersonalInfoById']);
    Route::post("nurse/information/personal-uploadnewprofile", [Nurse::class, 'hisNurseUploadProfile']);
    Route::post("nurse/information/personal-update", [Nurse::class, 'hisNurseUpdatePersonalInfo']);
    Route::post("nurse/update-username", [Nurse::class, 'hisNurseUpdateUsername']);
    Route::post("nurse/update-password", [Nurse::class, 'hisNurseUpdatePassword']);

    Route::get("nurse/patients/nurse-queue", [Nurse::class, 'getAllNurseOnQueue']);
    Route::get('nurse/patient/patient-information', [Nurse::class, 'nurseGetPatientInformation']);
    Route::post("nurse/patients/edit-patient", [Nurse::class, 'nurseUpdatePatientInfo']);
    Route::get('nurse/get-all-doctors', [Nurse::class, 'nurseGetAllDoctors']);
    Route::post("nurse/patient/create-appointment", [Nurse::class, 'nurseCreateAppointment']);

    Route::post("nurse/patient/appointment/reschedule", [Nurse::class, 'nurseRescheduleAppointment']);
    Route::post("nurse/update/new-queue-to-cashier", [Nurse::class, 'nurseNewQueueToCashier']);

    //8.23.2021
    Route::get("cashier/get/patient/cashier-queue", [Cashier::class, 'getAllCashierOnQueue']);
    Route::get("cashier/get/patient/biling-details", [Cashier::class, 'getAllCashierBillingDetails']);
    Route::post('doctor/psychology/new/order/order-addtounsave', [Doctor::class, 'addPsycOrderTounsave']);
    Route::get('doctor/psychology/new/order/unsave-orderlist', [Doctor::class, 'getUnsavePsycOrder']);
    Route::post('doctor/psychology/new/order/process-laborder', [Doctor::class, 'processPsychologyOrder']);
    Route::post('doctor/patient/psychology/order/unpaid-list', [Doctor::class, 'psychologyUnpaidOrderByPatient']);
    Route::post('doctor/patient/psychology/order/paid-list', [Doctor::class, 'psychologyPaidOrderByPatient']);
    Route::post('doctor/patient/psychology/order/paid-detailsbytable', [Doctor::class, 'paidPsychologyOrderDetails']);

    Route::get("imaging/get/patient/imaging-queue", [Imaging::class, 'imagingGetPatientListQueue']);

    // new routeeeeeeeeeee 8-16-2021
    Route::get("laboratory/monitoring/product/in-productbybatches", [Laboratory::class, 'getItemMonitoringBatches']);
    // endorsement new rouse
    Route::get('endorsement/sidebar/header-infomartion', [Endorsement::class, 'getInformation']);
    Route::post('endorsement/information/personal-uploadnewprofile', [Endorsement::class, 'updateProfileImage']);
    Route::post('endorsement/update/username', [Endorsement::class, 'updateUsername']);
    Route::post('endorsement/update/password', [Endorsement::class, 'updatePassword']);
    Route::get('endorsement/patient/queue-list', [Endorsement::class, 'getQueueList']);
    Route::get('endorsement/walk-in/patient/laboratory/laboratory-orders', [Endorsement::class, 'getLaboratoryOrder']);
    Route::post('endorsement/walk-in/laboratory/new/order/process-laborder', [Endorsement::class, 'processLabOrder']);
    Route::post('endorsement/walk-in/patient/laboratory/order/paid-detailsbytable', [Endorsement::class, 'paidLabOrderDetails']);
    Route::get('endorsement/imaging/imaging-details', [Endorsement::class, 'getImagingDetails']);
    Route::get('endorsement/imaging/local/imaging-orderlist', [Endorsement::class, 'imagingOrderList']);
    Route::get('endorsement/order/local/getimaging-list', [Endorsement::class, 'getImagingOrderList']);
    Route::get('endorsement/walkin/package-list', [Endorsement::class, 'getPackagesList']);
    Route::get('endorsement/walkin/unpaid-bills', [Endorsement::class, 'getUnpaidListByPatientId']);
    Route::post('endorsement/walkin/package-savetemp', [Endorsement::class, 'savePackageOrderTemp']);
    Route::post("endorsement/information/personal-update", [Endorsement::class, 'updatePersonalInfo']);

    // new route 8-23-2021
    Route::post('endorsement/patient/setas-done', [Endorsement::class, 'setAsDone']);
    Route::get('endorsement/company/list', [Endorsement::class, 'getCompany']);
    Route::get('endorsement/company/hmo-list', [Endorsement::class, 'getCompanyHMO']);

    Route::get('endorsement/get-personal-info-by-id', [Endorsement::class, 'endorsementGetPersonalInfoById']);
    Route::get('receiving/get-personal-info-by-id', [Receiving::class, 'receivingGetPersonalInfoById']);

    // new route 8-23-2021
    Route::get('receiving/sidebar/header-infomartion', [Receiving::class, 'getInformation']);
    Route::get('receiving/patient/queue-list', [Receiving::class, 'getPatientQueue']);
    Route::post('receiving/specimen/new', [Receiving::class, 'newSpecimen']);
    Route::get('receiving/specimen/list', [Receiving::class, 'specimentList']);
    Route::post('receiving/specimen/remove', [Receiving::class, 'specimentRemove']);
    Route::post('receiving/patient/setas-done', [Receiving::class, 'setAsDone']);

    Route::post('receiving/information/personal-uploadnewprofile', [Receiving::class, 'updateProfileImage']);
    Route::post('receiving/update/username', [Receiving::class, 'updateUsername']);
    Route::post('receiving/update/password', [Receiving::class, 'updatePassword']);
    Route::post("receiving/information/personal-update", [Receiving::class, 'updatePersonalInfo']);

    //8-25-2021
    Route::post('cashier/laboratory/new/order/department-details', [Cashier::class, 'getLabOrderDeptDetails']);
    Route::post('cashier/laboratory/new/order/order-addtounsave', [Cashier::class, 'addLabOrderTounsave']);
    Route::post('cashier/laboratory/new/order/order-cancel', [Cashier::class, 'removeLabOrderFromUnsave']);
    Route::post('cashier/laboratory/new/order/process-laborder', [Cashier::class, 'processLabOrder']);

    Route::get('cashier/imaging/imaging-details', [Cashier::class, 'getImagingDetails']);
    Route::get('cashier/imaging/local/imaging-orderlist', [Cashier::class, 'imagingOrderList']);
    Route::get('cashier/imaging/local/imaging-orderlist/details', [Cashier::class, 'imagingOrderSelectedDetails']);
    Route::get('cashier/imaging/local/imaging-orderlist/unsave', [Cashier::class, 'imagingAddOrderUnsavelist']);
    Route::post('cashier/imaging/local/order-add', [Cashier::class, 'imagingAddOrder']);
    Route::post("cashier/order/local/unsave/process-order", [Cashier::class, 'imagingOrderUnsaveProcess']);

    // billing hmo/newroute
    Route::get("billing/hmo/soa/get-hmo-transactions", [Billing::class, "getCompaniesHMOTransaction"]);

    Route::post("cashier/psychology/new/order/department-details", [Cashier::class, 'getPsycOrderDeptDetails']);
    Route::get('cashier/psychology/new/order/unsave-orderlist', [Cashier::class, 'getUnsavePsycOrder']);
    Route::post('cashier/psychology/new/order/order-addtounsave', [Cashier::class, 'addPsycOrderTounsave']);
    Route::post('cashier/psychology/new/order/order-cancel', [Cashier::class, 'removePsyOrderFromUnsave']);
    Route::post('cashier/psychology/new/order/process-laborder', [Cashier::class, 'processPsychologyOrder']);

    // new route psychology
    Route::post("psychology/get/order-details/list", [Psychology::class, 'getPsycologyOrder']);
    Route::get("psychology/get/order/unsave-orderlist", [Psychology::class, 'getUnsavePsycologyOrder']);
    Route::post('psychology/order/order-addtounsave', [Psychology::class, 'addPsycOrderTounsave']);
    Route::post('psychology/psychology/order/order-cancel', [Psychology::class, 'removePsyOrderFromUnsave']);
    Route::post('psychology/order/process-laborder', [Psychology::class, 'processPsychologyOrder']);
    Route::post('psychology/patient/order/unpaid-list', [Psychology::class, 'getPsychologyUnpaidList']);
    Route::post('psychology/patient/order/unpaid-listdetails', [Psychology::class, 'getPsychologyUnpaidListDetails']);
    Route::post('psychology/patient/order/paid-list', [Admission::class, 'psychologyPaidOrderByPatient']);

    //8-26-2021
    Route::get('cashier/get/all-report', [Cashier::class, 'getAllReport']);
    Route::post('cashier/get/filter-by-date/report', [Cashier::class, 'getAllReportByFilter']);
    Route::get('admission/get/queuing-list', [Admission::class, 'getQueuingList']);
    Route::get('laboratory/order/specimen-list', [Laboratory::class, 'getSpecimenList']);
    Route::post("admission/create/new-queue-to-cashier", [Admission::class, 'createNewQueueForAdditional']);
    Route::get("accounting/get/doctor-services", [Accounting::class, "getDoctorServiceByDocId"]);
    Route::get("accounting/get/laboratory-test", [Accounting::class, "getLaboratoryTestByOrderId"]);

    Route::get('cashier/walkin/package-list', [Cashier::class, 'getPackagesList']);
    Route::get('cashier/walkin/unpaid-bills', [Cashier::class, 'getUnpaidListByPatientId']);

    // new cbc / cbc platelet count
    Route::post('laboratory/order/ordernew-cbc', [Laboratory::class, 'getOrderCBCNew']);
    Route::get('laboratory/order/ordernew-cbc/details', [Laboratory::class, 'getOrderCBCNewDetails']);
    Route::post('laboratory/order/ordernew-cbc/save-process-result', [Laboratory::class, 'saveCBCOrderResult']);
    Route::post('laboratory/order/ordernew-cbc/save-setpending', [Laboratory::class, 'setCBCOrderPending']);
    Route::post('laboratory/order/ordernew-cbc/save-setprocessing', [Laboratory::class, 'setCBCOrderProcessed']);
    Route::get('laboratory/order/ordernew-cbc/complete/details-print', [Laboratory::class, 'getCompleteCBCOrderDetails']);
    Route::post('laboratory/cbc/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryCBCOrderProcessed']);

    // doctor new route p.e
    Route::get('doctor/physical/exam/new-pe', [Doctor::class, 'newPhysicalExam']);
    Route::get('doctor/pe-exam/new-pe-orderlist', [Doctor::class, 'newPEOrderList']);
    Route::post('doctor/pe-exam/new-setprocessed', [Doctor::class, 'setPEOrderProcess']);

    Route::get('nurse/patient/notes/notes-list', [Nurse::class, 'getNotes']);
    Route::get('nurse/patient/patient-treatmentplan', [Nurse::class, 'getTreatmentPlan']);
    Route::post("nurse/information/patient-uploadnewprofile", [Nurse::class, 'hisNurseUploadPatientProfile']);
    Route::get("cashier/get/doctor-list", [Cashier::class, 'casherGetAllLocalDoctors']);

    Route::get("cashier/get/by-doctor-id/doctor-details", [Cashier::class, 'casherGetDoctorDetailsById']);

    // new route updates on bmdc opening 9-18-2021
    // new p.e exam route
    Route::post('accounting/items/pysical-exam/create-order', [Accounting::class, 'saveMedicalOrder']);
    Route::get('accounting/items/pysical-exam/list-order', [Accounting::class, 'getMedicalOrder']);

    Route::post('cashier/physical-exam/new/order/unsave-orderlist', [Cashier::class, 'getUnsavePEOrder']);
    Route::post('cashier/physical-exam/new/order/process-pe-order', [Cashier::class, 'processPEOrder']);
    Route::post('cashier/physical-exam/new/order/order-addtounsave', [Cashier::class, 'addPEOrderTounsave']);

    Route::post('endorsement/physical-exam/new/order/order-addtounsave', [Endorsement::class, 'addPEOrderTounsave']);
    Route::post('endorsement/physical-exam/new/order/process-pe-order', [Endorsement::class, 'processPEOrder']);

    Route::post('endorsement/physical-exam/order/unpaid-list', [Endorsement::class, 'PEUnpaidOrderByPatient']);
    Route::post('endorsement/physical-exam/order/unpaid-listdetails', [Endorsement::class, 'PEUnpaidOrderByPatientDetails']);

    Route::get('cashier/add-on/doctor/list-ofdoctors', [Cashier::class, 'getDoctorsList']);
    Route::get('endorsement/add-on/doctor/list-ofdoctor-services', [Endorsement::class, 'getDoctorsServices']);

    Route::post('cashier/add-on/doctor/save-orderto-unpaid', [Cashier::class, 'handleNewDoctorsServiceOrder']);
    Route::get('cashier/add-on/doctor/list-unpaidorder', [Cashier::class, 'getUnpaidDoctorServiceOrder']);

    Route::get('doctors/information/personal-information', [Doctor::class, 'getPersonalInfo']);

    Route::get('doctors/patient/certificates/medicalcert-order-new', [Doctor::class, 'getNewMedCertOrder']);

    Route::get('doctors/certificates/medicalcert-order-all', [Doctor::class, 'getNewMedCertOrderAll']);

    Route::post('doctors/patient/certificates/medicalcert-setcomplete', [Doctor::class, 'setMedCertOrderCompleted']);

    Route::get('nurse/certificates/medicalcert-allcompleted', [Nurse::class, 'getCompletedMedCert']);
    Route::get('nurse/certificates/medicalcert-doctors-info', [Nurse::class, 'getDoctorsInfo']);

    // new route sunday -9-19-2021
    Route::post('doctor/patient/certificate/medical/new-certificate', [Doctor::class, 'newPatientMedicalCertificate']);
    Route::get('doctors/patient/certificates/medical/list-ofmedcert', [Doctor::class, 'getPatientMedicalCertificateList']);
    Route::get('nurse/certificates/medicalcert-patient-info', [Nurse::class, 'getPatientInformation']);
    Route::post('doctor/patient/appointment/update-consultation-rate', [Doctor::class, 'updateConsultationRate']);

    //new route brand and category for haptech 9-19-2021
    Route::post('pharmacy/warehouse/create/new-brand', [Warehouse::class, 'createNewBrand']);
    Route::get('pharmacy/warehouse/get/brand-list', [Warehouse::class, 'getHaptechBrandList']);
    Route::post('pharmacy/warehouse/create/new-category', [Warehouse::class, 'createNewCategory']);
    Route::get('pharmacy/warehouse/get/category-list', [Warehouse::class, 'getHaptechCategoryList']);

    Route::get('accounting/get/haptech/product-list', [Accounting::class, 'getHaptechProducts']);
    Route::get('accounting/get/haptech/product-desc', [Accounting::class, 'getHaptechProductDescriptions']);
    Route::get('accounting/get/lab-item/product-list', [Accounting::class, 'getLabItemProducts']);
    Route::get('accounting/get/lab-item/product-desc', [Accounting::class, 'getLabItemProductDescriptions']);

    //09-21-2021
    Route::get('pharmacy/warehouse/new-invoice/get-brandlist', [Warehouse::class, 'getBrandListForNewInvoice']);
    Route::get('pharmacy/warehouse/new-invoice/get-categorylist', [Warehouse::class, 'getCategoryListForNewInvoice']);
    Route::get('pharmacy/warehouse/new-invoice/get-desclist', [Warehouse::class, 'getDescListForNewInvoice']);

    // 09-22-2021
    Route::post("pharmacy/warehouse/account/editaccount", [Warehouse::class, "editAccountInfo"]);
    Route::post('pharmacy/warehouse/inventory/new-po-savetotemp', [Warehouse::class, 'savePOToTemp']);
    Route::get("pharmacy/warehouse/account/deliver/list-pototemp", [Warehouse::class, "getProductToPOList"]);
    Route::post("pharmacy/warehouse/inventory/product/po/item-removeintemp", [Warehouse::class, "removeItemFromPOTempList"]);
    Route::post("pharmacy/warehouse/inventory/product/po/save-tempproducts", [Warehouse::class, "processPOItemsInTemp"]);

    Route::get("haptech/invoiced/po-list", [Haptech::class, 'getPurchaseOrderProducts']);
    Route::get("pharmacy/warehouse/ponumber-details", [Warehouse::class, "getPONumberDetails"]);

    Route::get("pharmacy/warehouse/get/prod-by-po", [Warehouse::class, "getProductsByPONumber"]);
    Route::post('pharmacy/warehouse/inventory/complete/new-po-savetotemp', [Warehouse::class, 'saveCompletePOToTemp']);
    Route::post("pharmacy/warehouse/inventory/product/po/save-products-info", [Warehouse::class, "saveProductInfo"]);
    Route::post("pharmacy/warehouse/inventory/product/po/save-confirmeditem", [Warehouse::class, "saveConfirmedItem"]);

    Route::post("pharmacy/warehouse/update/brandname", [Warehouse::class, "updateBrandName"]);
    Route::post("pharmacy/warehouse/update/categoryname", [Warehouse::class, "updateCategoryName"]);

    Route::post("pharmacy/warehouse/products/update/product-by-id", [Warehouse::class, "updateProductById"]);

    // new route 9-24-2021
    // covid19
    Route::post('laboratory/order/ordernew-codiv19test', [Laboratory::class, 'getOrderCovidTestNew']);
    Route::get('laboratory/order/ordernew-codiv19test/details', [Laboratory::class, 'getOrderCovidTestNewDetails']);
    Route::post('laboratory/codiv19test/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryCovid19OrderProcessed']);
    Route::post('laboratory/order/ordernew-codiv19test/save-process-result', [Laboratory::class, 'saveCovid19TestOrderResult']);
    Route::get('laboratory/order/ordernew-covidtest/complete/details-print', [Laboratory::class, 'getCompleteCovid19OrderDetails']);

    // tumor maker route
    Route::post('laboratory/order/ordernew-tumor-maker-test', [Laboratory::class, 'getOrderTumorMakerTestNew']);
    Route::get('laboratory/order/ordernew-tumor-maker/details', [Laboratory::class, 'getOrderTumorMakerTestNewDetails']);
    Route::post('laboratory/tumor-makertest/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryTumorMakerOrderProcessed']);
    Route::post('laboratory/order/ordernew-tumor-makertest/save-process-result', [Laboratory::class, 'saveTumorMakerOrderResult']);
    Route::get('laboratory/order/ordernew-tumormaker/complete/details-print', [Laboratory::class, 'getCompleteTumorMakerOrderDetails']);
    //

    // drug test route
    Route::post('laboratory/order/ordernew-drug-test', [Laboratory::class, 'getOrderDrugTestTestNew']);
    Route::get('laboratory/order/ordernew-drug-test/details', [Laboratory::class, 'getOrderDrugTestTestNewDetails']);
    Route::post('laboratory/drugtest/order-setprocess/custom-qty', [Laboratory::class, 'laboratoryDrugTestOrderProcessed']);
    Route::post('laboratory/order/ordernew-drugtest/save-process-result', [Laboratory::class, 'saveDrugTestOrderResult']);
    Route::get('laboratory/order/ordernew-drugtest/complete/details-print', [Laboratory::class, 'getCompleteDrugTestOrderDetails']);
    //

    Route::post("pharmacy/warehouse/update/product-deactivate", [Warehouse::class, "updateProductDeactivate"]);
    Route::post("pharmacy/warehouse/update/product-activate", [Warehouse::class, "updateProductActivate"]);
    Route::get("pharmacy/warehouse/account/get/last-ponumber", [Warehouse::class, "getLastPONumber"]);

    //09-27-2021
    Route::get("hr/get/for-edit/specific-info", [HR::class, 'getSpecificInfoOfuserForEdit']);
    Route::post("hr/users/edit-users", [HR::class, 'hisHRUpdateUserInfo']);
    Route::get('documentation//to-edit/all-branches', [Documentation::class, "getAllBraches"]);
    Route::get('imaging/get/online/result-to-edit', [Imaging::class, 'getResultToEditOnline']);
    Route::get('documentation/get/online/to-print', [Documentation::class, 'getResultToPrint']);
    Route::get('imaging/order/printable/printheader', [Imaging::class, 'getImagingPrintableHeader']);

    //09-28-2021
    Route::get('laboratory/get/lab-item/product-desc', [Laboratory::class, 'getLabItemProductDescriptions']);
    Route::get('laboratory/get/lab-item/product-list', [Laboratory::class, 'getLabItemProducts']);

    // geapos
    Route::post('accounting/other/order/new-othertest', [Accounting::class, 'newAdditionalOrder']);
    Route::get('accounting/other/order/get-othertest', [Accounting::class, 'getAdditionalOrder']);

    Route::get('cashier/add-on/othertest-list', [Cashier::class, 'getOtherTestList']);
    Route::get('cashier/add-on/othertest-unpaid', [Cashier::class, 'getOtherTestListUnpaid']);
    Route::post('cashier/other/order/new-othertest', [Cashier::class, 'saveOtherTestToUnpaid']);
    Route::post('cashier/other/order/remove-othertest', [Cashier::class, 'removeUnpaidOrderTest']);

    //09-29=2021
    Route::post('laboratory/edit/result-print-layout', [Laboratory::class, 'editResultPrintLayout']);
    Route::get('laboratory/get/result/print-layout', [Laboratory::class, 'getCurrentFormInformationResult']);

    //09-30-2021
    Route::get("accounting/other/test-person-list", [Accounting::class, "getOtherTestPerson"]);
    Route::get("accounting/other/sales/report-bydate", [Accounting::class, "getOtherSalesByDate"]);

    Route::post("nurse/update/patient-prof-pic", [Nurse::class, "updatePatientProfPic"]);
    Route::get("accounting/get/doctor-services", [Accounting::class, "getDoctorServices"]);
    Route::post("accounting/create/newservice-save", [Accounting::class, "createNewServiceSave"]);
    Route::post("accounting/update/doctor-service", [Accounting::class, "updateNewServiceSave"]);

    Route::get('cashier/get/add-on/doctorservice-list', [Cashier::class, 'getDoctorServiceList']);
    Route::post('cashier/get/order/new-doctorservice', [Cashier::class, 'saveDoctorServiceToUnpaid']);
    Route::get('doctor/get/all-service', [Doctor::class, 'getAllServicesMadamada']);

    // 10-2-2021 - fever master after vasex
    Route::get('accounting/psychology/order-list', [Accounting::class, 'getPsychologyOrderList']);

    Route::get("accounting/doctor/test-person-list", [Accounting::class, "getDoctorTestPerson"]);
    Route::get("accounting/doctor/sales/report-bydate", [Accounting::class, "getDoctorSalesByDate"]);

    Route::get("accounting/psychology/test-person-list", [Accounting::class, "getPsychologyTestPerson"]);
    Route::get("accounting/psychology/sales/report-bydate", [Accounting::class, "getPsychologySalesByDate"]);

    Route::get("accounting/package/test-person-list", [Accounting::class, "getPackageTestPerson"]);
    Route::get("accounting/package/sales/report-bydate", [Accounting::class, "getPackageSalesByDate"]);

    //11-02-2021
    Route::get("imaging/get/print-order", [Imaging::class, "getPrintOrder"]);

    // 11/06/2021
    Route::post('imaging/edit/confirmed/result', [Imaging::class, 'editConfirmedResultImaging']);

    // 11-08-2021
    Route::get("/laboratory/get/all/lab-report", [Laboratory::class, "getAllLaboratoryReport"]);
    Route::get("laboratory/get/all-test/by-tracerno", [Laboratory::class, "getAllTestByTracerNumber"]);
    Route::get("laboratory/get/all-spec-test/by-dept", [Laboratory::class, "getAllSpecTestByDepartment"]);

    Route::get("laboratory/get/to-edit-result", [Laboratory::class, "getLaboratoryGetToEditResult"]);

    //edit form
    Route::post('laboratory/hema-cbc/edit-result', [Laboratory::class, 'editResultHemaCBCConfirm']);
    Route::post('laboratory/serology/edit-result', [Laboratory::class, 'editResultSerologyConfirm']);
    Route::post('laboratory/hema/edit-result', [Laboratory::class, 'editResultHemaConfirm']);
    Route::post('laboratory/clinical-micro/edit-result', [Laboratory::class, 'editResultClinicalMicroConfirm']);
    Route::post('laboratory/clinical-chemistry/edit-result', [Laboratory::class, 'editResultClinicalChemistryConfirm']);
    Route::post('laboratory/stool/edit-result', [Laboratory::class, 'editResultStoolConfirm']);
    Route::post('laboratory/urinalysis/edit-result', [Laboratory::class, 'editResultUrinalysisConfirm']);
    Route::post('laboratory/thyroid-prof/edit-result', [Laboratory::class, 'editResultThyroidProfConfirm']);
    Route::post('laboratory/miscellaneous/edit-result', [Laboratory::class, 'editResultMiscellaneousConfirm']);
    Route::post('laboratory/hepatitis-prof/edit-result', [Laboratory::class, 'editResultHepatitisProfileConfirm']);
    Route::post('laboratory/covid19/edit-result', [Laboratory::class, 'editResultCovid19Confirm']);
    Route::post('laboratory/tumor-maker/edit-result', [Laboratory::class, 'editResultTumorMakerConfirm']);
    Route::post('laboratory/drug-test/edit-result', [Laboratory::class, 'editResultDrugTestConfirm']);

    Route::get("imaging/get/local/add-result", [Imaging::class, "getAllToAddResult"]);
    //email mode without using telerad and local radiologist
    Route::post('imaging/save/new-flow/edited-result', [Imaging::class, 'saveNewFlowEditedResult']);

    Route::post("/laboratory/get/all/lab-report/filter", [Laboratory::class, "getAllLaboratoryReportFilter"]);

    Route::get("cashier/get/all/hmo-list", [Cashier::class, "getAllHMOListNotBaseInCompany"]);
    Route::post("cashier/create/new-hmo", [Cashier::class, "createNewHMO"]);
    Route::post("cashier/update/hmo-by-id", [Cashier::class, "updateExistingHMOInfo"]);

    Route::get("accounting/get/doctor-list", [Accounting::class, "accountingGetDoctorList"]);
    Route::post("accounting/update/doctor-share", [Accounting::class, "accountingUpdateDoctorShare"]);
    Route::get("cashier/get/all/doctors/gen-record", [Cashier::class, "getAllDoctorGeneratedRecord"]);

    Route::post("cashier/create/salary-record", [Cashier::class, "cashierCreateSalaryRecord"]);

    Route::get("doctor/get/all/salary-record", [Doctor::class, "getAllSalaryRecord"]);
    Route::post("doctor/update/to-received/salary-record", [Doctor::class, "doctorUpdateToReceivedSalaryRecord"]);

    Route::get('imaging/get/patient/ultra-sound/forimaging', [Imaging::class, 'hisimagingGetPatientForImagingUltraSound']);
    Route::post('imaging/patient/order/ultra-sound/addresult', [Imaging::class, 'hisimagingOrderAddResultUltraSound']);

    Route::get('radiologist/patients/ultra-sound/getpatient-forreview', [Radiologist::class, 'getPatientForReviewUltraSound']);

    Route::post('radiologist/patients/order-ultra-sound/order-saveresult', [Radiologist::class, 'saveOrderUltraSoundResult']);

    Route::get('imaging/get/result/print-layout', [Imaging::class, 'getCurrentFormInformationResult']);
    Route::post('imaging/edit/result-print-layout', [Imaging::class, 'editResultPrintLayout']);
    Route::get("imaging/order/formheader-details", [Imaging::class, 'getImagingFormHeader']);

    //van endorsement
    Route::get('van/sidebar/header-infomartion', [Van::class, 'getInformation']);
    Route::get("van/get-personal-info-by-id", [Van::class, 'hisVanGetPersonalInfoById']);
    Route::post("van/information/personal-uploadnewprofile", [Van::class, 'hisVanEndtUploadProfile']);
    Route::post("van/information/personal-update", [Van::class, 'hisVanEndtUpdatePersonalInfo']);
    Route::post("van/patients/newpatient-save", [Van::class, 'hisVanNewPatient']);
    Route::get('van/patient/patient-information', [Van::class, 'vanGetPatientInformation']);
    Route::post("admission/patients/edit-patient-vital", [Van::class, 'vanEditPatientVital']);
    Route::post('van/add/package-savetemp', [Van::class, 'savePackageOrderTemp']);
    Route::post("van/billing/setaspaid-bill", [Van::class, 'vanBillingSetAsPaid']);
    Route::get('van/patient/patient-information', [Van::class, 'vanGetPatientInformation']);

    //van documentation
    Route::get("van/mobile-queue/patient-list", [Van::class, 'vanPatientListQueuing']);
    Route::get("van/order/urine-test", [Van::class, 'getVanUrineTest']);
    Route::get("van/order/stool-test", [Van::class, 'getVanStoolTest']);

    Route::post("van/process-order/urine-test", [Van::class, 'updateProcessVanUrineTest']);
    Route::post("van/process-order/stool-test", [Van::class, 'updateProcessVanStoolTest']);
    Route::post('van/order/ordernew-urinalysis/save-process-result', [Van::class, 'saveUrinalysisOrderResult']);
    Route::post('van/order/ordernew-stool/save-process-result', [Van::class, 'saveStoolOrderResult']);

    Route::get("van/get/online/to-print", [Van::class, 'vanPatientListToPrintResult']);

    // mobile van laboratory // 11-19-2021-updates-jhomar
    Route::get('van/laboratory/patient-withneworder', [Van::class, 'getMobileVanPatientsWithNewOrder']);

    // mobile documentation  // 11-19-2021-updates-jhomar

    Route::get('documentation/van/patient/get-neworderPE', [Van::class, 'getMobileVanPatientNewPEOrder']);
    Route::get('documentation/van/patient/imaging/get-neworderXRAY', [Van::class, 'getMobileVanPatientNewXRAYOrder']);
    Route::post('documentation/van/patient/imaging/order/addresult', [Van::class, 'getMobileVanPatientNewXRAYOrderAddResult']);
    Route::get('documentation/van/patient/get-neworderMedCert', [Van::class, 'getMobileVanPatientNewMedCertOrder']);
    Route::get('van/get/all/patient/record/imaging/for-print-van', [Van::class, 'getAllPatientRecordImagingForPrintVan']);

    // 11-22-2021
    Route::get('van/patients/getpatient-list', [Van::class, 'vanGetPatientList']);

    // 11-23-2021
    Route::post('accounting/update/laboratory-rates', [Accounting::class, 'getUpdateLaboratoryRates']);
    //11-24-2021
    Route::post('accounting/package/update/package-addorder', [Accounting::class, 'addOrderToPackage']);

    Route::get('cashier/get/laboratory-list', [Cashier::class, 'getLaboratoryList']);
    Route::post('van/add/laboratory-savetemp', [Van::class, 'addLabOrderTounsave']);
    Route::post("van/billing/cancel-bill", [Van::class, 'vanBillingCancel']);
    Route::get('van/imaging/local/imaging-orderlist', [Van::class, 'imagingOrderList']);
    Route::post('van/add/imaging-savetemp', [Van::class, 'addImagingOrderTounsave']);

    Route::get('van/get/psychology-list', [Van::class, 'getPsychologyList']);
    Route::post('van/add/psychology-savetemp', [Van::class, 'addPsychologyOrderTounsave']);
    Route::get('van/patient/get-neworderMedCert', [Van::class, 'getVanPatientNewMedCertOrder']);

    //01-21-2022
    Route::get('van/get/other-list', [Van::class, 'getOtherList']);
    Route::post('van/add/other-savetemp', [Van::class, 'addOtherOrderToUnsave']);

    //12082021
    Route::get('documentation/van/first/descending/patient/get-neworderMedCert', [Van::class, 'getMobileVanPatientNewMedCertOrderFirstDesc']);

    Route::get('cashier/get/all/order-details/by-trace-no', [Cashier::class, 'getAllOrdersByTraceNumberToEdit']);

    Route::post('cashier/delete/patient-test/by-id', [Cashier::class, 'deletePatientTestById']);
    Route::get('admission/get/company-list', [Admission::class, 'getAllCompanyListRegistration']);
    Route::get('admission/get/patients/by-company-id', [Admission::class, 'getAllPatientListByCompanyId']);

    Route::get('documentation/get/patient-info/patient-id', [Documentation::class, 'getPatientInfoPatientId']);
    Route::get('imaging/get/order/ordernew-imaging/complete/details-print', [Imaging::class, 'getOrderImagingDetailsPrint']);
    Route::get('laboratory/order/ordernew-medicalcert/complete/medicalcert-print', [Laboratory::class, 'getCompleteMedCertOrderDetails']);

    //01-10-2022
    Route::get('van/get/medical-technologist', [Van::class, 'getMedicalTechVanByBranch']);
    Route::get('van/get/radiologist', [Van::class, 'getRadiologistVanByBranch']);

    Route::get('van/get/all-doctor-list', [Van::class, 'getAllDoctorList']);
    Route::get("hr/get/for-edit/doctor/specific-info", [HR::class, 'getSpecificInfoOfuserForEditDoc']);
    Route::post("hr/users/doctors/edit-users", [HR::class, 'hisHRUpdateUserInfoDoc']);
    Route::post("haptech/add/new-account", [HMIS::class, 'haptechAddNewDoctorAccount']);

    Route::get("van/mobile-queue/clinical-summary/patient-list", [Van::class, 'vanClinicalSummaryPatientListQueuing']);
    Route::post('van/order/ordernew-medicalexam/save-process-result', [Van::class, 'vanSaveMedicalExamOrderResult']);
    Route::post('van/patient/certificates/medicalcert-setcomplete', [Van::class, 'vanSetMedCertOrderCompleted']);

    // 01-20-2022
    Route::post("doctor/shared/images/laboratory-result-new", [Doctor::class, "doctorResultAttachment"]);
    Route::post('doctor/laboratory/laboratory-details/doc-attach-dateingroupby', [Doctor::class, 'getPatientDocAttachImageDates']);
    Route::post('doctor/laboratory/laboratory-details/doc-attach-dateingroupby-details', [Doctor::class, 'getPatientDocAttachImageDatesDetails']);

    // 01-21-2022
    Route::post('prescription/nurse/local/prescriptionsaveallUnsave', [Prescription::class, 'prescriptionSaveallUnsaveNurse']);
    Route::post('nurse/get/local/prescriptionlist', [Prescription::class, 'getPrescriptionByNurse']);
    Route::post('nurse/get/local/prescriptiondetailslist', [Prescription::class, 'getPrescriptionDetailsByNurse']);
    Route::get('nurse/get/certificates/by-id/medicalcert-allcompleted', [Nurse::class, 'getCompletedMedCertById']);
    Route::get("nurse/get/history-illness/patient-id", [Nurse::class, 'getAllHistoryIllnessList']);
    Route::post("nurse/create/history-illness-pe/patient-id", [Nurse::class, 'createAllHistoryIllnessList']);
    Route::post("nurse/update/history-illness-pe/patient-id", [Nurse::class, 'updateAllHistoryIllnessList']);

    Route::get('documentation/van/patient/other/get-neworderECG', [Van::class, 'getMobileVanPatientNewECGOrder']);
    Route::post('van/order/ordernew-ecg/save-process-result', [Van::class, 'saveECGOrderResult']);
    Route::get("van/order/sarscov-test", [Van::class, 'getVanSarsCovTest']);
    Route::post('van/order/ordernew-sarscov/save-process-result', [Van::class, 'saveSarsCovOrderResult']);
    Route::get('laboratory/order/ordernew-sarscov/complete/details-print', [Laboratory::class, 'getCompleteSarsCovOrderDetails']);

    Route::post('accounting/set-as-paid/soa-by-patient', [Accounting::class, "setAsPaidByPatient"]);

    Route::get("/psychology/get/all/psy-report", [Psychology::class, "getAllPsychologyReport"]);
    Route::post("/psychology/get/all/psy-report/filter", [Psychology::class, "getAllPsychologyReportFilter"]);
    Route::get("admission/patients/other-test/test-getlist", [Admission::class, 'getOthersTestList']);

    Route::post("laboratory/create/request-item", [Laboratory::class, 'createRequestItemReagents']);
    Route::get("laboratory/test/items/get-itemtemplist", [Laboratory::class, 'getItemRequestTemp']);
    Route::post("laboratory/remove/request-item-temp", [Laboratory::class, 'removeRequestItemReagents']);
    Route::post("laboratory/confirm/request-item-temp", [Laboratory::class, 'confirmRequestItemReagents']);
    Route::get("laboratory/get/request-confirm", [Laboratory::class, 'getItemRequestConfirm']);

    Route::get("accounting/get/request-confirm-info-by-id", [Accounting::class, 'getItemRequestConfirmById']);
    Route::post("accounting/create/add-dr-product-to-temp", [Accounting::class, "saveProductDrtoTemp"]);
    Route::get("accounting/warehouse/account/deliver/list-producttotemp", [Accounting::class, "getProductToDrList"]);
    Route::post("accounting/warehouse/inventory/product/item-removeintemp", [Accounting::class, "removeItemFromTempList"]);
    Route::get('accounting/warehouse/new-invoice/get-productlist', [Accounting::class, 'getProducsListForNewInvoice']);
    Route::get("accounting/warehouse/inventory/product-details", [Accounting::class, "getProductDetails"]);
    Route::get('accounting/warehouse/new-invoice/get-desclist', [Accounting::class, 'getDescListForNewInvoice']);
    Route::get("accounting/warehouse/account/getProductBatchesDetails", [Accounting::class, "getProductBatchDetails"]);
    Route::get('accounting/warehouse/new-invoice/get-brandlist', [Accounting::class, 'getBrandListForNewInvoice']);
    Route::post("accounting/confirm/request-item-to-haptech", [Accounting::class, "confirmRequestToHaptech"]);

    Route::post("haptech/update/request-decision-by-id", [Warehouse::class, "updateHaptechRequestToDR"]);
    Route::post("haptech/confirm/request-haptech-to-dr", [Warehouse::class, "confirmHaptechRequestToDR"]);
    Route::get('hmis/get/item-for-approval', [HMIS::class, 'getForItemApprovalByID']);
    Route::post("hmis/update/item-approval-by-id", [HMIS::class, "updateItemApprovalByID"]);
    Route::post("accounting/warehouse/update/restore-disapprove-request", [Accounting::class, "restoreDisapprovedRequest"]);
    Route::post("pharmacy/warehouse/update-exclusive/inventory/product/process-tempproducts", [Warehouse::class, "updateExclusiveToDR"]);

    Route::get('warehouse/get/supplier/all-request', [Warehouse::class, 'getSupplierAllRequest']);
    Route::get('admission/patient/patient-contacttracing', [Admission::class, 'hisadmissionGetPatientContactTracing']);
    Route::get('cashier/get/composition/package', [Cashier::class, 'getCompositionPackage']);
    Route::post('cashier/update/send-out-test/by-id', [Cashier::class, 'updateToSendOutPatientTestById']);

    Route::post('cashier/get/filter-by-date/clinic-soa', [Cashier::class, 'getFilterByDateClinicSOA']);
    Route::post('accounting/package/update/edit-rate', [Accounting::class, 'updateAccountingPackage']);
    Route::post('accounting/update/other/edit-rate', [Accounting::class, 'updateAccountingOtherRate']);
    Route::post('accounting/update/psychology/edit-rate', [Accounting::class, 'updateAccountingPsychologyRate']);
    Route::post('cashier/update/bill-out-test/by-id', [Cashier::class, 'updateToBillOutPatientTestById']);
    Route::get('cashier/get/soa-temp/list', [Cashier::class, 'getAllSOATempList']);
    Route::post('cashier/remove/soa-temp/list', [Cashier::class, 'removeSOATempList']);
    Route::post('cashier/create/add-all-temp/to-soa-list', [Cashier::class, 'addAllToSOAList']);
    Route::get('cashier/get/soa/list', [Cashier::class, 'getAllSOAList']);
    Route::get('cashier/get/soa/details-by-id', [Cashier::class, 'getSOADetailsById']);
    Route::get('accounting/get/soa/details-by-mgmt-id', [Accounting::class, 'getAllSOAListByMgmtID']);
    Route::get('cashier/get/filter-by-date/sales', [Cashier::class, 'getAllSalesByFilterDate']);
    Route::get('cashier/get/filter-by-date/sales-expenses', [Cashier::class, 'getAllSalesExpenseByFilterDate']);
    Route::post('cashier/create/sales-expenses', [Cashier::class, 'createSalesExpensesByDate']);
    Route::post('cashier/archive/patient-transaction/by-id', [Cashier::class, 'archivePatientTransactionByID']);
    Route::post('cashier/delete/patient-queue/by-id', [Cashier::class, 'deletePatientQueueById']);
    Route::get('cashier/billing/records/list/not-group-receipt', [Cashier::class, 'getBillingRecordsNotGroup']);

    Route::get("accounting/get/getHMOList-by-main-mgmt", [Accounting::class, 'getHMOListByMainMgmt']);
    Route::post('accounting/create/new-hmo', [Accounting::class, 'createNewHMOAccounting']);
    Route::post('accounting/update/specific-hmo', [Accounting::class, 'updateSpecificHMOByMain']);

    // 04-06-2022
    Route::get('accounting/management/accredited/hmo-list', [Accounting::class, 'getHMOAccreditedList']);
    Route::post('accounting/update/hmo-status', [Accounting::class, 'updateHMOStatus']);
    Route::post('accounting/create/new-hmo/by-company-id', [Accounting::class, 'createNewHMOByCompanyId']);

    /** sph new routes **/

    Route::post('doctors/patient/sent-to-admitting', [Doctor::class, 'sentPatientToAdmitting']);
    Route::get('doctors/patient/check-in-admitting', [Doctor::class, 'checkPatientToAdmitting']);

    Route::get('doctors/patient/get-admitting-record', [Doctor::class, 'getPatientAdmittingRecord']);

    Route::get("admitting/sidebar/header-infomartion", [Admitting::class, 'getAdmittingInfo']);
    Route::get("admitting/rooms/get-roomlist", [Admitting::class, 'getHospitalRoooms']);
    Route::get("admitting/rooms/room-details", [Admitting::class, 'getHospitalRooomDetails']);
    Route::get("admitting/rooms/room-beds-details", [Admitting::class, 'getHospitalRooomBedsDetails']);

    Route::get("admitting/patients/for-admitlist", [Admitting::class, 'getPatientsForAdmit']);
    Route::get("admitting/rooms/get-room-number-list", [Admitting::class, 'getRoomNumberList']);
    Route::get("admitting/rooms/get-beds-bynumber", [Admitting::class, 'getRoomsBedsList']);

    Route::post("admitting/rooms/new-admitpatient", [Admitting::class, 'handleAdmitPatient']);
    Route::get("admitting/rooms/room-listbyid", [Admitting::class, 'getRoomsListByRoomId']);
    Route::get("admitting/rooms/room-bedbyid", [Admitting::class, 'getRoomsBedByRoomId']);

    Route::get("admitting/get-personal-info-by-id", [Admitting::class, 'AdmittingGetPersonalInfoById']);
    Route::post("admitting/information/personal-update", [Admitting::class, 'AdmittingUpdatePersonalInfo']);
    Route::post("admitting/update-password", [Admitting::class, 'AdmittingUpdatePassword']);
    Route::post("admitting/update-username", [Admitting::class, 'AdmittingUpdateUsername']);
    Route::post("admitting/information/personal-uploadnewprofile", [Admitting::class, 'AdmittingUploadProfile']);

    Route::get("nurse/room/get-admitted-patient", [Nurse::class, 'getAllAdmittedPatient']);

    // chart routes from csqmmh-ambu //
    Route::get("or/management/doctorlist-bymanagement", [OperatingRoom::class, 'getDoctorsListByManagement']);
    Route::post("or/charts/patient/patient-newfrontpage", [OperatingRoom::class, 'newChartFrontPage']);
    Route::get("or/charts/patient/patient-getfrontpage", [OperatingRoom::class, 'getChartFrontPage']);
    Route::post("or/charts/patient/patient-informationsheet", [OperatingRoom::class, 'newChartInformationSheet']);
    Route::get("or/charts/patient/patient-getinformationsheet", [OperatingRoom::class, 'getChartInformationSheet']);
    Route::post("or/charts/patient/patient-surgeryconsentsheet", [OperatingRoom::class, 'newSurgeryContent']);
    Route::get("or/charts/patient/patient-getsurgeryconsentsheet", [OperatingRoom::class, 'getSurgeryContent']);
    Route::post("or/charts/patient/patient-cardiopulmonary", [OperatingRoom::class, 'newCardioPulmononary']);
    Route::get("or/charts/patient/patient-getcardiopulmonary", [OperatingRoom::class, 'getCardioPulmononary']);
    Route::post("or/charts/patient/patient-chartbilling", [OperatingRoom::class, 'newChartBilling']);
    Route::get("or/charts/patient/patient-getchartbilling", [OperatingRoom::class, 'getChartBilling']);
    Route::post("or/charts/patient/patient-bedsidenotes", [OperatingRoom::class, 'newBedsideNotes']);
    Route::get("or/charts/patient/patient-getbedsidenotes", [OperatingRoom::class, 'getBedsideNotes']);
    Route::get("or/charts/patient/patient-getbedsidenotestable", [OperatingRoom::class, 'getBedsideNotesTable']);
    Route::post("or/charts/patient/patient-medicalabstract", [OperatingRoom::class, 'newMedicalAbstract']);
    Route::get("or/charts/patient/patient-getmedicalabstract", [OperatingRoom::class, 'getMedicalAbstract']);
    Route::post("or/charts/patient/patient-clinicalsummary", [OperatingRoom::class, 'newClinicalSummary']);
    Route::get("or/charts/patient/patient-getclinicalsummary", [OperatingRoom::class, 'getClinicalSummary']);

    //12082021
    Route::get("or/get/chart/laboratory", [OperatingRoom::class, 'getChartLaboratory']);
    Route::post("or/update/chart/laboratory", [OperatingRoom::class, 'updateChartLaboratory']);
    Route::get("or/get/chart/perioperative", [OperatingRoom::class, 'getChartPeriOperative']);
    Route::post("or/update/chart/perioperative", [OperatingRoom::class, 'updateChartPeriOperative']);
    Route::get("or/get/chart/postoperative", [OperatingRoom::class, 'getChartPostOperative']);
    Route::post("or/update/chart/postoperative", [OperatingRoom::class, 'updateChartPostOperative']);
    Route::get("or/get/chart/surgicalmember", [OperatingRoom::class, 'getChartSurgicalMember']);
    Route::post("or/update/chart/surgicalmember", [OperatingRoom::class, 'updateChartSurgicalMember']);
    Route::get("or/get/chart/doctorsconsultation", [OperatingRoom::class, 'getChartDoctorConsultation']);
    Route::get("or/get/chart/doctorsconsultationtable", [OperatingRoom::class, 'getChartDoctorConsultationTable']);
    Route::post("or/update/chart/doctorsconsultation", [OperatingRoom::class, 'updateChartDoctorConsultation']);
    Route::post("or/remove/chart/doctorsconsultation", [OperatingRoom::class, 'removeChartDoctorConsultation']);
    Route::get("or/get/chart/operativerecord", [OperatingRoom::class, 'getChartOperativeRecord']);
    Route::post("or/update/chart/operativerecord", [OperatingRoom::class, 'updateChartOperativeRecord']);
    Route::get("or/get/chart/doctorsorder", [OperatingRoom::class, 'getChartDoctorsOrder']);
    Route::get("or/get/chart/doctorsordertable", [OperatingRoom::class, 'getChartDoctorsOrderTable']);
    Route::post("or/update/chart/doctorsorder", [OperatingRoom::class, 'updateChartDoctorsOrder']);
    Route::post("or/remove/chart/doctorsorder", [OperatingRoom::class, 'removeChartDoctorsOrder']);
    Route::get("or/get/chart/postanesthesiacareunit", [OperatingRoom::class, 'getChartPostAnesthesiaCareUnit']);
    Route::get("or/get/chart/postanesthesiacareunittable", [OperatingRoom::class, 'getChartPostAnesthesiaCareUnitTable']);
    Route::post("or/update/chart/postanesthesiacareunit", [OperatingRoom::class, 'updateChartPostAnesthesiaCareUnit']);
    Route::post("or/remove/chart/postanesthesiacareunit", [OperatingRoom::class, 'removeChartPostAnesthesiaCareUnit']);
    Route::get("or/get/chart/jpdrainmonitoring", [OperatingRoom::class, 'getChartJPDrainMonitoring']);
    Route::get("or/get/chart/jpdrainmonitoringtable", [OperatingRoom::class, 'getChartJPDrainMonitoringTable']);
    Route::post("or/update/chart/jpdrainmonitoring", [OperatingRoom::class, 'updateChartJPDrainMonitoring']);
    Route::post("or/remove/chart/jpdrainmonitoring", [OperatingRoom::class, 'removeChartJPDrainMonitoring']);
    Route::get("or/get/chart/attendancesheet", [OperatingRoom::class, 'getChartAttendanceSheet']);
    Route::post("or/update/chart/attendancesheet", [OperatingRoom::class, 'updateChartAttendanceSheet']);

    Route::post("or/charts/patient/patient-dischargeins", [OperatingRoom::class, 'newDischargedIns']);
    Route::get("or/charts/patient/patient-getdischargeins", [OperatingRoom::class, 'getDischargedIns']);
    Route::post("or/charts/patient/patient-addressograph", [OperatingRoom::class, 'newAddressoGraph']);
    Route::get("or/charts/patient/patient-getaddressograph", [OperatingRoom::class, 'getAddressoGraph']);
    Route::post("or/charts/patient/patient-senttodocu", [OperatingRoom::class, 'sentPatientToDocu']);

    Route::get("or/get/chart/covid19checklist", [OperatingRoom::class, 'getChartCovid19Checklist']);
    Route::post('or/update/chart/covid19checklist', [OperatingRoom::class, 'createCovid19Checklist']);
    Route::post("or/update/chart/tempandpulsechart", [OperatingRoom::class, 'updateChartTempAndPulse']);

    Route::get("or/get/charts/patient/patient-medicalsheet-stat", [OperatingRoom::class, 'getMedicalSheetStatChart']);
    Route::post("or/create/charts/patient/patient-medicalsheet-stat", [OperatingRoom::class, 'createMedicalSheetStat']);
    Route::get("or/get/charts/patient/patient-medicalsheet-prn", [OperatingRoom::class, 'getMedicalSheetPRNChart']);
    Route::post("or/create/charts/patient/patient-medicalsheet-prn", [OperatingRoom::class, 'createMedicalSheetPRN']);

    //1/13/2022
    Route::post("or/edit/chart/doctorsconsultation", [OperatingRoom::class, 'editChartDoctorConsultation']);
    Route::post("or/edit/chart/doctorsorder", [OperatingRoom::class, 'editChartDoctorsOrder']);
    Route::post("or/edit/chart/postanesthesiacareunit", [OperatingRoom::class, 'editChartPostAnesthesiaCareUnit']);
    Route::post("or/edit/chart/jpdrainmonitoring", [OperatingRoom::class, 'editChartJPDrainMonitoring']);
    Route::post("or/remove/chart/bedsidenote", [OperatingRoom::class, 'removeChartBedSideNotes']);
    Route::post("or/edit/chart/bedsidenote", [OperatingRoom::class, 'editChartBedSideNotes']);
    Route::post("or/create/patient/patient-bedsidenotes", [OperatingRoom::class, 'createChartBedsideNotes']);
    // 1/14/2022
    Route::post('prescription/nurse/local/prescriptionsaveallUnsave', [Prescription::class, 'prescriptionSaveallUnsaveNurse']);
    Route::post('or/create/chart/doctorsconsultationtable', [OperatingRoom::class, 'createDoctorsConsultationTable']);
    Route::post('or/create/chart/doctorsordertable', [OperatingRoom::class, 'createDoctorsOrderTable']);
    Route::post('or/create/chart/postanesthesiacareunittable', [OperatingRoom::class, 'createPostAnethesiaCareUnitTable']);
    Route::post('or/create/chart/jpdrainmonitoringtable', [OperatingRoom::class, 'createJPDrainMonitoringTable']);
    Route::post('or/create/chart/bedsidenotestable', [OperatingRoom::class, 'createBedSideNotesTable']);

    //1/20/2022
    Route::get("or/get/charts/patient/patient-medicalsheet", [OperatingRoom::class, 'getMedicalSheetChart']);
    Route::post("or/create/charts/patient/patient-medicalsheet", [OperatingRoom::class, 'createMedicalSheet']);

    Route::get("doctor/get/tracenumber-list/patient-id", [OperatingRoom::class, 'getAllTraceNoList']);
    Route::get("doctor/get/case-record/patient-id", [OperatingRoom::class, 'getAllCaseRecordList']);
    Route::get("doctor/get/case-record/patient-id/case-id", [OperatingRoom::class, 'getAllCaseRecordListDetails']);

    Route::post("admitting/nurse/patient/sent-todischarge", [Nurse::class, 'sentPatientToDischarge']);
    Route::post("admitting/nurse/patient/bill-addto-admissionbilling", [Cashier::class, 'addBillToAdmittedPatients']);

    Route::get("doctor/patient/admitted-list", [Doctor::class, 'getAdmittedPatientAssignByMd']);

    Route::post("nurse/admitted/patient/sent-billing-forbillout", [Nurse::class, 'sentPatientToBillout']);
    Route::get("billing/admitted/patient/get-patient-for-billout", [Billing::class, 'getAdmittedPatientForBilling']);
    Route::get("billing/admitted/patient/get-patient-billslist", [Billing::class, 'getPatientsAdmittingBills']);

    Route::post("billing/admitted/patient/add-philhealth-tobill", [Billing::class, 'admittedPatientProcessBillingAddPhilhealth']);

    Route::post("billing/admitted/patient/sent-tocashier", [Billing::class, 'admittedPatientSentToCashier']);

    Route::get("cashier/admitted/patient/for-dischage", [Cashier::class, 'getAdmittedPatientForDischarge']);
    Route::post("cashier/admitted/patient/dischaged-patient", [Cashier::class, 'dischargedPatientFromAdmitting']);

    Route::get("billing/discharged/patient/get-patientlist-groupby-patientid", [Billing::class, 'dischargedPatientListGroupByPatientId']);
    Route::get("billing/discharged/patient/get-patientlist-tracenumber", [Billing::class, 'dischargedPatientListByTracenumber']);
    Route::get("billing/discharged/patient/bill-records", [Billing::class, 'dischargedPatientBillRecords']);

    Route::get("accounting/room/get-roomlist", [Accounting::class, 'getRoomLists']);
    Route::get("accounting/room/list/room-numberlist", [Accounting::class, 'getRoomListByRoomId']);
    Route::get("accounting/room/list/room-bedslist", [Accounting::class, 'getListOfBedsByRoom']);

    Route::post("accounting/room/new-room", [Accounting::class, 'newRoom']);
    Route::post("accounting/room/bed/new-bed-in-room", [Accounting::class, 'newBedInRoom']);
    Route::post("accounting/room/bed/add-room-tolist", [Accounting::class, 'addRoomToList']);

    Route::get("cashier/discharged/patient/get-patientlist-groupby-patientid", [Billing::class, 'dischargedPatientListGroupByPatientId']);
    Route::post("cashier/discharged/patient/add-charge-slip", [Cashier::class, 'addDischargeSlipToPatient']);
    Route::get("cashier/discharged/patient/get-charge-slip", [Cashier::class, 'getDischargeSlipToPatient']);
    Route::get("cashier/discharged/patient/get-patient-info", [Cashier::class, 'getDischargedSlipPatientInfo']);
    Route::get("cashier/report/philhealth-list", [Cashier::class, 'getPhilhealthRecord']);

    Route::post("doctor/patient/for-operation", [Doctor::class, 'sentPatientForOperation']);
    Route::get("doctor/patient/for-operation-check", [Doctor::class, 'checkPatientForOperation']);

    Route::get("nurse/operating-room/get-patient-foroperation", [Nurse::class, 'getPatientListForOperation']);

    Route::post("nurse/operating-room/set-patient-topacu-room", [Nurse::class, 'setOrPatientToPacuNurse']);

    Route::post("nurse/operating-room/set-patient-formonitoring", [Nurse::class, 'setPatientForMonitoring']);

    Route::get("nurse/patient/admitting/get-admitting-details", [Nurse::class, 'getAdmittedPatientDetails']);
    Route::get("doctor/appointment/get-all-appointment", [Doctor::class, 'getAppointmentRecordsAll']);
});
