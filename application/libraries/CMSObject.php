<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMSObject {
	protected $id = 0;
	protected $title = '';
	protected $description = '';
	protected $status = 0;
	protected $_CI;

    public function __construct() {
		$this->_CI = & get_instance();
    }
	
	public function load($id = 0) {
		$context = array(
			'table' => 'objects',
			'where' => array(
				'id' => $id
			)
		);
		if ($rows = $this->_CI->entity_model->getItem($context, 'id, title, description, status')) {
			$this->_init($rows[0]);
		}
	}
	
	public function loadByTitle($title = '') {
		$context = array(
			'table' => 'objects',
			'where' => array(
				'title like \''.$title.'\'' => NULL
			)
		);
		if ($rows = $this->_CI->entity_model->getItem($context, 'id, title, description, status')) {
			$this->_init($rows[0]);
		} else { // create a new object, save him to DB and load into $this
			$this->_init();
			$this->title = $title;
			$this->save();
		}
	}
	
	public function save($values = array()) {
		if (!empty($values)) {
			$this->_init($values);
		}
		$context = array(
			'table' => 'objects'
		);
		if (!empty($this->id)) {
			$context['where'] = array(
				'id' => $this->id
			);
		}
		$values = array(
			'title' => $this->title,
			'description' => $this->description,
			'status' => $this->status
		);
		$this->id = $this->_CI->entity_model->saveItem($context, $values);
	}
	
	public function delete() {
	}
	
	public function _init($params = array()) {
		$defaults = array(
			'id' => 0,
			'title' => '',
			'description' => '',
			'status' => 0
		);
		foreach ($defaults as $key => $val) {
			if (isset($params[$key])) {
				$this->$key = $params[$key];
			} else {
				$this->$key = $val;
			}
		}
	}
	
	public function show($params = array()) {
		$params['html'] = $this->description;
		$urls = $this->_CI->config->item('routes');
		if ($this->_CI->loggedUser) {
			if (isset($params['class'])) {
				$params['class'] .= ' admin';
			} else {
				$params['class'] = 'admin';
			}
			$params['class'] .= ' loadable cmsObject';
			$params['src'] = site_url($urls['cmsobject']['show'].$this->id);
			$params['html'] .= '<span class="cmsTitle">'.$this->title.'</span>';
			$params['html'] .= '<a class="edit_button pop_triger" href="'.site_url($urls['cmsobject']['edit'].$this->id).'" title="Edit"><span class="ui-icon ui-icon-circle-plus"></span>edit</a>';
		}
		return showObject($params);
	}
	
	public function dialog($type = 'edit') {
		$urls = $this->_CI->config->item('routes');
		$data = array(
			'formAction' => site_url($urls['cmsobject']['save'].$this->id),
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'status' => $this->status
		);
		$this->_CI->load->view('loadObject', $data);
	}
	
	public function dump() {
		echo '<pre>'.print_r($this, TRUE).'</pre>';
	}
}

/* End of file CMSObject.php */