<?php

namespace App\Http\Controllers\admin;

use App\Models\Billing;
use App\Models\Collector;
use Illuminate\Http\Request;
use App\Http\Controllers\admin\AdminBaseController;

class FinalcialReportController extends AdminBaseController
{
    public function show()
    {
        $this->main_page_title  =   'Financial Report';
        $data                   =   $this->getMenuData();
        $data['collectors']     =   Collector::select('name', 'code', 'id')->get();
        $data['startDate']      =   date('Y-m-01');
        $data['endDate']        =   date('Y-m-d');

        return view('admin.financial-report.show', $data);
    }

    public function generateReport(Request $request)
    {
        $startDate  =   $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : date('Y-m-01');
        $endDate    =   $request->endDate ? date('Y-m-d', strtotime($request->endDate.'+1 day')) : date('Y-m-d', strtotime('+1 day'));
        $collector  =   $request->collectorId ?? false;
        $status     =   $request->status ?? false;

        $billings   =   Billing::query();
        $billings   =   $billings->whereBetween('created_at', [$startDate, $endDate]);
        if ($status) {
            $billings   =   $billings->whereStatus($status);
        }
        if ($collector) {
            $billings   =   $billings->whereIn('collector_id', $collector);
        }
        $data['billingReport']  =   $billings->get();

        return view('admin.financial-report.table', $data);
    }
}
