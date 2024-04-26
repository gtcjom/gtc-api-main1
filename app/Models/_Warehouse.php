<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Warehouse extends Model
{
    public static function hisWarehouseHeaderInfo($data)
    {
        return DB::table('warehouse_accounts')
            ->select('warehouse_id', 'user_fullname as name', 'image', 'user_address as address')
            ->where('user_id', $data['user_id'])
            ->first();
    }

    public static function getPharmacyWarehouseId($data)
    {
        return DB::table('warehouse_accounts')
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function newWarehouseProducts($data)
    {
        // return DB::table('pharmacyclinic_warehose_products')->insert([
        //     'pwp_id' => 'pwp-' . rand(0, 9999) . time(),
        //     'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
        //     'management_id' => $data['management_id'],
        //     'product_id' => 'product-' . rand(0, 9999) . time(),
        //     'product_name' => $data['product_name'],
        //     'product_generic' => $data['product_generic'],
        //     'unit' => $data['unit'],
        //     'msrp' => $data['msrp'],
        //     'srp' => $data['srp'],
        //     'status' => 1,
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ]);
        return DB::table('pharmacyclinic_warehose_products')->insert([
            'pwp_id' => 'pwp-' . rand(0, 9999) . time(),
            'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'product_id' => 'product-' . rand(0, 9999) . time(),

            'product_code' => $data['product_code'],
            'product_name' => $data['product_name'],
            'product_generic' => $data['product_generic'],
            'product_category' => $data['product_category'],
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
        ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehose_products.product_name')
        ->leftJoin('pharmacyclinic_warehouse_category', 'pharmacyclinic_warehouse_category.category_id', '=', 'pharmacyclinic_warehose_products.product_category')
        ->select('pharmacyclinic_warehose_products.*', 'pharmacyclinic_warehouse_brand.brand as brandName', 'pharmacyclinic_warehouse_category.category as categoryName')
        ->where('pharmacyclinic_warehose_products.management_id', $data['management_id'])
        ->where('pharmacyclinic_warehose_products.warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
        ->where('pharmacyclinic_warehose_products.status', 1)
        ->orderBy('pharmacyclinic_warehouse_category.category', 'ASC')
        ->get();
    }

    public static function getProductListUnAvailable($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
        ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehose_products.product_name')
        ->leftJoin('pharmacyclinic_warehouse_category', 'pharmacyclinic_warehouse_category.category_id', '=', 'pharmacyclinic_warehose_products.product_category')
        ->select('pharmacyclinic_warehose_products.*', 'pharmacyclinic_warehouse_brand.brand as brandName', 'pharmacyclinic_warehouse_category.category as categoryName')
        ->where('pharmacyclinic_warehose_products.management_id', $data['management_id'])
        ->where('pharmacyclinic_warehose_products.warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
        ->where('pharmacyclinic_warehose_products.status', 0)
        ->orderBy('pharmacyclinic_warehouse_category.category', 'ASC')
        ->get();
    }

    public static function getProducsListForNewInvoice($data)
    {
        // return DB::table('pharmacyclinic_warehose_products')
        //     ->select('*', 'product_name as label', 'product_id as value')
        //     ->where('management_id', $data['management_id'])
        //     ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
        //     ->get();
        return DB::table('pharmacyclinic_warehose_products')
        ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehose_products.product_name')
        ->leftJoin('pharmacyclinic_warehouse_category', 'pharmacyclinic_warehouse_category.category_id', '=', 'pharmacyclinic_warehose_products.product_category')
        ->select('pharmacyclinic_warehose_products.*', 'pharmacyclinic_warehouse_brand.brand as brandName', 'pharmacyclinic_warehouse_category.category as categoryName', 'pharmacyclinic_warehose_products.product_code as label', 'pharmacyclinic_warehose_products.product_id as value')
        ->where('pharmacyclinic_warehose_products.management_id', $data['management_id'])
        ->where('pharmacyclinic_warehose_products.warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
        ->where('pharmacyclinic_warehose_products.status', 1)
        ->get();
    }

    public static function getProducsByBatchNo($product_id, $batchno)
    {
        return DB::table('pharmacyclinic_warehouse_inventory')
            ->where('batch_number', $batchno)
            ->where('product_id', $product_id)
            ->get();
    }


    public static function saveProductToTemp($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_temp')->insert([
            'pwit_id' => 'pwit-' . rand(0, 9999) . time(),
            'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
            'management_id' => $data['management_id'],
            'product_id' => $data['product_name'],
            'product_name' => $data['selectedName'],
            'product_generic' => $data['selectedGeneric'],
            'unit' => $data['selectedUnit'],
            'msrp' => $data['selectedMsrp'],
            'srp' => $data['selectedSrp'],
            'qty' => $data['product_quantity'],
            'batch_number' => $data['product_batch'],
            'expiration_date' => date('Y-m-d', strtotime($data['product_expiration'])),
            'manufactured_date' => date('Y-m-d', strtotime($data['product_manufactured'])),
            'type' => 'IN',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getProductTempList($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_temp')
            ->where('management_id', $data['management_id'])
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('type', 'IN')
            ->get();
    }

    public static function removeItemFromUnsaveList($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_temp')
            ->where('pwit_id', $data['remove_id'])
            ->delete();
    }

    public static function processUnsaveProduct($data)
    {
        $tempProducts = DB::table('pharmacyclinic_warehouse_inventory_temp')
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', $data['management_id'])
            ->get();

        $products = [];
        foreach ($tempProducts as $v) {
            $products[] = array(
                'pwif_id' => '' . rand(0, 9999) . time(),
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
                'delivered_date' => $data['dr_date'],
                'delivered_by' => $data['dr_by'],
                'type' => $v->type,
                'status' => $v->status,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at,
            );
        }

        DB::table('pharmacyclinic_warehouse_inventory_forapproval')->insert($products);

        return DB::table('pharmacyclinic_warehouse_inventory_temp')
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', $data['management_id'])
            ->delete();
    }

    public static function getProductInventory($data)
    {
        $warehouse_id = _Warehouse::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = $data['management_id'];

        $query = "SELECT *, product_id as product_ids,
            
            (SELECT product_code from pharmacyclinic_warehose_products where pharmacyclinic_warehose_products.product_id = pharmacyclinic_warehouse_inventory.product_id LIMIT 1) as product_code,
            (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = product_ids and type = 'IN') as _total_in_qty,
            (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = product_ids and type = 'OUT') as _total_out_qty,
            (SELECT (_total_in_qty - _total_out_qty) ) _total_available_qty
        from pharmacyclinic_warehouse_inventory where warehouse_id = '$warehouse_id' and management_id = '$management_id' group by product_id";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getProductDetails($data)
    {
        // $product_id = $data['product_id'];
        // $warehouse_id = _Warehouse::getPharmacyWarehouseId($data)->warehouse_id;
        // $management_id = $data['management_id'];

        // $query = "SELECT *, product_id as product_ids, batch_number as batch_id,
        // (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = product_ids and type = 'IN' and batch_number = batch_id) as _total_in_qty,
        // (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = product_ids and type = 'OUT' and batch_number = batch_id) as _total_out_qty,
        // (SELECT (_total_in_qty - _total_out_qty)) as _total_available_qty
        // from pharmacyclinic_warehouse_inventory where warehouse_id = '$warehouse_id' and product_id = '$product_id' group by batch_number";

        // $result = DB::connection('mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);
        $product_id = $data['product_id'];
        $warehouse_id = _Warehouse::getPharmacyWarehouseId($data)->warehouse_id;
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

    public static function getProductBatchDetails($data)
    {
        $product_id = $data['product_id'];
        $warehouse_id = _Warehouse::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = $data['management_id'];
        $batch_number = $data['batch_number'];

        $query = "SELECT *, product_id as product_ids, batch_number as batch_id,
        (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = product_ids and type = 'IN' and batch_number = batch_id) as _total_in_qty,
        (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory_temp where product_id = product_ids and type = 'OUT' and batch_number = batch_id) as _total_out_qty_intemp,
        (SELECT IFNULL(sum(qty), 0) from pharmacyclinic_warehouse_inventory where product_id = product_ids and type = 'OUT' and batch_number = batch_id) as _total_out_qty
        from pharmacyclinic_warehouse_inventory where warehouse_id = '$warehouse_id' and product_id = '$product_id' and batch_number = '$batch_number' group by batch_number";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

    }

    public static function getAccountList($data)
    {
        return DB::table('pharmacyclinic_warehose_draccounts')
            ->leftJoin('pharmacyclinic_warehose_draccounts_agent', 'pharmacyclinic_warehose_draccounts_agent.agent_id', '=', 'pharmacyclinic_warehose_draccounts.sales_agent_id')
            ->select('pharmacyclinic_warehose_draccounts.*', 'pharmacyclinic_warehose_draccounts_agent.*', "pharmacyclinic_warehose_draccounts.pharmacy_name as label", "pharmacyclinic_warehose_draccounts.pwdr_id as value")
            ->where('pharmacyclinic_warehose_draccounts.warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('pharmacyclinic_warehose_draccounts.management_id', $data['management_id'])
            ->where('pharmacyclinic_warehose_draccounts.status', 1)
            ->get();
    }

    public static function accountSave($data)
    {
        return DB::table('pharmacyclinic_warehose_draccounts')
            ->insert([
                'pwdr_id' => 'pwdr' . rand(0, 9999) . time(),
                'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
                'management_id' => $data['management_id'],
                'pharmacy_name' => $data['account_name'],
                'pharmacy_address' => $data['account_address'],
                'contact_number' => $data['contact_number'],
                'contact_person' => $data['contact_person'],
                'contact_position' => $data['contact_position'],
                'sales_agent_id' => $data['agent_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function removeItem($data)
    {
        return DB::table('pharmacyclinic_warehose_draccounts')
            ->where('pwdr_id', $data['pwdr_id'])
            ->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function saveProductDrtoTemp($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_temp')
            ->insert([
                'pwit_id' => 'pwit-' . rand(0, 9999) . time(),
                'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
                'management_id' => $data['management_id'],
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
        // return DB::table('pharmacyclinic_warehouse_inventory_temp')
        //     ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
        //     ->where('management_id', $data['management_id'])
        //     ->where('type', 'OUT')
        //     ->get();
        return DB::table('pharmacyclinic_warehouse_inventory_temp')
            ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_temp.product_name')
            ->select('pharmacyclinic_warehouse_inventory_temp.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
            ->where('pharmacyclinic_warehouse_inventory_temp.warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('pharmacyclinic_warehouse_inventory_temp.management_id', $data['management_id'])
            ->where('pharmacyclinic_warehouse_inventory_temp.type', 'OUT')
            ->get();
    }

    public static function checkProductInTemp($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_temp')
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', $data['management_id'])
            ->where('product_id', $data['product_id'])
            ->where('batch_number', $data['batch_number'])
            ->where('type', 'OUT')
            ->get();
    }

    public static function removeItemFromTempList($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_temp')
            ->where('id', $data['remove_id'])
            ->delete();
    }

    public static function processDrItemsInTemp($data)
    {
        $dr_account = 'pwdr'.rand(0,99).time();
        $products = DB::table('pharmacyclinic_warehouse_inventory_temp')
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', $data['management_id'])
            ->where('type', 'OUT')
            ->get();

        if($data['sort'] == 'new-acc'){
            DB::table('pharmacyclinic_warehose_draccounts')
            ->insert([
                'pwdr_id' => $dr_account,
                'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
                'management_id' => $data['management_id'],
                'pharmacy_name' => $data['new_account'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $dritems = [];
        foreach ($products as $v) {
            $dritems[] = array(
                'pwif_id' => 'pwif-' . rand() . time(),
                'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
                'management_id' => $data['management_id'],
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
                'dr_account' => $data['sort'] == 'new-acc' ? $dr_account : $data['dr_account'],
                'dr_accountname' => $data['sort'] == 'new-acc' ? $data['new_account'] : $data['dr_accountname'],
                'dr_accountaddress' => $data['sort'] == 'new-acc' ? NULL : $data['dr_accountaddress'],
                'dr_date' => date('Y-m-d H:i:s'),
                'expiration_date' => $v->expiration_date,
                'manufactured_date' => $v->manufactured_date,
                'type' => $v->type,
                'status' => $v->status,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at,
            );
        }

        DB::table("pharmacyclinic_warehouse_inventory_forapproval")->insert($dritems);

        return DB::table('pharmacyclinic_warehouse_inventory_temp')
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', $data['management_id'])
            ->where('type', 'OUT')
            ->delete();
    }

    public static function getDrProducts($data)
    {
        $warehouse_id = _Warehouse::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = $data['management_id'];

        $query = "SELECT *
        from pharmacyclinic_warehouse_inventory where type='OUT' and warehouse_id = '$warehouse_id' and management_id = '$management_id' group by dr_number ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getDrNumberDetails($data)
    {
        $warehouse_id = _Warehouse::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = $data['management_id'];

        $dr_number = $data['dr_number'];
        $invoice_number = $data['invoice_number'];
        $type = $data['type'];

        $dr = "SELECT *,
            (SELECT brand from pharmacyclinic_warehouse_brand WHERE pharmacyclinic_warehouse_brand.brand_id = pharmacyclinic_warehouse_inventory.product_name limit 1) as brandName
        from pharmacyclinic_warehouse_inventory where type='OUT' and warehouse_id = '$warehouse_id' and management_id = '$management_id' and dr_number = '$dr_number' ";

        $invoice = "SELECT * from pharmacyclinic_warehouse_inventory where type='IN' and warehouse_id = '$warehouse_id' and management_id = '$management_id' and invoice_number = '$invoice_number' ";

        $result = DB::connection('mysql')->getPdo()->prepare($type == 'delivery' ? $dr : $invoice);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getMonitoringList($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory')
            ->leftJoin('pharmacyclinic_warehose_products', 'pharmacyclinic_warehose_products.product_id', '=', 'pharmacyclinic_warehouse_inventory.product_id')
            ->select('pharmacyclinic_warehouse_inventory.*', 'pharmacyclinic_warehose_products.product_code as product_code')
            ->where('pharmacyclinic_warehouse_inventory.warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->orderBy('pharmacyclinic_warehouse_inventory.created_at', 'DESC')
            ->get();
    }

    public static function warehouseGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM warehouse_accounts WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function warehouseUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('warehouse_accounts')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function warehouseUpdatePersonalInfo($data)
    {
        return DB::table('warehouse_accounts')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function warehouseUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function warehouseUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    // new routes 7-5-2021 1:14pm

    public static function getForApprovalInvoice($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', $data['management_id'])
            ->where('type', 'IN')
            ->groupBy('invoice_number')
            ->get();
    }

    public static function getForApprovalInvoiceDetails($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', $data['management_id'])
            ->where('invoice_number', $data['invoice_number'])
            ->get();
    }

    public static function getForApprovalDr($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', $data['management_id'])
            ->where('type', 'OUT')
            ->groupBy('dr_number')
            ->get();
    }

    public static function getForApprovalDrDetails($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', $data['management_id'])
            ->where('dr_number', $data['dr_number'])
            ->get();
    }

    public static function getDrAccountAgentList($data)
    {
        return DB::table('pharmacyclinic_warehose_draccounts_agent')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getMainManagementId($data)
    {
        return DB::table('general_management_branches')->select('general_management_id')->where('management_id', $data['management_id'])->first();
    }

    public static function newDrAccountAgent($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('pharmacyclinic_warehose_draccounts_agent')->insert([
            'phwda_id' => 'phwda-' . rand(0, 9999) . '-' . time(),
            'agent_id' => 'agent-' . rand(0, 9999) . '-' . time(),
            'management_id' => $data['management_id'],
            'name' => $data['name'],
            'main_mngt_id' => _Warehouse::getMainManagementId($data)->general_management_id,
            'address' => $data['address'],
            'contact' => $data['contact'],
            'date_started' => date('Y-m-d', strtotime($data['date_started'])),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getDrAccountProductDelivered($data)
    { 
        return DB::table('pharmacyclinic_warehouse_inventory')
            ->where('dr_account', $data['account_id'])
            ->get();
    }

    public static function getAgentAccounts($data)
    { 

        return DB::table('pharmacyclinic_warehose_draccounts')
            ->leftJoin('pharmacyclinic_warehose_draccounts_agent', 'pharmacyclinic_warehose_draccounts_agent.agent_id', 'pharmacyclinic_warehose_draccounts.sales_agent_id')
            ->where('sales_agent_id', $data['agent_id'])
            ->get();
    }

    public static function getProductMonitoringDetails($data)
    { 

        return DB::table('pharmacyclinic_warehouse_inventory')
        ->where('product_id', $data['product_id'])
            ->get();
    }
    
    public static function getDrAccountProductDeliveredByReport($data)
    {

        $date_from = date('Y-m-d 00:00:00', strtotime($data['date_from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['date_to']));

        return DB::table('pharmacyclinic_warehouse_inventory')
            ->where('dr_account', $data['account_id'])
            ->where('dr_date', '>=', $date_from)
            ->where('dr_date', '<=', $date_to)
            ->get();
    }

    public static function createNewBrand($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('pharmacyclinic_warehouse_brand')->insert([
            'brand_id' => 'b-' . rand(0, 9) . '-' . time(),
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'brand' => $data['brand'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getHaptechBrandList($data)
    {
        return DB::table('pharmacyclinic_warehouse_brand')
        ->select('*', 'brand_id as value', 'brand as label')
        ->where('main_mgmt_id', $data['main_mgmt_id'])
        ->get();
    }

    public static function createNewCategory($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('pharmacyclinic_warehouse_category')->insert([
            'category_id' => 'c-' . rand(0, 9) . '-' . time(),
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'category' => $data['category'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getHaptechCategoryList($data)
    {
        return DB::table('pharmacyclinic_warehouse_category')
            ->select('*', 'category_id as value', 'category as label')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function getBrandListForNewInvoice($data)
    {
        return DB::table('pharmacyclinic_warehouse_brand')
        ->select('brand_id', 'brand as label', 'brand_id as value')
        ->where('management_id', $data['management_id'])
        ->orderBy('brand', 'ASC')
        ->get();
    }

    public static function getCategoryListForNewInvoice($data)
    {
        return DB::table('pharmacyclinic_warehouse_category')
        ->select('category_id', 'category as label', 'category_id as value')
        ->where('management_id', $data['management_id'])
        ->orderBy('category', 'ASC')
        ->get();
    }

    public static function getDescListForNewInvoice($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
        ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehose_products.product_name')
        ->leftJoin('pharmacyclinic_warehouse_category', 'pharmacyclinic_warehouse_category.category_id', '=', 'pharmacyclinic_warehose_products.product_category')
        ->select('pharmacyclinic_warehose_products.*', 'pharmacyclinic_warehouse_brand.brand as brandName', 'pharmacyclinic_warehouse_category.category as categoryName', 'pharmacyclinic_warehose_products.product_generic as label', 'pharmacyclinic_warehose_products.product_id as value')
        ->where('pharmacyclinic_warehouse_brand.brand', $data['brand'])
        ->where('pharmacyclinic_warehose_products.management_id', $data['management_id'])
        ->where('pharmacyclinic_warehose_products.warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
        ->orderBy('pharmacyclinic_warehouse_brand.brand', 'ASC')
        ->where('pharmacyclinic_warehose_products.status', 1)
        ->get();
    }

    public static function editAccountInfo($data)
    {
        DB::table('pharmacyclinic_warehouse_inventory_forapproval')
        ->where('dr_account', $data['dr_account'])
        ->update([
            'dr_accountname' => $data['pharmacy_name'],
            'dr_accountaddress' => $data['pharmacy_address'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('pharmacyclinic_warehouse_inventory')
        ->where('dr_account', $data['dr_account'])
        ->update([
            'dr_accountname' => $data['pharmacy_name'],
            'dr_accountaddress' => $data['pharmacy_address'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('pharmacyclinic_warehose_draccounts')
        ->where('pwdr_id', $data['dr_account'])
        ->update([
            'sales_agent_id' => $data['agent_id'],
            'pharmacy_name' => $data['pharmacy_name'],
            'pharmacy_address' => $data['pharmacy_address'],
            'contact_number' => $data['contact_number'],
            'contact_person' => $data['contact_person'],
            'contact_position' => $data['contact_position'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function savePOToTemp($data)
    {   
        $product_id = 'product'.rand(0,9).time();
        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')->insert([
            'pwpit_id' => 'pwpit-' . rand(0, 9999) . time(),
            'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
            'management_id' => $data['management_id'],
            'product_id' => $data['type'] == 'existing' ? $data['product_id'] : $product_id,
            'product_name' => $data['product_name'],
            'product_generic' => $data['selectedGeneric'],
            'product_category' => $data['product_category'],
            'unit' => $data['selectedUnit'],
            'qty' => $data['product_quantity'],
            'type' => $data['type'] == 'existing' ? 'old' : 'new',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getProductToPOList($data)
    {
        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')
            ->select('pharmacyclinic_warehouse_po_invoice_temp.*')
            ->where('pharmacyclinic_warehouse_po_invoice_temp.warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('pharmacyclinic_warehouse_po_invoice_temp.management_id', $data['management_id'])
            ->whereNull('pharmacyclinic_warehouse_po_invoice_temp.is_saved')
            ->get();
    }

    public static function removeItemFromPOTempList($data)
    {
        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')
            ->where('id', $data['remove_id'])
            ->delete();
    }

    public static function processPOItemsInTemp($data)
    {
        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')
            ->where('management_id', $data['management_id'])
            ->whereNull('is_saved')
            ->update([
                'saved_by' => $data['user_id'],
                'po_number' => $data['po_number'],
                'is_saved' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getPONumberDetails($data)
    {
        $warehouse_id = _Warehouse::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = $data['management_id'];
        $po_number = $data['po_number'];

        $invoice = "SELECT * FROM pharmacyclinic_warehouse_po_invoice_temp WHERE warehouse_id = '$warehouse_id' AND management_id = '$management_id' AND po_number = '$po_number' AND is_saved IS NOT NULL ";

        $result = DB::connection('mysql')->getPdo()->prepare($invoice);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public static function getProductsByPONumber($data)
    {
        // return DB::table('pharmacyclinic_warehouse_po_invoice_temp')
        //     ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
        //     ->where('management_id', $data['management_id'])
        //     ->where('po_number', $data['po_number'])
        //     ->where('is_saved', 1)
        //     ->get();


        $warehouse_id = _Warehouse::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = $data['management_id'];
        $po_number = $data['po_number'];

        $query = "SELECT *,
            (SELECT product_code FROM pharmacyclinic_warehose_products WHERE pharmacyclinic_warehose_products.product_id = pharmacyclinic_warehouse_po_invoice_temp.product_id limit 1 ) as prod_code,
            (SELECT msrp FROM pharmacyclinic_warehose_products WHERE pharmacyclinic_warehose_products.product_id = pharmacyclinic_warehouse_po_invoice_temp.product_id limit 1 ) as prod_msrp,
            (SELECT srp FROM pharmacyclinic_warehose_products WHERE pharmacyclinic_warehose_products.product_id = pharmacyclinic_warehouse_po_invoice_temp.product_id limit 1 ) as prod_srp,

            (SELECT count(id) FROM pharmacyclinic_warehouse_po_invoice_temp WHERE po_number = '$po_number' AND management_id = '$management_id' AND is_saved = 1 AND msrp IS NULL ) as nullmsrp,
            (SELECT count(id) FROM pharmacyclinic_warehouse_po_invoice_temp WHERE po_number = '$po_number' AND management_id = '$management_id' AND is_saved = 1 AND srp IS NULL ) as nullsrp,

            (SELECT count(id) FROM pharmacyclinic_warehouse_po_invoice_temp WHERE po_number = '$po_number' AND management_id = '$management_id' AND is_saved = 1 AND product_code IS NULL ) as nullprodcode,
            (SELECT count(id) FROM pharmacyclinic_warehouse_po_invoice_temp WHERE po_number = '$po_number' AND management_id = '$management_id' AND is_saved = 1 AND batch_number IS NULL ) as nullbatchno,
            (SELECT count(id) FROM pharmacyclinic_warehouse_po_invoice_temp WHERE po_number = '$po_number' AND management_id = '$management_id' AND is_saved = 1 AND expiration_date IS NULL ) as nullexpirydate,
            (SELECT count(id) FROM pharmacyclinic_warehouse_po_invoice_temp WHERE po_number = '$po_number' AND management_id = '$management_id' AND is_saved = 1 AND manufactured_date IS NULL ) as nullmfgdate,


            (SELECT GREATEST(0, nullmsrp + nullsrp + nullprodcode + nullbatchno + nullexpirydate + nullmfgdate )) as _total_count
        FROM pharmacyclinic_warehouse_po_invoice_temp WHERE warehouse_id = '$warehouse_id' AND management_id = '$management_id' AND po_number = '$po_number' AND is_saved = 1 AND is_confirmed IS NULL ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public static function saveCompletePOToTemp($data)
    {   
        $product_id = 'product'.rand(0,9).time();
        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')->insert([
            'pwpit_id' => 'pwpit-' . rand(0, 9999) . time(),
            'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
            'management_id' => $data['management_id'],
            'product_id' => $data['type'] == 'existing' ? $data['product_id'] : $product_id,
            'product_name' => $data['product_name'],
            'product_generic' => $data['selectedGeneric'],
            'unit' => $data['selectedUnit'],
            'qty' => $data['product_quantity'],
            'type' => $data['type'] == 'existing' ? 'old' : 'new',
            'saved_by' => $data['user_id'],
            'po_number' => $data['po_number'],
            'is_saved' => 1,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getProducsByProdId($product_id)
    {
        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')
            ->where('product_id', $product_id)
            ->get();
    }

    public static function checkIfCodeAlreadyExisted($product_code)
    {
        return DB::table('pharmacyclinic_warehose_products')
            ->where('product_code', $product_code)
            ->get();
    }

    public static function checkPOIfExistByGivenPO($po_number)
    {
        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')
            ->where('po_number', $po_number)
            ->get();
    }

    public static function checkInvoiceIfExistedByGivenInvoice($invoice_number)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_forapproval')
            ->where('invoice_number', $invoice_number)
            ->get();
    }

    public static function saveProductInfo($data)
    {
        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')
            ->where('management_id', $data['management_id'])
            ->where('product_id', $data['product_id'])
            ->where('is_saved', 1)
            ->update([
                'product_code' => $data['product_code'],
                'msrp' => $data['selectedBatchType'] == 'new_batch' ? $data['msrp'] : $data['msrp'],
                'srp' => $data['selectedBatchType'] == 'new_batch' ? $data['srp'] : $data['srp'],
                'batch_number' => $data['selectedBatchType'] == 'new_batch' ? $data['product_batch'] : $data['productBatch'],
                'expiration_date' => $data['selectedBatchType'] == 'new_batch' ? date('Y-m-d', strtotime($data['product_expiration'])) : date('Y-m-d', strtotime($data['productExpiry'])),
                'manufactured_date' => $data['selectedBatchType'] == 'new_batch' ? date('Y-m-d', strtotime($data['product_manufactured'])) : date('Y-m-d', strtotime($data['productMfg'])),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    
    public static function saveConfirmedItem($data){
        $products = DB::table('pharmacyclinic_warehouse_po_invoice_temp')
            ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('management_id', $data['management_id'])
            ->where('po_number', $data['po_number'])
            ->get();
    
        $potoinvoice = [];
        $newproduct = [];
        foreach ($products as $v) {
            if($v->type == 'new'){
                $queeryy = DB::table('pharmacyclinic_warehouse_brand')->select('brand_id')->where('brand', $v->product_name)->where('management_id', $data['management_id'])->first();

                $newproduct[] = array(
                    'pwp_id' => 'pwp-' . rand(0,9) . time(),
                    'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'product_id' => $v->product_id,
                    'product_code' => $v->product_code,
                    'product_name' => $queeryy->brand_id,
                    'product_generic' => $v->product_generic,
                    'product_category' => $v->product_category,
                    'unit' => $v->unit,
                    'msrp' => $v->msrp,
                    'srp' => $v->srp,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            }

            $potoinvoice[] = array(
                'pwif_id' => 'pwif-' . rand() . time(),
                'warehouse_id' => _Warehouse::getPharmacyWarehouseId($data)->warehouse_id,
                'management_id' => $data['management_id'],
                'product_id' => $v->product_id,
                'product_name' => $v->product_name,
                'product_generic' => $v->product_generic,
                'unit' => $v->unit,
                'msrp' => $v->msrp,
                'srp' => $v->srp,
                'qty' => $v->qty,
                'batch_number' => $v->batch_number,
                'po_number' => $v->po_number,
                'invoice_number' => $data['invoice_number'],
                'expiration_date' => $v->expiration_date,
                'manufactured_date' => $v->manufactured_date,
                'delivered_date' => date('Y-m-d', strtotime($data['dr_date'])),
                'delivered_by' => $data['dr_by'],
                'type' => 'IN',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table("pharmacyclinic_warehouse_inventory_forapproval")->insert($potoinvoice);
        DB::table("pharmacyclinic_warehose_products")->insert($newproduct);

        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')
        ->where('warehouse_id', _Warehouse::getPharmacyWarehouseId($data)->warehouse_id)
        ->where('management_id', $data['management_id'])
        ->where('po_number', $data['po_number'])
        ->update([
            'is_confirmed' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function updateBrandName($data)
    {
        DB::table('pharmacyclinic_warehouse_brand')
        ->where('management_id', $data['management_id'])
        ->where('brand_id', $data['brand_id'])
        ->update([
            'brand' => $data['brand'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('pharmacyclinic_warehouse_inventory_forapproval')
        ->where('management_id', $data['management_id'])
        ->where('product_name', $data['old_brand'])
        ->update([
            'product_name' => $data['brand'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('pharmacyclinic_warehouse_po_invoice_temp')
        ->where('management_id', $data['management_id'])
        ->where('product_name', $data['old_brand'])
        ->update([
            'product_name' => $data['brand'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('pharmacyclinic_warehouse_inventory_temp')
        ->where('management_id', $data['management_id'])
        ->where('product_name', $data['old_brand'])
        ->update([
            'product_name' => $data['brand'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('pharmacyclinic_warehouse_inventory')
        ->where('management_id', $data['management_id'])
        ->where('product_name', $data['old_brand'])
        ->update([
            'product_name' => $data['brand'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function updateCategoryName($data)
    {
        DB::table('pharmacyclinic_warehouse_category')
        ->where('management_id', $data['management_id'])
        ->where('category_id', $data['category_id'])
        ->update([
            'category' => $data['category'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')
        ->where('management_id', $data['management_id'])
        ->where('product_category', $data['old_category'])
        ->update([
            'product_category' => $data['category'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function updateProductById($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
        ->where('management_id', $data['management_id'])
        ->where('product_id', $data['product_id'])
        ->update([
            'product_code' => $data['product_code'],
            'product_generic' => $data['product_generic'],
            'product_category' => $data['product_category'],
            'unit' => $data['unit'],
            'msrp' => $data['msrp'],
            'srp' => $data['srp'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function updateProductDeactivate($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
        ->where('management_id', $data['management_id'])
        ->where('product_id', $data['product_id'])
        ->update([
            'status' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function updateProductActivate($data)
    {
        return DB::table('pharmacyclinic_warehose_products')
        ->where('management_id', $data['management_id'])
        ->where('product_id', $data['product_id'])
        ->update([
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getLastPONumber($data)
    {
        return DB::table('pharmacyclinic_warehouse_po_invoice_temp')
        ->select('po_number')
        ->where('management_id', $data['management_id'])
        ->orderBy('id', 'DESC')
        ->whereNotNull('po_number')
        ->first();
    }
    
    public static function updateHaptechRequestToDR($data)
    {
        $management_id = _Accounting::getPharmacyWarehouseId($data)->management_id;
        $warehouse_id = _Accounting::getPharmacyWarehouseId($data)->warehouse_id;
        return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
        ->where('management_id', $management_id)
        ->where('warehouse_id', $warehouse_id)
        ->where('pwite_id', $data['pwite_id'])
        ->update([
            'item_check' => $data['item_check'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function confirmHaptechRequestToDR($data)
    {
        $management_id = _Accounting::getPharmacyWarehouseId($data)->management_id;
        $warehouse_id = _Accounting::getPharmacyWarehouseId($data)->warehouse_id;

        if($data['status'] == 'all-approve'){
            return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->where('management_id', $management_id)
            ->where('warehouse_id', $warehouse_id)
            ->update([
                'supplier_approve' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }else{
            return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->where('management_id', $management_id)
            ->where('warehouse_id', $warehouse_id)
            ->update([
                'accounting_approve' => NULL,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function checkDRNumberIfExist($data)
    {
        $warehouse_id = _Accounting::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = _Accounting::getPharmacyWarehouseId($data)->management_id;
        $dr_number = $data['dr_number'];

        return DB::table('pharmacyclinic_warehouse_inventory')
            ->where('warehouse_id', $warehouse_id)
            ->where('management_id', $management_id)
            ->where("dr_number", $dr_number)
            ->get();
    }

    public static function updateExclusiveToDR($data)
    {
        $dr_account = 'pwdr'.rand(0,99).time();
        $request_id = $data['request_id'];
        $warehouse_id = _Accounting::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = _Accounting::getPharmacyWarehouseId($data)->management_id;

        $products = DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->where('warehouse_id', $warehouse_id)
            ->where('management_id', $management_id)
            ->where('request_id', $request_id)
            ->where('type', 'OUT')
            ->get();

        if($data['sort'] == 'new-acc'){
            DB::table('pharmacyclinic_warehose_draccounts')
            ->insert([
                'pwdr_id' => $dr_account,
                'warehouse_id' => $warehouse_id,
                'management_id' => $management_id,
                'pharmacy_name' => $data['new_account'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

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
                'dr_number' => $data['dr_number'],
                'dr_totalamount' => $data['dr_totalamount'],
                'dr_account' => $data['sort'] == 'new-acc' ? $dr_account : $data['dr_account'],
                'dr_accountname' => $data['sort'] == 'new-acc' ? $data['new_account'] : $data['dr_accountname'],
                'dr_accountaddress' => $data['sort'] == 'new-acc' ? NULL : $data['dr_accountaddress'],
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

        return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->where('warehouse_id', $warehouse_id)
            ->where('management_id', $management_id)
            ->where("request_id", $request_id)
            ->where('type', 'OUT')
            ->update([
                'finalized' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]); 
    }
    
    public static function getSupplierAllRequest($data){
        return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
        ->where('warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
        ->where('management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
        ->whereNotNull('request_id')
        ->where('accounting_approve', 1)
        ->where('type', 'OUT')
        ->groupBy('request_id')
        ->orderBy('created_at', 'DESC')
        ->get();
    }
    
    
}
