<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\PathTestRequest;
use App\Imports\PathTestImport;
use Maatwebsite\Excel\Facades\Excel;

class PathTestCrud extends Crud
{
	/**
	 * name of the table . REQUIRED
	 * @var string
	 */
	public $table_name 				=	'path_tests';

	/**
	 * contain model path
	 * @var string
	 */
	public $model_path				=	'App\Models\\';

	/**
	 * route name that shold be used to create different action link. REQUIRED
	 * @var string
	 */
	public $route_slug 				=	'admin-tests-';
	/**
	 * You can use RBAC to manage action button by crud. OPTIONAL
	 * @var bool
	 */
	public $use_rbac				=	true;

	/**
	 * crud will check permission for this slug if rbac is true
	 * @var string
	 */
	public $module_slug				=	'tests-';
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
		$this->page_title = 'Test List';
		$this->setExtraButton('Upload CSV','btn btn-info upload_button','cloud_upload',route('admin-tests-get-upload-csv-form'));
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
		$this->page_title = 'View Test';
		$data = $this->rendarView($id);
		return view('admin.crud.view',$data);
	}
	/**
	 * This will load an insert form for current table
	 * @return view   load view page
	 */
	public function add()
	{
		$this->page_title = 'Add Test';
		$data = $this->rendarAdd();
		return view('admin.crud.form',$data);
	}
	/**
	 * This will insert data into databse
	 * @param  PathTestRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function insert(PathTestRequest $request)
	{
		$response = $this->insertData($request);
		return redirect($response);
	}
	/**
	 * this will load edit form
	 * @param  integer $id id of this table
	 * @return view     load edit form
	 */
	public function edit($id)
	{
		$this->page_title = 'Edit Test';
		$data = $this->rendarEdit($id);
		return view('admin.crud.form',$data);
	}
	/**
	 * this will update a row
	 * @param  PathTestRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function update(PathTestRequest $request)
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
		$this->additional_where   = 'WHERE path_tests.deleted_at IS NULL';
		$this->addCallBackColoumn('price', 'Price', 'setPriceView');
	}
	public function getUploadCsvForm()
	{
		$this->main_page_title 	= 'Test Csv Upload';
		$data					=	$this->getMenuData();
		$data['insert_url']		=	route('admin-tests-upload-csv');
		$data['back_url']		=	route('admin-tests-');

		return view('admin.common.csv-from', $data);
	}
	public function uploadCsv(Request $request)
	{
		if(!$request->has('csv_file')) {
			throwValidationError('csv_file', 'Please choose a valid csv file');
		}
		$all_rows = file($request->file('csv_file'));

		if(count($all_rows) && str_getcsv($all_rows[0]) === ['name', 'sample', 'price']) {
			unset($all_rows[0]);
			foreach ($all_rows as $row) {
				$entry = PathTestImport::insert(str_getcsv($row));
			}

			$this->setFlashAlert('success', 'Successfully Imported');

			return redirect(route($this->route_slug));
		} else {
			throwValidationError('csv_file', 'Please choose a valid csv file');
		}
	}
	public function setPriceView($row_data,$value,$type)
	{
		if($type =="list" || $type =="view"){
			return priceFormat($value);
		}
		return $value;
	}
}
