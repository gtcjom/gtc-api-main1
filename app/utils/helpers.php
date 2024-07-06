<?php

use JetBrains\PhpStorm\Pure;

function userTypes(): array
{
    return [
        'PHO',
        'PHO-HO',
        'PHO-FP',
        'MUNICIPAL-HO',
        'BARANGAY-HO',
        'BARANGAY-HO',
        'PUROK-HO',
        'LMIS-WH',
        'LMIS-SPH',
        'LMIS-RHU',
        'LMIS-BHS',
        'LMIS-CNOR',
        'BHS-BHW',
        'BHS-PHAR',
        'RHU-NURSE',
        'RHU-DOC',
        'RHU-DOCTOR',
        'RHU-PHAR',
        'RHU-LAB',
        'RHU-XRAY',
        'HIS-IMAGING',
        'SPH-NURSE',
        'SPH-DOCTOR',
        'SPH-PHAR',
        'SPH-LAB',
        'SPH-XRAY',
        'SPH-BILLING',
        'SPH-CASHIER',
    ];
}

function userClinicTypes(): array
{
    return [
        'BHS-BHW',
        'CIS-Doctor',
        'CIS-Nurse',
        'CIS-Med-tech',
        'CIS-Volunteer',
        'CIS-Laboratory',
        'CIS-Registration',
        /*'Clinic-Doctor',
        'Clinic-Nurse',
        'Clinic-Med-tech',
        'Clinic-Volunteer',
        'HIS-Doctor',
        'HIS-Registration',
        'HIS-Laboratory',
        'HIS-Nurse',
        'HIS-Med-tech',
        'HIS-Volunteer',*/
    ];
}
