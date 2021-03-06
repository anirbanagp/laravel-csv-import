<?php
namespace App\Http\Library\Crud\Traits;
use Illuminate\Support\Facades\DB;

/**
 * This will contain all methods we need to display data into table format
 *
 *  @author Anirban Saha
 */
trait ShowList
{
	/**
	 * visible coloumn list in table, associative array where coloumn name in key and label as value
	 */
	public $columns_list;
	/**
	 * if you want to unset default action button, set this. view/edit/delete
	 * @var array
	 */
	public $unset_actions_button = array();
	/**
	 * if you want to unset default realtion field, set this.all _id fields are default relational field
	 * @var array
	 */
	public $unset_relation_coloumn 	= array();
	/**
	 * If you want to add any additional clause in fetch query like where/ group by/ order by
	 * @var string
	 */
	public $additional_where;
	/**
	 * If you want to unset any coloumn from table. by default 3 are unset
	 * @var array
	 */
	public $unset_coloumn 			= ['id','created_at','updated_at'];
	/**
	 * You can set it true if you want to export data
	 * @var [type]
	 */
	public $show_export				= false;
	/**
	 * contain custom add link
	 * @var string
	 */
	public $add_link				= false;
	/**
	 * This will contain field name and callback function name
	 * @var array
	 */
	private $callback_coloumn 		= array();
	/**
	 * contain those coloumn name and label those has callback function
	 * @var array
	 */
	private $extra_coloumn 			= array();
	/**
	 * It will contain relationship details of a table
	 * @var array
	 */
	private $relation_with 			= array();
	/**
	 * If nothing defined crud will try to create relationship for those coloumn which has _id in title
	 * @var array
	 */
	protected $auto_relationship_field = array();
	/**
	 * contain action button lists
	 * @var array
	 */
	private $actions_button 		= array();
	/**
	 * Contain add button details of false
	 * @var mixed
	 */
	public $add_button 			= array();
	/**
	 * This will contain fetched data
	 * @var array
	 */
	private $selected_data 			= array();

	/**
	 * this will contain all extra buttons
	 * @var array
	 */
	private $extra_buttons			=	array();

	/**
	 * This will add more button in action coloumn
	 * @param string $label visible name of button
	 * @param string $class class name of button
	 * @param string $icon  icon of button
	 * @param string $link  link of button, we will add id of respective row at the end
	 * @param int 	$order  order by this
	 */
	public function setActionButton($label,$class,$icon,$link,$order=1)
	{
		$each_action = [];
		$each_action['label'] 	= $label;
		$each_action['class'] 	= $class;
		$each_action['icon'] 	= $icon;
		$each_action['link'] 	= $link;
		$each_action['rank'] 	= $order;
		$this->actions_button[$label] = $each_action;
	}
	/**
	 * If you want to unset Add button
	 */
	public function unsetAdd()
	{
		$this->add_button = false;
	}
	/**
	 * this will generate all data to create table view of a table
	 */
	private function setShowTableData()
	{
		$final_coloumn_list 			= $this->getTableColumnList();
		if(count($final_coloumn_list)){
			$all_data 					= $this->generateTableData($final_coloumn_list);
			$this->show_table_data 		= $all_data;
			$extra_fields 				= array_diff_key($this->extra_coloumn,$final_coloumn_list);
			$view_final_coloumn_list 	= array_merge($extra_fields,$final_coloumn_list);
			$this->show_table_coloumns 	= $this->action_type == "list" ? $final_coloumn_list :$view_final_coloumn_list;
		}else{
			abort(403, 'No coloumn found');
		}
	}
	/**
	 * This will generate table data
	 * @param  array $final_coloumn_list coloumn list of a table
	 * @return array                     all data to be displayed in table
	 */
	private function generateTableData($final_coloumn_list)
	{
		$all_data 					= $this->getShowData($final_coloumn_list);
		$file_field 				= $this->getFileTypeField();
		$table_data 				= [];
		if($all_data){
			$normal_field 			= array_diff_key($final_coloumn_list,$this->callback_coloumn);
			foreach($all_data as $each_row){
				$row_data 			= [];
				foreach ($this->callback_coloumn as $field_name => $details) {
					if(property_exists($each_row,$field_name)){
						$row_data[$field_name] =  $this->{$details['callback_function']}($each_row,$each_row->{$field_name},$this->action_type);
					}
				}
				foreach ($normal_field as $key => $value) {
					if($key != 'action'){
						//set field value name checking with relation field
						$should_to_show_field_value = isset($this->relation_with[$key]) ? $this->relation_with[$key]['show_constraint'] : $key;
						$row_data[$key] 			= $this->action_type == 'list' ? str_limit(strip_tags($each_row->{$should_to_show_field_value}), 50, '...') : $each_row->{$should_to_show_field_value};
						if(in_array($key, $file_field)){
							$row_data[$key] 		= '<a href="'.asset('storage/'.$row_data[$key]).'" target="_blank">'.$row_data[$key].'</a>';
						}
					}else{
						//set action field
						$action 					= '';
						if(count($this->actions_button) && is_array($this->actions_button)){
							$this->actions_button = collect($this->actions_button)->sortBy('rank')->toArray();
							$action 				=	$this->renderActionButton($each_row);
						}
						$row_data[$key] 			= $action;
					}
				}
				$table_data[] 						= $row_data;
			}
		}
		return $table_data;
	}

