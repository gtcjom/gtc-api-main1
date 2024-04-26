<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Haptech extends Model
{

    public static function hisHaptechHeaderInfo($data)
    {
        // return DB::table('haptech_account')
        //     ->select('ha_id', 'user_fullname as name', 'image', 'user_address as address')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

            return DB::table('haptech_account')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'haptech_account.user_id')
            ->select('haptech_account.ha_id', 'haptech_account.user_fullname as name', 'haptech_account.image', 'haptech_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('haptech_account.user_id', $data['user_id'])
            ->first();
    }

    public static function hisHaptechGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM haptech_account WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisHaptechUpdatePersonalInfo($data)
    {
        return DB::table('haptech_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisHaptechUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisHaptechUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisHaptechUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('haptech_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    // // new route for haptech - 7 -31 - 2021
    public static function drApprovedByHaptech($data)
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
                'dr_number' => $v->dr_number,
                'dr_totalamount' => $v->dr_totalamount,
                'dr_account' => $v->dr_account,
                'dr_accountname' => $v->dr_accountname,
                'dr_accountaddress' => $v->dr_accountaddress,
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
        // return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
        //     ->where('management_id', $data['management_id'])
        //     ->where('dr_number', $data['dr_number'])
        //     ->get();
        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_forapproval.product_name')
            ->select('pharmacyclinic_warehouse_inventory_forapproval.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
            ->where('pharmacyclinic_warehouse_inventory_forapproval.management_id', $data['management_id'])
            ->where('pharmacyclinic_warehouse_inventory_forapproval.dr_number', $data['dr_number'])
            ->get();
    }

    public static function invoiceApprovedByHaptech($data)
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
                'pwi_id' => '' . rand(0, 9999) . time(),
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

        DB::table('pharmacyclinic_warehouse_inventory')->insert($products);

        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('management_id', $data['management_id'])
            ->where('invoice_number', $data['invoice_number'])
            ->where('type', 'IN')
            ->delete();
    }

    public static function getInvoiceProducts($data)
    {
        $warehouse_id = _Warehouse::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = $data['management_id'];

        $query = "SELECT *
        from pharmacyclinic_warehouse_inventory where type='IN' and warehouse_id = '$warehouse_id' and management_id = '$management_id' group by invoice_number order by created_at desc";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisWarehouseGetRole($data)
    {
        return DB::table('warehouse_accounts')
            ->where('user_id', $data['user_id'])
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function getPurchaseOrderProducts($data)
    {
        $warehouse_id = _Warehouse::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = $data['management_id'];

        $query = "SELECT *,
            (SELECT user_fullname FROM warehouse_accounts WHERE warehouse_accounts.user_id = pharmacyclinic_warehouse_po_invoice_temp.saved_by LIMIT 1) as haptechName,
            (SELECT count(id) FROM pharmacyclinic_warehouse_inventory_forapproval WHERE pharmacyclinic_warehouse_inventory_forapproval.po_number = pharmacyclinic_warehouse_po_invoice_temp.po_number) as poCount
        FROM pharmacyclinic_warehouse_po_invoice_temp WHERE warehouse_id = '$warehouse_id' AND management_id = '$management_id' AND is_saved IS NOT NULL GROUP BY po_number ORDER BY created_at DESC";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

}