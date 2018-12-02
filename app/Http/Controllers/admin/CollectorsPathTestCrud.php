<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\CollectorsPathTestRequest;
use App\Http\Library\Crud\Traits\RbacHelper;
use App\Imports\CollectorPathTestImport;
use App\Models\PathTest;

class CollectorsPathTestCrud extends Crud
{
	use RbacHelper;
	/**
	 * name of the table . REQUIRED
	 * @var string
	 */
	public $table_name 				=	'collectors_path_tests';

	/**
	 * contain model path
	 * @var string
	 */
	public $model_path				=	'App\Models\\';

	/**
	 * route name that shold be used to create different action link. REQUIRED
	 * @var string
	 */
	public $route_slug 				=	'admin-collectors-commission-';
	/**
	 * You can use RBAC to manage action button by crud. OPTIONAL
	 * @var bool
	 */
	public $use_rbac				=	true;

	/**
	 * crud will check permission for this slug if rbac is true
	 * @var string
	 */
	public $module_slug				=	'collectors-commission-';
	/**
	 * You can customize you table coloumn.
	 *  field name as key, label as value. only table field are acceptable. OPTIONAL
	 * @var array
	 */
	public $columns_list			=	['path_test_id' => 'Test name',  'price' => 'Deposit Price'];

	public $unset_relation_coloumn	=	['collector_id'];
	/**
	 * You can unset action button. 'view/edit/delete acceptable'. OPTIONAL
	 * @var array
	 */
	public $unset_actions_button	=	['delete'];

	public $unset_coloumn 			=	['id','created_at','updated_at','updated_by', 'deleted_at'];

