<?php

namespace App\Http\Controllers\Philhealth;
use Illuminate\Support\Facades\Storage;
use CodeDredd\Soap\Facades\Soap;

class PhilhealthSoapController
{



    public function ping()
    {


        $context = stream_context_create([
            'ssl' => [
                // set some SSL/TLS specific options
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        $pecwsWsdlUrl = storage_path("PECWS.wsdl");

        $client = new \SoapClient($pecwsWsdlUrl, ['stream_context' => $context]);
        $serverDateTime = $client->GetServerDateTime();

        dd($serverDateTime);


      /*  $pecwsWsdlUrl = storage_path('PECWS.wsdl');



        $response = Soap::baseWsdl($pecwsWsdlUrl)->call('GetServerDateTime');

        dd($response);*/


    }


    public function isClaimEligible()
    {
        $context = stream_context_create([
            'ssl' => [
                // set some SSL/TLS specific options
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        $pecwsWsdlUrl = storage_path("PECWS.wsdl");
        $userId = "PMCC";
        $SoftwareCertifcationId = "DUMMYSCERTZ07526";
        $pUserName = "{$userId}:{$SoftwareCertifcationId}";
        $pUserPassword = 'PHilhealthDuMmyciPHerKeyS';
        $pHospitalCode = '207526';
        $pPIN = '';
        $pMemberLastName = 'STAR';
        $pMemberFirstName = 'TWINKLE';
        $pMemberMiddleName = 'LITTLE';
        $pMemberSuffix = '';
        $pMemberBirthDate = '10-20-1974';
        $pMailingAddress = '';
        $pZipCode = '';
        $pPatientIs = '';
        $pAdmissionDate = '';
        $pDischargeDate = '';
        $pPatientLastName = '';
        $pPatientFirstName = '';
        $pPatientMiddleName = '';
        $pPatientSuffix = '';
        $pPatientBirthDate = '';
        $pPatientGender = '';
        $pMemberShipType = '';
        $pPEN = '';
        $pEmployerName = '';
        $pRVS = '';
        $pTotalAmountActual = '';
        $pTotalAmountClaimed = '';
        $pIsFinal = '';

        $client = new \SoapClient($pecwsWsdlUrl, ['stream_context' => $context]);


        return $client->GetMemberPIN("PMCC:DUMMYSCERTZ07526", $pUserPassword, $pHospitalCode, $pMemberLastName, 	$pMemberFirstName, $pMemberMiddleName, $pMemberSuffix, $pMemberBirthDate);

        $serverDateTime = $client->isClaimEligible ($pUserName, $pUserPassword, $pHospitalCode, $pPIN,
            $pMemberLastName, $pMemberFirstName, $pMemberMiddleName,
            $pMemberSuffix, $pMemberBirthDate, $pMailingAddress, $pZipCode,
            $pPatientIs, $pAdmissionDate, $pDischargeDate, $pPatientLastName,
            $pPatientFirstName, $pPatientMiddleName, $pPatientSuffix, $pPatientBirthDate,
            $pPatientGender, $pMemberShipType, $pPEN, $pEmployerName, $pRVS, $pTotalAmountActual,
            $pTotalAmountClaimed, $pIsFinal
        );



    }


}
