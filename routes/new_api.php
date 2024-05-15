<?php

use App\Http\Controllers\AnesthesiaOrderController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\CensusController;
use App\Http\Controllers\Clinic\ClinicController;
use App\Http\Controllers\Clinic\ClinicPatientController;
use App\Http\Controllers\Clinic\ClinicPersonnelController;
use App\Http\Controllers\Clinic\ClinicReferralController;
use App\Http\Controllers\Clinic\ConsignmentController;
use App\Http\Controllers\Clinic\DashboardController;
use App\Http\Controllers\Clinic\Doctor\DiagnosisController;
use App\Http\Controllers\Clinic\Doctor\DoctorController;
use App\Http\Controllers\Clinic\Doctor\DoctorPrescriptionController;
use App\Http\Controllers\Clinic\Doctor\MedicationController;
use App\Http\Controllers\Clinic\Doctor\PatientAcceptanceController;
use App\Http\Controllers\Clinic\Doctor\PrescriptionsController;
use App\Http\Controllers\Clinic\Patient\AppointmentController;
use App\Http\Controllers\Clinic\Nurse\AppointmentQueueController;
use App\Http\Controllers\Clinic\PatientChartsController;
use App\Http\Controllers\Clinic\PatientVitalController;
use App\Http\Controllers\Clinic\PersonnelController;
use App\Http\Controllers\Clinic\QueueController;
use App\Http\Controllers\Clinic\ReceivingPatientController;
use App\Http\Controllers\Cloud\PatientCaseController;
use App\Http\Controllers\Household\HouseholdMembersController;
use App\Http\Controllers\Household\HouseHoldsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Patient\AnalyticsMapController;
use App\Http\Controllers\Patient\ConsultationController;
use App\Http\Controllers\Patient\DiseaseController;
use App\Http\Controllers\Patient\EditRemarksController;
use App\Http\Controllers\Patient\KobotoolsController;
use App\Http\Controllers\Patient\NotesController;
use App\Http\Controllers\MedicalCertificateController;
use App\Http\Controllers\Patient\PatientProfileController;
use App\Http\Controllers\Patient\PatientsController;
use App\Http\Controllers\Patient\PhilHealthController;

use App\Http\Controllers\PHO\ItemsController;
use App\Http\Controllers\PHO\PurokController;
use App\Http\Controllers\PHO\UsersController;
use App\Http\Controllers\PublicData\DiseasesController;
use App\Http\Controllers\TeleMedicine\ScheduleController;
use App\Http\Controllers\User\PasswordController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\UsernameController;
use App\Http\Controllers\V2\Patient\DietController;
use App\Http\Controllers\V2\Patient\PatientAllergyController;
use App\Http\Controllers\V2\Patient\PatientPrescriptionController;
use App\Http\Controllers\V2\Patient\VitalChartController;
use App\Http\Controllers\V2\Patient\VitalController;
use App\Http\Controllers\V2\Patient\TreatmentPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clinic\Patient\PatientProfileController as PatientProfileUpdateController;
use App\Http\Controllers\Clinic\Patient\TBSymptomsController;
use App\Http\Controllers\Clinic\Patient\TbVitalsController;
use App\Http\Controllers\HealthUnitController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\OperatingRoomChartController;
use App\Http\Controllers\PhilhealthMemberRegistrationController;
use App\Http\Controllers\V2\CISDashboardController;
use App\Http\Controllers\V2\Program\TuberculosisProgramController;

use App\Http\Controllers\V2\Doctor\LaboratoryOrderController;
use App\Http\Controllers\V2\Laboratory\LaboratoryTestController;
use App\Http\Controllers\V2\LaboratoryResultController;
use App\Http\Controllers\V2\ImagingController;
use App\Models\ClinicReferral;
use App\Models\ItemInventory;
use App\Http\Controllers\Clinic\Patient\LaboratoryItemUsageController;
use App\Http\Controllers\Clinic\Patient\ReferToRHUController;
use App\Http\Controllers\HealthUnitPersonnelController;
use App\Http\Controllers\InventoryCsrController;
use App\Http\Controllers\InventoryCSROrderController;
use App\Http\Controllers\InventoryPharmacyController;
use App\Http\Controllers\InventoryPharmacyOrderController;
use App\Http\Controllers\InventoryStocksController;
use App\Http\Controllers\OperatingRoomController;
use App\Http\Controllers\OperationProcedureController;
use App\Http\Controllers\PHOPMRFQueueController;
use App\Http\Controllers\SpecialtiesController;
use App\Models\OperationProcedure;

Route::get('jsondata', [KobotoolsController::class, 'jsondata']);
Route::post('kobotools', [KobotoolsController::class, 'store']);
Route::post('kobotools/logs', [KobotoolsController::class, 'logData']);

Route::get('v1/locations', [LocationController::class, 'index']);
Route::get('v1/diseases', [DiseasesController::class, 'index']);
Route::get('v1/patient-details/{id}', [PatientsController::class, 'show']);

