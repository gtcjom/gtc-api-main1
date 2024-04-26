<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Accounting extends Model
{

    public static function getPharmacyWarehouseId($data)
    {
        return DB::table('warehouse_accounts')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->first();
    }

    public static function hisAccountingGetHeaderInfo($data)
    {
        // return DB::table('accounting_account')
        //     ->select('accounting_id', 'user_fullname as name', 'image', 'user_address as address')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

        return DB::table('accounting_account')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'accounting_account.user_id')
            ->select('accounting_account.accounting_id', 'accounting_account.user_fullname as name', 'accounting_account.image', 'accounting_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('accounting_account.user_id', $data['user_id'])
            ->first();
    }

    public static function getPsychologyIdByManagement($data)
    {
        return DB::table('psychology_account')
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function getLaboratoryIdByManagement($data)
    {
        return DB::table('laboratory_list')
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function getWarehouseIdByManagement($data)
    {
        return DB::table('warehouse_accounts')
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function getImagingIdByManagement($data)
    {
        return DB::table('imaging')
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function hisAccountingGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM accounting_account WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisAccountingUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('accounting_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisAccountingUpdatePersonalInfo($data)
    {
        return DB::table('accounting_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisAccountingUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisAccountingUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function accountingItemDeliveryTempList($data)
    {
        return DB::table('laboratory_items_temp_dr')
            ->join('laboratory_items', "laboratory_items.item_id", '=', "laboratory_items_temp_dr.item_id")
            ->select('laboratory_items.*', 'laboratory_items_temp_dr.*', 'laboratory_items_temp_dr.id as  temp_id')
            ->where('laboratory_items_temp_dr.management_id', $data['management_id'])
            ->where('laboratory_items_temp_dr.laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->get();
    }

    public static function accountingItemDeliveryTempRemove($data)
    {
        return DB::table('laboratory_items_temp_dr')
            ->where('id', $data['remove_id'])
            ->delete();
    }

    public static function accountingItemList($data)
    {

        // $management_id = $data['management_id'];
        // $labid = (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id;

        // $query = "SELECT *, item as label, item_id as value, item_id as itemId,
        //     (SELECT IFNULL(count(id), 0) from laboratory_items_laborder where item_id = itemId) as _order_user
        // FROM laboratory_items WHERE management_id = '$management_id' and status = 1 and laboratory_id = '$labid' order by item asc";

        // $result = DB::connection('mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);

        $management_id = $data['management_id'];
        $labid = (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id;

        $query = "SELECT *, description as label, item_id as value, item_id as itemId,
            (SELECT IFNULL(count(id), 0) from laboratory_items_laborder where item_id = itemId) as _order_user
        FROM laboratory_items WHERE management_id = '$management_id' and status = 1 and laboratory_id = '$labid' order by item asc";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function accountingItemListByBatches($data)
    {

        $management_id = $data['management_id'];
        $itemid = $data['item_id'];
        $labid = (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id;

        $query = "SELECT *, batch_number as value, batch_number as label
        FROM laboratory_items_monitoring WHERE management_id = '$management_id' and laboratory_id = '$labid' and item_id = '$itemid' group by batch_number";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function accountingItemDeliveryTempProcess($data)
    {
        date_default_timezone_set('Asia/Manila');
        $items = DB::table('laboratory_items_temp_dr')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->get();

        $gtc = [];
        foreach ($items as $v) {
            $gtc[] = array(
                'lrm_id' => 'lrm-' . time() . rand(0, 99958),
                'item_id' => $v->item_id,
                'management_id' => $v->management_id,
                'laboratory_id' => $v->laboratory_id,
                'qty' => $v->qty,
                'dr_number' => $data['dr_number'],
                'batch_number' => $v->batch_number,
                'mfg_date' => $v->mfg_date,
                'expiration_date' => $v->expiration_date,
                'type' => 'IN',
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('laboratory_items_monitoring')->insert($gtc);

        return DB::table('laboratory_items_temp_dr')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->delete();
    }

    public static function getAccountingItemsInventory($data)
    {
        $management_id = $data['management_id'];
        $labid = (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id;

        $query = "SELECT *, item_id as xid,
        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = xid and type = 'IN' ) as _total_qty_in,
        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = xid and type = 'OUT' ) as _total_qty_out,
        (SELECT _total_qty_in - _total_qty_out ) as _total_qty_available
        FROM laboratory_items WHERE management_id = '$management_id' and laboratory_id = '$labid' ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getItemMonitoring($data)
    {
        $management_id = $data['management_id'];
        $labid = (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id;

        $query = "SELECT *, item_id as xid,
        (SELECT item from laboratory_items where item_id = xid ) as item,
        (SELECT description from laboratory_items where item_id = xid ) as description,
        (SELECT supplier from laboratory_items where item_id = xid ) as supplier
        FROM laboratory_items_monitoring WHERE management_id = '$management_id' and laboratory_id = '$labid' order by id desc";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getLabOrderTemp($data)
    {
        return DB::table('laboratory_items_laborder_tempnoitem')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->get();
    }

    public static function getAccountingOrderList($data)
    {
        return DB::table('laboratory_items_laborder')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->groupBy('order_id')
            ->orderBy('laborder', 'asc')
            ->get();
    }

    public static function getAccountingOrderListItems($data)
    {

        $mgn = $data['management_id'];
        $lab = (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id;
        $orderid = $data['order_id'];

        $query = " SELECT *, item_id as itemid,
            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemid and type='IN') as _total_qty_in,
            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemid and type='OUT') as _total_qty_out,
            (SELECT _total_qty_in - _total_qty_out) as _total_qty_available
            from laboratory_items_laborder where management_id = '$mgn' and  laboratory_id = '$lab'  and order_id = '$orderid'
        ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function saveTempOrderNoItem($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_items_laborder_tempnoitem')->insert([
            'order_id' => 'order-' . rand(0, 99999) . time(),
            'management_id' => $data['management_id'],
            'laboratory_id' => (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id,
            'category' => $data['dept'],
            'laborder' => $data['test'],
            'can_be_discounted' => (int) $data['can_be_discounted'],
            'rate' => $data["rate"],
            'mobile_rate' => $data["mobile_rate"],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function laboratoryRemoveOrder($data)
    {
        // remove temp order with temp order items
        DB::table('laboratory_items_laborder_tempitems')
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', $data['laboratory_id'])
            ->where('management_id', $data['management_id'])
            ->delete();

        return DB::table('laboratory_items_laborder_tempnoitem')
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', $data['laboratory_id'])
            ->where('management_id', $data['management_id'])
            ->delete();
    }

    public static function laboratoryRemoveItemInOrder($data)
    {
        return DB::table('laboratory_items_laborder_tempitems')
            ->where('id', $data['remove_id'])
            ->delete();
    }

    public static function laboratoryItemRemove($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_items')
            ->where('id', $data['remove_id'])
            ->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function laboratoryOrderUsesThisItem($data)
    {
        return DB::table('laboratory_items_laborder')
            ->where('item_id', $data['item_id'])
            ->groupBy('order_id')
            ->get();
    }

    public static function getOrdersItems($data)
    {
        return DB::table('laboratory_items_laborder_tempitems')
            ->join('laboratory_items', 'laboratory_items.item_id', 'laboratory_items_laborder_tempitems.item_id')
            ->select('laboratory_items_laborder_tempitems.*', 'laboratory_items.*', 'laboratory_items_laborder_tempitems.id as ids')
            ->where('laboratory_items_laborder_tempitems.management_id', $data['management_id'])
            ->where('laboratory_items_laborder_tempitems.laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->where('laboratory_items_laborder_tempitems.order_id', $data['order_id'])
            ->get();
    }

    public static function saveOrderItem($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_items_laborder_tempitems')
            ->insert([
                'management_id' => $data['management_id'],
                'laboratory_id' => (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id,
                'item_id' => $data['selected_item'],
                'order_id' => $data['order_id'],
                'category' => $data['category'],
                'laborder' => $data['laborder'],
                'can_be_discounted' => (int) $data['can_be_discounted'],
                'rate' => $data['rate'],
                'mobile_rate' => $data['mobile_rate'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function processTempOrderWithItems($data)
    {
        date_default_timezone_set('Asia/Manila');

        $query = DB::table('laboratory_items_laborder_tempitems')
            ->join('laboratory_items', 'laboratory_items.item_id', 'laboratory_items_laborder_tempitems.item_id')
            ->where('laboratory_items_laborder_tempitems.management_id', $data['management_id'])
            ->where('laboratory_items_laborder_tempitems.laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->where('laboratory_items_laborder_tempitems.order_id', $data['order_id'])
            ->get();

        $gtc = [];
        foreach ($query as $x) {
            $gtc[] = array(
                "lil_id" => "lil-" . rand() . time(),
                "laboratory_id" => $x->laboratory_id,
                "management_id" => $x->management_id,
                "order_id" => $x->order_id,
                "category" => $x->category,
                "laborder" => $x->laborder,
                "can_be_discounted" => $x->can_be_discounted,
                "rate" => $x->rate,
                "mobile_rate" => $x->mobile_rate,
                "item_id" => $x->item_id,
                "item" => $x->item,
                "description" => $x->description,
                "supplier" => $x->supplier,
                "status" => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('laboratory_items_laborder')->insert($gtc);

        DB::table('laboratory_items_laborder_tempitems')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->where('order_id', $data['order_id'])
            ->delete();

        return DB::table('laboratory_items_laborder_tempnoitem')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->where('order_id', $data['order_id'])
            ->delete();
    }

    public static function getImagingTestPerson($data)
    {
        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'patients.birthday', 'patients.street', 'patients.barangay', 'patients.city', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_department', 'imaging')
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function getImagingSalesByDate($data)
    {
        date_default_timezone_set('Asia/Manila');

        $date_from = date('Y-m-d 00:00:00', strtotime($data['from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['to']));

        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_department', 'imaging')
            ->where('cashier_patientbills_records.created_at', '>=', $date_from)
            ->where('cashier_patientbills_records.created_at', '<=', $date_to)
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function getLaboratorySalesReport($data)
    {
        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select(DB::raw("IFNULL(patients.firstname, 'UNDEFINED') as firstname"), DB::raw("IFNULL(patients.lastname, 'UNDEFINED') as lastname"), 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_from', 'laboratory')
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function getLaboratorySalesReportByDate($data)
    {
        date_default_timezone_set('Asia/Manila');

        $date_from = date('Y-m-d 00:00:00', strtotime($data['from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['to']));

        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_from', 'laboratory')
            ->where('cashier_patientbills_records.created_at', '>=', $date_from)
            ->where('cashier_patientbills_records.created_at', '<=', $date_to)
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function getOrderByDepartment($data)
    {
        if ($data['department'] == 'doctor') {
            return DB::table('doctors')
                ->where('management_id', $data['management_id'])
                ->orderBy('name', 'ASC')
                ->get();
        }

        if ($data['department'] == 'imaging') {
            return DB::table('imaging_order_menu')
                ->where('management_id', $data['management_id'])
                ->orderBy('order_desc', 'ASC')
                ->get();
        }

        if ($data['department'] == 'laboratory') {
            return DB::table('laboratory_items_laborder')
                ->where('management_id', $data['management_id'])
                ->groupBy('category')
                ->orderBy('category', 'ASC')
                ->get();
        }

    }

    // public static function addPackage($data)
    // {
    //     date_default_timezone_set('Asia/Manila');
    //     if ($data['department'] == "imaging") {
    //         $query = DB::table('imaging_order_menu')->select('order_desc')->where('order_id', $data['imaging_order_id'])->first();
    //         return DB::table('packages_charge_temp')->insert([
    //             'pck_temp_id' => "pti-" . rand(0, 99) . time(),
    //             'management_id' => $data['management_id'],
    //             'department' => $data['department'],
    //             'order_id' => $data['imaging_order_id'],
    //             'order_name' => $query->order_desc,
    //             'status' => 1,
    //             'updated_at' => date('Y-m-d H:i:s'),
    //             'created_at' => date('Y-m-d H:i:s'),
    //         ]);
    //     } elseif ($data['department'] == 'laboratory') {
    //         $query = DB::table('laboratory_items_laborder')->select('laborder', 'category')->where('order_id', $data['order_id'])->first();
    //         return DB::table('packages_charge_temp')->insert([
    //             'pck_temp_id' => "pti-" . rand(0, 99) . time(),
    //             'management_id' => $data['management_id'],
    //             'department' => $data['department'],
    //             'category' => $data['category'],
    //             'order_id' => $data['order_id'],
    //             'order_name' => $query->laborder,
    //             'status' => 1,
    //             'updated_at' => date('Y-m-d H:i:s'),
    //             'created_at' => date('Y-m-d H:i:s'),
    //         ]);
    //     } else {
    //         $query = DB::table('doctors_appointment_services')->select('services')->where('service_id', $data['service_id'])->first();
    //         return DB::table('packages_charge_temp')->insert([
    //             'pck_temp_id' => "pti-" . rand(0, 99) . time(),
    //             'management_id' => $data['management_id'],
    //             'department' => $data['department'],
    //             'order_id' => $data['service_id'],
    //             'order_name' => $query->services,
    //             'status' => 1,
    //             'updated_at' => date('Y-m-d H:i:s'),
    //             'created_at' => date('Y-m-d H:i:s'),
    //         ]);
    //     }

    // }

    public static function addPackage($data)
    {
        date_default_timezone_set('Asia/Manila');
        if ($data['department'] == "imaging") {
            $query = DB::table('imaging_order_menu')->select('order_desc')->where('order_id', $data['imaging_order_id'])->first();
            return DB::table('packages_charge_temp')->insert([
                'pck_temp_id' => "pti-" . rand(0, 99) . time(),
                'management_id' => $data['management_id'],
                'department' => $data['department'],
                'category' => $data['category'],
                'order_id' => $data['imaging_order_id'],
                'order_name' => $query->order_desc,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } elseif ($data['department'] == 'laboratory') {
            $query = DB::table('laboratory_items_laborder')->select('laborder', 'category')->where('order_id', $data['order_id'])->first();
            return DB::table('packages_charge_temp')->insert([
                'pck_temp_id' => "pti-" . rand(0, 99) . time(),
                'management_id' => $data['management_id'],
                'department' => $data['department'],
                'category' => $data['category'],
                'order_id' => $data['order_id'],
                'order_name' => $query->laborder,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } elseif ($data['department'] == 'psychology') {
            $query = DB::table('psychology_test')->select('test')->where('test_id', $data['order_id'])->first();
            return DB::table('packages_charge_temp')->insert([
                'pck_temp_id' => "pti-" . rand(0, 99) . time(),
                'management_id' => $data['management_id'],
                'department' => $data['department'],
                'category' => $data['category'],
                'order_id' => $data['order_id'],
                'order_name' => $query->test,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } elseif ($data['department'] == 'others') {
            $query = DB::table('other_order_test')->select('order_name')->where('order_id', $data['order_id'])->first();
            return DB::table('packages_charge_temp')->insert([
                'pck_temp_id' => "pti-" . rand(0, 99) . time(),
                'management_id' => $data['management_id'],
                'department' => $data['department'],
                'category' => $data['category'],
                'order_id' => $data['order_id'],
                'order_name' => $query->order_name,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $query = DB::table('doctors_appointment_services')->select('services')->where('service_id', $data['service_id'])->first();
            return DB::table('packages_charge_temp')->insert([
                'pck_temp_id' => "pti-" . rand(0, 99) . time(),
                'management_id' => $data['management_id'],
                'department' => $data['department'],
                'category' => $data['category'],
                'order_id' => $data['service_id'],
                'order_name' => $query->services,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

    }

    public static function removePackage($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('packages_charge_temp')
            ->where('pck_temp_id', $data['pck_temp_id'])
            ->where('management_id', $data['management_id'])
            ->delete();
    }

    public static function getAllUnsavePackage($data)
    {
        return DB::table('packages_charge_temp')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function confirmPackage($data)
    {
        date_default_timezone_set('Asia/Manila');
        $unsave = DB::table('packages_charge_temp')->where('management_id', $data['management_id'])->get();
        $package_id = "pck-" . rand(0, 99) . time();
        $confirmed = [];
        foreach ($unsave as $x) {
            $confirmed[] = array(
                'pck_id' => "pck_id-" . rand(0, 99) . time(),
                'package_id' => $package_id,
                'package_name' => $data['package_name'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $x->department,
                'category' => $x->category,
                'order_id' => $x->order_id,
                'order_name' => $x->order_name,
                'order_amount' => $data['order_amount'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('packages_charge')->insert($confirmed);

        return DB::table('packages_charge_temp')
            ->where('management_id', $data['management_id'])
            ->delete();
    }

    public static function getAllConfirmedPackages($data)
    {
        return DB::table('packages_charge')
            ->where('management_id', $data['management_id'])
            ->groupBy('package_id')
            ->get();
    }

    public static function getDetailsPackageById($data)
    {
        return DB::table('packages_charge')
            ->where('management_id', $data['management_id'])
            ->where('package_id', $data['package_id'])
            ->get();
    }

    //7-2-2021
    public static function newLaboratoryItem($data)
    {
        date_default_timezone_set('Asia/Manila');
        $item_id = 'agent-' . rand(0, 9999) . time();
        return DB::table('laboratory_items')
            ->insert([
                'management_id' => $data['management_id'],
                'laboratory_id' => (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id,
                'item_id' => $item_id,
                'item' => $data['item'],
                'description' => $data['description'],
                'item_haptech_id' => $data['product_id'],
                'unit' => $data['unit'],
                'supplier' => $data['supplier'],
                'msrp' => $data['msrp'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function laboratoryItemDeliveryTemp($data)
    {
        date_default_timezone_set('Asia/Manila');

        $item_id = 'agent-' . rand(0, 9999) . time();

        return DB::table('laboratory_items_temp_dr')->insert([
            'management_id' => $data['management_id'],
            'laboratory_id' => (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id,
            'item_id' => $data['item_id'],
            'batch_number' => $data['batch_number'],
            'qty' => $data['qty'],
            'mfg_date' => date('Y-m-d', strtotime($data['mfg_date'])),
            'expiration_date' => date('Y-m-d', strtotime($data['expiration_date'])),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function laboratoryItemDeliveryTempProcess($data)
    {
        date_default_timezone_set('Asia/Manila');

        $items = DB::table('laboratory_items_temp_dr')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->get();

        $gtc = [];
        foreach ($items as $v) {
            $gtc[] = array(
                'lrm_id' => 'lrm-' . time() . rand(0, 99958),
                'item_id' => $v->item_id,
                'management_id' => $v->management_id,
                'laboratory_id' => $v->laboratory_id,
                'qty' => $v->qty,
                'dr_number' => $data['dr_number'],
                'batch_number' => $v->batch_number,
                'mfg_date' => $v->mfg_date,
                'expiration_date' => $v->expiration_date,
                'type' => 'IN',
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('laboratory_items_monitoring')->insert($gtc);

        return DB::table('laboratory_items_temp_dr')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Accounting)::getLaboratoryIdByManagement($data)->laboratory_id)
            ->delete();
    }

    public static function getForApprovalInvoice($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('management_id', $data['management_id'])
            ->where('type', 'IN')
            ->groupBy('invoice_number')
            ->get();
    }

    public static function getForApprovalInvoiceDetails($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('management_id', $data['management_id'])
            ->where('invoice_number', $data['invoice_number'])
            ->get();
    }

    public static function getForApprovalDr($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('management_id', $data['management_id'])
            ->where('type', 'OUT')
            ->groupBy('dr_number')
            ->get();
    }

    public static function getForApprovalDrDetails($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('management_id', $data['management_id'])
            ->where('dr_number', $data['dr_number'])
            ->get();
    }

    public static function drApprovedByAccounting($data)
    {

        date_default_timezone_set('Asia/Manila');

        $products = DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('management_id', $data['management_id'])
            ->where('dr_number', $data['dr_number'])
            ->where('type', 'OUT')
            ->get();

        $dritems = [];
        foreach ($products as $v) {
            $dritems[] = array(
                'pwi_id' => 'pwif-' . rand() . time(),
                'warehouse_id' => $v->warehouse_id,
                'management_id' => $v->management_id,
                'product_id' => $v->product_id,
                'product_name' => $v->product_name,
                'product_generic' => $v->product_generic,
                'unit' => $v->unit,
                'msrp' => $v->msrp,
                'srp' => $v->srp,
                'qty' => $v->qty,
                'batch_number' => $v->batch_number,
                'invoice_number' => $v->product_id,
                'dr_number' => $data['dr_number'],
                'dr_totalamount' => $data['dr_totalamount'],
                'dr_account' => $data['dr_account'],
                'dr_accountname' => $data['dr_accountname'],
                'dr_accountaddress' => $data['dr_accountaddress'],
                'dr_date' => date('Y-m-d H:i:s'),
                'expiration_date' => $v->expiration_date,
                'manufactured_date' => $v->manufactured_date,
                'type' => $v->type,
                'status' => $v->status,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at,
            );
        }

        DB::table("pharmacyclinic_warehouse_inventory")->insert($dritems);

        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('management_id', $data['management_id'])
            ->where('dr_number', $data['dr_number'])
            ->where('type', 'OUT')
            ->delete();
    }

    public static function invoiceApprovedByAccounting($data)
    {

        date_default_timezone_set('Asia/Manila');

        $tempProducts = DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('management_id', $data['management_id'])
            ->where('invoice_number', $data['invoice_number'])
            ->where('type', 'IN')
            ->get();

        $products = [];
        foreach ($tempProducts as $v) {
            $products[] = array(
                'pwi_id' => 'pwi-' . rand(0, 9999) . time(),
                'warehouse_id' => $v->warehouse_id,
                'management_id' => $v->management_id,
                'product_id' => $v->product_id,
                'product_name' => $v->product_name,
                'product_generic' => $v->product_generic,
                'unit' => $v->unit,
                'msrp' => $v->msrp,
                'srp' => $v->srp,
                'qty' => $v->qty,
                'batch_number' => $v->batch_number,
                'expiration_date' => $v->expiration_date,
                'manufactured_date' => $v->manufactured_date,
                'invoice_number' => $data['invoice_number'],
                'delivered_date' => $v->delivered_date,
                'delivered_by' => $v->delivered_by,
                'type' => $v->type,
                'status' => $v->status,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at,
            );
        }
        // insert to payables
        DB::table('accounting_payable')->insert([
            'ap_id' => 'ap-' . rand(0, 9999) . time(),
            'management_id' => $data['management_id'],
            'invoice_number' => $data['invoice_number'],
            'invoice_amount' => $data['totalInvoice'],
            'invoice_status' => 'unpaid',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('pharmacyclinic_warehouse_inventory')->insert($products);

        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('management_id', $data['management_id'])
            ->where('invoice_number', $data['invoice_number'])
            ->where('type', 'IN')
            ->delete();
    }

    public static function newWarehouseProducts($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('pharmacyclinic_warehose_products')->insert([
            'pwp_id' => 'pwp-' . rand(0, 9999) . time(),
            'warehouse_id' => _Accounting::getWarehouseIdByManagement($data)->warehouse_id,
            'management_id' => $data['management_id'],
            'product_id' => 'product-' . rand(0, 9999) . time(),
            'product_name' => $data['product_name'],
            'product_generic' => $data['product_generic'],
            'unit' => $data['unit'],
            'msrp' => $data['msrp'],
            'srp' => $data['srp'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getProductListInWarehouse($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
            ->where('management_id', $data['management_id'])
            ->where('warehouse_id', _Accounting::getWarehouseIdByManagement($data)->warehouse_id)
            ->get();
    }

    public static function getMonitoringList($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory')
            ->where('warehouse_id', _Accounting::getWarehouseIdByManagement($data)->warehouse_id)
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function getCompanyAccreditedList($data)
    {
        return DB::table('management_accredited_companies')
            ->where('management_id', $data['management_id'])
            ->orderBy('company', 'asc')
            ->get();
    }

    public static function saveCompanyAccredited($data)
    {
        date_default_timezone_set('Asia/Manila');
        $company_id = 'company-' . rand(0, 999) . time();
        $hmo_name = $data['hmo_name'];
        $hmonew = [];

        if (!empty($data['hmo_name'])) {
            for ($i = 0; $i < count($hmo_name); $i++) {
                $hmonew[] = array(
                    'mach_id' => 'mach-' . rand(0, 9999) . time(),
                    'company_id' => $company_id,
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'hmo' => $hmo_name[$i],
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                );
            }
        }

        DB::table('management_accredited_company_hmo')->insert($hmonew);

        return DB::table('management_accredited_companies')->insert([
            'mac_id' => 'mac-' . rand(0, 9999) . time(),
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'company_id' => $company_id,
            'company' => $data['company'],
            'address' => $data['address'],
            'tin' => $data['tin'],
            'contact_person' => $data['contact_person'],
            'contact' => $data['contact'],
            'contact_position' => $data['contact_position'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function removeCompanyAccredited($data)
    {
        return DB::table('management_accredited_companies')->where('id', $data['remove_id'])->delete();
    }

    public static function editCompanyAccredited($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::table('management_accredited_companies')->where('id', $data['edit_id'])->update([
            'company' => $data['company'],
            'address' => $data['address'],
            'tin' => $data['tin'],
            'contact' => $data['contact'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function addNewBankAccount($data)
    {
        date_default_timezone_set('Asia/Manila');
        $bank_id = 'bank-' . rand(0, 9999) . time();
        $contact_person = $data['contact_person'];
        $contact_number = $data['contact_number'];
        $contact_position = $data['contact_position'];
        $bnknew = [];

        if (!empty($data['contact_person']) && $data['contact_number'] != null) {
            for ($i = 0; $i < count($contact_person); $i++) {
                $bnknew[] = array(
                    'cbc_id' => 'cbc-' . rand(0, 9999) . time(),
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'bank_id' => $bank_id,
                    'contact_person' => $contact_person[$i],
                    'contact_number' => $contact_number[$i],
                    'contact_position' => $contact_position[$i],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                );
            }
        }

        DB::table('clinic_bank_contacts')->insert($bnknew);

        return DB::table('clinic_bank')->insert([
            'bank_id' => $bank_id,
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'bank_name' => $data['bank_name'],
            'bank_address' => $data['bank_address'],
            'bank_account_no' => $data['bank_account_no'],
            'added_by' => $data['user_id'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getBankAccountList($data)
    {
        $mgn = $data['management_id'];
        $query = " SELECT *, bank_id as value, bank_name as label,
            (SELECT IFNULL(sum(amount),0) from clinic_bank_transaction where category = 'Deposit' AND clinic_bank_transaction.bank_id = clinic_bank.bank_id ) as total_bank_deposit,
            (SELECT IFNULL(sum(amount),0) from clinic_bank_transaction where category = 'Withdrawal' AND clinic_bank_transaction.bank_id = clinic_bank.bank_id ) as total_bank_withdrawal,
            (select IFNULL(sum(total_bank_deposit-total_bank_withdrawal), 0)) as total_bank_balance
        from clinic_bank where management_id = '$mgn' ORDER BY bank_name ASC ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getContactList($data)
    {
        return DB::table('clinic_bank_contacts')
            ->where('management_id', $data['management_id'])
            ->where('bank_id', $data['bank_id'])
            ->orderBy('contact_person', 'ASC')
            ->get();
    }

    public static function editContactInfo($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('clinic_bank_contacts')->where('cbc_id', $data['cbc_id'])
            ->update([
                'contact_person' => $data['contact_person'],
                'contact_number' => $data['contact_number'],
                'contact_position' => $data['contact_position'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function editBankInfo($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('clinic_bank')->where('bank_id', $data['bank_id'])
            ->update([
                'bank_name' => $data['bank_name'],
                'bank_address' => $data['bank_address'],
                'bank_account_no' => $data['bank_account_no'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function removeBankAccount($data)
    {
        return DB::table('clinic_bank')
            ->where('bank_id', $data['bank_id'])
            ->update([
                'reason' => $data['reason'],
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        // $countresult = DB::table('clinic_bank_contacts')
        // ->where('bank_id', $data['bank_id'])
        // ->get();

        // if(count($countresult) > 0){
        //     DB::table('clinic_bank_contacts')
        //     ->where('bank_id', $data['bank_id'])
        //     ->delete();
        // }

        // return DB::table('clinic_bank')
        // ->where('bank_id', $data['bank_id'])
        // ->delete();
    }

    public static function getBankDetailsById($data)
    {
        $bank_id = $data['bank_id'];
        $query = "SELECT *,
            (SELECT IFNULL(sum(amount), 0) from clinic_bank_transaction where clinic_bank_transaction.bank_id = clinic_bank.bank_id and category = 'Deposit') as bank_deposit,
            (SELECT IFNULL(sum(amount), 0) from clinic_bank_transaction where clinic_bank_transaction.bank_id = clinic_bank.bank_id and category = 'Withdrawal') as bank_withdrawal
        FROM clinic_bank where bank_id = '$bank_id' LIMIT 1 ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function addNewDeposit($data)
    {
        return DB::table('clinic_bank_transaction')
            ->insert([
                'trans_id' => 'trans-' . rand(0, 9999) . time(),
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'account' => 'BMCDC Deposit',
                'amount' => $data['deposit_amount'],
                'payment_type' => $data['deposit_type'],
                'date_transact' => date('Y-m-d H:i:s', strtotime($data['date_deposit'])),
                'check_date' => $data['deposit_type'] == 'Check' ? date('Y-m-d H:i:s', strtotime($data['check_date'])) : null,
                'check_number' => $data['deposit_type'] == 'Check' ? $data['check_no'] : null,
                'bank_name' => $data['deposit_type'] == 'Check' ? $data['bank'] : null,
                'bank_id' => $data['bank_id'],
                'authorized_person' => $data['deposit_by'],
                'added_by' => $data['user_id'],
                'note' => $data['deposit_note'],
                'category' => 'Deposit',
                'transaction_type' => 'DIRECT-DEPOSIT',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function addNewWithdrawal($data)
    {
        return DB::table('clinic_bank_transaction')
            ->insert([
                'trans_id' => 'trans-' . rand(0, 9999) . time(),
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'account' => 'BMCDC Withdrawal',
                'amount' => $data['withdrawal_amount'],
                'payment_type' => $data['withdrawal_type'],
                'date_transact' => date('Y-m-d H:i:s', strtotime($data['date_withdrawal'])),
                'check_date' => $data['withdrawal_type'] == 'Check' ? date('Y-m-d H:i:s', strtotime($data['check_date'])) : null,
                'check_number' => $data['withdrawal_type'] == 'Check' ? $data['check_no'] : null,
                'bank_name' => $data['withdrawal_type'] == 'Check' ? $data['bank'] : null,
                'bank_id' => $data['bank_id'],
                'authorized_person' => null,
                'added_by' => $data['user_id'],
                'note' => $data['withdrawal_note'],
                'category' => 'Withdrawal',
                'transaction_type' => 'DIRECT-WITHDRAWAL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function addNewExpense($data)
    {
        $amount = $data['amount'];
        $expense_item = null;
        if ($data['expense_item'] === "BIR/Taxes/SSS/PHIC/PAG-IBIG") {
            $expense_item = 'government';
        }
        if ($data['expense_item'] === "Communication/Internet") {
            $expense_item = 'communication';
        }
        if ($data['expense_item'] === "Construction/Office Materials") {
            $expense_item = 'materials';
        }
        if ($data['expense_item'] === "Entertainment/Representation") {
            $expense_item = 'entertainment';
        }
        if ($data['expense_item'] === "Gasoline/Repair/Maintenance") {
            $expense_item = 'maintenance';
        }
        if ($data['expense_item'] === "Hotel") {
            $expense_item = 'hotel';
        }
        if ($data['expense_item'] === "Incentives/Rebate") {
            $expense_item = 'incentives';
        }
        if ($data['expense_item'] === "Insurance/Credit Card/Loan") {
            $expense_item = 'insurance';
        }
        if ($data['expense_item'] === "Meals") {
            $expense_item = 'meals';
        }
        if ($data['expense_item'] === "Office Supply") {
            $expense_item = 'office_supply';
        }
        if ($data['expense_item'] === "Others") {
            $expense_item = 'others';
        }
        if ($data['expense_item'] === "Retainer's Fee") {
            $expense_item = 'retainers';
        }
        if ($data['expense_item'] === "Salary") {
            $expense_item = 'salary';
        }
        if ($data['expense_item'] === "Solicitation") {
            $expense_item = 'solicitation';
        }
        if ($data['expense_item'] === "Transportation") {
            $expense_item = 'transporation';
        }

        return DB::table('clinic_expenses')
            ->insert([
                'expense_id' => 'eid-' . rand(0, 9999) . time(),
                'management_id' => $data['branch'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'date_expense' => date('Y-m-d H:i:s', strtotime($data['expense_date'])),
                'official_receipt' => $data['or_number'],
                'account_number' => $data['account_number'],
                'nonvat_vat' => $data['vat'],
                'expense_category' => 'BMCDC',
                'expense_tin' => $data['tin'],
                'merchant' => $data['merchant'],
                $expense_item => $amount,
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getDepositList($data)
    {
        $bank_id = $data['bank_id'];
        $query = "SELECT * FROM clinic_bank_transaction where category = 'Deposit' and bank_id = '$bank_id' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getWithdrawalList($data)
    {
        $bank_id = $data['bank_id'];
        $query = "SELECT * FROM clinic_bank_transaction where category = 'Withdrawal' and bank_id = '$bank_id' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getExpenseList($data)
    {
        $management_id = $data['management_id'];
        $main_mgmt_id = $data['main_mgmt_id'];

        if ($data['management_name'] == "BMCDC HQ") {
            $query = "SELECT *,
                (SELECT IFNULL(SUM(quantity * unit_price), 0) FROM clinic_expenses_list WHERE main_mgmt_id = '$main_mgmt_id' ) as totalExpense
            FROM clinic_expenses_list where main_mgmt_id = '$main_mgmt_id' GROUP BY voucher ORDER BY invoice_date DESC ";
            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        } else {
            $query = "SELECT *,
                (SELECT IFNULL(SUM(quantity * unit_price), 0) FROM clinic_expenses_list WHERE management_id = '$management_id' ) as totalExpense
            FROM clinic_expenses_list where management_id = '$management_id' GROUP BY voucher ORDER BY invoice_date DESC ";
            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        }

        // if($data['management_name'] == "BMCDC HQ"){
        //     $query = "SELECT *,

        //         (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_government,
        //         (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_communication,
        //         (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_materials,
        //         (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_entertainment,
        //         (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_maintenance,
        //         (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_hotel,
        //         (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_incentives,
        //         (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_insurance,
        //         (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_meals,
        //         (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_office_supply,
        //         (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_others,
        //         (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_retainers,
        //         (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_salary,
        //         (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_solicitation,
        //         (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_transporation,
        //         (SELECT SUM(total_government+total_communication+total_materials+total_entertainment+total_maintenance+total_hotel+total_incentives+total_insurance+total_meals+total_office_supply+total_others+total_retainers+total_salary+total_solicitation+total_transporation * 1)) as totalExpense

        //     FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' ";
        //     $result = DB::getPdo()->prepare($query);
        //     $result->execute();
        //     return $result->fetchAll(\PDO::FETCH_OBJ);
        // }else{
        //     $query = "SELECT *,

        //         (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_government,
        //         (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_communication,
        //         (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_materials,
        //         (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_entertainment,
        //         (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_maintenance,
        //         (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_hotel,
        //         (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_incentives,
        //         (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_insurance,
        //         (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_meals,
        //         (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_office_supply,
        //         (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_others,
        //         (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_retainers,
        //         (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_salary,
        //         (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_solicitation,
        //         (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_transporation,
        //         (SELECT SUM(total_government+total_communication+total_materials+total_entertainment+total_maintenance+total_hotel+total_incentives+total_insurance+total_meals+total_office_supply+total_others+total_retainers+total_salary+total_solicitation+total_transporation * 1)) as totalExpense

        //     FROM clinic_expenses WHERE management_id = '$management_id' ";
        //     $result = DB::getPdo()->prepare($query);
        //     $result->execute();
        //     return $result->fetchAll(\PDO::FETCH_OBJ);
        // }
    }

    public static function getFilterSearch($data)
    {
        $management_id = $data['management_id'];
        $main_mgmt_id = $data['main_mgmt_id'];
        $vat = $data['vat'];

        $date_from = date('Y-m-d', strtotime($data['date_from']));
        $date_to = date('Y-m-d', strtotime($data['date_to']));

        if ($data['management_name'] == "BMCDC HQ") {
            if ($data['vat'] == 'All') {
                if ($data['expense_item'] == 'All') {

                    $query = "SELECT *,
                        (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_government,
                        (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_communication,
                        (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_materials,
                        (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_entertainment,
                        (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_maintenance,
                        (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_hotel,
                        (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_incentives,
                        (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_insurance,
                        (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_meals,
                        (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_office_supply,
                        (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_others,
                        (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_retainers,
                        (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_salary,
                        (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_solicitation,
                        (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_transporation,
                        (SELECT SUM(total_government+total_communication+total_materials+total_entertainment+total_maintenance+total_hotel+total_incentives+total_insurance+total_meals+total_office_supply+total_others+total_retainers+total_salary+total_solicitation+total_transporation * 1)) as totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND `date_expense` BETWEEN '$date_from' and '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'BIR/Taxes/SSS/PHIC/PAG-IBIG') {

                    $query = "SELECT *,
                        (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_government,

                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_government)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND government IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Communication/Internet') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,

                        (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_communication,

                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_communication)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND communication IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Construction/Office Materials') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,

                        (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_materials,

                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_materials)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND materials IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Entertainment/Representation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,

                        (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_entertainment,

                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_entertainment)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND entertainment IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Gasoline/Repair/Maintenance') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,

                        (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_maintenance,

                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_maintenance)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND maintenance IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Hotel') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,

                        (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_hotel,

                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_hotel)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND hotel IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Incentives/Rebate') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,

                        (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_incentives,

                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_incentives)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND incentives IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Insurance/Credit Card/Loan') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,

                        (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_insurance,

                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_insurance)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND insurance IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Meals') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,

                        (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_meals,

                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_meals)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND meals IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Office Supply') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,

                        (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_office_supply,

                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_office_supply)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND office_supply IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Others') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,

                        (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_others,

                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_others)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND others IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == "Retainer's Fee") {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,

                        (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_retainers,

                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_retainers)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND retainers IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Salary') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,

                        (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_salary,

                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_salary)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND salary IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Solicitation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,

                        (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_solicitation,

                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_solicitation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND solicitation IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Transportation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,

                        (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_transporation,

                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND transporation IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }
            } else {
                if ($data['expense_item'] == 'All') {

                    $query = "SELECT *,
                        (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_government,
                        (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_communication,
                        (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_materials,
                        (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_entertainment,
                        (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_maintenance,
                        (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_hotel,
                        (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_incentives,
                        (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_insurance,
                        (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_meals,
                        (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_office_supply,
                        (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_others,
                        (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_retainers,
                        (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_salary,
                        (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_solicitation,
                        (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_transporation,
                        (SELECT SUM(total_government+total_communication+total_materials+total_entertainment+total_maintenance+total_hotel+total_incentives+total_insurance+total_meals+total_office_supply+total_others+total_retainers+total_salary+total_solicitation+total_transporation * 1)) as totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'BIR/Taxes/SSS/PHIC/PAG-IBIG') {

                    $query = "SELECT *,
                        (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_government,

                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND government IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Communication/Internet') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,

                        (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_communication,

                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND communication IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Construction/Office Materials') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,

                        (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_materials,

                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND materials IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Entertainment/Representation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,

                        (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_entertainment,

                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND entertainment IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Gasoline/Repair/Maintenance') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,

                        (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_maintenance,

                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND maintenance IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Hotel') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,

                        (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_hotel,

                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND hotel IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Incentives/Rebate') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,

                        (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_incentives,

                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND incentives IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Insurance/Credit Card/Loan') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,

                        (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_insurance,

                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND insurance IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Meals') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,

                        (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_meals,

                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND meals IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Office Supply') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,

                        (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_office_supply,

                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND office_supply IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Others') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,

                        (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_others,

                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND others IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == "Retainer's Fee") {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,

                        (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_retainers,

                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND retainers IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Salary') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,

                        (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_salary,

                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND salary IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Solicitation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,

                        (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_solicitation,

                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND solicitation IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Transportation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,

                        (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_transporation,

                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE main_mgmt_id = '$main_mgmt_id' AND nonvat_vat = '$vat' AND transporation IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));
                }
            }
        } else {
            if ($data['vat'] == 'All') {
                if ($data['expense_item'] == 'All') {

                    $query = "SELECT *,
                        (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_government,
                        (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_communication,
                        (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_materials,
                        (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_entertainment,
                        (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_maintenance,
                        (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_hotel,
                        (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_incentives,
                        (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_insurance,
                        (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_meals,
                        (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_office_supply,
                        (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_others,
                        (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_retainers,
                        (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_salary,
                        (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_solicitation,
                        (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_transporation,
                        (SELECT SUM(total_government+total_communication+total_materials+total_entertainment+total_maintenance+total_hotel+total_incentives+total_insurance+total_meals+total_office_supply+total_others+total_retainers+total_salary+total_solicitation+total_transporation * 1)) as totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND `date_expense` BETWEEN '$date_from' and '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'BIR/Taxes/SSS/PHIC/PAG-IBIG') {

                    $query = "SELECT *,
                        (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_government,

                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_government)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND government IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Communication/Internet') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,

                        (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_communication,

                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_communication)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND communication IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Construction/Office Materials') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,

                        (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_materials,

                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_materials)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND materials IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Entertainment/Representation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,

                        (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_entertainment,

                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_entertainment)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND entertainment IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Gasoline/Repair/Maintenance') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,

                        (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_maintenance,

                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_maintenance)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND maintenance IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Hotel') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,

                        (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_hotel,

                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_hotel)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND hotel IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Incentives/Rebate') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,

                        (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_incentives,

                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_incentives)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND incentives IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Insurance/Credit Card/Loan') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,

                        (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_insurance,

                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_insurance)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND insurance IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Meals') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,

                        (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_meals,

                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_meals)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND meals IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Office Supply') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,

                        (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_office_supply,

                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_office_supply)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND office_supply IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Others') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,

                        (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_others,

                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_others)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND others IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == "Retainer's Fee") {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,

                        (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_retainers,

                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_retainers)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND retainers IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Salary') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,

                        (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_salary,

                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_salary)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND salary IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Solicitation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,

                        (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_solicitation,

                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_solicitation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND solicitation IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Transportation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,

                        (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id and `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_transporation,

                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND transporation IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }
            } else {
                if ($data['expense_item'] == 'All') {

                    $query = "SELECT *,
                        (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_government,
                        (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_communication,
                        (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_materials,
                        (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_entertainment,
                        (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_maintenance,
                        (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_hotel,
                        (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_incentives,
                        (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_insurance,
                        (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_meals,
                        (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_office_supply,
                        (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_others,
                        (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_retainers,
                        (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_salary,
                        (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_solicitation,
                        (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' AND '$date_to' ) AS total_transporation,
                        (SELECT SUM(total_government+total_communication+total_materials+total_entertainment+total_maintenance+total_hotel+total_incentives+total_insurance+total_meals+total_office_supply+total_others+total_retainers+total_salary+total_solicitation+total_transporation * 1)) as totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'BIR/Taxes/SSS/PHIC/PAG-IBIG') {

                    $query = "SELECT *,
                        (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_government,

                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND government IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Communication/Internet') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,

                        (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_communication,

                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND communication IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Construction/Office Materials') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,

                        (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_materials,

                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND materials IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Entertainment/Representation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,

                        (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_entertainment,

                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND entertainment IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Gasoline/Repair/Maintenance') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,

                        (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_maintenance,

                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND maintenance IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Hotel') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,

                        (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_hotel,

                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND hotel IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Incentives/Rebate') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,

                        (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_incentives,

                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND incentives IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Insurance/Credit Card/Loan') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,

                        (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_insurance,

                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND insurance IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Meals') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,

                        (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_meals,

                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND meals IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Office Supply') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,

                        (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_office_supply,

                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND office_supply IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Others') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,

                        (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_others,

                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND others IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == "Retainer's Fee") {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,

                        (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_retainers,

                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND retainers IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Salary') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,

                        (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_salary,

                        (SELECT SUM(0.00 * 1)) AS total_solicitation,
                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND salary IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Solicitation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,

                        (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_solicitation,

                        (SELECT SUM(0.00 * 1)) AS total_transporation,
                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND solicitation IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));

                }if ($data['expense_item'] == 'Transportation') {

                    $query = "SELECT *,
                        (SELECT SUM(0.00 * 1)) AS total_government,
                        (SELECT SUM(0.00 * 1)) AS total_communication,
                        (SELECT SUM(0.00 * 1)) AS total_materials,
                        (SELECT SUM(0.00 * 1)) AS total_entertainment,
                        (SELECT SUM(0.00 * 1)) AS total_maintenance,
                        (SELECT SUM(0.00 * 1)) AS total_hotel,
                        (SELECT SUM(0.00 * 1)) AS total_incentives,
                        (SELECT SUM(0.00 * 1)) AS total_insurance,
                        (SELECT SUM(0.00 * 1)) AS total_meals,
                        (SELECT SUM(0.00 * 1)) AS total_office_supply,
                        (SELECT SUM(0.00 * 1)) AS total_others,
                        (SELECT SUM(0.00 * 1)) AS total_retainers,
                        (SELECT SUM(0.00 * 1)) AS total_salary,
                        (SELECT SUM(0.00 * 1)) AS total_solicitation,

                        (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND nonvat_vat = '$vat' AND `date_expense` BETWEEN '$date_from' and '$date_to' ) AS total_transporation,

                        (SELECT SUM(total_transporation)) AS totalExpense
                    FROM clinic_expenses WHERE management_id = '$management_id' AND nonvat_vat = '$vat' AND transporation IS NOT NULL AND `date_expense` BETWEEN '$date_from' AND '$date_to' ORDER BY date_expense ASC";
                    return DB::select(DB::raw($query));
                }
            }
        }
    }

    public static function getReceivableList($data)
    {
        $management_id = $data['management_id'];
        $query = "SELECT *,

            (SELECT concat(lastname,', ', firstname) from patients where patients.patient_id = cashier_patientbills_records.patient_id limit 1) as patient_name,
            (SELECT company from patients where patients.patient_id = cashier_patientbills_records.patient_id limit 1) as companyId,
            (SELECT company from management_accredited_companies where management_accredited_companies.company_id = companyId limit 1) as companyName

        FROM cashier_patientbills_records WHERE management_id = '$management_id' AND is_charged = 1 GROUP BY trace_number ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getProductInventory($data)
    {
        $mgt = $data['management_id'];

        $query = "SELECT *, product_id as _pid,
        (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = _pid and type='IN') as _qty_in,
        (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = _pid and type='OUT') as _qty_out,
        (SELECT _qty_in - _qty_out ) as _qty_remaining
        from  pharmacyclinic_warehose_products where management_id = '$mgt'";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getBranchStockroomInventory($data)
    {
        $mgt = $data['management_id'];

        $query = "SELECT *, product_id as _pid,
        (SELECT IFNULL(sum(qty), 0) from stockroom_account_products where product_id = _pid and type='IN') as _qty_in,
        (SELECT IFNULL(sum(qty), 0) from stockroom_account_products where product_id = _pid and type='OUT') as _qty_out,
        (SELECT srp from pharmacyclinic_warehose_products where product_id = _pid) as _srp,
        (SELECT _qty_in - _qty_out ) as _qty_remaining
        from  stockroom_account_products where management_id = '$mgt' group by product_id";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getBranchLaboratoryInventory($data)
    {
        $mgt = $data['management_id'];

        $query = "SELECT *, item_id as _itemId,
        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = _itemId and type='IN') as _qty_in,
        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = _itemId and type='OUT') as _qty_out,
        (SELECT _qty_in - _qty_out ) as _qty_remaining

        from laboratory_items where management_id = '$mgt'";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getBranchSales($data)
    {
        $management_id = $data['management_id'];
        $main_mgmt_id = $data['main_mgmt_id'];

        if ($management_id == 'hq-management') {
            return DB::table('cashier_patientbills_records')
                ->where('main_mgmt_id', $main_mgmt_id)
                ->where('bill_from', $data['type'])
                ->get();
        } else {
            return DB::table('cashier_patientbills_records')
                ->where('management_id', $management_id)
                ->where('bill_from', $data['type'])
                ->get();
        }
    }

    public static function getPayableList($data)
    {
        return DB::table('accounting_payable')
            ->where('management_id', $data['management_id'])
            ->where('invoice_status', $data['type'])
            ->get();
    }

    public static function getTotalResources($data)
    {
        $mgt = $data['management_id'];
        $main_mgmt_id = $data['main_mgmt_id'];
        $branch_name = $data['branch_name'];

        if ($branch_name == 'hq-management') {
            $query = "SELECT *,
                (SELECT IFNULL(SUM(amount), 0) FROM clinic_bank_transaction WHERE category = 'Deposit' AND main_mgmt_id = '$main_mgmt_id') as bank_deposit,
                (SELECT IFNULL(SUM(amount), 0) FROM clinic_bank_transaction WHERE category = 'Withdrawal' AND main_mgmt_id = '$main_mgmt_id') as bank_withdrawal,
                (SELECT SUM(bank_deposit-bank_withdrawal * 1)) as totalBank,

                (SELECT IFNULL(SUM(bill_amount), 0) FROM cashier_patientbills_records WHERE is_charged = 1 AND bill_from = 'packages') as totalReceivable,

                (SELECT IFNULL(SUM(bill_amount), 0) FROM cashier_patientbills_records WHERE is_charged = 2 AND bill_from = 'packages') as totalCollection,

                (SELECT IFNULL(SUM(invoice_amount), 0) FROM accounting_payable WHERE invoice_status <> 'paid') as totalPayable,

                (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_government,
                (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_communication,
                (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_materials,
                (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_entertainment,
                (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_maintenance,
                (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_hotel,
                (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_incentives,
                (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_insurance,
                (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_meals,
                (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_office_supply,
                (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_others,
                (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_retainers,
                (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_salary,
                (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_solicitation,
                (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND main_mgmt_id = '$main_mgmt_id') AS total_transporation,
                (SELECT SUM(total_government+total_communication+total_materials+total_entertainment+total_maintenance+total_hotel+total_incentives+total_insurance+total_meals+total_office_supply+total_others+total_retainers+total_salary+total_solicitation+total_transporation * 1)) as totalExpense

            from clinic_expenses ";

            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        } else {
            $query = "SELECT *,
                (SELECT IFNULL(SUM(amount), 0) FROM clinic_bank_transaction WHERE category = 'Deposit' AND management_id = '$branch_name') as bank_deposit,
                (SELECT IFNULL(SUM(amount), 0) FROM clinic_bank_transaction WHERE category = 'Withdrawal' AND management_id = '$branch_name') as bank_withdrawal,
                (SELECT SUM(bank_deposit-bank_withdrawal * 1)) as totalBank,

                (SELECT IFNULL(SUM(bill_amount), 0) FROM cashier_patientbills_records WHERE is_charged = 1 AND bill_from = 'packages') as totalReceivable,

                (SELECT IFNULL(SUM(bill_amount), 0) FROM cashier_patientbills_records WHERE is_charged = 2 AND bill_from = 'packages') as totalCollection,

                (SELECT IFNULL(SUM(invoice_amount), 0) FROM accounting_payable WHERE invoice_status <> 'paid') as totalPayable,

                (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_government,
                (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_communication,
                (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_materials,
                (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_entertainment,
                (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_maintenance,
                (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_hotel,
                (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_incentives,
                (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_insurance,
                (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_meals,
                (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_office_supply,
                (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_others,
                (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_retainers,
                (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_salary,
                (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_solicitation,
                (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id AND management_id = '$branch_name') AS total_transporation,
                (SELECT SUM(total_government+total_communication+total_materials+total_entertainment+total_maintenance+total_hotel+total_incentives+total_insurance+total_meals+total_office_supply+total_others+total_retainers+total_salary+total_solicitation+total_transporation * 1)) as totalExpense

            from clinic_expenses ";

            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        }

    }

    public static function setAsPaidByCompany($data)
    {
        $patientList = DB::table('patients')
            ->select('patient_id')
            ->where('company', $data['company_id'])
            ->get();

        foreach ($patientList as $v) {
            DB::table('cashier_patientbills_records')
                ->where('patient_id', $v->patient_id)
                ->where('hmo_category', $data['hmo_category'])
                ->where('is_charged', 1)
                ->update([
                    'is_charged_paid' => 1,
                    'is_charged' => 2,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
        return true;
    }

    public static function payableInvoicePayment($data)
    {
        date_default_timezone_set('Asia/Manila');

        // insert transaction to bank
        DB::table('clinic_bank_transaction')
            ->insert([
                'trans_id' => 'trans-' . rand(0, 9999) . time(),
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'account' => 'BMCDC Supplier',
                'amount' => $data['amounto_pay'],
                'payment_type' => $data['payment_type'],
                'date_transact' => date('Y-m-d H:i:s'),
                'check_date' => $data['payment_type'] == 'Check' ? date('Y-m-d H:i:s', strtotime($data['check_date'])) : null,
                'check_number' => $data['payment_type'] == 'Check' ? $data['check_no'] : null,
                'bank_name' => $data['payment_type'] == 'Check' ? $data['bank_name'] : null,
                'bank_id' => $data['bank'],
                'authorized_person' => $data['user_id'],
                'added_by' => $data['user_id'],
                'note' => $data['remarks'],
                'category' => 'Withdrawal',
                'transaction_type' => 'INVOICE-WITHDRAWAL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::table("accounting_payable")
            ->where('invoice_number', $data['invoice_number'])
            ->update([
                'invoice_status' => 'paid',
                'updated_at' => date("Y-m-d, h:i:s"),
            ]);
    }

    public static function getCollectionList($data)
    {
        $management_id = $data['management_id'];
        $query = "SELECT *,

            (SELECT concat(lastname,', ', firstname) from patients where patients.patient_id = cashier_patientbills_records.patient_id limit 1) as patient_name,
            (SELECT company from patients where patients.patient_id = cashier_patientbills_records.patient_id limit 1) as companyId,
            (SELECT company from management_accredited_companies where management_accredited_companies.company_id = companyId limit 1) as companyName

        FROM cashier_patientbills_records WHERE management_id = '$management_id' AND is_charged = 2 AND bill_from = 'packages' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getAllBraches($data)
    {
        return DB::table('general_management_branches')
            ->join('management', 'management.management_id', '=', 'general_management_branches.management_id')
            ->where('general_management_branches.general_management_id', $data['main_management_id'])
            ->get();
    }

    public static function getSalesGrandTotalAmount($data)
    {
        $management_mainId = DB::table('general_management_branches')->where('management_id', $data['management_id'])->get();
        if (count($management_mainId) > 0) {

            $all_mngt = DB::table('general_management_branches')->where('general_management_id', $management_mainId[0]->general_management_id)->get();

            $xx = [];

            foreach ($all_mngt as $v) {

                $gt = $v->management_id;
                $query = "SELECT * from  cashier_patientbills_records where management_id = '$gt' and is_refund is null or is_refund = 0 and management_id = '$gt'";
                // $sales = DB::table('cashier_patientbills_records')->where('management_id', $v->management_id)
                $result = DB::getPdo()->prepare($query);
                $result->execute();
                $sales = $result->fetchAll(\PDO::FETCH_OBJ);

                $xx[] = $sales;
            }

            return $xx;
        } else {
            return [];
        }
    }

    public static function getInventoryGrandTotalAmountLaboratory($data)
    {
        $management_mainId = DB::table('general_management_branches')->where('management_id', $data['management_id'])->get();
        if (count($management_mainId) > 0) {

            $all_mngt = DB::table('general_management_branches')->where('general_management_id', $management_mainId[0]->general_management_id)->get();

            $xx = [];

            foreach ($all_mngt as $v) {

                $gt = $v->management_id;

                $query = "SELECT *, item_id as _itemId,
                (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = _itemId and type='IN') as _qty_in,
                (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = _itemId and type='OUT') as _qty_out,
                (SELECT _qty_in - _qty_out ) as _qty_remaining

                from laboratory_items where management_id = '$gt'";

                $result = DB::getPdo()->prepare($query);
                $result->execute();
                $inventoryStockroom = $result->fetchAll(\PDO::FETCH_OBJ);

                $xx[] = $inventoryStockroom;
            }

            return $xx;
        } else {
            return [];
        }
    }

    public static function getInventoryGrandTotalAmountStockroom($data)
    {
        $management_mainId = DB::table('general_management_branches')->where('management_id', $data['management_id'])->get();
        if (count($management_mainId) > 0) {

            $all_mngt = DB::table('general_management_branches')->where('general_management_id', $management_mainId[0]->general_management_id)->get();

            $xx = [];

            foreach ($all_mngt as $v) {

                $gt = $v->management_id;

                $query = "SELECT *, product_id as _pid,
                    (SELECT IFNULL(sum(qty), 0) from stockroom_account_products where product_id = _pid and type='IN') as _qty_in,
                    (SELECT IFNULL(sum(qty), 0) from stockroom_account_products where product_id = _pid and type='OUT') as _qty_out,
                    (SELECT srp from pharmacyclinic_warehose_products where product_id = _pid) as _srp,
                    (SELECT _qty_in - _qty_out ) as _qty_remaining
                    from  stockroom_account_products where management_id = '$gt' group by product_id";

                $result = DB::getPdo()->prepare($query);
                $result->execute();
                $inventoryStockroom = $result->fetchAll(\PDO::FETCH_OBJ);

                $xx[] = $inventoryStockroom;
            }

            return $xx;
        } else {
            return [];
        }
    }

    public static function getPayableGrandTotalAmount($data)
    {

        $management_id = $data['management_id'];

        $query = "SELECT *, IFNULL(SUM(invoice_amount), 0) as _total_available  from accounting_payable WHERE invoice_status <> 'paid' and management_id = '$management_id' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();

        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getRecievableGrandTotalAmount($data)
    {
        $management_mainId = DB::table('general_management_branches')->where('management_id', $data['management_id'])->get();
        if (count($management_mainId) > 0) {

            $all_mngt = DB::table('general_management_branches')->where('general_management_id', $management_mainId[0]->general_management_id)->get();

            $xx = [];

            foreach ($all_mngt as $v) {

                $mngt = $v->management_id;

                $query = "SELECT * FROM cashier_patientbills_records WHERE is_charged = 1 AND bill_from = 'packages' and management_id = '$mngt' ";

                $result = DB::getPdo()->prepare($query);
                $result->execute();
                $receivable = $result->fetchAll(\PDO::FETCH_OBJ);

                $xx[] = $receivable;
            }

            return $xx;
        } else {
            return [];
        }
    }

    public static function getExpensesGrandTotalAmount($data)
    {

        $mngt = $data['main_mgmt_id'];

        $query = "SELECT id,
                    (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_government,
                    (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_communication,
                    (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_materials,
                    (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_entertainment,
                    (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_maintenance,
                    (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_hotel,
                    (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_incentives,
                    (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_insurance,
                    (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_meals,
                    (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_office_supply,
                    (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_others,
                    (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_retainers,
                    (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_salary,
                    (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_solicitation,
                    (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_transporation,
                    (SELECT SUM(total_government+total_communication+total_materials+total_entertainment+total_maintenance+total_hotel+total_incentives+total_insurance+total_meals+total_office_supply+total_others+total_retainers+total_salary+total_solicitation+total_transporation * 1)) as totalExpense
    from clinic_expenses where main_mgmt_id = '$mngt' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

    }

    public static function getBankGrandTotalAmount($data)
    {
        $query = "SELECT id,
            (SELECT IFNULL(SUM(amount), 0) FROM clinic_bank_transaction WHERE category = 'Deposit') as bank_deposit,
            (SELECT IFNULL(SUM(amount), 0) FROM clinic_bank_transaction WHERE category = 'Withdrawal') as bank_withdrawal,
            (SELECT SUM(bank_deposit-bank_withdrawal * 1)) as _total_bank_balance
        from clinic_bank_transaction where main_mgmt_id = '" . $data['main_mgmt_id'] . "' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getCollectionGrandTotalAmount($data)
    {
        $mngt = $data['management_id'];

        $query = "SELECT id, IFNULL(SUM(bill_amount), 0) as _total_collection from cashier_patientbills_records WHERE is_charged = 2 AND bill_from = 'packages' and management_id = '$mngt'";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getBranchLaboratoryInventoryTotal($data)
    {
        $mngt = $data['management_id'];

        $query = "SELECT *, item_id as _itemId,
        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = _itemId and type='IN') as _qty_in,
        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = _itemId and type='OUT') as _qty_out,
        (SELECT _qty_in - _qty_out ) as _qty_remaining

        from laboratory_items where management_id = '$mngt'";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getBranchStockroomInventoryTotal($data)
    {

        $mngt = $data['management_id'];

        $query = "SELECT *, product_id as _pid,
        (SELECT IFNULL(sum(qty), 0) from stockroom_account_products where product_id = _pid and type='IN') as _qty_in,
        (SELECT IFNULL(sum(qty), 0) from stockroom_account_products where product_id = _pid and type='OUT') as _qty_out,
        (SELECT srp from pharmacyclinic_warehose_products where product_id = _pid) as _srp,
        (SELECT _qty_in - _qty_out ) as _qty_remaining
        from  stockroom_account_products where management_id = '$mngt' group by product_id";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getBranchSalesTotal($data)
    {

        $mngt = $data['management_id'];

        $query = "SELECT * from  cashier_patientbills_records where management_id = '$mngt' and is_refund is null or is_refund = 0 and management_id = '$mngt'";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

    }

    public static function getBranchReceivableTotal($data)
    {
        $mngt = $data['management_id'];

        $query = "SELECT * FROM cashier_patientbills_records WHERE is_charged = 1 AND bill_from = 'packages' and management_id = '$mngt' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getBranchCollectionTotal($data)
    {
        $mngt = $data['management_id'];

        $query = "SELECT * from cashier_patientbills_records WHERE is_charged = 2 AND bill_from = 'packages' and management_id = '$mngt'";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getBranchExpenseTotal($data)
    {
        $mngt = $data['management_id'];

        $query = "SELECT id,
                    (SELECT COALESCE(SUM(government),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_government,
                    (SELECT COALESCE(SUM(communication),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_communication,
                    (SELECT COALESCE(SUM(materials),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_materials,
                    (SELECT COALESCE(SUM(entertainment),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_entertainment,
                    (SELECT COALESCE(SUM(maintenance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_maintenance,
                    (SELECT COALESCE(SUM(hotel),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_hotel,
                    (SELECT COALESCE(SUM(incentives),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_incentives,
                    (SELECT COALESCE(SUM(insurance),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_insurance,
                    (SELECT COALESCE(SUM(meals),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_meals,
                    (SELECT COALESCE(SUM(office_supply),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_office_supply,
                    (SELECT COALESCE(SUM(others),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_others,
                    (SELECT COALESCE(SUM(retainers),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_retainers,
                    (SELECT COALESCE(SUM(salary),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_salary,
                    (SELECT COALESCE(SUM(solicitation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_solicitation,
                    (SELECT COALESCE(SUM(transporation),0) FROM clinic_expenses WHERE clinic_expenses.id = clinic_expenses.id ) AS total_transporation,
                    (SELECT SUM(total_government+total_communication+total_materials+total_entertainment+total_maintenance+total_hotel+total_incentives+total_insurance+total_meals+total_office_supply+total_others+total_retainers+total_salary+total_solicitation+total_transporation * 1)) as totalExpense
    from clinic_expenses where management_id = '$mngt' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getTempSaveExpense($data)
    {
        return DB::table('clinic_expenses_temp')
            ->where('main_mgmt_id', $data['main_management_id'])
            ->get();
    }

    public static function addNewTempExpense($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('clinic_expenses_temp')->insert([
            'cet_id' => 'cet-' . rand(0, 9999) . time(),
            'management_id' => $data['branch'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'description' => $data['description'],
            'tin' => $data['tin'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'tax' => $data['vat'],
            'amount' => (float) $data['unit_price'] * (float) $data['quantity'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function removeTempExpense($data)
    {
        return DB::table('clinic_expenses_temp')
            ->where('cet_id', $data['cet_id'])
            ->delete();
    }

    public static function saveConfirmExpense($data)
    {
        date_default_timezone_set('Asia/Manila');
        $query = DB::table('clinic_expenses_temp')->where('main_mgmt_id', $data['main_mgmt_id'])->get();
        $expense = [];
        $expense_main_id = 'emi-' . rand(0, 9999) . time();
        foreach ($query as $v) {
            $expense[] = array(
                'expense_id' => 'cel-' . rand(0, 9999) . time(),
                'expense_main_id' => $expense_main_id,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'invoice_date' => date('Y-m-d H:i:s', strtotime($data['invoice_date'])),
                'voucher' => $data['voucher'],
                'description' => $v->description,
                'tin' => $v->tin,
                'quantity' => $v->quantity,
                'unit_price' => $v->unit_price,
                'tax' => $v->tax,
                'amount' => $v->amount,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('clinic_expenses_list')->insert($expense);
        return DB::table('clinic_expenses_temp')->where('main_mgmt_id', $data['main_mgmt_id'])->delete();
    }

    public static function getExpenseAllNoGroup($data)
    {
        $management_id = $data['management_id'];
        $main_mgmt_id = $data['main_mgmt_id'];
        $management_name = $data['management_name'];

        if ($management_name == 'BMCDC HQ') {
            return DB::table('clinic_expenses_list')
                ->where('main_mgmt_id', $main_mgmt_id)
                ->get();
        } else {
            return DB::table('clinic_expenses_list')
                ->where('management_id', $management_id)
                ->get();
        }
    }

    public static function getExpenseDetailsById($data)
    {
        return DB::table('clinic_expenses_list')
            ->where('expense_main_id', $data['expense_main_id'])
            ->get();
    }

    public static function getCurrentFormInformationExpense($data)
    {
        return DB::table('form_footer_header_information')
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->where('used_to', 'expense-print')
            ->first();
    }

    public static function editExpensePrintInfo($data)
    {
        date_default_timezone_set('Asia/Manila');
        if (!empty($data['ffhi_id'])) {
            return DB::table('form_footer_header_information')
                ->where('ffhi_id', $data['ffhi_id'])
                ->where('management_id', $data['management_id'])
                ->where('main_mgmt_id', $data['main_mgmt_id'])
                ->update([
                    'company_name' => $data['company_name'],
                    'to_company' => $data['to_company'],
                    'to_attention' => $data['to_attention'],
                    'to_address' => $data['to_address'],
                    'to_full_address' => $data['to_full_address'],
                    'ref_number' => $data['ref_number'],
                    'expense_category' => $data['expense_category'],
                    'prepared_by' => $data['prepared_by'],
                    'approved_by' => $data['approved_by'],
                    'reviewed_by' => $data['reviewed_by'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else {
            return DB::table('form_footer_header_information')
                ->insert([
                    'ffhi_id' => 'ffhi-' . rand(0, 99) . time(),
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'used_to' => 'expense-print',
                    'company_name' => $data['company_name'],
                    'to_company' => $data['to_company'],
                    'to_attention' => $data['to_attention'],
                    'to_address' => $data['to_address'],
                    'to_full_address' => $data['to_full_address'],
                    'ref_number' => $data['ref_number'],
                    'expense_category' => $data['expense_category'],
                    'prepared_by' => $data['prepared_by'],
                    'approved_by' => $data['approved_by'],
                    'reviewed_by' => $data['reviewed_by'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function saveLeaveApplication($data)
    {

        if ($data['leave_type'] == 'sickleave') {
            DB::table('hospital_employee_details')->where('user_id', $data['user_id'])->update([
                'sick_leave' => (float) $data['current_sick_leave_credit'] - (float) $data['no_days'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($data['leave_type'] == 'vacationleave') {
            DB::table('hospital_employee_details')->where('user_id', $data['user_id'])->update([
                'vacation_leave' => (float) $data['current_vacation_leave_credit'] - (float) $data['no_days'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return DB::table('clinic_leave_application')->insert([
            'cla_id' => 'cla-' . rand(0, 99) . time(),
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'user_id' => $data['user_id'],
            'purpose' => $data['purpose'],
            'leave_type' => $data['leave_type'],
            'date_from' => date('Y-m-d H:i:s', strtotime($data['date_from'])),
            'date_to' => date('Y-m-d H:i:s', strtotime($data['date_to'])),
            'no_days' => $data['no_days'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getForLeaveApproval($data)
    {
        $mngt = $data['main_mgmt_id'];

        $query = "SELECT *,
            (SELECT name FROM management where management.management_id = clinic_leave_application.management_id limit 1) as branch_name,
            (SELECT user_fullname FROM accounting_account where accounting_account.user_id = clinic_leave_application.user_id limit 1) as _name_accounting,
            (SELECT user_fullname FROM accounting_account where accounting_account.user_id = clinic_leave_application.user_id limit 1) as _name_accounting,
            (SELECT user_fullname FROM hospital_billing_account where hospital_billing_account.user_id = clinic_leave_application.user_id limit 1) as _name_billing,
            (SELECT user_fullname FROM cashier where cashier.user_id = clinic_leave_application.user_id limit 1) as _name_cashier,
            (SELECT name FROM doctors where doctors.user_id = clinic_leave_application.user_id limit 1) as _name_doctors,
            (SELECT user_fullname FROM encoder where encoder.user_id = clinic_leave_application.user_id limit 1) as _name_encoder,
            (SELECT user_fullname FROM haptech_account where haptech_account.user_id = clinic_leave_application.user_id limit 1) as _name_haptech,
            (SELECT user_fullname FROM hr_account where hr_account.user_id = clinic_leave_application.user_id limit 1) as _name_hr,
            (SELECT user_fullname FROM imaging where imaging.user_id = clinic_leave_application.user_id limit 1) as _name_imaging,
            (SELECT user_fullname FROM laboratory_list where laboratory_list.user_id = clinic_leave_application.user_id limit 1) as _name_laboratory,
            (SELECT user_fullname FROM operation_manager_account where operation_manager_account.user_id = clinic_leave_application.user_id limit 1) as _name_om,
            (SELECT user_fullname FROM pharmacy where pharmacy.user_id = clinic_leave_application.user_id limit 1) as _name_pharmacy,
            (SELECT user_fullname FROM psychology_account where psychology_account.user_id = clinic_leave_application.user_id limit 1) as _name_psychology,
            (SELECT name FROM radiologist where radiologist.user_id = clinic_leave_application.user_id limit 1) as _name_radiologist,
            (SELECT user_fullname FROM admission_account where admission_account.user_id = clinic_leave_application.user_id limit 1) as _name_registration,
            (SELECT user_fullname FROM stockroom_acccount where stockroom_acccount.user_id = clinic_leave_application.user_id limit 1) as _name_stockroom,
            (SELECT user_fullname FROM triage_account where triage_account.user_id = clinic_leave_application.user_id limit 1) as _name_triage,
            (SELECT user_fullname FROM warehouse_accounts where warehouse_accounts.user_id = clinic_leave_application.user_id limit 1) as _name_warehouse,
            (SELECT user_fullname FROM other_account where other_account.user_id = clinic_leave_application.user_id limit 1) as _name_other,

            (SELECT
                CASE
                    WHEN _name_accounting IS NOT NULL THEN _name_accounting
                    WHEN _name_billing IS NOT NULL THEN _name_billing
                    WHEN _name_cashier IS NOT NULL THEN _name_cashier
                    WHEN _name_doctors  IS NOT NULL THEN _name_doctors
                    WHEN _name_encoder IS NOT NULL THEN _name_encoder
                    WHEN _name_haptech  IS NOT NULL THEN _name_haptech
                    WHEN _name_hr  IS NOT NULL THEN _name_hr
                    WHEN _name_imaging  IS NOT NULL THEN _name_imaging
                    WHEN _name_laboratory  IS NOT NULL THEN _name_laboratory
                    WHEN _name_om IS NOT NULL THEN _name_om
                    WHEN _name_pharmacy  IS NOT NULL THEN _name_pharmacy
                    WHEN _name_psychology IS NOT NULL THEN _name_psychology
                    WHEN _name_radiologist  IS NOT NULL THEN _name_radiologist
                    WHEN _name_registration  IS NOT NULL THEN _name_registration
                    WHEN _name_stockroom  IS NOT NULL THEN _name_stockroom
                    WHEN _name_triage IS NOT NULL THEN _name_triage
                    WHEN _name_warehouse  IS NOT NULL THEN _name_warehouse
                    WHEN _name_other IS NOT NULL THEN _name_other
                    ELSE user_id
                END
            ) as usersfname
        from clinic_leave_application where main_mgmt_id = '$mngt' AND noted_by IS NULL ORDER BY created_at ASC ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function saveLeaveDecision($data)
    {
        if ($data['noted_by_des'] == 'disapprove') {
            $query = DB::table('hospital_employee_details')->select('sick_leave', 'vacation_leave')->where('user_id', $data['users_id'])->first();

            if ($data['leave_type'] == 'sickleave') {
                DB::table('hospital_employee_details')
                    ->where('user_id', $data['users_id'])
                    ->update([
                        'sick_leave' => (float) $query->sick_leave + (float) $data['no_days'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            if ($data['leave_type'] == 'vacationleave') {
                DB::table('hospital_employee_details')
                    ->where('user_id', $data['users_id'])
                    ->update([
                        'vacation_leave' => (float) $query->vacation_leave + (float) $data['no_days'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            return DB::table('clinic_leave_application')
                ->where('cla_id', $data['cla_id'])
                ->update([
                    'payment_type' => $data['payment_type'],
                    'noted_by' => $data['user_id'],
                    'noted_by_des' => $data['noted_by_des'],
                    'disapprove_reason' => $data['disapprove_reason'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else {
            return DB::table('clinic_leave_application')
                ->where('cla_id', $data['cla_id'])
                ->update([
                    'payment_type' => $data['payment_type'],
                    'noted_by' => $data['user_id'],
                    'noted_by_des' => $data['noted_by_des'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function saveTempPsychologyOrder($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('psychology_test')->insert([
            'ptl_id' => 'ptl-' . rand(0, 9999) . time(),
            'psycho_id' => _Accounting::getPsychologyIdByManagement($data)->psycho_id,
            'management_id' => $data['management_id'],
            'test_id' => 'test-' . rand(0, 9999) . time(),
            'department' => $data['dept'],
            'test' => $data['test'],
            'rate' => $data['rate'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getAllPsychology($data)
    {
        return DB::table('psychology_test')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getDoctorServiceByDocId($data)
    {
        // return DB::table('doctors_appointment_services')
        //     ->where('management_id', $data['management_id'])
        //     ->where('doctors_id', $data['doctors_id'])
        //     ->get();

        return DB::table('doctors_appointment_services')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function getLaboratoryTestByOrderId($data)
    {
        return DB::table('laboratory_items_laborder')
            ->where('category', $data['category'])
            ->where('management_id', $data['management_id'])
            ->orderBy('laborder')
            ->groupBy('order_id')
            ->get();
    }

    public static function saveMedicalOrder($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('medical_examination_test')->insert([
            'met_id' => 'ptl-' . rand(0, 99999) . time(),
            'management_id' => $data['management_id'],
            'test_id' => 'test-' . rand(0, 99999) . time(),
            'department' => $data['dept'],
            'test' => $data['test'],
            'rate' => $data['rate'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getMedicalOrder($data)
    {
        return DB::table('medical_examination_test')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getHaptechProducts($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
            ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehose_products.product_name')
        // ->leftJoin('pharmacyclinic_warehouse_category', 'pharmacyclinic_warehouse_category.category_id', '=', 'pharmacyclinic_warehose_products.product_category')
            ->select('pharmacyclinic_warehose_products.id', 'pharmacyclinic_warehose_products.product_name as value', 'pharmacyclinic_warehouse_brand.brand as label')
            ->where('pharmacyclinic_warehose_products.main_mgmt_id', $data['main_mgmt_id'])
            ->groupBy('pharmacyclinic_warehose_products.product_name')
            ->get();
    }

    public static function getHaptechProductDescriptions($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
            ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehose_products.product_name')
            ->leftJoin('pharmacyclinic_warehouse_category', 'pharmacyclinic_warehouse_category.category_id', '=', 'pharmacyclinic_warehose_products.product_category')
            ->select('pharmacyclinic_warehose_products.*', 'pharmacyclinic_warehose_products.product_id as value', 'pharmacyclinic_warehose_products.product_generic as label', 'pharmacyclinic_warehouse_brand.brand', 'pharmacyclinic_warehouse_category.category')
            ->where('pharmacyclinic_warehose_products.main_mgmt_id', $data['main_mgmt_id'])
            ->where('pharmacyclinic_warehose_products.product_name', $data['item_id'])
            ->groupBy('pharmacyclinic_warehose_products.product_generic')
            ->get();
    }

    public static function getLabItemProducts($data)
    {
        return DB::table('laboratory_items')
            ->select('id', 'item_id as value', 'item as label')
            ->where('management_id', $data['management_id'])
            ->groupBy('laboratory_items.item')
            ->get();
    }

    public static function getLabItemProductDescriptions($data)
    {
        return DB::table('laboratory_items')
            ->select('*', 'description as value', 'description as label')
            ->where('management_id', $data['management_id'])
            ->where('item', $data['item'])
            ->orderBy('description', 'ASC')
            ->get();
    }

    public static function newAdditionalOrder($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::table('other_order_test')->insert([
            'oot_id' => 'oot-' . time() . '-' . rand(0, 99999),
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'order_id' => 'order-' . time() . '-' . rand(0, 99999),
            'order_name' => $data['order_name'],
            'order_amount' => $data['order_amount'],
            'department' => $data['department'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getAdditionalOrder($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('other_order_test')
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function getOtherTestPerson($data)
    {
        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.lastname', 'patients.firstname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_department', 'Other Test')
            ->orderBy('cashier_patientbills_records.created_at', 'desc')
            ->get();
    }

    public static function getOtherSalesByDate($data)
    {
        date_default_timezone_set('Asia/Manila');

        $date_from = date('Y-m-d 00:00:00', strtotime($data['from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['to']));

        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.lastname', 'patients.firstname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_department', 'Other Test')
            ->where('cashier_patientbills_records.created_at', '>=', $date_from)
            ->where('cashier_patientbills_records.created_at', '<=', $date_to)
            ->orderBy('cashier_patientbills_records.created_at', 'desc')
            ->get();
    }

    public static function getDoctorServices($data)
    {
        return DB::table('doctors_appointment_services')
            ->where('management_id', $data['management_id'])
            ->orderBy('services', 'ASC')
            ->get();
    }

    public static function createNewServiceSave($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('doctors_appointment_services')->insert([
            'service_id' => 'si-' . time() . '-' . rand(0, 99999),
            'doctors_id' => 'accounting-add',
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'services' => $data['services'],
            'amount' => $data['rate'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function updateNewServiceSave($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('doctors_appointment_services')
            ->where('service_id', $data['service_id'])
            ->update([
                'services' => $data['services'],
                'amount' => $data['rate'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getPsychologyOrderList($data)
    {
        return DB::table('psychology_test')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getDoctorTestPerson($data)
    {
        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_from', '<>', 'packages')
            ->where('cashier_patientbills_records.bill_from', '<>', 'Other Test')
            ->where('cashier_patientbills_records.bill_from', '<>', 'psychology')
            ->where('cashier_patientbills_records.bill_from', '<>', 'laboratory')
            ->where('cashier_patientbills_records.bill_from', '<>', 'imaging')
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function getDoctorSalesByDate($data)
    {
        date_default_timezone_set('Asia/Manila');
        $date_from = date('Y-m-d 00:00:00', strtotime($data['from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['to']));

        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_from', '<>', 'packages')
            ->where('cashier_patientbills_records.bill_from', '<>', 'Other Test')
            ->where('cashier_patientbills_records.bill_from', '<>', 'psychology')
            ->where('cashier_patientbills_records.bill_from', '<>', 'laboratory')
            ->where('cashier_patientbills_records.bill_from', '<>', 'imaging')
            ->where('cashier_patientbills_records.created_at', '>=', $date_from)
            ->where('cashier_patientbills_records.created_at', '<=', $date_to)
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function getPsychologyTestPerson($data)
    {
        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_from', 'psychology')
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function getPsychologySalesByDate($data)
    {
        date_default_timezone_set('Asia/Manila');
        $date_from = date('Y-m-d 00:00:00', strtotime($data['from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['to']));

        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_from', 'psychology')
            ->where('cashier_patientbills_records.created_at', '>=', $date_from)
            ->where('cashier_patientbills_records.created_at', '<=', $date_to)
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function getPackageTestPerson($data)
    {
        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_from', 'packages')
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function getPackageSalesByDate($data)
    {
        date_default_timezone_set('Asia/Manila');
        $date_from = date('Y-m-d 00:00:00', strtotime($data['from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['to']));

        return DB::table('cashier_patientbills_records')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', (new _Accounting)::getImagingIdByManagement($data)->management_id)
            ->where('cashier_patientbills_records.bill_from', 'packages')
            ->where('cashier_patientbills_records.created_at', '>=', $date_from)
            ->where('cashier_patientbills_records.created_at', '<=', $date_to)
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function accountingGetDoctorList($data)
    {
        return DB::table('doctors')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function accountingUpdateDoctorShare($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('doctors')
            ->where('doctors_id', $data['doctors_id'])
            ->update([
                'share_rate' => $data['share_rate'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getUpdateLaboratoryRates($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_items_laborder')
            ->where('order_id', $data['order_id'])
            ->update([
                'can_be_discounted' => (int) $data['can_be_discounted'],
                'rate' => $data['rate'],
                'mobile_rate' => $data['mobile_rate'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function addOrderToPackage($data)
    {

        date_default_timezone_set('Asia/Manila');
        $x_order = [];

        if ($data['department'] == "imaging") {
            $query = DB::table('imaging_order_menu')->select('order_desc')->where('order_id', $data['order_id'])->first();

            $x_order[] = array(
                'pck_id' => 'pck-' . rand(0, 9999) . '-' . time(),
                'package_id' => $data['package_id'],
                'package_name' => $data['package_name'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'category' => $data['category'],
                'order_id' => $data['order_id'],
                'order_name' => $query->order_desc,
                'order_amount' => $data['order_amount'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );

        } elseif ($data['department'] == 'laboratory') {
            $query = DB::table('laboratory_items_laborder')->select('laborder', 'category')->where('order_id', $data['order_id'])->first();

            $x_order[] = array(
                'pck_id' => 'pck-' . rand(0, 9999) . '-' . time(),
                'package_id' => $data['package_id'],
                'package_name' => $data['package_name'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'category' => $data['category'],
                'order_id' => $data['order_id'],
                'order_name' => $query->laborder,
                'order_amount' => $data['order_amount'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
        } elseif ($data['department'] == 'psychology') {
            $query = DB::table('psychology_test')->select('test')->where('test_id', $data['order_id'])->first();

            $x_order[] = array(
                'pck_id' => 'pck-' . rand(0, 9999) . '-' . time(),
                'package_id' => $data['package_id'],
                'package_name' => $data['package_name'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'category' => $data['category'],
                'order_id' => $data['order_id'],
                'order_name' => $query->test,
                'order_amount' => $data['order_amount'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
        } else { // ($data['department'] == 'others')
            $query = DB::table('other_order_test')->select('order_name')->where('order_id', $data['order_id'])->first();

            $x_order[] = array(
                'pck_id' => 'pck-' . rand(0, 9999) . '-' . time(),
                'package_id' => $data['package_id'],
                'package_name' => $data['package_name'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'category' => $data['category'],
                'order_id' => $data['order_id'],
                'order_name' => $query->order_name,
                'order_amount' => $data['order_amount'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
        }

        return DB::table('packages_charge')->insert($x_order);
    }

    public static function setAsPaidByPatient($data)
    {
        return DB::table('cashier_patientbills_records')
            ->where('patient_id', $data['patient_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->where('is_charged', 1)
            ->update([
                'is_charged_paid' => 1,
                'is_charged' => 2,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getItemRequestConfirmById($data)
    {
        return DB::table('laboratory_request_item')
            ->where('management_id', $data['management_id'])
            ->where('request_id', $data['request_id'])
            ->get();
    }

    public static function checkProductInTemp($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->where('warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
            ->where('product_id', $data['product_id'])
            ->where('batch_number', $data['batch_number'])
            ->where('request_id', $data['request_id'])
            ->where('type', 'OUT')
            ->get();
    }

    public static function saveProductDrtoTemp($data)
    {
        $warehouse_id = _Accounting::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = _Accounting::getPharmacyWarehouseId($data)->management_id;

        return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->insert([
                'pwite_id' => 'pwite-' . rand(0, 9999) . time(),
                'request_id' => $data['request_id'],
                'warehouse_id' => $warehouse_id,
                'management_id' => $management_id,
                'product_id' => $data['product_id'],
                'product_name' => $data['brand'],
                'product_generic' => $data['product_generic'],
                'unit' => $data['unit'],
                'msrp' => $data['msrp'],
                'srp' => $data['srp'],
                'qty' => $data['orderQty'],
                'batch_number' => $data['batch_number'],
                'expiration_date' => date('Y-m-d', strtotime($data['expiration_date'])),
                'manufactured_date' => date('Y-m-d', strtotime($data['manufactured_date'])),
                'type' => 'OUT',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getProductToDrList($data)
    {
        if ($data['status'] == 'create') {
            return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
                ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_temp_exclusive.product_name')
                ->select('pharmacyclinic_warehouse_inventory_temp_exclusive.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.accounting_approve')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.type', 'OUT')
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.finalized')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id', $data['request_id'])
                ->get();
        } elseif ($data['status'] == 'queue-for-haptech') {
            return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
                ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_temp_exclusive.product_name')
                ->select('pharmacyclinic_warehouse_inventory_temp_exclusive.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
                ->whereNotNull('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.accounting_approve', 1)
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.supplier_approve')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.type', 'OUT')
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.finalized')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id', $data['request_id'])
                ->get();
        } elseif ($data['status'] == 'waiting') {
            return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
                ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_temp_exclusive.product_name')
                ->select('pharmacyclinic_warehouse_inventory_temp_exclusive.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
                ->whereNotNull('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.accounting_approve', 1)
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.supplier_approve')
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.owner_approve')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.type', 'OUT')
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.finalized')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id', $data['request_id'])
                ->get();
        } elseif ($data['status'] == 'waiting-for-haptech') {
            return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
                ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_temp_exclusive.product_name')
                ->select('pharmacyclinic_warehouse_inventory_temp_exclusive.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
                ->whereNotNull('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.accounting_approve', 1)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.supplier_approve', 1)
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.owner_approve')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.type', 'OUT')
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.finalized')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id', $data['request_id'])
                ->get();
        } elseif ($data['status'] == 'approval-for-coo') {
            return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
                ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_temp_exclusive.product_name')
                ->select('pharmacyclinic_warehouse_inventory_temp_exclusive.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
                ->whereNotNull('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.accounting_approve', 1)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.supplier_approve', 1)
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.owner_approve')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.type', 'OUT')
                ->whereNull('pharmacyclinic_warehouse_inventory_temp_exclusive.finalized')
                ->groupBy('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id')
                ->orderBy('pharmacyclinic_warehouse_inventory_temp_exclusive.created_at')
                ->get();
        } elseif ($data['status'] == 'approve') {
            return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
                ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_temp_exclusive.product_name')
                ->select('pharmacyclinic_warehouse_inventory_temp_exclusive.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
                ->whereNotNull('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.accounting_approve', 1)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.supplier_approve', 1)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.owner_approve', 1)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.type', 'OUT')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id', $data['request_id'])
                ->get();
        } elseif ($data['status'] == 'approve-for-haptech') {
            return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
                ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_temp_exclusive.product_name')
                ->select('pharmacyclinic_warehouse_inventory_temp_exclusive.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
                ->whereNotNull('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.accounting_approve', 1)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.supplier_approve', 1)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.owner_approve', 1)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.type', 'OUT')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id', $data['request_id'])
                ->get();
        } elseif ($data['status'] == 'disapprove') {
            return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
                ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_temp_exclusive.product_name')
                ->select('pharmacyclinic_warehouse_inventory_temp_exclusive.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
                ->whereNotNull('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id')
                ->whereNotNull('pharmacyclinic_warehouse_inventory_temp_exclusive.owner_disapprove_reason')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.accounting_approve', 1)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.supplier_approve', 1)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.owner_approve', 0)
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.type', 'OUT')
                ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.request_id', $data['request_id'])
                ->get();
        }

    }

    public static function removeItemFromTempList($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->where('id', $data['remove_id'])
            ->delete();
    }

    public static function getProducsListForNewInvoice($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
            ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehose_products.product_name')
            ->leftJoin('pharmacyclinic_warehouse_category', 'pharmacyclinic_warehouse_category.category_id', '=', 'pharmacyclinic_warehose_products.product_category')
            ->select('pharmacyclinic_warehose_products.*', 'pharmacyclinic_warehouse_brand.brand as brandName', 'pharmacyclinic_warehouse_category.category as categoryName', 'pharmacyclinic_warehose_products.product_code as label', 'pharmacyclinic_warehose_products.product_id as value')
            ->where('pharmacyclinic_warehose_products.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
            ->where('pharmacyclinic_warehose_products.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('pharmacyclinic_warehose_products.status', 1)
            ->get();
    }

    public static function getProductDetails($data)
    {
        $product_id = $data['product_id'];
        $warehouse_id = _Accounting::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = $data['management_id'];

        $query = "SELECT *, product_id as product_ids, batch_number as batch_id, batch_number as label, batch_number as value,
            (SELECT brand from pharmacyclinic_warehouse_brand where pharmacyclinic_warehouse_brand.brand_id = pharmacyclinic_warehouse_inventory.product_name limit 1) as brandName,
            (SELECT msrp from pharmacyclinic_warehose_products where pharmacyclinic_warehose_products.product_id = '$product_id' limit 1) as product_msrp,
            (SELECT srp from pharmacyclinic_warehose_products where pharmacyclinic_warehose_products.product_id = '$product_id' limit 1) as product_srp,
            (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = product_ids and type = 'IN' and batch_number = batch_id) as _total_in_qty,
            (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = product_ids and type = 'OUT' and batch_number = batch_id) as _total_out_qty,
            (SELECT product_code from pharmacyclinic_warehose_products where product_id = product_ids limit 1) as product_code,
            (SELECT (_total_in_qty - _total_out_qty)) as _total_available_qty
        from pharmacyclinic_warehouse_inventory where warehouse_id = '$warehouse_id' and product_id = '$product_id' group by batch_number";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getDescListForNewInvoice($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
            ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehose_products.product_name')
            ->leftJoin('pharmacyclinic_warehouse_category', 'pharmacyclinic_warehouse_category.category_id', '=', 'pharmacyclinic_warehose_products.product_category')
            ->select('pharmacyclinic_warehose_products.*', 'pharmacyclinic_warehouse_brand.brand as brandName', 'pharmacyclinic_warehouse_category.category as categoryName', 'pharmacyclinic_warehose_products.product_generic as label', 'pharmacyclinic_warehose_products.product_id as value')
            ->where('pharmacyclinic_warehouse_brand.brand', $data['brand'])
            ->where('pharmacyclinic_warehose_products.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
            ->where('pharmacyclinic_warehose_products.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
            ->orderBy('pharmacyclinic_warehouse_brand.brand', 'ASC')
            ->where('pharmacyclinic_warehose_products.status', 1)
            ->get();
    }

    public static function getProductBatchDetails($data)
    {
        $product_id = $data['product_id'];
        $warehouse_id = _Accounting::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = $data['management_id'];
        $batch_number = $data['batch_number'];

        $query = "SELECT *, product_id as product_ids, batch_number as batch_id,
        (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = product_ids and type = 'IN' and batch_number = batch_id) as _total_in_qty,
        (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory_temp_exclusive where product_id = product_ids and type = 'OUT' and batch_number = batch_id) as _total_out_qty_intemp,
        (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = product_ids and type = 'OUT' and batch_number = batch_id) as _total_out_qty
        from pharmacyclinic_warehouse_inventory where warehouse_id = '$warehouse_id' and product_id = '$product_id' and batch_number = '$batch_number' group by batch_number";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getBrandListForNewInvoice($data)
    {
        $management_id = _Accounting::getPharmacyWarehouseId($data)->management_id;
        return DB::table('pharmacyclinic_warehouse_brand')
            ->select('brand_id', 'brand as label', 'brand_id as value')
            ->where('management_id', $management_id)
            ->orderBy('brand', 'ASC')
            ->get();
    }

    public static function confirmRequestToHaptech($data)
    {
        // $req_id = 'rq-'.rand(0, 99).time();
        $req_id = $data['request_id'];
        $management_id = _Accounting::getPharmacyWarehouseId($data)->management_id;
        $warehouse_id = _Accounting::getPharmacyWarehouseId($data)->warehouse_id;
        return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->where('management_id', $management_id)
            ->where('warehouse_id', $warehouse_id)
            ->where('request_id', $req_id)
            ->update([
                'accounting_approve' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function restoreDisapprovedRequest($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->where('request_id', $data['request_id'])
            ->update([
                'request_id' => null,
                'item_check' => null,
                'accounting_approve' => null,
                'supplier_approve' => null,
                'owner_approve' => null,
                'owner_disapprove_reason' => null,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function updateAccountingPackage($data)
    {
        return DB::table('packages_charge')
            ->where('package_id', $data['package_id'])
            ->where('management_id', $data['management_id'])
            ->update([
                'order_amount' => $data['order_amount'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function updateAccountingOtherRate($data)
    {
        return DB::table('other_order_test')
            ->where('order_id', $data['order_id'])
            ->where('management_id', $data['management_id'])
            ->update([
                'order_amount' => $data['order_amount'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function updateAccountingPsychologyRate($data)
    {
        return DB::table('psychology_test')
            ->where('test_id', $data['test_id'])
            ->where('management_id', $data['management_id'])
            ->update([
                'rate' => $data['rate'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllSOAListByMgmtID($data)
    {
        return DB::table('cashier_statement_of_account')
            ->where("main_mgmt_id", $data['main_mgmt_id'])
            ->where('management_id', $data['management_id'])
            ->groupBy("soa_id")
            ->orderBy("created_at", "DESC")
            ->get();
    }

    public static function getHMOListByMainMgmt($data)
    {
        return DB::table('hmo_list')
            ->where("main_mgmt_id", $data['main_mgmt_id'])
            ->orderBy("name", "ASC")
            ->get();
    }

    public static function createNewHMOAccounting($data)
    {
        return DB::table('hmo_list')
            ->insert([
                'hl_id' => 'hl-' . rand(0, 9) . time(),
                'management_id' => 'hq-accounting',
                'main_mgmt_id' => $data['main_mgmt_id'],
                'name' => $data['name'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function updateSpecificHMOByMain($data)
    {
        return DB::table('hmo_list')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->where("hl_id", $data['hl_id'])
            ->update([
                'name' => $data['name'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getHMOAccreditedList($data)
    {
        return DB::table('management_accredited_company_hmo')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->orderBy('company_id', 'asc')
            ->get();
    }

    public static function updateHMOStatus($data)
    {
        return DB::table('management_accredited_company_hmo')
            ->where('mach_id', $data['mach_id'])
            ->where("company_id", $data['company_id'])
            ->update([
                'status' => $data['status'] == 1 ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function createNewHMOByCompanyId($data)
    {
        return DB::table('management_accredited_company_hmo')
            ->insert([
                'mach_id' => 'march-' . rand(0, 9) . time(),
                'company_id' => $data['company_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'hmo' => $data['hmo'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getRoomLists($data)
    {
        return DB::table('hospital_rooms')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function newRoom($data)
    {
        return DB::table('hospital_rooms')->insert([
            'r_id' => 'r-' . rand(0, 9) . time(),
            'room_id' => 'room-' . rand(999999, 9999999999),
            'management_id' => $data["management_id"],
            'room_name' => $data["room_name"],
            "no_of_rooms" => $data["no_of_rooms"],
            "type" => $data["type"],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function addRoomToList($data)
    {
        return DB::table('hospital_rooms_list')->insert([
            "room_number" => "room-number-" . $data["room_number"],
            "management_id" => $data["management_id"],
            "room_id" => $data["room_id"],
            "no_of_beds" => $data["no_of_beds"],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getRoomListByRoomId($data)
    {
        return DB::table('hospital_rooms_list')
            ->where('room_id', $data['room_id'])
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getListOfBedsByRoom($data)
    {
        $roomId = $data['room_id'];
        $managementId = $data['management_id'];
        $roomNumber = $data['room_number'];

        $query = "SELECT *,
        (SELECT count(id) FROM hospital_admitted_patient WHERE hospital_admitted_patient.room_id = hospital_rooms_beds.room_id AND hospital_admitted_patient.room_number = hospital_rooms_beds.room_number AND hospital_admitted_patient.room_bed_number = hospital_rooms_beds.bed_number AND hospital_admitted_patient.nurse_department != 'discharged') as _availabilityCount
        FROM hospital_rooms_beds WHERE room_id = '$roomId' AND management_id = '$managementId' AND room_number = '$roomNumber' ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function newBedInRoom($data)
    {
        return DB::table('hospital_rooms_beds')->insert([
            'hrb_id' => 'hrb-' . rand(0, 9) . time(),
            'bed_id' => 'bed-' . rand(999999, 999989899) . time(),
            'bed_number' => $data['bed_number'],
            'room_id' => $data['room_id'],
            'room_number' => $data['room_number'],
            'management_id' => $data['management_id'],
            'amount' => $data['amount'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
