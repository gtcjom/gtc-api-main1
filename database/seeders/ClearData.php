<?php

namespace Database\Seeders;

use App\Models\AppointmentData;
use App\Models\Diagnosis;
use App\Models\ItemUsage;
use App\Models\LaboratoryOrder;
use App\Models\LaboratoryResult;
use App\Models\Note;
use App\Models\Patient;
use App\Models\PatientAppointmentSymptoms;
use App\Models\PatientCase;
use App\Models\PatientDependents;
use App\Models\PatientGeneralHistory;
use App\Models\PatientInformation;
use App\Models\PatientPMRFInformation;
use App\Models\PatientQueue;
use App\Models\PatientRawAnswer;
use App\Models\Sanitation;
use App\Models\SocialHistory;
use App\Models\SourceIncome;
use App\Models\TeleMedicineSchedule;
use App\Models\TuberculosisData;
use App\Models\TuberculosisProgram;
use App\Models\V2\PatientPrescription;
use App\Models\V2\TreatmentPlan;
use App\Models\Vital;
use App\Models\WasteManagement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClearData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Patient::query()->truncate();
        AppointmentData::query()->truncate();
        PatientCase::query()->truncate();
        PatientQueue::query()->truncate();
        Vital::query()->truncate();
        ItemUsage::query()->truncate();
        Diagnosis::query()->truncate();
        LaboratoryOrder::query()->truncate();
        LaboratoryResult::query()->truncate();
        Note::query()->truncate();
        PatientAppointmentSymptoms::query()->truncate();
        PatientDependents::query()->truncate();
        PatientGeneralHistory::query()->truncate();
        PatientInformation::query()->truncate();
        PatientPMRFInformation::query()->truncate();
        PatientPrescription::query()->truncate();
        PatientRawAnswer::query()->truncate();
        Sanitation::query()->truncate();
        SocialHistory::query()->truncate();
        SourceIncome::query()->truncate();
        TeleMedicineSchedule::query()->truncate();
        TreatmentPlan::query()->truncate();
        TuberculosisData::query()->truncate();
        TuberculosisProgram::query()->truncate();
        Vital::query()->truncate();
        WasteManagement::query()->truncate();
    }
}