	/**
	 * This will display table data in view page in data table
	 * @return view           	 load view page
	 */
    public function show(Request $request)
    {
		$this->base_id = $request->id;
		$this->page_title = $request->collector->name.'`s Price List';
		$this->setRelation('path_test_id', 'path_tests', 'name');
		$this->setExtraButton('Upload CSV','btn btn-info upload_button','cloud_upload',route('admin-collectors-commission-get-upload-csv-form', $request->id));
		$this->setExtraButton('Back','btn bg-grey ','keyboard_backspace',route('admin-collectors-'));
    	$data = $this->rendarShow();
		if($data['add_button']) {
			$data['add_button']['link'] = $data['add_button']['link'].'/'.$request->id;
		}
		return view('admin.crud.show',$data);
    }
	/**
	 * This will display a details for an id of this table
	 * @param  integer  $id      id of selected row
	 * @return view           	 load view page
	 */
	public function view(Request $request, $test_id)
	{
		$this->base_id = $request->id;
		$this->page_title = 'View '.$request->collector->name. '`s Price';
		array_push($this->unset_coloumn, 'collector_id');
		$data = $this->rendarView($test_id);
		$data['edit_url'] 	= str_before($data['edit_url'], 'edit').'edit/'.$request->id.'/'.$test_id;
		$data['back_url'] 	= $data['back_url'].'/'.$request->id;
		return view('admin.crud.view',$data);
	}
	/**
	 * This will load an insert form for current table
	 * @return view   load view page
	 */
	public function add(Request $request, $id)
	{
		$this->page_title = 'Add '.$request->collector->name. '`s Price';
		$this->changeFieldType('collector_id', 'text' , "Collector", $request->collector->visible_name, null, null, 'disabled');
		$data = $this->rendarAdd();
		$data['insert_url'] = $data['insert_url'].'/'.$id;
		$data['back_url'] 	= $data['back_url'].'/'.$id;
		moveElement($data['input_list'], 2, 0);
		return view('admin.crud.form',$data);
	}
	public function massAdd(Request $request, $id)
	{
		$this->main_page_title  =   $request->collector->name. '`s Price List';
        $data   =   $this->getMenuData();
        $data['id']      	=   $id;
        $data['tests']      =   PathTest::select('name', 'sample', 'price', 'id')->get();
		$data['prices']		=	$request->collector->collector_path_tests()->pluck('price', 'path_test_id')->toArray();
        return view('admin.collectors-price.add', $data);
	}
	/**
	 * This will insert data into databse
	 * @param  CollectorsPathTestRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function insert(CollectorsPathTestRequest $request)
	{
		$request->collector_id	=	$request->collector->id;
		$response = $this->insertData($request);
		return redirect($response.'/'.$request->collector->id);
	}
	public function massInsert(Request $request)
	{
		$request->collector_id	=	$request->collector->id;
		$tests      =   PathTest::select('name', 'sample', 'price', 'id')->get();
		$errors		=	[];
		foreach ($tests as $test) {
			if ((float)$request->{'test_'.$test->id} && (float)$request->{'test_'.$test->id} > $test->price) {
				$errors['test_'.$test->id] = $test->name .' deposit price is greater than test price ';
			}
			$price			=	(float)$request->{'test_'.$test->id} ?? $test->price;
			$request->collector->collector_path_tests()->updateOrCreate(['path_test_id' => $test->id], ['price' => $price]);
		}
		if(count($errors)) {

			$error = \Illuminate\Validation\ValidationException::withMessages($errors);
			throw $error;
		}
		$this->setFlashAlert('success', 'Successfully Updated.');
		return redirect(route('admin-collectors-commission-', $request->collector_id));
	}
	/**
	 * this will load edit form
	 * @param  integer $id id of this table
	 * @return view     load edit form
	 */
	public function edit(Request $request,$id, $test_id)
	{
		$this->base_id = $request->id;
		$this->page_title = 'Edit '.$request->collector->name. '`s Price';
		$this->changeFieldType('collector_id', 'hidden' , "Collector", $request->collector->id, null, null, 'disabled');
		$this->changeFieldType('path_test_id', 'text' , "Test Name", $request->collectorspathtest->path_test->visibleNameWithMRP, null, null, 'disabled');
		$data = $this->rendarEdit($test_id);
		$data['insert_url'] = $data['insert_url'].'/'.$id;
		$data['back_url'] 	= $data['back_url'].'/'.$id;
		$data['input_list']['id']['field_name']				=	'test_id';

		return view('admin.crud.form',$data);
	}
	/**
	 * this will update a row
	 * @param  CollectorsPathTestRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function update(CollectorsPathTestRequest $request)
	{
		$request->collectorspathtest->price = $request->price;
		$request->collectorspathtest->save();
		return redirect(route('admin-collectors-commission-', $request->collector->id));
	}
	/**
	 * this will delete a row
	 * @param  inetger $id id or row to be deleted
	 * @return void     redirect to list page
	 */
	public function delete($id, $test_id)
	{
		$response = $this->deleteData($test_id);
		return redirect($response);
	}
	/**
	 * If you want to call any function for all, set here. by default crud will call this
	 * @return void        called by crud self
	 */
	public function callDefault()
	{
		$this->additional_where   = 'WHERE collectors_path_tests.collector_id ='.$this->base_id;
		$this->addCallBackColoumn('price', 'Price', 'setPriceView');
		$this->addCallBackColoumn('path_test_id', 'Test Name', 'setTestVisibleName');
	}
	public function getUploadCsvForm(Request $request)
	{
		$this->main_page_title 	= 	$request->collector->name. '`s Price Csv Upload';
		$data					=	$this->getMenuData();
		$data['insert_url']		=	route('admin-collectors-commission-upload-csv', $request->id);
		$data['back_url']		=	route('admin-collectors-commission-', $request->id);

		return view('admin.common.csv-from', $data);
	}
	public function uploadCsv(Request $request)
	{
		if(!$request->has('csv_file')) {
			throwValidationError('csv_file', 'Please choose a valid csv file');
		}
		$all_rows = file($request->file('csv_file'));

		if(count($all_rows) && str_getcsv($all_rows[0]) === ['test_name', 'sample', 'price']) {
			unset($all_rows[0]);
			foreach ($all_rows as $row) {
				$entry = CollectorPathTestImport::insert(str_getcsv($row), $request->id);
			}

			$this->setFlashAlert('success', 'Successfully Imported');

			return redirect(route($this->route_slug, $request->id));
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
	public function setTestVisibleName($row_data,$value,$type)
	{
		$path_test	=	PathTest::find($row_data->path_test_id);
		if($type =="list" || $type =="view" || $type =="edit"){
			return $path_test->visibleNameWithMRP ?? $value;
		}
	}
}
