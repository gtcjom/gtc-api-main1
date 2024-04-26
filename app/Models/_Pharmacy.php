<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Pharmacy extends Model
{
    public static function hispharmacyGetHeaderInfo($data)
    {
        // return DB::table('pharmacy')
        // ->select('pharmacy_id', 'user_fullname as name', 'image', 'user_address as address')
        // ->where('user_id', $data['user_id'])
        // ->first();

        return DB::table('pharmacy')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'pharmacy.user_id')
            ->select('pharmacy.pharmacy_id', 'pharmacy.user_fullname as name', 'pharmacy.image', 'pharmacy.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('pharmacy.user_id', $data['user_id'])
            ->first();
    }

    public static function hispharmacyGetRole($data)
    {
        return DB::table('pharmacy')
            ->where('user_id', $data['user_id'])
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function hispharmacyGetInventoryList($management_id)
    {
        return DB::table('pharmacyhospital_products')
            ->join('pharmacyhospital_inventory', 'pharmacyhospital_inventory.product_id', '=', 'pharmacyhospital_products.product_id')
            ->select('pharmacyhospital_products.*', 'pharmacyhospital_inventory.expiry_date', 'pharmacyhospital_inventory.unit', 'pharmacyhospital_inventory.quantity', DB::raw("sum(pharmacyhospital_inventory.quantity) as sum_all_quantity"))
            ->where('pharmacyhospital_products.management_id', $management_id)
            ->groupBy('pharmacyhospital_products.product')
            ->orderBy('pharmacyhospital_products.product', 'ASC')
            ->get();
    }

    public static function hispharmacyGetPuchaseList($data)
    {
        $query = "SELECT *,
            (SELECT IFNULL(SUM(pharmacyhospital_temporary_out.total), 0) from pharmacyhospital_temporary_out where pharmacyhospital_temporary_out.management_id = '" . $data['management_id'] . "' AND pharmacyhospital_temporary_out.user_id = '" . $data['user_id'] . "') as sumAllTotalCost
        from pharmacyhospital_temporary_out where management_id = '" . $data['management_id'] . "' AND user_id = '" . $data['user_id'] . "' ORDER BY id DESC ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyGetBrandList($data)
    {
        return DB::table('pharmacyhospital_products')
            ->select('product', 'product_id')
            ->where('management_id', $data['management_id'])
            ->where('pharmacy_id', $data['pharmacy_id'])
            ->get();
    }

    public static function hispharmacyGetBatchList($data)
    {
        $query = "SELECT batch_no, product_id from pharmacyhospital_inventory where product_id = '" . $data['product_id'] . "' AND management_id = '" . $data['management_id'] . "' AND pharmacy_id = '" . $data['pharmacy_id'] . "' GROUP BY batch_no ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyGetBatchInfo($data)
    {
        $query = "SELECT expiry_date, quantity, unit, product_id,
            (SELECT product from pharmacyhospital_products where product_id = '" . $data['product_id'] . "') as product,
            (SELECT supplier from pharmacyhospital_products where product_id = '" . $data['product_id'] . "') as supplier,
            (SELECT description from pharmacyhospital_products where product_id = '" . $data['product_id'] . "') as generic,
            (SELECT srp from pharmacyhospital_products where product_id = '" . $data['product_id'] . "') as srp
        from pharmacyhospital_inventory where product_id = '" . $data['product_id'] . "' AND batch_no = '" . $data['batch_no'] . "' AND management_id = '" . $data['management_id'] . "' AND pharmacy_id = '" . $data['pharmacy_id'] . "' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyConfirmPaymentPurchase($data)
    {
        date_default_timezone_set('Asia/Manila');
        $payment = DB::table('pharmacyhospital_temporary_out')
            ->where('user_id', $data['user_id'])
            ->get();

        $invoice = rand(0, 9999999);
        $receipt = [];
        foreach ($payment as $v) {
            $receipt[] = array(
                'sales_id' => 'si-' . rand(0, 999999),
                'product_id' => $v->product_id,
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'product' => $v->product,
                'description' => $v->description,
                'unit' => $v->unit,
                'quantity' => $v->purchase_quantity,
                'total' => $v->total,
                'dr_no' => $data['receipt_id'],
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );

            DB::table('pharmacyhospital_history')->insert([
                'pch_id' => 'hist-' . rand(0, 999999),
                'product_id' => $v->product_id,
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'product' => $v->product,
                'description' => $v->description,
                'unit' => $v->unit,
                'quantity' => $v->purchase_quantity,
                'request_type' => 'OUT',
                'dr_no' => $data['receipt_id'],
                'supplier' => $v->supplier,
                'remarks' => $data['remarks_payment'],
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('pharmacyhospital_receipt')->insert([
                'pcr_id' => 'pcr-' . rand(0, 9999) . time(),
                'receipt_id' => $data['receipt_id'],
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'name_customer' => $data['client_name'],
                'address_customer' => $data['client_add'] === null ? null : $data['client_add'],
                'tin_customer' => $data['client_tin'] === null ? null : $data['client_tin'],
                'product' => $v->product,
                'description' => $v->description,
                'unit' => $v->unit,
                'quantity' => $v->purchase_quantity,
                'srp' => $v->price,
                'total' => $v->total,
                'amount_paid' => $data['amount_paid'],
                'payment_change' => (float) $data['totalCost'] - (float) $data['amount_paid'],
                'dr_no' => $data['receipt_id'],
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        DB::table('pharmacyhospital_sales')->insert($receipt);

        return DB::table('pharmacyhospital_temporary_out')
            ->where('user_id', $data['user_id'])
            ->delete();
    }

    public static function hispharmacyNewProductSave($data)
    {
        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d', strtotime($data['expiry']));
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $day = date('d', strtotime($date));

        $notification_date = '';

        $product_id = 'p-' . rand(0, 99) . time();

        if ($month == 7) {
            $notification_date = $year . '-' . '01-' . $day;
        } else if ($month == 8 && $day == 29 || $month == 8 && $day == 30 || $month == 8 && $day == 31) {
            $notification_date = $year . '-' . '02-28';
        } else if ($month == 8) {
            $notification_date = $year . '-' . '02-' . $day;
        } else if ($month == 9) {
            $notification_date = $year . '-' . '03-' . $day;
        } else if ($month == 10 && $day == 31) {
            $notification_date = $year . '-' . '04-30';
        } else if ($month == 10) {
            $notification_date = $year . '-' . '04-' . $day;
        } else if ($month == 11) {
            $notification_date = $year . '-' . '05-' . $day;
        } else if ($month == 12 && $day == 31) {
            $notification_date = $year . '-' . '06-30';
        } else if ($month == 12) {
            $notification_date = $year . '-' . '06-' . $day;
        } else if ($month == 1) {
            $notification_date = ($year - 1) . '-07-' . $day;
        } else if ($month == 2) {
            $notification_date = ($year - 1) . '-08-' . $day;
        } else if ($month == 3 && $day == 31) {
            $notification_date = ($year - 1) . '-09-30';
        } else if ($month == 3) {
            $notification_date = ($year - 1) . '-09-' . $day;
        } else if ($month == 4) {
            $notification_date = ($year - 1) . '-10-' . $day;
        } else if ($month == 5 && $day == 31) {
            $notification_date = ($year - 1) . '-11-30';
        } else if ($month == 5) {
            $notification_date = ($year - 1) . '-11-' . $day;
        } else if ($month == 6) {
            $notification_date = ($year - 1) . '-12-' . $day;
        }

        DB::table('pharmacyhospital_inventory')->insert([
            'inventory_id' => 'stck-' . rand(0, 999999),
            'management_id' => $data['management_id'],
            'product_id' => $product_id,
            'pharmacy_id' => $data['pharmacy_id'],
            'dr_no' => $data['invoice'],
            'quantity' => $data['qty'],
            'unit' => $data['unit'],
            'starting_quantity' => $data['qty'],
            'manufacture_date' => $data['manufacture'],
            'batch_no' => $data['batch_no'],
            'expiry_date' => $data['expiry'],
            'notification_date' => $notification_date,
            'request_type' => 'IN',
            'comment' => $data['remarks'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('pharmacyhospital_products')->insert([
            'product_id' => $product_id,
            'pharmacy_id' => $data['pharmacy_id'],
            'management_id' => $data['management_id'],
            'product' => $data['brand'],
            'description' => $data['generic'],
            'supplier' => $data['supplier'],
            'unit_price' => $data['msrp'],
            'srp' => $data['srp'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return DB::table('pharmacyhospital_history')->insert([
            'pch_id' => 'hist-' . rand(0, 999999),
            'product_id' => $product_id,
            'pharmacy_id' => $data['pharmacy_id'],
            'management_id' => $data['management_id'],
            'username' => $data['user_id'],
            'product' => $data['brand'],
            'description' => $data['generic'],
            'unit' => $data['unit'],
            'quantity' => $data['qty'],
            'request_type' => 'IN',
            'dr_no' => $data['invoice'],
            'supplier' => $data['supplier'],
            'remarks' => $data['remarks'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function hispharmacyGetBatchesByProdId($data)
    {
        return DB::table('pharmacyhospital_inventory')
            ->join('pharmacyhospital_products', 'pharmacyhospital_products.product_id', '=', 'pharmacyhospital_inventory.product_id')
            ->select('pharmacyhospital_products.*', 'pharmacyhospital_inventory.*')
            ->where('pharmacyhospital_inventory.product_id', $data['product_id'])
            ->groupBy('pharmacyhospital_inventory.batch_no')
            ->get();
    }

    public static function hispharmacyAddNewStockByProdId($data)
    {
        date_default_timezone_set('Asia/Manila');
        date_default_timezone_set('Asia/Manila');

        $date = date('Y-m-d', strtotime($data['expiry']));
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $day = date('d', strtotime($date));
        $notification_date = '';

        if ($month == 7) {
            $notification_date = $year . '-' . '01-' . $day;
        } else if ($month == 8 && $day == 29 || $month == 8 && $day == 30 || $month == 8 && $day == 31) {
            $notification_date = $year . '-' . '02-28';
        } else if ($month == 8) {
            $notification_date = $year . '-' . '02-' . $day;
        } else if ($month == 9) {
            $notification_date = $year . '-' . '03-' . $day;
        } else if ($month == 10 && $day == 31) {
            $notification_date = $year . '-' . '04-30';
        } else if ($month == 10) {
            $notification_date = $year . '-' . '04-' . $day;
        } else if ($month == 11) {
            $notification_date = $year . '-' . '05-' . $day;
        } else if ($month == 12 && $day == 31) {
            $notification_date = $year . '-' . '06-30';
        } else if ($month == 12) {
            $notification_date = $year . '-' . '06-' . $day;
        } else if ($month == 1) {
            $notification_date = ($year - 1) . '-07-' . $day;
        } else if ($month == 2) {
            $notification_date = ($year - 1) . '-08-' . $day;
        } else if ($month == 3 && $day == 31) {
            $notification_date = ($year - 1) . '-09-30';
        } else if ($month == 3) {
            $notification_date = ($year - 1) . '-09-' . $day;
        } else if ($month == 4) {
            $notification_date = ($year - 1) . '-10-' . $day;
        } else if ($month == 5 && $day == 31) {
            $notification_date = ($year - 1) . '-11-30';
        } else if ($month == 5) {
            $notification_date = ($year - 1) . '-11-' . $day;
        } else if ($month == 6) {
            $notification_date = ($year - 1) . '-12-' . $day;
        }

        DB::table('pharmacyhospital_inventory')->insert([
            'inventory_id' => 'stock-' . rand(0, 999999),
            'management_id' => $data['management_id'],
            'product_id' => $data['product_id'],
            'pharmacy_id' => $data['pharmacy_id'],
            'dr_no' => $data['invoice'],
            'quantity' => $data['qty'],
            'unit' => $data['unit'],
            'starting_quantity' => $data['qty'],
            'manufacture_date' => $data['manufacture'],
            'batch_no' => $data['batch_no'],
            'expiry_date' => $data['expiry'],
            'notification_date' => $notification_date,
            'request_type' => 'IN',
            'comment' => $data['remarks'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return DB::table('pharmacyhospital_history')->insert([
            'pch_id' => 'hist-' . rand(0, 999999),
            'product_id' => $data['product_id'],
            'pharmacy_id' => $data['pharmacy_id'],
            'management_id' => $data['management_id'],
            'username' => $data['user_id'],
            'product' => $data['product'],
            'description' => $data['description'],
            'unit' => $data['unit'],
            'quantity' => $data['qty'],
            'request_type' => 'IN',
            'dr_no' => $data['invoice'],
            'supplier' => $data['supplier'],
            'remarks' => $data['remarks'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function hispharmacyAddQtyBySpecificBatch($data)
    {

        $getAvailableQty = DB::table('pharmacyhospital_inventory')
            ->select('quantity')
            ->where('product_id', $data['product_id'])
            ->where('batch_no', $data['batch_no'])
            ->where('expiry_date', $data['expiry'])
            ->first();

        $returnQty = $data['qty'] + $getAvailableQty->quantity;

        DB::table('pharmacyhospital_inventory')
            ->where('product_id', $data['product_id'])
            ->where('batch_no', $data['batch_no'])
            ->where('expiry_date', $data['expiry'])
            ->update([
                'quantity' => $returnQty,
            ]);

        return DB::table('pharmacyhospital_history')->insert([
            'pch_id' => 'hist-' . rand(0, 999999),
            'product_id' => $data['product_id'],
            'pharmacy_id' => $data['pharmacy_id'],
            'management_id' => $data['management_id'],
            'username' => $data['user_id'],
            'product' => $data['product'],
            'description' => $data['description'],
            'unit' => $data['unit'],
            'quantity' => $data['qty'],
            'request_type' => 'IN',
            'dr_no' => $data['invoice'],
            'supplier' => $data['supplier'],
            'remarks' => $data['remarks'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function hispharmacyDelQtyBySpecificBatch($data)
    {
        date_default_timezone_set('Asia/Manila');
        DB::table('pharmacyhospital_inventory')
            ->where('product_id', $data['product_id'])
            ->where('batch_no', $data['batch_no'])
            ->where('expiry_date', $data['expiry'])
            ->delete();

        return DB::table('pharmacyhospital_history')->insert([
            'pch_id' => 'hist-' . rand(0, 999999),
            'product_id' => $data['product_id'],
            'pharmacy_id' => $data['pharmacy_id'],
            'management_id' => $data['management_id'],
            'username' => $data['user_id'],
            'product' => $data['product'],
            'description' => $data['description'],
            'unit' => $data['unit'],
            'quantity' => $data['quantity'],
            'request_type' => 'DELETE',
            'supplier' => $data['supplier'],
            'remarks' => $data['reason'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function hispharmacyAddPuchase($data)
    {
        $getAvailableQty = DB::table('pharmacyhospital_inventory')
            ->select('quantity')
            ->where('product_id', $data['product_id'])
            ->where('batch_no', $data['batch_no'])
            ->where('expiry_date', $data['expiry'])
            ->first();

        $returnQty = (float) $getAvailableQty->quantity - (float) $data['quantity'];

        DB::table('pharmacyhospital_temporary_out')->insert([
            'pharmacy_id' => $data['pharmacy_id'],
            'user_id' => $data['user_id'],
            'management_id' => $data['management_id'],
            'product' => $data['product'],
            'product_id' => $data['product_id'],
            'batch_no' => $data['batch_no'],
            'description' => $data['description'],
            'supplier' => $data['supplier'],
            'expiry_date' => $data['expiry'],
            'available' => $getAvailableQty->quantity,
            'purchase_quantity' => $data['quantity'],
            'unit' => $data['unit'],
            'price' => (float) $data['srp'],
            'total' => (float) $data['srp'] * (float) $data['quantity'],
        ]);

        return DB::table('pharmacyhospital_inventory')
            ->where('product_id', $data['product_id'])
            ->where('batch_no', $data['batch_no'])
            ->where('expiry_date', $data['expiry'])
            ->update([
                'quantity' => $returnQty,
            ]);
    }

    public static function hispharmacyGetReceiptList($data)
    {

        $date_from = date('Y-m-d 00:00:00', strtotime($data['dateFrom']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['dateTo']));
        $mg = $data['management_id'];

        $query = "SELECT *, receipt_id as receiptID,
            (SELECT sum(total) from pharmacyhospital_receipt WHERE pharmacyhospital_receipt.receipt_id = receiptID) as total_cost
        from pharmacyhospital_receipt WHERE management_id = '$mg' AND created_at >= '$date_from' AND created_at <= '$date_to' GROUP BY receipt_id ORDER BY id DESC ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyPrintForTransaction($data)
    {
        $query = "SELECT *,
            (SELECT sum(total) from pharmacyhospital_receipt WHERE pharmacyhospital_receipt.receipt_id = '" . $data['receipt_number'] . "') as total_sum_spec_receipt,
            (SELECT name from pharmacy WHERE pharmacy.management_id = '" . $data['management_id'] . "' AND pharmacy.user_id = pharmacyhospital_receipt.username limit 1) as pharmacy_name,
            (SELECT address from pharmacy WHERE pharmacy.management_id = '" . $data['management_id'] . "' AND pharmacy.user_id = pharmacyhospital_receipt.username limit 1) as address,
            (SELECT tin_number from pharmacy WHERE pharmacy.management_id = '" . $data['management_id'] . "' AND pharmacy.user_id = pharmacyhospital_receipt.username limit 1) as tin,
            (SELECT email from pharmacy WHERE pharmacy.management_id = '" . $data['management_id'] . "' AND pharmacy.user_id = pharmacyhospital_receipt.username limit 1) as pharmacy_email,
            (SELECT company_logo from pharmacy WHERE pharmacy.management_id = '" . $data['management_id'] . "' AND pharmacy.user_id = pharmacyhospital_receipt.username limit 1) as company_logo,
            (SELECT user_fullname from pharmacy WHERE pharmacy.management_id = '" . $data['management_id'] . "' AND pharmacy.user_id = pharmacyhospital_receipt.username limit 1) as pharmacyNameById
        from pharmacyhospital_receipt WHERE receipt_id = '" . $data['receipt_number'] . "' AND management_id = '" . $data['management_id'] . "' ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyGetStockList($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('pharmacyhospital_inventory')
            ->join('pharmacyhospital_products', 'pharmacyhospital_products.product_id', '=', 'pharmacyhospital_inventory.product_id')
            ->select('pharmacyhospital_products.product', 'pharmacyhospital_products.description', 'pharmacyhospital_inventory.*')
            ->where('pharmacyhospital_inventory.management_id', $data['management_id'])
            ->orderBy('pharmacyhospital_products.product', 'ASC')
            ->get();
    }

    public static function hispharmacyGetLogAct($data)
    {
        return DB::table('pharmacyhospital_history')
            ->join('pharmacy', 'pharmacy.user_id', '=', 'pharmacyhospital_history.username')
            ->select('pharmacyhospital_history.*', 'pharmacy.user_fullname as pharmName')
            ->where('pharmacyhospital_history.management_id', $data['management_id'])
            ->orderBy('pharmacyhospital_history.created_at', 'DESC')
            ->get();
    }

    public static function hispharmacyGetSalesReport($data)
    {
        // in where -- pharmacy_id = $data['pharmacy_id'] AND
        $query = "SELECT *,

            (SELECT product_id FROM pharmacyhospital_products WHERE pharmacyhospital_products.product_id = pharmacyhospital_sales.product_id ) as productID,
            (SELECT srp FROM pharmacyhospital_products WHERE pharmacyhospital_products.product_id = productID ) as productSrp,
            (SELECT IFNULL(sum(total), 0) FROM pharmacyhospital_sales) as sum_all_total_sales,
            (SELECT IFNULL(sum(total), 0) FROM pharmacyhospital_sales WHERE pharmacyhospital_sales.product_id = productID) as sum_spec_total_quantity,
            (SELECT IFNULL(sum(quantity), 0) FROM pharmacyhospital_sales WHERE pharmacyhospital_sales.product_id = productID) as sum_all_total_quantity

        FROM pharmacyhospital_sales WHERE management_id = '" . $data['management_id'] . "' GROUP BY product ORDER BY created_at desc ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyGetFilterByDate($data)
    {
        date_default_timezone_set('Asia/Manila');

        $dailyStrt = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
        $dailyLst = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
        $strDaily = date('Y-m-d H:i:s', strtotime($dailyStrt));
        $lstDaily = date('Y-m-d H:i:s', strtotime($dailyLst));

        if ($data['sales_from'] == 'all') {
            $query = "SELECT *,
                (SELECT product_id FROM pharmacyhospital_products WHERE pharmacyhospital_products.product_id = pharmacyhospital_sales.product_id ) as productID,
                (SELECT srp FROM pharmacyhospital_products WHERE pharmacyhospital_products.product_id = productID ) as productSrp,
                (SELECT IFNULL(sum(total), 0) FROM pharmacyhospital_sales WHERE created_at >= '$strDaily' AND created_at <= '$lstDaily') as sum_all_total_sales,
                (SELECT IFNULL(sum(total), 0) FROM pharmacyhospital_sales WHERE pharmacyhospital_sales.product_id = productID AND created_at >= '$strDaily' AND created_at <= '$lstDaily') as sum_spec_total_quantity,
                (SELECT IFNULL(sum(quantity), 0) FROM pharmacyhospital_sales WHERE pharmacyhospital_sales.product_id = productID AND created_at >= '$strDaily' AND created_at <= '$lstDaily') as sum_all_total_quantity
            FROM pharmacyhospital_sales WHERE created_at >= '$strDaily' AND created_at <= '$lstDaily' GROUP BY product ORDER BY product ASC ";
            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        }

        $query = "SELECT *,
            (SELECT product_id FROM pharmacyhospital_products WHERE  pharmacyhospital_products.product_id = pharmacyhospital_sales.product_id ) as productID,
            (SELECT srp FROM pharmacyhospital_products WHERE pharmacyhospital_products.product_id = productID ) as productSrp,
            (SELECT IFNULL(sum(total), 0) FROM pharmacyhospital_sales WHERE sales_from = '" . $data['sales_from'] . "' and created_at >= '$strDaily' AND created_at <= '$lstDaily') as sum_all_total_sales,
            (SELECT IFNULL(sum(total), 0) FROM pharmacyhospital_sales WHERE sales_from = '" . $data['sales_from'] . "' and pharmacyhospital_sales.product_id = productID AND created_at >= '$strDaily' AND created_at <= '$lstDaily') as sum_spec_total_quantity,
            (SELECT IFNULL(sum(quantity), 0) FROM pharmacyhospital_sales WHERE sales_from = '" . $data['sales_from'] . "' and pharmacyhospital_sales.product_id = productID AND created_at >= '$strDaily' AND created_at <= '$lstDaily') as sum_all_total_quantity
        FROM pharmacyhospital_sales WHERE sales_from = '" . $data['sales_from'] . "' and created_at >= '$strDaily' AND created_at <= '$lstDaily' GROUP BY product ORDER BY product ASC ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyDeletePurchaseById($data)
    {
        $getAvailableQty = DB::table('pharmacyhospital_inventory')
            ->select('quantity')
            ->where('product_id', $data['prod_id'])
            ->where('batch_no', $data['batch'])
            ->where('expiry_date', $data['expiry'])
            ->first();

        $getPurQty = DB::table('pharmacyhospital_temporary_out')
            ->select('purchase_quantity')
            ->where('id', $data['id'])
            ->first();

        $returnQty = $getPurQty->purchase_quantity + $getAvailableQty->quantity;

        DB::table('pharmacyhospital_inventory')
            ->where('product_id', $data['prod_id'])
            ->where('batch_no', $data['batch'])
            ->where('expiry_date', $data['expiry'])
            ->update([
                'quantity' => $returnQty,
            ]);

        return DB::table('pharmacyhospital_temporary_out')
            ->where('id', $data['id'])
            ->delete();
    }

    public static function hispharmacyGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM pharmacy WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('pharmacy')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hispharmacyUpdatePersonalInfo($data)
    {
        return DB::table('pharmacy')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hispharmacyUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hispharmacyUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hispharmacyGetPrescriptionList($data)
    {
        $query = "SELECT order_no, patient_id as patientID, doctor_id, delivery,
            (SELECT concat(firstname,' ',lastname) from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1 ) as patientFullname,
            (SELECT firstname from patients where patients.patient_id = patientID limit 1 ) as patientFName,
            (SELECT lastname from patients where patients.patient_id = patientID limit 1) as patientLName,
            (SELECT middle from patients where patients.patient_id = patientID limit 1) as patientMName,
            (SELECT image from patients where patients.patient_id = patientID limit 1) as patientImage,
            (SELECT name from doctors where doctors.doctors_id = gtc_cart_patient_confirm.doctor_id limit 1) as doctorFullName
        FROM gtc_cart_patient_confirm WHERE order_status = 'order-finalized' AND order_toID = '" . $data['pharmacy_id'] . "' GROUP BY order_no ORDER BY patientFName ASC";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyGetPrescriptionDetails($data)
    {
        $query = "SELECT order_no, patient_id, product, quantity, type, price, id, dosage, cart_con_id, order_toID, is_rx, delivery,
            (SELECT ifnull(count(order_no),0) from gtc_cart_sigbin where gtc_cart_sigbin.order_no = '" . $data['order_id'] . "') as countBroadcastStatus,
            (SELECT rx_number FROM gtc_cart WHERE gtc_cart.order_no = gtc_cart_patient_confirm.order_no AND gtc_cart.rx_number IS NOT NULL limit 1 ) as rxNumber,
            (SELECT doctor_id FROM gtc_cart WHERE gtc_cart.order_no = gtc_cart_patient_confirm.order_no AND gtc_cart.rx_number IS NOT NULL limit 1 ) as doctor_ID,
            (SELECT concat(firstname,' ',lastname) from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1 ) as patientFullName,
            (SELECT concat(street,', ',barangay,', ',city) from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1 ) as patientFullAdd,
            (SELECT concat(tin) from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1 ) as patientTIN,
            (SELECT firstname from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1 ) as patientFName,
            (SELECT lastname from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1) as patientLName,
            (SELECT middle from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1) as patientMName,
            (SELECT image from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1) as patientImage,
            (SELECT mobile from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1) as patientMobile,
            (SELECT telephone from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1) as patientTelephone,
            (SELECT user_id from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1) as patientUserId,
            (SELECT gender from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1) as patientGender,
            (SELECT birthday from patients where patients.patient_id = gtc_cart_patient_confirm.patient_id limit 1) as patientBDay,

            (SELECT quantity from gtc_cart where gtc_cart.product = gtc_cart_patient_confirm.product AND gtc_cart.order_no = '" . $data['order_id'] . "' limit 1) as maxQtyProd,
            (SELECT quantity_claimed from doctors_prescription where doctors_prescription.prescription = gtc_cart_patient_confirm.product_id AND doctors_prescription.claim_id = gtc_cart_patient_confirm.rx_number limit 1) as maxQtyConsumed,
            (SELECT IFNULL(SUM(maxQtyProd - maxQtyConsumed),0)) as maxEntryEdit,

            (SELECT ifnull(sum(quantity * price), 0) from gtc_cart_patient_confirm where gtc_cart_patient_confirm.order_no = '" . $data['order_id'] . "' AND gtc_cart_patient_confirm.is_rx = 1) as totalOverallRX,
            (SELECT ifnull(sum(quantity * price), 0) from gtc_cart_patient_confirm where gtc_cart_patient_confirm.order_no = '" . $data['order_id'] . "' AND gtc_cart_patient_confirm.is_rx = 0) as totalOverallAddOn,
            (SELECT ifnull(count(is_rx), 0) from gtc_cart_patient_confirm where gtc_cart_patient_confirm.order_no = '" . $data['order_id'] . "' AND gtc_cart_patient_confirm.is_rx = 0) as countAddOns,
            (SELECT ifnull(count(price), 0) from gtc_cart_patient_confirm where gtc_cart_patient_confirm.order_no = '" . $data['order_id'] . "' AND price = 0) as countZeroPrice,

            (SELECT COALESCE(sum(doctors_service_amount), 0) from virtual_appointment where virtual_appointment.patient_id = patientUserId AND virtual_appointment.payment_status = 'Unpaid' limit 1 ) as TotalUnpaid

        from gtc_cart_patient_confirm where order_status = 'order-finalized' AND order_no = '" . $data['order_id'] . "' ";
        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyGetRxDoctorsRx($data)
    {
        return DB::connection('mysql2')->table('doctors')
            ->leftJoin('doctors_rxheader', 'doctors_rxheader.doctors_id', '=', 'doctors.doctors_id')
            ->where('doctors.doctors_id', $data['doctors_id'])
            ->get();
    }

    public static function hispharmacyGetPatientInformation($data)
    {
        return DB::connection('mysql2')
            ->table('patients')->where('patient_id', $data['patient_id'])->get();
    }

    public static function hispharmacyGetPrescription($data)
    {
        $query = "SELECT *
        -- (SELECT product from pharmacyclinic_products where pharmacyclinic_products.product_id = doctors_prescription.prescription ) as product_name
        from doctors_prescription where patients_id = '" . $data['patient_id'] . "' and doctors_id = '" . $data['doctors_id'] . "' and claim_id = '" . $data['claim_id'] . "' ";

        $result = DB::connection('mysql2')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hispharmacyUpdateQtyPrescription($data)
    {
        date_default_timezone_set('Asia/Manila');
        $id = $data['id'];
        $quantity = $data['quantity'];
        $array = [];
        for ($i = 0; $i < count($quantity); $i++) {
            DB::connection('mysql2')->table('gtc_cart_patient_confirm')
                ->where('id', $id[$i])
                ->update([
                    'quantity' => $quantity[$i],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
        return true;
    }

    public static function hispharmacyProcessPaymentPresc($data)
    {
        date_default_timezone_set('Asia/Manila');
        $payment = DB::connection('mysql2')->table('gtc_cart_patient_confirm')
            ->join($data['tableProducts'], $data['tableProducts'] . '.product_id', '=', 'gtc_cart_patient_confirm.product_id')
            ->select('gtc_cart_patient_confirm.*', $data['tableProducts'] . '.description', $data['tableProducts'] . '.supplier')
            ->where('gtc_cart_patient_confirm.order_no', $data['order_no'])
            ->where('gtc_cart_patient_confirm.quantity', '>', 0)
            ->get();

        $invoice = rand(0, 9999999);

        foreach ($payment as $v) {

            $getAvailableQty = DB::table($data['tableInventory'])
                ->select('quantity')
                ->where('product_id', $v->product_id)
                ->first();

            $returnQty = (int) $getAvailableQty->quantity - (int) $v->quantity;

            DB::table($data['tableInventory'])
                ->where('product_id', $v->product_id)
                ->update([
                    'quantity' => $returnQty,
                ]);

            $getConsumeQty = DB::connection('mysql2')->table('doctors_prescription')
                ->select('quantity_claimed')
                ->where('claim_id', $v->rx_number)
                ->where('prescription', $v->product_id)
                ->first();

            $consumeQty = $getConsumeQty->quantity_claimed + $v->quantity;

            DB::connection('mysql2')->table('doctors_prescription')
                ->where('claim_id', $v->rx_number)
                ->where('prescription', $v->product_id)
                ->update([
                    'quantity_claimed' => $consumeQty,
                ]);

            DB::table($data['tableSales'])->insert([
                'sales_id' => 'si-' . rand(0, 999999),
                'product_id' => $v->product_id,
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'product' => $v->product,
                'description' => $v->description,
                'unit' => $v->type,
                'quantity' => $v->quantity,
                'total' => (float) $v->quantity * (float) $v->price,
                'dr_no' => $data['receipt_id'],
                'sales_from' => 'online',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table($data['tableHistory'])->insert([
                'pch_id' => 'hist-' . rand(0, 999999),
                'product_id' => $v->product_id,
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'product' => $v->product,
                'description' => $v->description,
                'unit' => $v->type,
                'quantity' => $v->quantity,
                'request_type' => 'OUT',
                'dr_no' => $data['receipt_id'],
                'supplier' => $v->supplier,
                'remarks' => $data['remarks_payment'],
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table($data['tableReceipt'])->insert([
                'pcr_id' => 'pcr-' . rand(0, 9999) . time(),
                'receipt_id' => $data['receipt_id'],
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'name_customer' => $data['client_name'],
                'address_customer' => $data['client_add'] === null ? null : $data['client_add'],
                'tin_customer' => $data['client_tin'] === null ? null : $data['client_tin'],
                'product' => $v->product,
                'description' => $v->description,
                'unit' => $v->type,
                'quantity' => $v->quantity,
                'srp' => $v->price,
                'total' => (float) $v->quantity * (float) $v->price,
                'amount_paid' => $data['amount_paid'],
                'payment_change' => (float) $data['totalCost'] - (float) $data['amount_paid'],
                'dr_no' => $data['receipt_id'],
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            DB::connection('mysql2')->table('gtc_cart_pharmacy_confirm')->insert([
                'cart_pc' => 'cart_pc-' . rand(9999, 0),
                'order_no' => $v->order_no,
                'patient_id' => $v->patient_id,
                'product' => $v->product,
                'product_id' => $v->product_id,
                'type' => $v->type,
                'dosage' => $v->dosage,
                'quantity' => $v->quantity,
                'original_qty' => $v->original_qty,
                'price' => $v->price,
                'order_status' => 'order-complete',
                'is_rx' => $v->is_rx,
                'doctor_id' => $v->doctor_id,
                'delivery' => $v->delivery,
                'delivery_fee' => $v->delivery_fee,
                'status' => 1,
                'order_toID' => $v->order_toID,
                'vpharma_status' => 'pharmacy-complete',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // if($data['delivery'] == 1){
        //     DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('gtc_cart_sigbin')
        //     ->where('order_no', $data['order_no'])
        //     ->update([
        //         'order_status' => 'order-pickedup',
        //         'vpharma_status' => 'pharmacy-complete',
        //         'rider_status' => 'rider-pickedup',
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ]);

        //     DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('patients_notification')
        //     ->insert([
        //         'notif_id' => 'nid-'.rand(0, 99).time(),
        //         'order_id' => $data['order_no'],
        //         'patient_id' => $data['patient_id'],
        //         'doctor_id' => $payment[0]->doctor_id,
        //         'category' => 'cart',
        //         'department' => 'sigbin-rider',
        //         'message' => 'The ordered prescription is already picked up by rider.',
        //         'is_view' => 0,
        //         'notification_from' => 'virtual',
        //         'status' => 1,
        //         'updated_at' => date('Y-m-d H:i:s'),
        //         'created_at' => date('Y-m-d H:i:s')
        //     ]);
        // }

        if ($data['delivery'] == 0) {
            if ($data['totalUnpaidCons'] > 0) {
                DB::connection('mysql2')->table('virtual_appointment')
                    ->where('patient_id', $data['patient_user_id'])
                    ->update([
                        'payment_status' => 'Paid',
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                DB::connection('mysql2')->table('pharmacyclinic_collection_unpaid_consultation')
                    ->where('patient_id', $data['patient_id'])
                    ->insert([
                        'pcuc_id' => 'Paid',
                        'order_no' => $data['order_no'],
                        'patient_id' => $data['patient_id'],
                        'amount' => $data['totalUnpaidCons'],
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);

                DB::connection('mysql2')->table('patients_notification')
                    ->insert([
                        'notif_id' => 'nid-' . rand(0, 99) . time(),
                        'order_id' => $data['order_no'],
                        'patient_id' => $data['patient_id'],
                        'doctor_id' => $payment[0]->doctor_id,
                        'category' => 'cart',
                        'department' => 'virtual-pharmacy',
                        'message' => 'You have already picked up your order from pharmacy, order complete.',
                        'is_view' => 0,
                        'notification_from' => 'virtual',
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
            }
        }

        DB::connection('mysql2')->table('gtc_cart')
            ->where('order_no', $data['order_no'])
            ->update([
                'order_status' => $data['delivery'] == 1 ? 'order-pickedup' : 'order-complete',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection('mysql2')->table('gtc_cart_patient_confirm')
            ->where('order_no', $data['order_no'])
            ->update([
                'order_status' => $data['delivery'] == 1 ? 'order-pickedup' : 'order-complete',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllUnClaimedPres($data)
    {
        $mng = $data['management_id'];
        $query = "SELECT id, claim_id as cid, claim_id,  created_at, patients_id, trace_number,
            ifnull(sum(quantity), 0) - ifnull(sum(quantity_claimed), 0) as prescriptionLeft,
            (SELECT concat(IFNULL(lastname, ''),', ', IFNULL(firstname, '')) from patients where patients.patient_id = doctors_prescription.patients_id) as patient_name
        from doctors_prescription where management_id = '$mng' and prescription_type = 'clinic' group by claim_id having prescriptionLeft > 0";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getClaimIdDetails($data)
    {
        return DB::table('doctors_prescription')->where('claim_id', $data['claim_id'])->get();
    }

    public static function prescriptionNewQtyOrdered($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::table('doctors_prescription')->where('id', $data['id'])->update([
            'quantity_order' => $data['orderQty'],
            'quantity_order_batchno' => $data['batch_no'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function prescriptionPaymentProcess($data)
    {
        $claimiddetails = _Pharmacy::getClaimIdDetails($data);
        $orderQty = DB::table('doctors_prescription')->where('claim_id', $data['claim_id'])->where('quantity_order', '>', 0)->get();

        foreach ($orderQty as $v) {
            $availableQty = DB::table('pharmacyhospital_inventory')->select('quantity')->where('product_id', $v->prescription)->where('batch_no', $v->quantity_order_batchno)->first();
            $qtyClaimed = DB::table('doctors_prescription')->select('quantity_claimed')->where('id', $v->id)->first();
            $description = DB::table('pharmacyhospital_products')->select('description')->where('product_id', $v->prescription)->first();

            // update purcjasdf qty
            DB::table('doctors_prescription')->where('id', $v->id)->update([
                'quantity_claimed' => (int) $qtyClaimed->quantity_claimed + (int) $v->quantity_order,
            ]);

            // update phamamdsf csales
            DB::table('pharmacyhospital_sales')->insert([
                'sales_id' => 'sales-' . rand(0, 99) . time(),
                'product_id' => $v->prescription,
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'product' => $v->product_name,
                'description' => $description->description,
                'unit' => $v->type,
                'quantity' => $v->quantity_order,
                'total' => $v->product_amount * $v->quantity_order,
                'dr_no' => $data['recpt'],
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // // update [harmac ] inventeory
            DB::table('pharmacyhospital_inventory')
                ->where('product_id', $v->prescription)
                ->where('batch_no', $v->quantity_order_batchno)
                ->update([
                    'quantity' => (int) $availableQty->quantity - (int) $v->quantity_order,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            // // update jophakdsf hiustory

            DB::table('pharmacyhospital_history')->insert([
                'pch_id' => 'pch-' . rand(0, 99) . time(),
                'product_id' => $v->prescription,
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'product' => $v->product_name,
                'description' => $v->product_name,
                'unit' => $v->type,
                'quantity' => $v->quantity_order,
                'request_type' => 'OUT',
                'dr_no' => $data['recpt'],
                'remarks' => 'purchased in pharmacy prescription rx local.',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // receipt keep record
            DB::table('pharmacyhospital_receipt')->insert([
                'pcr_id' => 'pcr-' . rand(0, 99) . time(),
                'receipt_id' => $data['recpt'],
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'name_customer' => $data['patient_name'],
                'address_customer' => '',
                'tin_customer' => '',
                'product' => $v->product_name,
                'description' => $description->description,
                'unit' => $v->type,
                'quantity' => $v->quantity_order,
                'srp' => $v->product_amount,
                'total' => (float) $v->product_amount * (int) $v->quantity_order,
                'amount_paid' => $data['payment_amount'],
                'payment_change' => $data['payment_change'],
                'dr_no' => $data['recpt'],
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return DB::table('doctors_prescription')->where('claim_id', $data['claim_id'])->update([
            'quantity_order_batchno' => null,
            'quantity_order' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }

    public static function prescriptionAddToBilling($data)
    {
        $claimiddetails = _Pharmacy::getClaimIdDetails($data);
        $orderQty = DB::table('doctors_prescription')->where('claim_id', $data['claim_id'])->where('quantity_order', '>', 0)->get();

        foreach ($orderQty as $v) {
            $availableQty = DB::table('pharmacyhospital_inventory')->select('quantity')->where('product_id', $v->prescription)->where('batch_no', $v->quantity_order_batchno)->first();
            $qtyClaimed = DB::table('doctors_prescription')->select('quantity_claimed')->where('id', $v->id)->first();
            $description = DB::table('pharmacyhospital_products')->select('description')->where('product_id', $v->prescription)->first();

            // update purcjasdf qty
            DB::table('doctors_prescription')->where('id', $v->id)->update([
                'quantity_claimed' => (int) $qtyClaimed->quantity_claimed + (int) $v->quantity_order,
            ]);

            // update phamamdsf csales
            DB::table('pharmacyhospital_sales')->insert([
                'sales_id' => 'sales-' . rand(0, 99) . time(),
                'product_id' => $v->prescription,
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'product' => $v->product_name,
                'description' => $description->description,
                'unit' => $v->type,
                'quantity' => $v->quantity_order,
                'total' => $v->product_amount * $v->quantity_order,
                'dr_no' => $data['recpt'],
                'sales_from' => 'admitted-patient',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // // update [harmac ] inventeory
            DB::table('pharmacyhospital_inventory')
                ->where('product_id', $v->prescription)
                ->where('batch_no', $v->quantity_order_batchno)
                ->update([
                    'quantity' => (int) $availableQty->quantity - (int) $v->quantity_order,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            // // update jophakdsf hiustory
            DB::table('pharmacyhospital_history')->insert([
                'pch_id' => 'pch-' . rand(0, 99) . time(),
                'product_id' => $v->prescription,
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'product' => $v->product_name,
                'description' => $v->product_name,
                'unit' => $v->type,
                'quantity' => $v->quantity_order,
                'request_type' => 'OUT',
                'dr_no' => $data['recpt'],
                'remarks' => 'purchase by doctors prescriptions to admitted patient',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // receipt keep record
            DB::table('pharmacyhospital_receipt')->insert([
                'pcr_id' => 'pcr-' . rand(0, 99) . time(),
                'receipt_id' => $data['recpt'],
                'pharmacy_id' => $data['pharmacy_id'],
                'management_id' => $data['management_id'],
                'username' => $data['user_id'],
                'name_customer' => $data['patient_name'],
                'address_customer' => '',
                'tin_customer' => '',
                'product' => $v->product_name,
                'description' => $description->description,
                'unit' => $v->type,
                'quantity' => $v->quantity_order,
                'srp' => $v->product_amount,
                'total' => (float) $v->product_amount * (int) $v->quantity_order,
                'amount_paid' => $data['payment_amount'],
                'payment_change' => $data['payment_change'],
                'dr_no' => $data['recpt'],
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // add to patient billing //
            DB::table('hospital_admitted_patient_billing_record')->insert([
                'dbr_id' => 'dbr-' . rand(999, 99999) . time(),
                'order_id' => $data['pharmacy_id'],
                'package_id' => $data['recpt'],
                'product_id' => $v->prescription,
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'management_id' => $data['management_id'],
                'bill_name' => $v->product_name,
                'bill_amount' => $v->product_amount,
                'bill_from' => 'pharmacy',
                'bill_payment' => 'billing',
                'bill_department' => 'pharmacy',
                'transaction_category' => 'admitted-patient',
                'billing_status' => 'billing-unpaid',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return DB::table('doctors_prescription')->where('claim_id', $data['claim_id'])->update([
            'quantity_order_batchno' => null,
            'quantity_order' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function prescriptionProductBatches($data)
    {
        return DB::table('pharmacyhospital_inventory')->where('product_id', $data['product_id'])->groupBy('batch_no')->get();
    }

    public static function prescriptionProductBatchesAvsQty($data)
    {
        return DB::table('pharmacyhospital_inventory')
            ->select('id', DB::raw("SUM(quantity) as totalAvsQty "))
            ->where('product_id', $data['product_id'])
            ->where('batch_no', $data['batch_no'])
            ->where('request_type', 'IN')
            ->get();
    }

}
