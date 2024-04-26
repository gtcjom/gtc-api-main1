<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class _StockRoom extends Model
{
    public static function hmisGetHeaderInfo($data)
    {
        // return DB::table('stockroom_acccount')
        //     ->select('stockroom_id', 'user_fullname as name', 'image')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

            return DB::table('stockroom_acccount')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'stockroom_acccount.user_id')
            ->select('stockroom_acccount.stockroom_id', 'stockroom_acccount.user_fullname as name', 'stockroom_acccount.image', 'stockroom_acccount.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('stockroom_acccount.user_id', $data['user_id'])
            ->first();
    }

    public static function getStockroomIdByManagement($data)
    {
        return DB::table('stockroom_acccount')->where('management_id', $data['management_id'])->first();
    }

    public static function hisAccountingGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM stockroom_acccount WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisStockroomUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('stockroom_acccount')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisStockroomUpdatePersonalInfo($data)
    {

        date_default_timezone_set('Asia/Manila');
        return DB::table('stockroom_acccount')
            ->where('user_id', $data['user_id'])
            ->update([
                'name' => $data['fullname'],
                'address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function warehouseProductList($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
            ->select('*', 'product_id as value', 'product_name as label')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function stockRoomTempProductsSave($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('stockroom_account_products_temp')
            ->insert([
                'sapt_id' => 'sapt-' . rand() . '-' . time(),
                'management_id' => $data['management_id'],
                'stockroom_id' => (new _StockRoom)::getStockroomIdByManagement($data)->stockroom_id,
                'product_id' => $data['product_id'],
                'product' => $data['product'],
                'unit' => $data['unit'],
                'qty' => $data['qty'],
                'type' => $data['type'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getStockRoomTempDrProducts($data)
    {
        return DB::table('stockroom_account_products_temp')
            ->where('management_id', $data['management_id'])
            ->where('stockroom_id', (new _StockRoom)::getStockroomIdByManagement($data)->stockroom_id)
            ->where('type', $data['type'])
            ->get();
    }

    public static function removeStockroomTempProducts($data)
    {
        return DB::table('stockroom_account_products_temp')
            ->where('sapt_id', $data['remove_id'])
            ->delete();
    }

    public static function processInProduct($data)
    {

        date_default_timezone_set('Asia/Manila');

        $qry = DB::table('stockroom_account_products_temp')
            ->where('management_id', $data['management_id'])
            ->where('stockroom_id', (new _StockRoom)::getStockroomIdByManagement($data)->stockroom_id)
            ->where('type', 'IN')
            ->get();

        $prods = [];

        foreach ($qry as $v) {
            $prods[] = array(
                "sap_id" => "sap-" . rand() . '-' . time(),
                "management_id" => $v->management_id,
                "stockroom_id" => $v->stockroom_id,
                "product_id" => $v->product_id,
                "product" => $v->product,
                "unit" => $v->unit,
                "qty" => $v->qty,
                "dr_number" => $data['dr_number'],
                "dr_date" => $data['dr_date'],
                "type" => 'IN',
                "status" => 1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            );
        }
        DB::table('stockroom_account_products')->insert($prods);

        return DB::table('stockroom_account_products_temp')
            ->where('management_id', $data['management_id'])
            ->where('stockroom_id', (new _StockRoom)::getStockroomIdByManagement($data)->stockroom_id)
            ->where('type', 'IN')
            ->delete();
    }

    public static function getStockroomMonitoring($data)
    {
        return DB::table('stockroom_account_products')
            ->where('management_id', $data['management_id'])
            ->where('stockroom_id', (new _StockRoom)::getStockroomIdByManagement($data)->stockroom_id)
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function getInventoryStockroom($data)
    {

        $stockroom_id = (new _StockRoom)::getStockroomIdByManagement($data)->stockroom_id;
        $management_id = $data['management_id'];

        $query = "SELECT *, product_id as product_ids,
        (SELECT IFNULL(sum(qty), 0) from stockroom_account_products where product_id = product_ids and type = 'IN') as _total_in_qty,
        (SELECT IFNULL(sum(qty), 0) from stockroom_account_products where product_id = product_ids and type = 'OUT') as _total_out_qty,
        (SELECT (_total_in_qty - _total_out_qty) ) _total_available_qty
        from stockroom_account_products where stockroom_id = '$stockroom_id' and management_id = '$management_id' group by product_id";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getInventoryStockroomDetails($data)
    {
        return DB::table('stockroom_account_products')->where('product_id', $data['product_id'])->get();
    }

    public static function processOutProduct($data)
    {

        date_default_timezone_set('Asia/Manila');

        $qry = DB::table('stockroom_account_products_temp')
            ->where('management_id', $data['management_id'])
            ->where('stockroom_id', (new _StockRoom)::getStockroomIdByManagement($data)->stockroom_id)
            ->where('type', 'OUT')
            ->get();

        $prods = [];

        foreach ($qry as $v) {
            $prods[] = array(
                "sap_id" => "sap-" . rand() . '-' . time(),
                "management_id" => $v->management_id,
                "stockroom_id" => $v->stockroom_id,
                "product_id" => $v->product_id,
                "product" => $v->product,
                "unit" => $v->unit,
                "qty" => $v->qty,
                "out_reason" => $data['out_reason'],
                "out_date" => $data['out_date'],
                "type" => 'OUT',
                "status" => 1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            );
        }
        DB::table('stockroom_account_products')->insert($prods);

        return DB::table('stockroom_account_products_temp')
            ->where('management_id', $data['management_id'])
            ->where('stockroom_id', (new _StockRoom)::getStockroomIdByManagement($data)->stockroom_id)
            ->where('type', 'OUT')
            ->delete();
    }
}
