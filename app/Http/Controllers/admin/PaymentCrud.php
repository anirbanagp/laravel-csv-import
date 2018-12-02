<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Library\Crud\Traits\RbacHelper;

class PaymentCrud extends Crud
{
	use RbacHelper;
	/**
	 * name of the table . REQUIRED
	 * @var string
	 */
	public $table_name 				=	'payments';

	/**
	 * contain model path
	 * @var string
	 */
	public $model_path				=	'App\Models\\';

	/**
	 * route name that shold be used to create different action link. REQUIRED
	 * @var string
	 */
	public $route_slug 				=	'admin-billing-payments-';
	/**
	 * You can use RBAC to manage action button by crud. OPTIONAL
	 * @var bool
	 */
	public $use_rbac				=	true;

	/**
	 * crud will check permission for this slug if rbac is true
	 * @var string
	 */
	public $module_slug				=	'payments-';
	/**
	 * You can customize you table coloumn.
	 *  field name as key, label as value. only table field are acceptable. OPTIONAL
	 * @var array
	 */
	public $columns_list			=	['amount' => 'Amount', 'created_at' => 'Paid at'];
	/**
	 * You can unset action button. 'view/edit/delete acceptable'. OPTIONAL
	 * @var array
	 */
	public $unset_actions_button	=	['view', 'edit', 'delete'];

	public $unset_coloumn 			=	['id','updated_at','updated_by', 'deleted_at'];

	public $unset_relation_coloumn	=	['billing_id'];


	/**
	 * This will display table data in view page in data table
	 * @return view           	 load view page
	 */
    public function show(Request $request)
    {
		$this->page_title = 'Payment List';
		$this->base_id		=	$request->id;
		$this->setExtraButton('Back','btn btn-info ','keyboard_backspace',route('admin-billing-'));
    	$data = $this->rendarShow();
		if($data['add_button'] && $request->billing->due_amount) {
			$data['add_button']['link'] = $data['add_button']['link'].'/'.$request->id;
		} else {
			$data['add_button']	=	false;
		}
		return view('admin.crud.show',$data);
    }
	/**
	 * This will display a details for an id of this table
	 * @param  integer  $id      id of selected row
	 * @return view           	 load view page
	 */
	public function view(Request $request)
	{
		$this->page_title = 'View Payment';
		$this->base_id		=	$request->id;
		$data = $this->rendarView($request->payment_id);
		return view('admin.crud.view',$data);
	}
	/**
	 * This will load an insert form for current table
	 * @return view   load view page
	 */
	public function add(Request $request)
	{
		$this->page_title = 'Add Payment';
		array_push($this->unset_coloumn, 'created_at');
		$this->changeFieldType('billing_id', 'text' , "Bill", $request->billing->code, null, null, 'disabled');
		$data = $this->rendarAdd();
		$data['insert_url'] = $data['insert_url'].'/'.$request->id;
		$data['back_url'] 	= $data['back_url'].'/'.$request->id;
		moveElement($data['input_list'], 1, 0);
		return view('admin.crud.form',$data);
	}
	/**
	 * This will insert data into databse
	 * @param  PaymentRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function insert(PaymentRequest $request)
	{
		$request->billing_id	=	$request->id;
		array_push($this->unset_coloumn, 'created_at');
		$response = $this->insertData($request);
		$request->billing->paid_amount += $request->amount;
		$request->billing->status 	   = ($request->billing->net_amount - $request->billing->paid_amount) === 0 ? 'paid' : 'due';
		$request->billing->save();
		return redirect($response.'/'.$request->id);
	}
	/**
	 * this will load edit form. NOT WORKING SHOULD CHANGE FIELD NAME
	 * @param  integer $id id of this table
	 * @return view     load edit form
	 */
	public function edit(Request $request)
	{
		$this->page_title = 'Edit Payment';
		$this->base_id		=	$request->id;
		$data = $this->rendarEdit($request->payment_id);
		return view('admin.crud.form',$data);
	}
	/**
	 * this will update a row. NOT WORKING SHOULD CHANGE FIELD NAME
	 * @param  PaymentRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function update(PaymentRequest $request)
	{
		$response = $this->updateData($request);
		return redirect($response);
	}
	/**
	 * this will delete a row.NOT WORKING SHOULD CHANGE FIELD NAME
	 * @param  inetger $id id or row to be deleted
	 * @return void     redirect to list page
	 */
	public function delete($id)
	{
		$response = $this->deleteData($id);
		return redirect($response);
	}
	/**
	 * If you want to call any function for all, set here. by default crud will call this
	 * @return void        called by crud self
	 */
	public function callDefault()
	{
		$this->additional_where   = 'WHERE payments.billing_id ='.$this->base_id;
		$this->addCallBackColoumn('amount', 'Amount', 'setPriceView');
		$this->addCallBackColoumn('created_at', 'Paid at', 'setPaidAt');
	}
	public function setPriceView($row_data,$value,$type)
	{
		if($type =="list" || $type =="view"){
			return priceFormat($value);
		}
		return $value;
	}
	public function setPaidAt($row_data,$value,$type)
	{
		if($type =="list" || $type =="view"){
			return date('d M, Y H:i', strtotime($value));
		}
		return $value;
	}
}