Route::post('send-case-to-cloud', [PatientCaseController::class, 'store']);
Route::get('get-case-by-appointment', [PatientCaseController::class, 'getByDoctorAppointment']);
Route::get('get-rhu-referrals', [PatientCaseController::class, 'getRhuReferrals']);
Route::get('get-case/{id}', [PatientCaseController::class, 'show']);
Route::patch('update-vitals/{id}', [PatientCaseController::class, 'updateVitals']);
Route::patch('update-case/{id}', [PatientCaseController::class, 'updateCase']);
Route::get('get-case-list', [PatientCaseController::class, 'getCaseList']);
Route::get('get-pmrf-done-list', [\App\Http\Controllers\PMRF\VerifyPatientController::class, 'getDoneList']);


Route::middleware(['auth:api'])->prefix('v1')->group(function () {



    Route::get('pmrf-done-list', [\App\Http\Controllers\PMRF\PatientController::class, 'index']);
    Route::patch('pmrf-patient-sync', [\App\Http\Controllers\PMRF\PatientController::class, 'sync']);


    Route::get('service-done-sync', [PatientCaseController::class, 'getServiceDone']);
    Route::patch('service-done-sync', [PatientCaseController::class, 'getServiceDoneSync']);

    Route::patch('update-vitals/{id}', [PatientCaseController::class, 'updateCloudVital']);
    Route::patch('accept-doctor-case/{id}', [PatientCaseController::class, 'acceptDoctorCase']);
    Route::patch('verify-patient-data/{id}', [\App\Http\Controllers\PHO\Cloud\UnSyncedPatientsController::class, 'verifyPatient']);
    Route::patch('cloud-to-local-patient', [\App\Http\Controllers\PHO\Cloud\UnSyncedPatientsController::class, 'update']);
    Route::get('un-synced-patients-count', [\App\Http\Controllers\PHO\Cloud\UnSyncedPatientsController::class, 'count']);
    Route::get('un-synced-patients', [\App\Http\Controllers\PHO\Cloud\UnSyncedPatientsController::class, 'unsyncPatients']);

    Route::get('doctors', [DoctorController::class, 'index']);
    Route::get('doctors-health-unit', [DoctorController::class, 'getDoctorByHealthUnit']);

    Route::get('patients', [PatientsController::class, 'index']);
    Route::patch('patient-philhealth/{id}', [PhilHealthController::class, 'update']);
    Route::patch('patient-consultation/{id}', [ConsultationController::class, 'update']);
    Route::get('base64-patient/{patient}', [PatientsController::class, 'imageToBase64']);
    Route::patch('patient-profile/{id}', [PatientProfileController::class, 'update']);
    Route::patch('patient-profile-update/{id}', [PatientProfileUpdateController::class, 'update']);
    Route::get('patients/{id}', [PatientsController::class, 'show']);
    Route::get('households', [HouseHoldsController::class, 'index']);
    Route::post('households', [HouseHoldsController::class, 'store']);
    Route::get('households/{id}', [HouseHoldsController::class, 'show']);
    Route::patch('households/{id}', [HouseHoldsController::class, 'update']);
    Route::patch('household-members/{id}', [HouseholdMembersController::class, 'update']);
    Route::patch('diseases/{id}', [DiseasesController::class, 'update']);
    Route::post('disease', [DiseaseController::class, 'store']);

    Route::get('purok-by-barangay', [PurokController::class, 'index']);


    Route::prefix('telemedicine')->group(function () {

        Route::get('all-schedules', [ScheduleController::class, 'AllSchedules']);
        Route::get('schedules', [ScheduleController::class, 'index']);
        Route::get('getPatientSchedules', [ScheduleController::class, 'patientSchedules']);
        Route::get('monthly-availability-schedules', [ScheduleController::class, 'getAvailableSlotsPerMonth']);
        Route::post('booked', [ScheduleController::class, 'store']);
        Route::get('doctor-schedules', [ScheduleController::class, 'doctorSchedules']);
        Route::get('doctor-schedules/{id}', [ScheduleController::class, 'showDoctorSchedules']);
    });



    Route::prefix('profile')->group(function () {
        Route::get('/me', [ProfileController::class, 'me']);
        Route::get('/rhu-profile-data', [ProfileController::class, 'profileData']);

        Route::get('/rhu-patient-queue', [ReferToRHUController::class, 'getRhuQueue']);

        Route::get('/sph-patient-queue', [ReferToRHUController::class, 'getSPHQueue']);

        Route::get('/show', [ProfileController::class, 'show']);
        Route::patch('/', [ProfileController::class, 'update']);
        Route::patch('/username', [ProfileController::class, 'updateUsername']);
        Route::patch('/password', [PasswordController::class, 'update']);
    });

    Route::prefix('pho')->group(function () {
        Route::get('users', [UsersController::class, 'index']);
        Route::post('users', [UsersController::class, 'store']);
        Route::patch('users/{id}', [UsersController::class, 'update']);
        Route::post('patients', [PatientsController::class, 'store']);
        Route::patch('patients/{id}', [PatientsController::class, 'update']);
        Route::delete('patients/delete/{id}', [PatientsController::class, 'destroy']);
        Route::patch('update-patient/{id}', [PatientsController::class, 'updateFromApp']);
        Route::get('patient-maps', [PatientsController::class, 'mapping']);
        Route::post('patient-remarks', [EditRemarksController::class, 'store']);
        Route::get('analytics-map', [AnalyticsMapController::class, 'index']);
    });
    Route::prefix('health-unit-personnels')->group(function () {

        Route::get('/list', [HealthUnitPersonnelController::class, 'index']);
        Route::post('/store', [HealthUnitPersonnelController::class, 'store']);
        Route::patch('/update/{id}', [HealthUnitPersonnelController::class, 'update']);
        Route::patch('/activate/{id}', [HealthUnitPersonnelController::class, 'activate']);
        Route::patch('/deactivate/{id}', [HealthUnitPersonnelController::class, 'deactivate']);
        Route::patch('/assignment/{id}', [HealthUnitPersonnelController::class, 'assignment']);
    });

    Route::prefix('management')->group(function () {
        Route::apiResource('items', ItemsController::class)->parameter('items', 'item');
        Route::apiResource('clinic', ClinicController::class);
        Route::get('getClinics/{type}', [ClinicController::class, 'getClinics']);
        Route::get('capable-clinics', [ClinicController::class, 'capableClinics']);
        Route::apiResource('supplier', \App\Http\Controllers\PHO\SupplierController::class);
        Route::apiResource('personnel', PersonnelController::class);
        Route::get('clinic-personnel', [ClinicPersonnelController::class, 'index']);
        Route::get('clinic-personnel/{id}', [ClinicPersonnelController::class, 'show']);
        Route::patch('clinic-personnel/{id}', [ClinicPersonnelController::class, 'update']);
    });


    Route::get('my-clinic', [ClinicController::class, 'myClinic']);

    Route::prefix('clinic-receiving')->group((function () {
        Route::post('/', [ReceivingPatientController::class, 'store']);
        Route::get('patients', [ReceivingPatientController::class, 'index']);
    }));

    Route::prefix('clinic')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index']);


        Route::get('doctors', [AppointmentController::class, 'getAllDoctors']);
        Route::get('rhu-doctors', [AppointmentController::class, 'getRHUDoctors']);
        Route::get('doctors-by-location', [AppointmentController::class, 'getDoctorsByLocation']);

        Route::get('tb-doctors', [AppointmentController::class, 'tbDoctors']);
        Route::get('sph-tb-doctors', [AppointmentController::class, 'getSphTbDoctors']);

        Route::get('appointments', [AppointmentController::class, 'index']);
        Route::get('appointments/{id}', [AppointmentController::class, 'getAppointments']);
        Route::get('get-appointment/{id}', [ReferToRHUController::class, 'getAppointmentData']);
        Route::get('doctor-patient-referrals', [AppointmentController::class, 'getDoctorPatientReferrals']);
        Route::get('sph-doctor-patient-referrals', [AppointmentController::class, 'getSphDoctorPatientReferrals']);


        Route::get('bhw-pending-service', [ReferToRHUController::class, 'getBHWPendingService']);

        Route::get('bhw-pending-for-medicine-release', [ReferToRHUController::class, 'getBhwPendingMedsRelease']);

        Route::get('rhu-pending-for-medicine-release', [ReferToRHUController::class, 'getRHUPendingMedsRelease']);

        Route::get('sph-pending-for-medicine-release', [ReferToRHUController::class, 'getSPHPendingMedsRelease']);



        Route::get('doctor-pending-for-confirmation', [ReferToRHUController::class, 'getMyPendingForConfirmation']);

        Route::get('pending-for-doctor-prescription',  [ReferToRHUController::class, 'getPendingForDoctorPrescription']);

        Route::patch('doctor-accept-patient/{id}', [ReferToRHUController::class, 'doctorAcceptPatient']);


        Route::get('doctor-for-result-reading', [ReferToRHUController::class, 'getForResultReading']);



        Route::get('nurse-pending-for-acceptance', [ReferToRHUController::class, 'getNursePendingForAcceptance']);


        Route::get('doctor-pending-for-consultation', [ReferToRHUController::class, 'getMyPendingForConsultation']);
        Route::get('doctor-in-service-consultation', [ReferToRHUController::class, 'getInServiceConsultation']);


        Route::get('doctor-pending-for-read-lab-result', [ReferToRHUController::class, 'getPendingForReadLabResult']);

        Route::get('pharmacy-pending-signal-for-release', [ReferToRHUController::class, 'getPharmaPendingSignalRelease']);
        Route::get('pharmacy-pending-medicine-release', [ReferToRHUController::class, 'getPharmaPendingMedRelease']);



        Route::get('xray-pending-result', [ReferToRHUController::class, 'getXrayPending']);
        Route::get('lab-order-pending-result', [ReferToRHUController::class, 'getLabOrderPending']);

        Route::get('pending-cashier', [ReferToRHUController::class, 'getPendingCashier']);
        Route::get('pending-billing', [ReferToRHUController::class, 'getPendingBilling']);
        Route::patch('send-from-cashier-to-nurse-for-release/{id}', [ReferToRHUController::class, 'sendFromCashierToNurseForRelease']);


        Route::get('get-pending-cashier/{id}', [AppointmentController::class, 'getPatientPendingCashier']);
        Route::get('sph-patient-referrals', [AppointmentController::class, 'getSphPatientReferrals']);
        Route::patch('approve-rhu-referrals/{id}', [ReferToRHUController::class, 'approveRhuReferrals']);

        Route::post('appointments', [AppointmentController::class, 'store']);
        Route::patch('tb-symptoms/{id}', [TBSymptomsController::class, 'update']);


        Route::patch('tb-lab-order/{id}', [AppointmentController::class, 'labOrder']);
        Route::patch('tb-xray-order/{id}', [AppointmentController::class, 'xRayOrder']);

        Route::patch('tb-xray-result/{id}', [ReferToRHUController::class, 'uploadXray']);
        Route::patch('tb-lab-result/{id}', [ReferToRHUController::class, 'updateLabTest']);

        Route::get('done-tb-patients', [AppointmentController::class, 'doneTbPatients']);
        //		Route::get('get-monthly-appointments/{clinic_id}', [AppointmentController::class, 'monthlyAppointments']);
        //		Route::get('get-daily-appointments/{clinic_id}', [AppointmentController::class, 'dailyAppointments']);
        //		Route::get('get-doctor-appointments/{doctor_id}', [AppointmentController::class, 'doctorAppointments']);
        //
        //		Route::patch('appointments-to-queue/{id}', [AppointmentQueueController::class, 'update']);
        Route::post('appointments', [AppointmentController::class, 'store']);
        Route::get('rhu-appointments', [AppointmentController::class, 'getRhuAppointments']);


        Route::patch('tb-approve-release-medication/{id}', [AppointmentController::class, 'tbApproveMedsForRelease']);

        Route::patch('patient-tb-status/{id}', [AppointmentController::class, 'update']);
        Route::patch('tb-symptoms/{id}', [TBSymptomsController::class, 'update']);
        Route::patch('tb-vitals/{id}', [TbVitalsController::class, 'update']);
        Route::patch('tb-prescribe/{id}', [LaboratoryItemUsageController::class, 'prescribe']);
        Route::patch('tb-lab-service/{id}', [LaboratoryItemUsageController::class, 'update']);
        Route::patch('tb-negative/{id}', [LaboratoryItemUsageController::class, 'setTbNegative']);
        Route::patch('tb-positive/{id}', [LaboratoryItemUsageController::class, 'setTbPositive']);
        Route::patch('signal-tb-positive/{id}', [LaboratoryItemUsageController::class, 'signalTbPositive']);
        Route::post('tb-refer-to-rhu', [ReferToRHUController::class, 'referToRhu']);
        Route::patch('tb-assign-to-doctor/{id}', [ReferToRHUController::class, 'assignToDoctor']);
        Route::patch('tb-refer-to-rhu/{id}', [ReferToRHUController::class, 'update']);
        Route::patch('tb-for-doctor-confirmation/{id}', [ReferToRHUController::class, 'referToDoctorForConfirmation']);

        Route::patch('tb-accept-patient/{id}', [ReferToRHUController::class, 'acceptPatient']);
        Route::patch('accept-patient/{id}', [ReferToRHUController::class, 'acceptPatient']);





        Route::patch('tb-refer-to-sph/{id}', [ReferToRHUController::class, 'referToSph']);
        Route::patch('refer-to-sph-doctor/{id}', [ReferToRHUController::class, 'referToSphDoctor']);


        Route::patch('tb-released-medicine/{id}', [ReferToRHUController::class, 'medicine_release']);
        Route::patch('tb-satisfaction-rate/{id}', [ReferToRHUController::class, 'satisfaction']);

        Route::patch('tb-approve/{id}', [ReferToRHUController::class, 'approve']);

        Route::patch('tb-selfie/{id}', [ReferToRHUController::class, 'selfie']);

        Route::patch('send-to-cashier/{id}', [ReferToRHUController::class, 'sendToCashier']);
        Route::patch('mark-as-paid/{id}', [ReferToRHUController::class, 'markAsPaid']);


        //		Route::get('get-monthly-appointments/{clinic_id}', [AppointmentController::class, 'monthlyAppointments']);
        //		Route::get('get-daily-appointments/{clinic_id}', [AppointmentController::class, 'dailyAppointments']);
        //		Route::get('get-doctor-appointments/{doctor_id}', [AppointmentController::class, 'doctorAppointments']);
        //
        //		Route::patch('appointments-to-queue/{id}', [AppointmentQueueController::class, 'update']);



        Route::get('patients', [ClinicPatientController::class, 'index']);
        Route::get('patients/{id}', [ClinicPatientController::class, 'show']);

        Route::patch('patient-vital/{id}', [PatientVitalController::class, 'update']);
        Route::get('patient-charts/{id}', [PatientChartsController::class, 'show']);

        Route::patch('patient-serve/{id}', [PatientAcceptanceController::class, 'update']);
        Route::delete('patient-done-serve/{id}', [PatientAcceptanceController::class, 'destroy']);

        Route::apiResource('patient-prescription', DoctorPrescriptionController::class);
        Route::apiResource('patient-diagnosis', DiagnosisController::class);
        Route::apiResource('patient-medication', MedicationController::class);

        Route::apiResource('clinic-referral', ClinicReferralController::class);
        Route::get('my-referrals/{clinic_id}', [ClinicReferralController::class, 'myReferrals']);
        Route::get('received-referrals/{clinic_id}', [ClinicReferralController::class, 'receivedReferrals']);
        Route::get('referral-list', [ClinicReferralController::class, 'list']);
        Route::patch('clinic-referral/serve/{id}', [ClinicReferralController::class, 'serve']);
        Route::patch('clinic-referral/done/{id}', [ClinicReferralController::class, 'done']);
        Route::patch('clinic-referral/cancel/{id}', [ClinicReferralController::class, 'cancel']);
    });

    Route::get('get-cashier-pending', [ReferToRHUController::class, 'getCashierPending']);

    Route::get('pmrf-queue', [PHOPMRFQueueController::class, 'getQueue']);
    Route::get('pmrf-queue/{id}', [PHOPMRFQueueController::class, 'getPatientPMRF']);
    Route::patch('save-patient-pmrf/{id}', [PHOPMRFQueueController::class, 'savePatientPMRF']);
    Route::patch('upload-pmrf-signature/{id}', [PHOPMRFQueueController::class, 'uploadSignature']);



    Route::prefix('notes')->group(function () {
        Route::get('/{id}', [NotesController::class, 'index']);
        Route::get('/show/{notes}', [NotesController::class, 'show']);
        Route::post('/store/{id}', [NotesController::class, 'store']);
        Route::patch('/update/{notes}', [NotesController::class, 'update']);
        Route::delete('/delete/{notes}', [NotesController::class, 'destroy']);
    });
    Route::prefix('medical-certificate')->group(function () {
        Route::get('/{id}', [MedicalCertificateController::class, 'index']);
        Route::get('/show/{certificate}', [MedicalCertificateController::class, 'show']);
        Route::post('/store/{id}', [MedicalCertificateController::class, 'store']);
        Route::patch('/update/{certificate}', [MedicalCertificateController::class, 'update']);
        Route::delete('/delete/{certificate}', [MedicalCertificateController::class, 'destroy']);
    });

    Route::prefix('consignment')->group(function () {
        Route::get('/list', [ConsignmentController::class, 'index']);
        Route::get('/show/{id}', [ConsignmentController::class, 'show']);
        Route::patch('/approve/{id}', [ConsignmentController::class, 'approve']);
        Route::patch('/check/{id}', [ConsignmentController::class, 'checkOrder']);
        Route::patch('/process-order/{id}', [ConsignmentController::class, 'processOrder']);
        Route::patch('/deliver-order/{id}', [ConsignmentController::class, 'deliverOrder']);
        Route::patch('/receive-order/{id}', [ConsignmentController::class, 'receiveOrder']);
        Route::post('/store', [ConsignmentController::class, 'store']);
    });

    Route::prefix('item-inventory')->group(function () {

        Route::get('/get-items', [InventoryController::class, 'getItems']);
        Route::get('/', [InventoryController::class, 'index']);
        Route::patch('/stock-in/{id}', [InventoryController::class, 'addStock']);
        Route::patch('/stock-out/{id}', [InventoryController::class, 'removeStock']);
    });
    Route::prefix('health-unit')->group(function () {
        Route::get('/list', [HealthUnitController::class, 'index']);
        Route::post('/store', [HealthUnitController::class, 'store']);
        Route::patch('/update/{id}', [HealthUnitController::class, 'update']);
        Route::patch('/activate/{id}', [HealthUnitController::class, 'activate']);
        Route::patch('/deactivate/{id}', [HealthUnitController::class, 'deactivate']);
        Route::get('/get-user-health-unit', [HealthUnitController::class, 'getUserHealthUnit']);
    });

    Route::prefix('specialties')->group(function () {
        Route::get('/list', [SpecialtiesController::class, 'index']);
        Route::post('/store', [SpecialtiesController::class, 'store']);
        Route::patch('/update/{id}', [SpecialtiesController::class, 'update']);
        Route::patch('/deactivate/{id}', [SpecialtiesController::class, 'deactivate']);
        Route::patch('/activate/{id}', [SpecialtiesController::class, 'activate']);
    });

    Route::prefix('patient-prescription')->group(function () {
        Route::get('/{id}', [PatientPrescriptionController::class, 'index']);
        Route::get('/show/{patientPrescription}', [PatientPrescriptionController::class, 'show']);
        Route::post('/store', [PatientPrescriptionController::class, 'store']);
        Route::patch('/update/{patientPrescription}', [PatientPrescriptionController::class, 'update']);
        Route::delete('/delete/{patientPrescription}', [PatientPrescriptionController::class, 'destroy']);
    });


    Route::prefix('patient-vitals')->group(function () {
        Route::get('/{id}', [VitalController::class, 'index']);
        Route::get('/show/{vitals}', [VitalController::class, 'show']);
        Route::post('/store', [VitalController::class, 'store']);
        Route::patch('/update/{vitals}', [VitalController::class, 'update']);
        Route::delete('/delete/{vitals}', [VitalController::class, 'destroy']);
        Route::get('/vital-signs/{id}', [VitalController::class, 'vitalSigns']);
    });

    Route::prefix('patient/vital-charts')->group(function () {
        Route::get('/temperatures/{id}', [VitalChartController::class, 'getTemperatures']);
        Route::get('/blood-pressures/{id}', [VitalChartController::class, 'getBloodPressures']);
        Route::get('/glucoses/{id}', [VitalChartController::class, 'getGlucoses']);
        Route::get('/cholesterols/{id}', [VitalChartController::class, 'getCholesterols']);
        Route::get('/pulses/{id}', [VitalChartController::class, 'getPulses']);
        Route::get('/respiratory-rates/{id}', [VitalChartController::class, 'getRespiratoryRates']);
        Route::get('/uric-acids/{id}', [VitalChartController::class, 'getUricAcids']);
        Route::get('/heights/{id}', [VitalChartController::class, 'getHeights']);
        Route::get('/weights/{id}', [VitalChartController::class, 'getWeights']);
    });

    Route::prefix('census')->group(function () {
        Route::get('/census-summary', [CensusController::class, 'censusSummary']);
        Route::get('/municipality-population', [CensusController::class, 'municipalityPopulation']);
        Route::get('/barangay-population', [CensusController::class, 'barangayPopulation']);
        Route::get('/purok-population', [CensusController::class, 'purokPopulation']);
    });

    Route::prefix('patient-allergies')->group(function () {
        Route::get('/{id}', [PatientAllergyController::class, 'index']);
        Route::get('/show/{patientAllergy}', [PatientAllergyController::class, 'show']);
        Route::post('/store', [PatientAllergyController::class, 'store']);
        Route::patch('/update/{patientAllergy}', [PatientAllergyController::class, 'update']);
        Route::delete('/delete/{patientAllergy}', [PatientAllergyController::class, 'destroy']);
    });

    Route::prefix('patient/diagnoses')->group(function () {
        Route::get('/{id}', [DiagnosisController::class, 'index']);
        Route::get('/show/{diagnosis}', [DiagnosisController::class, 'show']);
        Route::post('/store', [DiagnosisController::class, 'store']);
        Route::patch('/update/{diagnosis}', [DiagnosisController::class, 'update']);
        Route::delete('/delete/{diagnosis}', [DiagnosisController::class, 'destroy']);
    });

    Route::prefix('patient/medication')->group(function () {
        Route::get('/{id}', [MedicationController::class, 'index']);
        Route::get('/show/{medication}', [MedicationController::class, 'show']);
    });

    Route::prefix('patient/diet')->group(function () {
        Route::get('/{id}', [DietController::class, 'index']);
        Route::get('/show/{diet}', [DietController::class, 'show']);
        Route::post('/store', [DietController::class, 'store']);
        Route::patch('/update/{diet}', [DietController::class, 'update']);
        Route::delete('/delete/{diet}', [DietController::class, 'destroy']);
    });

    Route::prefix('patient/treatment-plan')->group(function () {
        Route::get('/{id}', [TreatmentPlanController::class, 'index']);
        Route::get('/show/{treatmentPlan}', [TreatmentPlanController::class, 'show']);
        Route::post('/store', [TreatmentPlanController::class, 'store']);
        Route::patch('/update/{treatmentPlan}', [TreatmentPlanController::class, 'update']);
        Route::delete('/delete/{treatmentPlan}', [TreatmentPlanController::class, 'destroy']);
    });


    Route::prefix('tuberculosis-program')->group(function () {
        Route::get('/show/{tuberculosisProgram}', [TuberculosisProgramController::class, 'show']);
        Route::get('/patient-existing/{patient}', [TuberculosisProgramController::class, 'existingTBProgramReferral']);

        // barangay functions
        Route::get('/barangay-list', [TuberculosisProgramController::class, 'barangayList']);
        Route::post('/create-from-barangay', [TuberculosisProgramController::class, 'createFromBarangay']);
        Route::patch('/update-from-barangay/{tuberculosisProgram}', [TuberculosisProgramController::class, 'updateFromBarangay']);

        // RHU functions
        Route::patch('/receive-barangay-referral/{tuberculosisProgram}', [TuberculosisProgramController::class, 'receiveBarangayReferral']);
        Route::get('/rhu-list', [TuberculosisProgramController::class, 'rhuList']);
        Route::patch('/rhu-to-sph-referral/{tuberculosisProgram}', [TuberculosisProgramController::class, 'rhuToSphReferral']);
        Route::patch('/update-rhu-to-sph-referral/{tuberculosisProgram}', [TuberculosisProgramController::class, 'updateRhuToSphReferral']);

        // SPH functions
        Route::patch('/receive-sph-referral/{tuberculosisProgram}', [TuberculosisProgramController::class, 'receiveSphReferral']);
        Route::patch('/approve/{tuberculosisProgram}', [TuberculosisProgramController::class, 'approve']);
        Route::get('/sph-list', [TuberculosisProgramController::class, 'sphList']);
    });

    Route::prefix('doctor')->group(function () {

        Route::patch('/mark-as-done/{id}', [ReferToRHUController::class, 'doctorMarkAsDone']);

        Route::prefix('laboratory-order')->group(function () {
            Route::get('/list', [LaboratoryOrderController::class, 'index']);
            Route::get('/patient/{id}', [LaboratoryOrderController::class, 'patientLabOrders']);
            Route::get('/get-laboratory-queue', [LaboratoryOrderController::class, 'getLaboratoryQueue']);
            Route::post('/store', [LaboratoryOrderController::class, 'store']);

            Route::patch('/send-patient-to-laboratory/{id}', [LaboratoryOrderController::class, 'sendPatientToLab']);


            Route::patch('/update/{id}', [LaboratoryOrderController::class, 'update']);
            Route::delete('/delete/{id}', [LaboratoryOrderController::class, 'destroy']);

            Route::get('/show/{id}', [LaboratoryOrderController::class, 'show']);
            Route::patch('/accept-order/{laboratoryOrder}', [LaboratoryOrderController::class, 'acceptLaboratoryOrder']);
        });
    });



    Route::prefix('laboratory')->group(function () {

        Route::get('/get-queue', [LaboratoryOrderController::class, 'getLaboratoryQueue']);
        Route::patch('/upload-lab-result/{id}', [LaboratoryOrderController::class, 'uploadLabResult']);

        Route::prefix('tests')->group(function () {
            Route::get('/list', [LaboratoryTestController::class, 'index']);
            Route::get('/show/{laboratoryResult}', [LaboratoryTestController::class, 'show']);
            Route::post('/store', [LaboratoryTestController::class, 'store']);
            Route::patch('/update/{id}', [LaboratoryTestController::class, 'update']);
            Route::delete('/delete/{id}', [LaboratoryTestController::class, 'destroy']);
        });

        Route::prefix('results')->group(function () {
            Route::get('/list', [LaboratoryResultController::class, 'index']);
            Route::get('/show/{laboratoryResult}', [LaboratoryResultController::class, 'show']);
            Route::post('/store', [LaboratoryResultController::class, 'store']);
            Route::patch('/update/{id}', [LaboratoryResultController::class, 'update']);
        });

        Route::prefix('imaging')->group(function () {
            Route::get('/', [ImagingController::class, 'index']);
            Route::get('/show-patient/{patientId}', [ImagingController::class, 'getPatientImaging']);
            Route::post('/store', [ImagingController::class, 'store']);
            Route::get('/show/{imaging}', [ImagingController::class, 'show']);
            Route::patch('/update/{imaging}', [ImagingController::class, 'update']);
        });
    });
    Route::get('/csr-supplies', [InventoryCsrController::class, 'getCsrSupplies']);
    Route::prefix('anesthesia')->group(function () {
        Route::get('/get-anesthesia-queue', [OperationProcedureController::class, 'getProcedureQueue']);
        Route::get('/upload-or-result/{id}', [OperationProcedureController::class, 'getProcedureQueue']);

        Route::prefix('operation-procedure')->group(function () {
            Route::get('/list', [OperationProcedureController::class, 'index']);
            Route::get('/patient/{id}', [OperationProcedureController::class, 'patientOperation']);
            Route::get('/show/{operationResult}', [OperationProcedureController::class, 'show']);
            Route::post('/store', [OperationProcedureController::class, 'store']);
            Route::patch('/update/{id}', [OperationProcedureController::class, 'update']);
            Route::delete('/delete/{id}', [OperationProcedureController::class, 'destroy']);
            Route::get('/updated-procedure/{id}', [OperationProcedureController::class, 'updateProcedureStatus']);
        });
        Route::prefix('csr-pharmacy')->group(function () {
            Route::get('/list', [InventoryCSROrderController::class, 'index']);
            Route::post('/store', [InventoryCSROrderController::class, 'store']);
            Route::get('/show/{id}', [InventoryCSROrderController::class, 'show']);
        });
        Route::prefix('pharmacy-csr')->group(function () {
            Route::get('/list', [InventoryPharmacyOrderController::class, 'index']);
            Route::post('/store', [InventoryPharmacyOrderController::class, 'store']);
            Route::get('/show/{id}', [InventoryPharmacyOrderController::class, 'show']);
        });
    });


    Route::prefix('cis/dashboard')->group(function () {
        Route::get('/population', [CISDashboardController::class, 'population']);
        Route::get('/patients/from-barangay-to-rhu', [CISDashboardController::class, 'tbPatientsfromBarangayToRHU']);
        Route::get('/patients/tb-positive-list', [CISDashboardController::class, 'tbPositiveList']);
        Route::get('/patients/treated', [CISDashboardController::class, 'treatedPatientPerMunicipality']);
    });

    Route::get('clinic-queueing/{id}', [ReceivingPatientController::class, 'show']);
    Route::delete('clinic-queue-clear/{id}', [QueueController::class, 'destroy']);

    Route::prefix('clinic-queueing')->group(function () {
        Route::delete('registering/{id}', [QueueController::class, 'destroy']);
        Route::get('registering/list/{clinic}', [QueueController::class, 'registeringList']);
        Route::get('in-service/list/{clinic}', [QueueController::class, 'inServiceList']);
        Route::get('done/list/{clinic}', [QueueController::class, 'doneList']);
        Route::post('store', [QueueController::class, 'store']);
        Route::get('show/{patientQueue}', [QueueController::class, 'show']);
        Route::patch('cancel/{patientQueue}', [QueueController::class, 'cancel']);
        Route::patch('serve/{patientQueue}', [QueueController::class, 'serve']);
        Route::patch('done/{patientQueue}', [QueueController::class, 'done']);
        Route::get('patient-queue-summary/{clinic_id}', [QueueController::class, 'patientQueueSummary']);
        Route::get('patient-exisiting/', [QueueController::class, 'existingPatientQueue']);
    });

    Route::get('municipalities', [MunicipalityController::class, 'index']);
    Route::prefix('municipality')->group(function () {
        Route::get('/show/{municipality}', [MunicipalityController::class, 'show']);
        Route::post('/store', [MunicipalityController::class, 'store']);
        Route::patch('/update/{municipality}', [MunicipalityController::class, 'update']);
        Route::delete('/delete/{municipality}', [MunicipalityController::class, 'destroy']);
    });

    Route::get('barangays', [BarangayController::class, 'index']);
    Route::prefix('barangay')->group(function () {
        Route::get('/show/{barangay}', [BarangayController::class, 'show']);
        Route::post('/store', [BarangayController::class, 'store']);
        Route::patch('/update/{barangay}', [BarangayController::class, 'update']);
        Route::delete('/delete/{barangay}', [BarangayController::class, 'destroy']);
    });

    Route::prefix('inventory-csr')->group(function () {
        Route::post('store', [InventoryCsrController::class, 'store']);
        Route::get('show/{id}', [InventoryCsrController::class, 'show']);
        Route::get('list', [InventoryCsrController::class, 'index']);
    });
    Route::prefix('inventory-pharmacy')->group(function () {
        Route::post('store', [InventoryPharmacyController::class, 'store']);
        Route::get('show/{id}', [InventoryPharmacyController::class, 'show']);
        Route::get('list', [InventoryPharmacyController::class, 'index']);
    });
    // Route::prefix('operation-procedure')->group(function () {
    //     Route::post('store', [OperationProcedureController::class, 'store']);
    //     Route::get('show/{id}', [OperationProcedureController::class, 'show']);
    //     Route::get('list', [OperationProcedureController::class, 'index']);
    //     Route::get('/patient/{id}', [OperationProcedureController::class, 'patientOperation']);
    // });

    Route::prefix('operating-room-chart')->group(function () {
        Route::get('show/{id}', [OperatingRoomChartController::class, 'show']);
        Route::post('store', [OperatingRoomChartController::class, 'store']);
        Route::patch('update/{id}', [OperatingRoomChartController::class, 'update']);
        Route::delete('delete/{id}', [OperatingRoomChartController::class, 'destroy']);
        Route::get('list', [OperatingRoomChartController::class, 'list']);
        Route::patch('to-resu1/{id}', [OperatingRoomChartController::class, 'toResu']);
        Route::patch('done/{id}', [OperatingRoomChartController::class, 'done']);
    });
    Route::prefix('operating-rooms')->group(function () {
        Route::get('show/{id}', [OperatingRoomController::class, 'show']);
        Route::post('store', [OperatingRoomController::class, 'store']);
        Route::patch('update/{id}', [OperatingRoomController::class, 'update']);
        Route::delete('delete/{id}', [OperatingRoomController::class, 'destroy']);
        Route::get('list', [OperatingRoomController::class, 'list']);
        Route::patch('/activate/{id}', [OperatingRoomController::class, 'activate']);
        Route::patch('/deactivate/{id}', [OperatingRoomController::class, 'deactivate']);
        // Route::patch('to-resu/{id}', [OperatingRoomChartController::class, 'toResu']);
        // Route::patch('done/{id}', [OperatingRoomChartController::class, 'done']);
    });


    Route::get('pmrf/show/{id}', [PhilhealthMemberRegistrationController::class, 'show']);
});
