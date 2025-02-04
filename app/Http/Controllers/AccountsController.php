<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LotInventories;
use App\Models\InvoiceCreditPayments;
use Illuminate\Support\Facades\DB;
class AccountsController extends BaseController
{


    public function getAccounts(Request $request)
    {

        $per_page = $request->get('per_page');
        $sort_by = $request->get('sort_by');
        $sort_column = $request->get('sort_query');
        $date_column = $request->get('date_column');
        $start_date = $request->get('start_date');
        $to_date = $request->get('to_date');
        $search_term = $request->get('search_term');
        $columns = $request->get('columns');
        $start_date = '2024-12-01';
        $to_date = '2024-12-31';

        $model = LotInventories::query()
            ->join('report_main.user_lot_inventories as uli', 'lot_inventories.id', '=', 'uli.lot_inventory_id')
            ->join('report_billing.payments as p', 'uli.account_number', '=', 'p.account_number')
            ->leftJoin('report_billing.invoice_credit_payments as icp', function ($join) {
                $join->on('p.account_number', '=', 'icp.account_number');
            })
            ->leftJoin('report_main.users as u', 'uli.user_id', '=', 'u.id')
            ->whereBetween('p.or_receipt_date', [$start_date, $to_date])
            ->where('p.status', 'VERIFIED')
            ->groupBy(
                'lot_inventories.id',
                'lot_inventories.project_site_id',
                'uli.account_number',
                'lot_inventories.name',
                'lot_inventories.lot_area',
                'lot_inventories.floor_area',
                'lot_inventories.first_bill_period',
                'lot_inventories.turned_over_date',
                'p.or_receipt_date',
                'u.first_name',
                'u.last_name'
            )
            ->select([
                'lot_inventories.id',
                'lot_inventories.project_site_id',
                'uli.account_number',
                'lot_inventories.name',
                'lot_inventories.lot_area',
                'lot_inventories.floor_area',
                'lot_inventories.first_bill_period',
                'lot_inventories.turned_over_date',
                DB::raw("CONCAT(COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, 'Unknown')) as customer_name"),
                'p.or_receipt_date as receipt_date',
                DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Membership Fee', 'Debit Memo (Membership Fee)') THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_membership_fee"),
                DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Interest on Assoc Dues', 'Association Dues', 'Penalty on Association Dues', 'Assoc. Dues - Water Tie-up', 'Association Dues (Water Tie-up)', 'Association Dues - (Water Tie-up)', 'Beginning Balance', 'Debit Memo (Association Dues)', 'Association Dues (Dev)', 'Debit Memo', 'Maintenance Dues') THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_assoc_dues"),
                DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Interest on Insurance', 'Penalty on Insurance', 'Special Assessments - Insurance') THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_insurance"),
                DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Interest on PMS', 'Penalty on PMS', 'Special Assessment - Preventive Maintenance') THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_maintenance"),
                DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Penalty on RPT', 'Interest on RPT', 'Special Assessments - Real Property Tax') THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_tax"),
                DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Water','Other Income - Water Charges', 'Electricity') THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_utility"),
                DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Special Assessments - STP System Upgrade', 'Electricity') OR icp.type LIKE 'Other Income%' THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_others"),
                DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Penalty on Building Improvement', 'Special Assessments - Bldg Improvement') THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_bldg"),
                DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type = 'Violations' THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_violations"),
            ]);
    
        $model->when($date_column, function ($query) use ($date_column, $start_date, $to_date) {
            return $query->whereBetween($date_column, [$start_date, $to_date]);
        });
    
        $model->when($search_term, function ($query) use ($search_term) {
            if (is_numeric($search_term)) {
                $query->where('lot_inventories.project_site_id', $search_term);
            } else {
                $query->where('lot_inventories.code', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('lot_inventories.name', 'LIKE', '%' . $search_term . '%');
            }
        });
    
        $model->when($sort_column, function ($query) use ($sort_column, $sort_by) {
            $query->orderBy($sort_column, $sort_by);
        });
    
        $data = $model->paginate($per_page);
    
        return $this->sendResponse($data, "Accounts list.");
    }
}
