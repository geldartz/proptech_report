<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LotInventories;

class AccountsController extends BaseController
{
    public function getAccounts(Request $request)
    {
        $model = LotInventories::query()->selectRaw('id');
    
        $per_page = $request->get('per_page');
        $sort_by = $request->get('sort_by');
        $sort_column = $request->get('sort_query');
    
        $date_column = $request->get('date_column');
        $start_date = $request->get('start_date');
        $to_date = $request->get('to_date');
    
        $search_term = $request->get('search_term');
        $keywords = $request->get('search');
    
        $columns = $request->get('columns');
    
        $model
            ->when(in_array('name', $columns), function ($query) {
                return $query->selectRaw('name');
            })
            ->when(in_array('lot_area', $columns), function ($query) {
                return $query->selectRaw('lot_area');
            })
            ->when(in_array('floor_area', $columns), function ($query) {
                return $query->selectRaw('floor_area');
            })
            ->when(in_array('first_bill_period', $columns), function ($query) {
                return $query->selectRaw('first_bill_period');
            })
            ->when(in_array('turned_over_date', $columns), function ($query) {
                return $query->selectRaw('turned_over_date');
            })
            ->when(in_array('account_number', $columns), function ($query) {
                return $query->selectRaw('id')->with('account_number');
            })
            ->when(in_array('customer_name', $columns), function ($query) {
                return $query->selectRaw('id')->with('customer_name');
            })
            ->when(in_array('pftm_membership_fee', $columns), function ($query) {
                return $query->selectRaw(
                    "COALESCE(
                        (SELECT SUM(amount_paid) 
                         FROM report_billing.payments 
                         WHERE account_number IN (
                             SELECT account_number 
                             FROM report_main.user_lot_inventories 
                             WHERE lot_inventory_id = lot_inventories.id
                         ) 
                         AND status = 'VERIFIED' 
                         AND or_receipt_date BETWEEN '2024-12-01' AND '2024-12-31'
                        ), 0
                    ) AS pftm_membership_fee"
                );
            });
    
        $data = $model
            ->when($date_column, function ($query) use ($date_column, $start_date, $to_date) {
                return $query->whereBetween($date_column, [$start_date, $to_date]);
            })
            ->when($sort_column, function ($query) use ($sort_column, $sort_by) {
                $query->orderBy($sort_column, $sort_by);
            })
            ->where(function ($query) use ($search_term) {
                if (is_numeric($search_term)) {
                    $query->where('project_site_id', $search_term);
                } else {
                    $query->where('code', 'LIKE', '%' . $search_term . '%')
                        ->orWhere('name', 'LIKE', '%' . $search_term . '%');
                }
            })
            ->paginate($per_page);
    
        // Debugging: Log the SQL query
        \Log::info($model->toSql());
    
        return $this->sendResponse($data, "Accounts list.");
    }
}
