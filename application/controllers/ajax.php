<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('a_controller.php');

// designated to work with ALL ajax requests!
class Ajax extends a_controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'html', 'array'));
	}

	public function edit($library, $idItem) {
		$this->load->library($library);
		if (isset($this->$library)) {
			$this->$library->load($idItem);
			$this->$library->dialog('edit');
		}
	}
	
	public function save($library, $idItem = 0) {
		$this->load->library($library);
		if (isset($this->$library)) {
			$values = $this->input->post();
			$values['id'] = $idItem;
			$this->$library->save($values);
		}
	}

	public function show($library, $idItem) {
		$this->load->library($library);
		if (isset($this->$library)) {
			$this->$library->load($idItem);
			$params = array(
				'ajax' => TRUE
			);			
			echo processItems($this->$library->show($params));
		}
	}



	public function _save() {
		$values = $this->input->post();
		if (empty($values['type'])) {
			return FALSE;
		}
		
		switch ($values['type']) {
			case 'image':
				$this->saveImage($values);
				break;
			default:
				echo 'invalid type!';
		}
	}
	
	protected function saveImage($values) {
		$item = array();
		if (!empty($values['id'])) {
			$item['id'] = $values['id'];
		} else { // no id provided, exit!
			return FALSE;
		}
		$context = array(
			'table' => 'files',
			'where' => array(
				'id' => $item['id']
			)
		);
		if ($rows = $this->entity_model->getItem($context, '*')) {
			$original = json_decode($rows[0]['params'], TRUE);
			if (empty($original)) {
				$original = array();
			}
			if (isset($values['top']) && isset($values['left'])) {
				$item['params'] = json_encode(array_merge($original, array(
					'top' => $values['top'],
					'left' => $values['left']
				)));
			}
			$this->load->library('item');
			$this->item->saveFile($item);
		}
	}
	
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */