  public function getAccounts(Request $request)
{
    // Start the query and select only the necessary columns
    $model = LotInventories::query()->select('lot_inventories.id');

    $per_page = $request->get('per_page');
    $sort_by = $request->get('sort_by');
    $sort_column = $request->get('sort_query');

    $date_column = $request->get('date_column');
    $start_date = $request->get('start_date');
    $to_date = $request->get('to_date');

    $search_term = $request->get('search_term');
    $keywords = $request->get('search');

    $columns = $request->get('columns');

    // Dynamically add columns based on the request
    $model
        ->when(in_array('name', $columns), function ($query) {
            return $query->addSelect('lot_inventories.name'); // Use addSelect to avoid duplicates
        })
        ->when(in_array('lot_area', $columns), function ($query) {
            return $query->addSelect('lot_inventories.lot_area');
        })
        ->when(in_array('floor_area', $columns), function ($query) {
            return $query->addSelect('lot_inventories.floor_area');
        })
        ->when(in_array('first_bill_period', $columns), function ($query) {
            return $query->addSelect('lot_inventories.first_bill_period');
        })
        ->when(in_array('turned_over_date', $columns), function ($query) {
            return $query->addSelect('lot_inventories.turned_over_date');
        })
        ->when(in_array('account_number', $columns), function ($query) {
            return $query->addSelect('lot_inventories.id')->with('account_number');
        })
        ->when(in_array('customer_name', $columns), function ($query) {
            return $query->addSelect('lot_inventories.id')->with('customer_name');
        })
        ->when(in_array('pftm_membership_fee', $columns), function ($query) {
            return $query->leftJoin('report_main.user_lot_inventories', 'lot_inventories.id', '=', 'report_main.user_lot_inventories.lot_inventory_id')
                        ->leftJoin('report_billing.payments', 'report_main.user_lot_inventories.account_number', '=', 'report_billing.payments.account_number')
                        ->selectRaw(
                            "COALESCE(SUM(CASE WHEN report_billing.payments.status = 'VERIFIED' THEN report_billing.payments.amount_paid ELSE 0 END), 0) AS pftm_membership_fee"
                        )
                        ->groupBy('lot_inventories.id');
        });

    // Apply filters, sorting, and pagination
    $data = $model
        ->when($date_column, function ($query) use ($date_column, $start_date, $to_date) {
            return $query->whereBetween($date_column, [$start_date, $to_date]);
        })
        ->when($sort_column, function ($query) use ($sort_column, $sort_by) {
            $query->orderBy($sort_column, $sort_by);
        })
        ->where(function ($query) use ($search_term) {
            if (is_numeric($search_term)) {
                $query->where('lot_inventories.project_site_id', $search_term);
            } else {
                $query->where('lot_inventories.code', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('lot_inventories.name', 'LIKE', '%' . $search_term . '%');
            }
        })
        ->paginate($per_page);

    return $this->sendResponse($data, "Accounts list.");
}

// public function getAccounts(Request $request)
    // {
    //     // Start the query and select only the necessary columns
    //     $model = LotInventories::query()->select('lot_inventories.id');
    
    //     $per_page = $request->get('per_page');
    //     $sort_by = $request->get('sort_by');
    //     $sort_column = $request->get('sort_query');
    
    //     $date_column = $request->get('date_column');
    //     $start_date = $request->get('start_date');
    //     $to_date = $request->get('to_date');
    
    //     $search_term = $request->get('search_term');
    //     $keywords = $request->get('search');
    
    //     $columns = $request->get('columns');
    
    //     $model
    //         ->when(in_array('name', $columns), function ($query) {
    //             return $query->addSelect('lot_inventories.name');
    //         })
    //         ->when(in_array('lot_area', $columns), function ($query) {
    //             return $query->addSelect('lot_inventories.lot_area');
    //         })
    //         ->when(in_array('floor_area', $columns), function ($query) {
    //             return $query->addSelect('lot_inventories.floor_area');
    //         })
    //         ->when(in_array('first_bill_period', $columns), function ($query) {
    //             return $query->addSelect('lot_inventories.first_bill_period');
    //         })
    //         ->when(in_array('turned_over_date', $columns), function ($query) {
    //             return $query->addSelect('lot_inventories.turned_over_date');
    //         })
    //         ->when(in_array('account_number', $columns), function ($query) {
    //             return $query->addSelect('lot_inventories.id')->with('account_number');
    //         })
    //         ->when(in_array('customer_name', $columns), function ($query) {
    //             return $query->addSelect('lot_inventories.id')->with('customer_name');
    //         })
    //         ->when(in_array('pftm_assoc_dues', $columns), function ($query) use($start_date, $to_date) {
    //             return $query->selectRaw(
    //                 "COALESCE(
    //                     (SELECT SUM(
    //                         CASE 
    //                             WHEN icp.type IN ('Interest on Assoc Dues', 'Association Dues', 'Penalty on Association Dues', 'Assoc. Dues - Water Tie-up', 'Association Dues (Water Tie-up)', 'Association Dues - (Water Tie-up)', 'Beginning Balance', 'Debit Memo (Association Dues)', 'Association Dues (Dev)', 'Debit Memo', 'Maintenance Dues')
    //                             THEN icp.allocated_amount 
    //                             ELSE 0 
    //                         END
    //                     ) 
    //                     FROM report_billing.invoice_credit_payments icp
    //                     JOIN report_billing.payments p ON icp.account_number = p.account_number 
    //                     WHERE icp.account_number IN (
    //                         SELECT account_number 
    //                         FROM report_main.user_lot_inventories 
    //                         WHERE lot_inventory_id = lot_inventories.id
    //                     ) 
    //                     AND p.status = 'VERIFIED'
    //                     AND p.or_receipt_date BETWEEN ? AND ? 
    //                     ), 0
    //                 ) AS pftm_assoc_dues",
    //                 [$start_date, $to_date]
    //             );
    //         })
    //         ->when(in_array('pftm_membership_fee', $columns), function ($query) use($start_date, $to_date) {
    //             return $query->selectRaw(
    //                 "COALESCE(
    //                     (SELECT SUM(
    //                         CASE 
    //                             WHEN icp.type IN ('Membership Fee', 'Debit Memo (Membership Fee)')
    //                             THEN icp.allocated_amount 
    //                             ELSE 0 
    //                         END
    //                     ) 
    //                     FROM report_billing.invoice_credit_payments icp
    //                     JOIN report_billing.payments p ON icp.account_number = p.account_number 
    //                     WHERE icp.account_number IN (
    //                         SELECT account_number 
    //                         FROM report_main.user_lot_inventories 
    //                         WHERE lot_inventory_id = lot_inventories.id
    //                     ) 
    //                     AND p.status = 'VERIFIED'
    //                     AND p.or_receipt_date BETWEEN ? AND ? 
    //                     ), 0
    //                 ) AS pftm_membership_fee",
    //                 [$start_date, $to_date]
    //             );
    //         });
    
    //     $data = $model
    //         ->when($date_column, function ($query) use ($date_column, $start_date, $to_date) {
    //             return $query->whereBetween($date_column, [$start_date, $to_date]);
    //         })
    //         ->when($sort_column, function ($query) use ($sort_column, $sort_by) {
    //             $query->orderBy($sort_column, $sort_by);
    //         })
    //         ->where(function ($query) use ($search_term) {
    //             if (is_numeric($search_term)) {
    //                 $query->where('lot_inventories.project_site_id', $search_term);
    //             } else {
    //                 $query->where('lot_inventories.code', 'LIKE', '%' . $search_term . '%')
    //                     ->orWhere('lot_inventories.name', 'LIKE', '%' . $search_term . '%');
    //             }
    //         })
    //         ->paginate($per_page);
    
    //     return $this->sendResponse($data, "Accounts list.");
    // }