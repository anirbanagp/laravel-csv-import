<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\CollectorRequest;
use App\Imports\CollectorImport;

class CollectorCrud extends Crud
{
	/**
	 * name of the table . REQUIRED
	 * @var string
	 */
	public $table_name 				=	'collectors';

	/**
	 * contain model path
	 * @var string
	 */
	public $model_path				=	'App\Models\\';

	/**
	 * route name that shold be used to create different action link. REQUIRED
	 * @var string
	 */
	public $route_slug 				=	'admin-collectors-';
	/**
	 * You can use RBAC to manage action button by crud. OPTIONAL
	 * @var bool
	 */
	public $use_rbac				=	true;

	/**
	 * crud will check permission for this slug if rbac is true
	 * @var string
	 */
	public $module_slug				=	'collectors-';
	/**
	 * You can unset action button. 'view/edit/delete acceptable'. OPTIONAL
	 * @var array
	 */
	public $unset_actions_button	=	[];

	public $unset_coloumn 			=	['id','created_at','updated_at','updated_by', 'deleted_at'];

	/**
	 * This will display table data in view page in data table
	 * @return view           	 load view page
	 */
    public function show()
    {
		$this->page_title = 'Associates List';
		$this->setExtraButton('Upload CSV','btn btn-info upload_button','cloud_upload',route('admin-collectors-get-upload-csv-form'));
    	$data = $this->rendarShow();
		return view('admin.crud.show',$data);
    }
	/**
	 * This will display a details for an id of this table
	 * @param  integer  $id      id of selected row
	 * @return view           	 load view page
	 */
	public function view($id)
	{
		$this->page_title = 'View Associate';
		$data = $this->rendarView($id);
		return view('admin.crud.view',$data);
	}
	/**
	 * This will load an insert form for current table
	 * @return view   load view page
	 */
	public function add()
	{
		$this->page_title = 'Add Associate';
		$data = $this->rendarAdd();
		return view('admin.crud.form',$data);
	}
	/**
	 * This will insert data into databse
	 * @param  CollectorRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function insert(CollectorRequest $request)
	{
		$response = $this->insertData($request);
		// $this->current_model->code	=	$this->current_model->id;
		// $this->current_model->save();
		return redirect($response);
	}
	/**
	 * this will load edit form
	 * @param  integer $id id of this table
	 * @return view     load edit form
	 */
	public function edit($id)
	{
		$this->page_title = 'Edit Associate';
		$data = $this->rendarEdit($id);
		return view('admin.crud.form',$data);
	}
	/**
	 * this will update a row
	 * @param  CollectorRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function update(CollectorRequest $request)
	{
		$response = $this->updateData($request);
		return redirect($response);
	}
	/**
	 * this will delete a row
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
		$this->additional_where   = 'WHERE collectors.deleted_at IS NULL AND id != 1';
		// $this->changeFieldType('code','hidden','code');
		$this->changeFieldType('address','textarea','Address');
		$this->setActionButton('Price', '', 'monetization_on', route('admin-collectors-commission-'),4);
	}
	public function getUploadCsvForm()
	{
		$this->main_page_title 	= 'Associates Csv Upload';
		$data					=	$this->getMenuData();
		$data['insert_url']		=	route('admin-collectors-upload-csv');
		$data['back_url']		=	route('admin-collectors-');

		return view('admin.common.csv-from', $data);
	}
	public function uploadCsv(Request $request)
	{
		if(!$request->has('csv_file')) {
			throwValidationError('csv_file', 'Please choose a valid csv file');
		}
		$all_rows = file($request->file('csv_file'));

		if(count($all_rows) && str_getcsv($all_rows[0]) === ['name', 'ph_number']) {
			unset($all_rows[0]);
			foreach ($all_rows as $row) {
				$entry = CollectorImport::insert(str_getcsv($row));
			}

			$this->setFlashAlert('success', 'Successfully Imported');

			return redirect(route($this->route_slug));
		} else {
			throwValidationError('csv_file', 'Please choose a valid csv file');
		}
	}
}
