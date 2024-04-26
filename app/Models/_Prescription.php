<?php

namespace App\Models;

use App\Models\_Doctor;
use DB;
use Illuminate\Database\Eloquent\Model;

class _Prescription extends Model
{
    public static function getLocalPharmacy($data)
    {
        $query = "SELECT pharmacy_id as value , name as  label from pharmacy where management_id = '" . $data['management_id'] . "' and name is not null ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getProduct($data)
    {
        $query = "SELECT product_id as value,
        (SELECT product from pharmacyhospital_products where pharmacyhospital_products.product_id = pharmacyhospital_inventory.product_id ) as label
        from pharmacyhospital_inventory where management_id = '" . $data['management_id'] . "' and quantity > 0 and request_type ='IN' group by product_id ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getProductDetails($data)
    {
        $query = "SELECT unit, sum(quantity) as available_qty,
        (SELECT product from pharmacyhospital_products where pharmacyhospital_products.product_id = pharmacyhospital_inventory.product_id) as product_name,
        (SELECT srp from pharmacyhospital_products where pharmacyhospital_products.product_id = pharmacyhospital_inventory.product_id) as product_amount
        from pharmacyhospital_inventory where product_id = '" . $data['product_id'] . "' and request_type ='IN' ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function addProduct($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_unsave_prescription')->insert([
            'dup_id' => 'dup-' . rand(0, 9999),
            'prescription_id' => 'prescription-' . rand(0, 9999),
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'prescription' => $data['product_name'],
            'amount' => $data['product_amount'],
            'product_id' => $data['prescription'],
            'quantity' => $data['order_qty'],
            'type' => $data['type'],
            'dosage' => $data['dosage'],
            'per_day' => $data['take_every'],
            'per_take' => $data['take_times'],
            'remarks' => $data['remarks'],
            'prescription_type' => $data['prescription_type'],
            'pharmacy_id' => $data['pharmacy_id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function unsaveProductCount($data)
    {
        $query = "SELECT count(quantity) as unsave_count from doctors_unsave_prescription where patient_id = '" . $data['patient_id'] . "' and prescription_type ='" . $data['prescription_type'] . "' ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function unsaveProduct($data)
    {
        $query = "SELECT *,
        (SELECT product from pharmacyclinic_products where pharmacyclinic_products.product_id = doctors_unsave_prescription.product_id ) as product_name
        from doctors_unsave_prescription where patient_id = '" . $data['patient_id'] . "' ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function removeUnsave($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_unsave_prescription')->where('id', $data['remove_id'])->delete();
    }
    //jhomar
    // public static function getMedication($data){
    //     $query = "SELECT claim_id, patients_id as patient_id, prescription_id, prescription_type, created_at, doctors_id from doctors_prescription where patients_id = '".$data['patient_id']."' group by YEAR(created_at), MONTH(created_at), DAY(created_at), prescription_type order by id desc";

    //     if($data['connection'] == 'online'){
    //         $result = DB::connection('mysql2')->getPdo()->prepare($query);
    //     }else{
    //         $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
    //     }
    //     $result->execute();
    //     return $result->fetchAll(\PDO::FETCH_OBJ);
    // }

    public static function getMedication($data)
    {
        $final = '';

        if ($data['user'] == 'patient') {
            $bolbol = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients')->select('patient_id')->where('user_id', $data['user_id'])->first();
            $final = $bolbol->patient_id;
        }
        if ($data['user'] == 'doctor') {
            $final = $data['patient_id'];
        }

        $query = "SELECT claim_id, patients_id as patient_id, prescription_id, prescription_type, created_at, doctors_id from doctors_prescription where patients_id = '$final' group by YEAR(created_at), MONTH(created_at), DAY(created_at), prescription_type order by id desc";
        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getMedicationDetails($data)
    {
        if ($data['connection'] == 'online') {
            return DB::connection('mysql2')->table('doctors_prescription')
                ->where('prescription_type', $data['prescription_type'])
                ->where('patients_id', $data['patient_id'])
                ->whereYear('created_at', date('Y', strtotime($data['prescription_date'])))
                ->whereMonth('created_at', date('m', strtotime($data['prescription_date'])))
                ->whereDay('created_at', date('d', strtotime($data['prescription_date'])))
                ->get();
        }

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_prescription')
            ->where('prescription_type', $data['prescription_type'])
            ->where('patients_id', $data['patient_id'])
            ->whereYear('created_at', date('Y', strtotime($data['prescription_date'])))
            ->whereMonth('created_at', date('m', strtotime($data['prescription_date'])))
            ->whereDay('created_at', date('d', strtotime($data['prescription_date'])))
            ->get();
    }

    public static function getprescriptionList($data)
    {

        $doctors_id = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        $queryLocal = "SELECT *
        from doctors_prescription where patients_id = '" . $data['patient_id'] . "' and doctors_id = '$doctors_id' and prescription_type <> 'virtual' group by claim_id order by created_at desc ";

        $queryVirtual = "SELECT *
        from doctors_prescription where patients_id = '" . $data['patient_id'] . "' and doctors_id = '$doctors_id' and prescription_type = 'virtual' group by claim_id order by created_at desc ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->getPdo()
            ->prepare($data['connection'] == 'online' ? $queryVirtual : $queryLocal);

        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getPrescriptionDetails($data)
    {
        $doctors_id = $data['user_id'];

        $query = "SELECT *
        -- (SELECT product from pharmacyclinic_products where pharmacyclinic_products.product_id = doctors_prescription.prescription ) as product_name
        from doctors_prescription where patients_id = '" . $data['patient_id'] . "' and doctors_id = '$doctors_id' and claim_id = '" . $data['claim_id'] . "' ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function pescriptionSaveallUnsave($data)
    {
        $unsavePresc = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_unsave_prescription')->where('patient_id', $data['patient_id'])->get();
        $presc = [];
        $claim = 'claim-' . rand(0, 9999) . time();
        $order_no = 'order-' . rand(0, 9999) . time();
        $add_to_statement = [];
        $add_to_bil = [];
        $add_to_gtcart = [];
        $add_to_gtcart_confirm = [];
        $patient_notif = [];

        foreach ($unsavePresc as $m) {
            $billing_id = 'bill-' . rand(0, 999) . time();
            $presc[] = array(
                'dp_id' => 'dp-' . rand(0, 9999) . time(),
                'prescription_id' => $m->prescription_id,
                'patients_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'management_id' => $m->management_id,
                'doctors_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
                'prescription' => $m->product_id,
                'product_name' => $m->prescription,
                'product_amount' => $m->amount,
                'is_package' => $m->is_package,
                'brand' => $m->brand,
                'quantity' => $m->quantity,
                'type' => $m->type,
                'dosage' => $m->dosage,
                'per_day' => $m->per_day,
                'per_take' => $m->per_take,
                'remarks' => $m->remarks,
                'prescription_type' => $m->prescription_type,
                'pharmacy_id' => $m->pharmacy_id,
                'claim_id' => $claim,
                'created_at' => $m->created_at,
                'updated_at' => $m->updated_at,
            );

            $add_to_bil[] = array(
                'b_id' => 'b_id' . rand(0, 9999) . time(),
                'billing_id' => $billing_id,
                'management_id' => $m->management_id,
                // 'encoder_id'=>$data['encoders_id'],
                'claim_id' => $claim,
                'billing' => $m->prescription,
                'product_id' => $m->product_id,
                'is_package' => $m->is_package,
                'brand' => $m->brand,
                'amount' => $m->amount,
                'quantity' => $m->quantity,
                'status' => 1,
                'release_status' => 'RELEASING',
                'updated_at' => $m->created_at,
                'created_at' => $m->updated_at,
            );

            $add_to_statement[] = array(
                'billing_statement_id' => 'bill-' . rand(0, 99999),
                'patient_id' => $data['patient_id'],
                // 'encoders_id' => $data['encoders_id'],
                'management_id' => $m->management_id,
                'billing_id' => $billing_id,
                'is_package' => $m->is_package,
                'bill_status' => 'unpaid',
                'updated_at' => $m->created_at,
                'created_at' => $m->updated_at,
            );

            $add_to_gtcart[] = array(
                'cart_id' => 'cart-' . time() . rand(0, 9999),
                'order_no' => $order_no,
                'patient_id' => $m->patient_id,
                'product' => $m->prescription,
                'product_id' => $m->product_id,
                'type' => $m->type,
                'dosage' => $m->dosage,
                'quantity' => $m->quantity,
                'original_qty' => $m->quantity,
                'price' => $m->amount,
                'order_status' => 'order-finalized',
                'is_rx' => 1,
                'rx_number' => $claim,
                'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
                'delivery' => 0,
                'status' => 1,
                'order_toID' => $m->pharmacy_id,
                'created_at' => $m->created_at,
                'updated_at' => $m->created_at,
            );

            $add_to_gtcart_confirm[] = array(
                'cart_con_id' => 'cart-con-' . time() . rand(0, 9999),
                'order_no' => $order_no,
                'patient_id' => $m->patient_id,
                'product' => $m->prescription,
                'product_id' => $m->product_id,
                'type' => $m->type,
                'dosage' => $m->dosage,
                'quantity' => $m->quantity,
                'original_qty' => $m->quantity,
                'price' => $m->amount,
                'order_status' => 'order-finalized',
                'is_rx' => 1,
                'rx_number' => $claim,
                'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
                'delivery' => 0,
                'status' => 1,
                'order_toID' => $m->pharmacy_id,
                'created_at' => $m->created_at,
                'updated_at' => $m->created_at,
            );
        }

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('gtc_cart')->insert($add_to_gtcart);
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('gtc_cart_patient_confirm')->insert($add_to_gtcart_confirm);
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_prescription')->insert($presc);
        // add initial billing at doctor
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('billing')->insert($add_to_bil);
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('billing_statement')->insert($add_to_statement);
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99) . time(),
            'order_id' => $order_no,
            'patient_id' => $data['patient_id'],
            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
            'category' => 'cart',
            'department' => 'doctor-prescription',
            'message' => 'New prescription added by doctor.',
            'is_view' => 0,
            'notification_from' => 'virtual',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // if prescription is virtual and patient info is not save in online record
        // save patients info to online record from local records
        if ($data['connection'] == 'online') {
            // get patient info sa local
            $getPatientLocalRecord = DB::connection('mysql')->table('patients')->where('patient_id', $data['patient_id'])->first();

            // check if patient info is naa na sa online else walay buhaton
            $getPatientOnlineRecord = DB::connection('mysql2')->table('patients')->where('patient_id', $data['patient_id'])->get();
            if (count($getPatientOnlineRecord) < 1) {
                DB::connection('mysql2')->table('patients')->insert([
                    'patient_id' => $getPatientLocalRecord->patient_id,
                    'encoders_id' => $getPatientLocalRecord->encoders_id,
                    'doctors_id' => $getPatientLocalRecord->doctors_id,
                    'user_id' => $getPatientLocalRecord->user_id,
                    'firstname' => $getPatientLocalRecord->firstname,
                    'lastname' => $getPatientLocalRecord->lastname,
                    'middle' => $getPatientLocalRecord->middle,
                    'email' => $getPatientLocalRecord->email,
                    'mobile' => $getPatientLocalRecord->mobile,
                    'telephone' => $getPatientLocalRecord->telephone,
                    'birthday' => $getPatientLocalRecord->birthday,
                    'birthplace' => $getPatientLocalRecord->birthplace,
                    'gender' => $getPatientLocalRecord->gender,
                    'street' => $getPatientLocalRecord->street,
                    'barangay' => $getPatientLocalRecord->barangay,
                    'city' => $getPatientLocalRecord->city,
                    'created_at' => $getPatientLocalRecord->created_at,
                    'updated_at' => $getPatientLocalRecord->updated_at,
                ]);
            }
        }

        // remove unsave prescription
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_unsave_prescription')->where('patient_id', $data['patient_id'])->delete();
    }

    public static function getvirtualPharmacy($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('pharmacy')
            ->select('company_name as label', 'pharmacy_id as value', 'pharmacy_category')
            ->groupBy('pharmacy_id')
            ->where('status', 1)
            ->where('pharmacy_type', $data['connection'] == 'online' ? 'virtual' : 'local')
            ->get();
    }

    public static function getvirtualPharmacyProducts($data)
    {

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table($data['pharmacy_category'] == 'hospital' ? 'pharmacyhospital_products' : 'pharmacyclinic_products')
            ->select('product as label', 'product_id as value')
            ->where('pharmacy_id', $data['pharmacy_id'])
            ->get();

        // return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_pharmacy_product')
        //     ->where('pharmacy_id', $data['virtual_pharmacy'])
        //     ->select('product as label', 'product_id as value')
        //     ->where('status', 1)
        //     ->get();
    }

    public static function getvirtualPharmacyProductsDetails($data)
    {
        date_default_timezone_set('Asia/Manila');
        if ($data['pharmacy_category'] == 'hospital') {
            return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('pharmacyhospital_products')
                ->join('pharmacyhospital_inventory', 'pharmacyhospital_inventory.product_id', '=', 'pharmacyhospital_products.product_id')
                ->select('pharmacyhospital_products.product as product_name', 'pharmacyhospital_products.srp as product_amount', 'pharmacyhospital_inventory.unit')
                ->where('pharmacyhospital_products.pharmacy_id', $data['pharmacy_id'])
                ->where('pharmacyhospital_products.product_id', $data['product_id'])
                ->get();
        }

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('pharmacyclinic_products')
            ->join('pharmacyclinic_inventory', 'pharmacyclinic_inventory.product_id', '=', 'pharmacyclinic_products.product_id')
            ->select('pharmacyclinic_products.product as product_name', 'pharmacyclinic_products.srp as product_amount', 'pharmacyclinic_inventory.unit')
            ->where('pharmacyclinic_products.pharmacy_id', $data['pharmacy_id'])
            ->where('pharmacyclinic_products.product_id', $data['product_id'])
            ->get();

        // return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_pharmacy_product')
        //     ->select('product as product_name', 'amount as product_amount', 'unit')
        //     ->where('pharmacy_id', $data['virtual_pharmacy'])
        //     ->where('product_id', $data['product_id'])
        //     ->where('status', 1)
        //     ->get();
    }

    public static function addVirtualPrescription($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_unsave_prescription')->insert([
            'dup_id' => 'dup-' . rand(0, 9999),
            'prescription_id' => 'prescription-' . rand(0, 9999),
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'prescription' => $data['product_name'],
            'amount' => $data['product_amount'],
            'product_id' => $data['prescription'],
            'quantity' => $data['order_qty'],
            'type' => $data['type'],
            'dosage' => $data['dosage'],
            'per_day' => $data['take_every'],
            'per_take' => $data['take_times'],
            'remarks' => $data['remarks'],
            'prescription_type' => $data['prescription_type'],
            'pharmacy_id' => $data['virtual_parmacy'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getUnsaveCountByPresc($data)
    {

        $queryLocal = " SELECT id  from doctors_unsave_prescription where patient_id = '" . $data['patient_id'] . "' and prescription_type <> 'virtual'  ";

        $virtualQuery = " SELECT id  from doctors_unsave_prescription where patient_id = '" . $data['patient_id'] . "' and prescription_type = 'virtual' ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->getPdo()
            ->prepare($data['connection'] == 'online' ? $virtualQuery : $queryLocal);

        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    //01-21-2022
    public static function prescriptionSaveallUnsaveNurse($data)
    {
        $unsavePresc = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_unsave_prescription')->where('patient_id', $data['patient_id'])->get();
        $presc = [];
        $claim = 'claim-' . rand(0, 9999) . time();
        $order_no = 'order-' . rand(0, 9999) . time();
        $add_to_statement = [];
        $add_to_bil = [];
        $add_to_gtcart = [];
        $add_to_gtcart_confirm = [];
        $patient_notif = [];

        foreach ($unsavePresc as $m) {
            $billing_id = 'bill-' . rand(0, 999) . time();
            $presc[] = array(
                'dp_id' => 'dp-' . rand(0, 9999) . time(),
                'prescription_id' => $m->prescription_id,
                'patients_id' => $data['patient_id'],
                'management_id' => $m->management_id,
                'doctors_id' => $data['doctor'],
                'prescription' => $m->product_id,
                'product_name' => $m->prescription,
                'product_amount' => $m->amount,
                'is_package' => $m->is_package,
                'brand' => $m->brand,
                'quantity' => $m->quantity,
                'type' => $m->type,
                'dosage' => $m->dosage,
                'per_day' => $m->per_day,
                'per_take' => $m->per_take,
                'remarks' => $m->remarks,
                'prescription_type' => $m->prescription_type,
                'pharmacy_id' => $m->pharmacy_id,
                'claim_id' => $claim,
                'created_at' => $m->created_at,
                'updated_at' => $m->updated_at,
            );

            $add_to_bil[] = array(
                'b_id' => 'b_id' . rand(0, 9999) . time(),
                'billing_id' => $billing_id,
                'management_id' => $m->management_id,
                // 'encoder_id'=>$data['encoders_id'],
                'claim_id' => $claim,
                'billing' => $m->prescription,
                'product_id' => $m->product_id,
                'is_package' => $m->is_package,
                'brand' => $m->brand,
                'amount' => $m->amount,
                'quantity' => $m->quantity,
                'status' => 1,
                'release_status' => 'RELEASING',
                'updated_at' => $m->created_at,
                'created_at' => $m->updated_at,
            );

            $add_to_statement[] = array(
                'billing_statement_id' => 'bill-' . rand(0, 99999),
                'patient_id' => $data['patient_id'],
                // 'encoders_id' => $data['encoders_id'],
                'management_id' => $m->management_id,
                'billing_id' => $billing_id,
                'is_package' => $m->is_package,
                'bill_status' => 'unpaid',
                'updated_at' => $m->created_at,
                'created_at' => $m->updated_at,
            );

            $add_to_gtcart[] = array(
                'cart_id' => 'cart-' . time() . rand(0, 9999),
                'order_no' => $order_no,
                'patient_id' => $m->patient_id,
                'product' => $m->prescription,
                'product_id' => $m->product_id,
                'type' => $m->type,
                'dosage' => $m->dosage,
                'quantity' => $m->quantity,
                'original_qty' => $m->quantity,
                'price' => $m->amount,
                'order_status' => 'order-finalized',
                'is_rx' => 1,
                'rx_number' => $claim,
                'doctor_id' => $data['doctor'],
                'delivery' => 0,
                'status' => 1,
                'order_toID' => $m->pharmacy_id,
                'created_at' => $m->created_at,
                'updated_at' => $m->created_at,
            );

            $add_to_gtcart_confirm[] = array(
                'cart_con_id' => 'cart-con-' . time() . rand(0, 9999),
                'order_no' => $order_no,
                'patient_id' => $m->patient_id,
                'product' => $m->prescription,
                'product_id' => $m->product_id,
                'type' => $m->type,
                'dosage' => $m->dosage,
                'quantity' => $m->quantity,
                'original_qty' => $m->quantity,
                'price' => $m->amount,
                'order_status' => 'order-finalized',
                'is_rx' => 1,
                'rx_number' => $claim,
                'doctor_id' => $data['doctor'],
                'delivery' => 0,
                'status' => 1,
                'order_toID' => $m->pharmacy_id,
                'created_at' => $m->created_at,
                'updated_at' => $m->created_at,
            );
        }

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('gtc_cart')->insert($add_to_gtcart);
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('gtc_cart_patient_confirm')->insert($add_to_gtcart_confirm);
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_prescription')->insert($presc);
        // add initial billing at doctor
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('billing')->insert($add_to_bil);
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('billing_statement')->insert($add_to_statement);
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99) . time(),
            'order_id' => $order_no,
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor'],
            'category' => 'cart',
            'department' => 'doctor-prescription',
            'message' => 'New prescription added by doctor.',
            'is_view' => 0,
            'notification_from' => 'virtual',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // if prescription is virtual and patient info is not save in online record
        // save patients info to online record from local records
        if ($data['connection'] == 'online') {
            // get patient info sa local
            $getPatientLocalRecord = DB::connection('mysql')->table('patients')->where('patient_id', $data['patient_id'])->first();

            // check if patient info is naa na sa online else walay buhaton
            $getPatientOnlineRecord = DB::connection('mysql2')->table('patients')->where('patient_id', $data['patient_id'])->get();
            if (count($getPatientOnlineRecord) < 1) {
                DB::connection('mysql2')->table('patients')->insert([
                    'patient_id' => $getPatientLocalRecord->patient_id,
                    'encoders_id' => $getPatientLocalRecord->encoders_id,
                    'doctors_id' => $getPatientLocalRecord->doctors_id,
                    'user_id' => $getPatientLocalRecord->user_id,
                    'firstname' => $getPatientLocalRecord->firstname,
                    'lastname' => $getPatientLocalRecord->lastname,
                    'middle' => $getPatientLocalRecord->middle,
                    'email' => $getPatientLocalRecord->email,
                    'mobile' => $getPatientLocalRecord->mobile,
                    'telephone' => $getPatientLocalRecord->telephone,
                    'birthday' => $getPatientLocalRecord->birthday,
                    'birthplace' => $getPatientLocalRecord->birthplace,
                    'gender' => $getPatientLocalRecord->gender,
                    'street' => $getPatientLocalRecord->street,
                    'barangay' => $getPatientLocalRecord->barangay,
                    'city' => $getPatientLocalRecord->city,
                    'created_at' => $getPatientLocalRecord->created_at,
                    'updated_at' => $getPatientLocalRecord->updated_at,
                ]);
            }
        }

        // remove unsave prescription
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_unsave_prescription')->where('patient_id', $data['patient_id'])->delete();
    }

    public static function getPrescriptionByNurse($data)
    {
        $patient_id = $data['patient_id'];
        $query = "SELECT * FROM doctors_prescription WHERE patients_id = '$patient_id' and prescription_type <> 'virtual' GROUP BY claim_id ORDER BY created_at DESC ";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getPrescriptionDetailsByNurse($data)
    {
        $doctors_id = $data['user_id'];
        $claim_id = $data['claim_id'];
        $patient_id = $data['patient_id'];

        $query = "SELECT * FROM doctors_prescription WHERE patients_id = '$patient_id' AND doctors_id = '$doctors_id' AND claim_id = '$claim_id' ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

}