	/**
	 * this will render action buttons of each row.can be over ride in crud
	 *
	 * @param  object $each_row each row object
	 * @return string           each row action button
	 */
	public function renderActionButton($each_row)
	{
		$html       =   '<div class="flex_button_">';
		$show_dropdown_start = '<div class="dropdown">
                    <button class="btn btn-default dropdown-toggle bg-blue-grey" type="button" id="menu'.$each_row->id.'" data-toggle="dropdown">More
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu'.$each_row->id.'">';
        $show_dropdown_end = '</ul></div></div>';
		$i =	1;
		foreach ($this->actions_button as $label => $button) {
			if($i < 4) {
				$html 				.= '<a href="'.$button['link'].'/'.$each_row->id.'" class="waves-effect '.$button['class'].'"><i class="material-icons">'.$button['icon'].'</i>'.$button['label'].'</a>';
			} else{
				if($i == 4) {
					$html				.= $show_dropdown_start;
				}
				$html					.=	'<li><a class="'.$button['class'].'" href="'.$button['link'].'/'.$each_row->id.'" ><i class="material-icons">'.$button['icon'].'</i> '.$button['label'].'</a></li>';
			}
			$i++;
		}
		$html       .=  $i >3 ? $show_dropdown_end : '';
		$html       .=	'</div>';
		return $html;
	}
	/**
	 * this retun raw table data
	 * @param  array $final_coloumn_list coloumn list of table
	 * @return array                     table data
	 */
	private function getShowData($final_coloumn_list)
	{
		$sql 					= $this->setQuery($final_coloumn_list);
		$all_data 				= DB::select($sql);
		$this->selected_data 	= $all_data;
		return $all_data;
	}
	/**
	 * This will generate Sql for fetching data
	 * @param array $final_coloumn_list coloumn list that need to fetch
	 */
	private function setQuery($final_coloumn_list)
	{
		$final_selected_field = array_keys($final_coloumn_list);
		$selected_field 	  = [];
		$alphabet = range('A', 'Z');
		foreach($final_selected_field as $each_field){
			if($each_field != 'action'){
				$selected_field[] = $this->table_name.'.'.$each_field;
			}
		}
		$join_statement = [];
		if(count($this->relation_with)){
			$i = 0;
			foreach($this->relation_with as $each_relation){
				$selected_field[] 	= $alphabet[$i].'.'.$each_relation['show_field'].' AS '.$each_relation['show_constraint'];
				$join_statement[] 	= 'LEFT JOIN '.$each_relation['with_table'].' AS '.$alphabet[$i].' ON '.$this->table_name.'.'.$each_relation['set_relation'].'='.$alphabet[$i].'.'.$each_relation['with_relation'] ;
				$i++;
			}
		}
		$sql 						= "SELECT ".$this->table_name.'.id,'.implode(',',$selected_field).' FROM '.$this->table_name.' '.implode(" ", $join_statement);
		if($this->additional_where){
			$sql 					.= ' '.$this->additional_where;
		}
		return $sql;
	}
	/**
	 * set default action like view edit delete
	 */
	protected function setDefaultActions()
	{
		$default = [
			'delete' => [
				'label' =>  __('admin_label.form_action_delete'),
				'class' => 'delete_button btn btn-danger',
				'icon' 	=> 'delete_sweep',
				'link' 	=> route($this->route_slug.'delete'),
				'order' => 3
			],
			'edit' => [
				'label' =>  __('admin_label.form_action_edit'),
				'class' => 'btn btn-warning',
				'icon' 	=> 'create',
				'link' 	=> route($this->route_slug.'edit'),
				'order' => 2
			],
			'view' => [
				'label' => __('admin_label.form_action_view'),
				'class' => 'btn btn-info',
				'icon' 	=> 'info',
				'link' 	=> route($this->route_slug.'view'),
				'order' => 1
			],
		];
		//set default action button
		$action_type	=	['canModify', 'canModify', 'canView'];
		$i = 0;
		foreach ($default as $key => $each) {
			if(!in_array($key,$this->unset_actions_button) && (!$this->use_rbac || ($this->use_rbac && $this->{$action_type[$i]}($this->module_slug) ))){
				$this->setActionButton($each['label'],$each['class'],$each['icon'],$each['link'],$each['order']);
			}
			$i++;
		}
	}
	/**
	 * this will set add button
	 */
	public function setAdd()
	{
		$each_action = [];
		$each_action['label'] 	=  __('admin_label.Add');
		$each_action['class'] 	= 'btn btn-success add_button';
		$each_action['icon'] 	= 'add_circle_outline';
		$each_action['link'] 	= $this->add_link == false ?   route($this->route_slug.'add') :  $this->add_link;
		$this->add_button = $each_action;
	}
	/**
	 * This will add more button in action coloumn
	 * @param string $label visible name of button
	 * @param string $class class name of button
	 * @param string $icon  icon of button
	 * @param string $link  link of button, we will add id of respective row at the end
	 * @param int 	$order  order by this
	 */
	public function setExtraButton($label,$class,$icon,$link,$order=0)
	{
		$each_button = [];
		$each_button['label'] 	= $label;
		$each_button['class'] 	= $class;
		$each_button['icon'] 	= $icon;
		$each_button['link'] 	= $link;
		$each_button['rank'] 	= $order;
		$this->extra_buttons[$order] = $each_button;
	}

	/**
	 * This will set custom  add  link
	 * @param string $link custom add link
	 */
	public function setAddLink($link)
	{
		$this->add_link = $link;
	}

	/**
	 * this should be over ride if you want custom action button with some checking
	 * you can modify actions_button depends on $each_row value
	 * @param  object $each_row all_details
	 */
	public function callbackBeforeRenderActionButtons($each_row)
	{
		// code...
	}
}

 ?>
