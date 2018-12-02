<?php

namespace App\Http\Controllers\admin;

use DB;
use App\Models\Patient;
use App\Models\Billing;
use App\Models\PathTest;
use App\Models\Collector;
use Illuminate\Http\Request;
use App\Models\CollectorsPathTest;
use App\Http\Requests\BillingRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\admin\AdminBaseController;

class BillingController extends AdminBaseController
{
    public $module_slug_name    =   'billing-';

    public function show()
    {
        $this->main_page_title  =   'Billing List';
        $data                   =   $this->getMenuData();
        $data                   =   array_merge($data , $this->getActionButton());
        return view('admin.billing.show', $data);
    }
    /**
     *  this will called bu ajax DataTables
     *
     *  @return  json  all data
     */
    public function data()
    {
        $billings	= Billing::with('patient','billing_details','collector')->orderBy('billings.created_at', 'desc');
        return   DataTables::of($billings)
                ->editColumn('patient_id', function(Billing $billing) {
                       return $billing->patient->name;
                })
                ->editColumn('total_amount', function(Billing $billing) {
                       return priceFormat($billing->total_amount);
                })
                ->editColumn('discount_or_commission', function(Billing $billing) {
                       return priceFormat($billing->discount_or_commission);
                })
                ->editColumn('paid_amount', function(Billing $billing) {
                       return priceFormat($billing->paid_amount);
                })
                ->editColumn('collector_id', function(Billing $billing) {
                       return optional($billing->collector)->visibleName;
                })
                // ->editColumn('status', '{!! setStatus($status) !!}')
                ->editColumn('created_at', function(Billing $billing) {
                       return date('d M, y', strtotime($billing->created_at));
                })
                ->addColumn('action', function(Billing $billing) {
                       return $this->actionField($billing);
                })
                // ->addColumn('tests', function(Billing $billing) {
                //        return $billing->billing_details->implode('test_name', ', ');
                // })
                ->addColumn('net_amount', function(Billing $billing) {
                       return priceFormat($billing->net_amount);
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }

    public function view(Request $request)
    {
        $this->main_page_title  =   'Billing Details';
        $data                   =   $this->getMenuData();
        $data                   =   array_merge($data , $this->getActionButton(),['billing' => $request->billing]);
        $data['edit_url']       =   route('admin-billing-edit', $request->id);
        $data['back_url']       =   route('admin-billing-');
        return view('admin.billing.view', $data);
    }
    public function add()
    {
        $this->main_page_title  =   'Add Billing ';
        $data   =   $this->getMenuData();
        $data['collectors'] =   Collector::select('name', 'code', 'id')->get();
        $data['tests']      =   PathTest::select('name', 'sample', 'price', 'id')->get();
        $data['billing']    =   optional();
        return view('admin.billing.add', $data);
    }
    public function insert(BillingRequest $request)
    {
        $amounts                =   json_decode($this->getBillingAmount($request));

        $data['patient_id']     =   $this->getPatientId();
        $data['collector_id']   =   $request->collector_id;
        $data['doctor_name']    =   $request->doctor_name;
        $data['paid_amount']    =   $request->paid_amount;
        $data['status']         =   ($amounts->total_amount - $amounts->discount -$request->paid_amount) === 0 ? 'paid' : 'due';
        $data['total_amount']   =   $amounts->total_amount;
        $data['created_at']     =   date('Y-m-d', strtotime($request->created_at));
        $data['discount_or_commission']   =   $amounts->discount;

        DB::transaction(function () use($data, $request) {
            $billing        =   Billing::updateOrCreate(['id' => $request->id], $data);
            $billing->code  =   $billing->id;
            $billing->save();

            $this->saveBillingDetails($billing)->savePaymentDetails($billing);
        });
        $this->setFlashAlert('success', 'Successfully Added!');

        return redirect(route('admin-billing-'));

    }

    public function edit(Request $request)
    {
        $this->main_page_title  =   'Update Billing';
        $data                   =   $this->getMenuData();
        $data['billing']        = $request->billing;
        // dd(optional($data['billing']->billDate));
        $data['collectors'] =   Collector::select('name', 'code', 'id')->get();
        $data['tests']      =   PathTest::select('name', 'sample', 'price', 'id')->get();

        return view('admin.billing.add', $data);
    }
    public function update(BillingRequest $request)
    {
        $request->billing->paid_amount  = $request->paid_amount;
        $request->billing->status       = ($request->billing->net_amount - $request->paid_amount) === 0 ? 'paid' : 'due';
        $request->billing->save();

        $this->setFlashAlert('success', 'Successfully Updated!');

        return redirect(route('admin-billing-'));
    }
    public function getPatientName($slug)
    {
        $users		=	Patient::select('id', 'name')->wherePhNumber($slug)->get();

        $html 		=	'<ul class="users-list">';
        if($users && count($users)) {
            foreach ($users as $key => $value) {
                $html 	.=	'<li class="each-user" data-id="'.$value->id.'" data-val="'.$value->name.'">'.$value->name.'</li>';
            }
        }
        $html 	        .=	'</ul>';
        return $html;
    }
    public function getBillingAmount(Request $request)
    {
        $data['net_amount']     =   0;
        $data['total_amount']   =   0;
        $data['discount']       =   0;
        $test_price             =   PathTest::whereIn('id', $request->test_id ?? [])->pluck('price', 'id')->toArray();

        foreach ((array)$request->test_id as  $id) {
            $assoc_price    =   CollectorsPathTest::whereId($id)->whereCollectorId($request->collector_id)->first();
            if(isset($assoc_price->id) && $assoc_price->price) {
                $data['net_amount']     +=  $assoc_price->price;
            } else {
                $data['net_amount']     +=  $test_price[$id];
            }
            $data['total_amount']       +=  $test_price[$id];
        }

        $data['discount']       =   $request->discount_or_commission ?? ($data['total_amount'] - $data['net_amount']);

        return json_encode($data);
    }
    private function getPatientId()
    {
        $request             =   $this->request;

        $patient            =   Patient::firstOrNew(['id' => $request->patient_id]);
        $patient->name      =   $request->patient_name;
        $patient->code      =   $request->patient_code;
        $patient->ph_number =   $request->ph_number;
        $patient->save();

        return $patient->id;
    }
    private function saveBillingDetails(Billing $billing)
    {
        $billingDetails =   [];
        foreach (array_wrap($this->request->test_id) as  $test_id) {
            $test   =   PathTest::find($test_id);
            if(isset($test->id)) {
                $testDetails    =   [];
                $testDetails['test_name']       =   $test->name . ' - '. $test->sample;
                $testDetails['test_price']      =   $test->price;
                $testDetails['net_price']       =   $billing->collector->getAssocPriceOf($test->id, $test->price);
                $testDetails['path_test_id']    =   $test->id;
                $billingDetails[]               =   $testDetails;

            } else {
                throwValidationError('test_id', 'Invalid test selected');
            }
        }
        $billing->billing_details()->delete();
        $billing->billing_details()->createMany($billingDetails);

        return $this;
    }
    private function savePaymentDetails(Billing $billing)
    {
        $billing->payments()->updateOrCreate(['billing_id' => $billing->id],['amount' => $this->request->paid_amount]);

        return $this;
    }
    /**
     *  it will return action button html based on role of user
     *
     *  @param  object  $value  each row object
     *  @return  string  html string
     */
    public function actionField($value)
    {
        $this->module_slug_name =   $this->module_slug_name ? $this->module_slug_name : ($this->module_slug ? $this->module_slug : '');
        $html = '';
        if($this->canView()) {
            $html	.=	'<a href="'. route('admin-billing-view',$value->id).'" class="waves-effect btn btn-info"><i class="material-icons">info</i> View</a>';
        }
        if($this->canModify()) {
            $html	.=	'<a href="'. route('admin-billing-edit',$value->id).'" class="waves-effect edit_button btn bg-teal"><i class="material-icons">create</i> Edit</a>';
        }
        if($this->canView('payments-')) {
            $html	.= '<a href="'. route('admin-billing-payments-',$value->id) .'" class="waves-effect btn btn-warning"><i class="material-icons">money</i> Payments</a>';
        }
        return $html;
    }
    public function delete(Request $request)
    {
        $request->billing->delete();
        $this->setFlashAlert('success', 'successfully deleted');

        return back();
    }
}
