<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LotInventories;
use App\Models\InvoiceCreditPayments;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AccountsController extends BaseController
{


    // public function getAccounts(Request $request)
    // {

    //     $per_page = $request->get('per_page');
    //     $sort_by = $request->get('sort_by');
    //     $sort_column = $request->get('sort_query');
    //     $date_column = $request->get('date_column');
    //     $search_term = $request->get('search_term');
    //     $columns = $request->get('columns');

    //     $monthName = $request->get('month');
    //     $year = $request->get('year');

    //     $monthNumeric = date('m', strtotime($monthName));


    //     $date = Carbon::create($year, $monthNumeric);
    //     $start_date = $date->startOfMonth()->format('Y-m-d');
    //     $to_date = $date->endOfMonth()->format('Y-m-d');


    //         $model = LotInventories::query()
    //         ->join('report_main.user_lot_inventories as uli', 'lot_inventories.id', '=', 'uli.lot_inventory_id')
    //         ->join('report_billing.payments as p', 'uli.account_number', '=', 'p.account_number')
    //         ->leftJoin('report_billing.invoice_credit_payments as icp', function ($join) {
    //             $join->on('p.account_number', '=', 'icp.account_number');
    //         })
    //         ->leftJoin('report_main.users as u', 'uli.user_id', '=', 'u.id')
    //         ->leftJoin('report_billing.invoices as inv', 'icp.invoice_id', '=', 'inv.id')
           
    //         ->whereBetween('p.or_receipt_date', [$start_date, $to_date])
    //         // ->whereBetween('inv.bill_date', [$start_date, $to_date])
    //         ->where('p.status', 'VERIFIED')
    //         // ->where('icp.account_number', '0079-32H3RFJNBV')
    //         ->groupBy(
    //             // 'lot_inventories.id',
    //             // 'lot_inventories.project_site_id',
    //             'uli.account_number',
    //             // 'lot_inventories.name',
    //             // 'lot_inventories.lot_area',
    //             // 'lot_inventories.floor_area',
    //             // 'lot_inventories.first_bill_period',
    //             // 'lot_inventories.turned_over_date',
    //              'p.or_receipt_date',
    //             // 'u.first_name',
    //             // 'u.last_name'
    //         )
    //         ->select([
    //             'lot_inventories.id',
    //             'lot_inventories.project_site_id',
    //             'uli.account_number',
    //             'lot_inventories.name',
    //             'lot_inventories.lot_area',
    //             'lot_inventories.floor_area',
    //             'lot_inventories.first_bill_period',
    //             'lot_inventories.turned_over_date',
    //             DB::raw("CONCAT(COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, 'Unknown')) as customer_name"),
    //             'p.or_receipt_date as or_receipt_date',
    //             DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Membership Fee', 'Debit Memo (Membership Fee)') AND icp.created_at BETWEEN ? AND ? THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_membership_fee"),
    //             DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Interest on Assoc Dues', 'Association Dues', 'Penalty on Association Dues', 'Assoc. Dues - Water Tie-up', 'Association Dues (Water Tie-up)', 'Association Dues - (Water Tie-up)', 'Beginning Balance', 'Debit Memo (Association Dues)', 'Association Dues (Dev)', 'Debit Memo', 'Maintenance Dues') AND icp.created_at BETWEEN ? AND ? THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_assoc_dues"),
    //             DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Interest on Insurance', 'Penalty on Insurance', 'Special Assessments - Insurance') AND icp.created_at BETWEEN ? AND ? THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_insurance"),
    //             DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Interest on PMS', 'Penalty on PMS', 'Special Assessment - Preventive Maintenance') AND icp.created_at BETWEEN ? AND ? THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_maintenance"),
    //             DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Penalty on RPT', 'Interest on RPT', 'Special Assessments - Real Property Tax') AND icp.created_at BETWEEN ? AND ? THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_tax"),
    //             DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Water','Other Income - Water Charges', 'Electricity') AND icp.created_at BETWEEN ? AND ? THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_utility"),
    //             DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Special Assessments - STP System Upgrade', 'Electricity') OR icp.type LIKE 'Other Income%' AND icp.created_at BETWEEN ? AND ? THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_others"),
    //             DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Penalty on Building Improvement', 'Special Assessments - Bldg Improvement') AND icp.created_at BETWEEN ? AND ? THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_bldg"),
    //             DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type = 'Violations' AND icp.created_at BETWEEN ? AND ? THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_violations"),
    //             // DB::raw("FORMAT(COALESCE(SUM(CASE WHEN inv.bill_date < ? AND inv.id IN (SELECT invoice_id FROM report_billing.invoice_credit_payments where model_id = p.id) THEN inv.amount_paid ELSE 0 END), 0), 2) AS arrears_hoa"),
    //             DB::raw("FORMAT(COALESCE(SUM(CASE WHEN inv.bill_date BETWEEN ? AND ? THEN inv.amount_paid ELSE 0 END), 0), 2) AS current_hoa"),
    //           //  DB::raw("FORMAT(COALESCE(SUM(CASE WHEN inv.bill_date > ? AND inv.id IN (SELECT invoice_id FROM report_billing.invoice_credit_payments where model_id = p.id) THEN inv.amount_paid ELSE 0 END), 0), 2) AS advance_hoa"),
    //         ])
    //         ->setBindings([
    //             // Bindings for pftm_membership_fee
    //             $start_date, $to_date,
    //             // Bindings for pftm_assoc_dues
    //             $start_date, $to_date,
    //             // Bindings for pftm_insurance
    //             $start_date, $to_date,
    //             // Bindings for pftm_maintenance
    //             $start_date, $to_date,
    //             // Bindings for pftm_tax
    //             $start_date, $to_date,
    //             // Bindings for pftm_utility
    //             $start_date, $to_date,
    //             // Bindings for pftm_others
    //             $start_date, $to_date,
    //             // Bindings for pftm_bldg
    //             $start_date, $to_date,
    //             // Bindings for pftm_violations
    //             $start_date, $to_date,
    //             // Bindings for arrears_hoa
    //             // $start_date,
    //             // Bindings for current_hoa
    //             $start_date, $to_date,
    //             // Bindings for advance_hoa
    //          //   $to_date,

    //             $start_date, $to_date,
    //             'VERIFIED',
    //         ]);
    
    //     $model->when($date_column, function ($query) use ($date_column, $start_date, $to_date) {
    //         return $query->whereBetween($date_column, [$start_date, $to_date]);
    //     });
    
    //     $model->when($search_term, function ($query) use ($search_term) {
    //         if (is_numeric($search_term)) {
    //             $query->where('lot_inventories.project_site_id', $search_term);
    //         } else {
    //             $query->where('lot_inventories.code', 'LIKE', '%' . $search_term . '%')
    //                 ->orWhere('lot_inventories.name', 'LIKE', '%' . $search_term . '%');
    //         }
    //     });
    
    //     $model->when($sort_column, function ($query) use ($sort_column, $sort_by) {
    //         $query->orderBy($sort_column, $sort_by);
    //     });
    
    //     $data = $model->paginate($per_page);
    
    //     return $this->sendResponse($data, "Accounts list.");
    // }

    public function getAccounts(Request $request)
    {
        $per_page = $request->get('per_page');
        $sort_by = $request->get('sort_by');
        $sort_column = $request->get('sort_query');
        $date_column = $request->get('date_column');
        $search_term = $request->get('search_term');
        $columns = $request->get('columns');
        $monthName = $request->get('month');
        $year = $request->get('year');
        $monthNumeric = date('m', strtotime($monthName));

        $date = Carbon::create($year, $monthNumeric);
        $start_date = $date->startOfMonth()->format('Y-m-d');
        $to_date = $date->endOfMonth()->format('Y-m-d');

        // Base query
        $model = LotInventories::query()
            ->join('report_main.user_lot_inventories as uli', 'lot_inventories.id', '=', 'uli.lot_inventory_id')
            ->join('report_billing.payments as p', 'uli.account_number', '=', 'p.account_number')
            ->leftJoin('report_billing.invoice_credit_payments as icp', function ($join) {
                $join->on('p.account_number', '=', 'icp.account_number');
            })
            ->leftJoin('report_billing.invoices as inv', 'icp.invoice_id', '=', 'inv.id') // try e raw query dre
            ->whereBetween('p.or_receipt_date', [$start_date, $to_date])
            ->where('p.status', 'VERIFIED')
            ->where('p.reference_number' , 'PRN000360396')
            ->whereBetween('inv.bill_date', [$start_date, $to_date])
            ->groupBy(
                'uli.account_number',
                'p.or_receipt_date'
            );

        // Select columns dynamically based on request
        $model->select([
            'uli.account_number',
            'p.or_receipt_date',
            DB::raw("FORMAT(COALESCE(SUM(CASE WHEN icp.type IN ('Interest on Assoc Dues', 'Association Dues', 'Penalty on Association Dues', 'Assoc. Dues - Water Tie-up', 'Association Dues (Water Tie-up)', 'Association Dues - (Water Tie-up)', 'Beginning Balance', 'Debit Memo (Association Dues)', 'Association Dues (Dev)', 'Debit Memo', 'Maintenance Dues') AND icp.created_at BETWEEN ? AND ? THEN icp.allocated_amount ELSE 0 END), 0), 2) AS pftm_assoc_dues"),
            DB::raw("FORMAT(COALESCE(SUM(CASE WHEN inv.bill_date < ?  THEN inv.amount_paid ELSE 0 END), 0), 2) AS arrears_hoa"),
            DB::raw("FORMAT(COALESCE(SUM(CASE WHEN inv.bill_date BETWEEN ? AND ? THEN inv.amount_paid ELSE 0 END), 0), 2) AS current_hoa"),
            DB::raw("FORMAT(COALESCE(SUM(CASE WHEN inv.bill_date > ? AND inv.id IN (SELECT invoice_id FROM report_billing.invoice_credit_payments where model_id = p.id) THEN inv.amount_paid ELSE 0 END), 0), 2) AS advance_hoa"),
            ])->setBindings([
            $start_date, $to_date,
            $start_date,
            $start_date, $to_date,
            $to_date,
             $start_date, $to_date,
            'VERIFIED', 'PRN000360396', $start_date, $to_date
        ]);;

        // Add additional columns dynamically
        if (in_array('name', $columns)) {
            $model->addSelect('name');
        }
        if (in_array('customer_name', $columns)) {
            $model->addSelect('lot_inventories.id')->with('customer_name'); 
        }
        if (in_array('lot_area', $columns)) {
            $model->addSelect('lot_area');
        }
        if (in_array('floor_area', $columns)) {
            $model->addSelect('floor_area');
        }
        if (in_array('first_bill_period', $columns)) {
            $model->addSelect('first_bill_period');
        }
        if (in_array('turned_over_date', $columns)) {
            $model->addSelect('turned_over_date');
        }

        // Apply filters
        $model->when($date_column, function ($query) use ($date_column, $start_date, $to_date) {
            return $query->whereBetween($date_column, [$start_date, $to_date]);
        });

        $model->when($sort_column, function ($query) use ($sort_column, $sort_by) {
            $query->orderBy($sort_column, $sort_by);
        });

        $model->when($search_term, function ($query) use ($search_term) {
            return $query->where(function ($q) use ($search_term) {
                $q->where('uli.account_number', 'like', "%$search_term%")
                ->orWhere('p.or_receipt_date', 'like', "%$search_term%");
            });
        });

        // Paginate the results
        $data = $model->paginate($per_page);

        return $this->sendResponse($data, "User lists.");
    }
}
