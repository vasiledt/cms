<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMSImage {
	protected $id = 0;
	protected $title = '';
	protected $description = '';
	protected $status = 0;
	protected $item = 0;
	protected $type = 'image';
	protected $entity = '';
	protected $path = '';
	protected $params = '';
	protected $_CI;

    public function __construct() {
		$this->_CI = & get_instance();
    }
	
	public function load($id = 0) {
		$context = array(
			'table' => 'files',
			'where' => array(
				'id' => $id
			)
		);
		if ($rows = $this->_CI->entity_model->getItem($context, 'id, title, description, status, item, entity, path, params')) {
			$this->_init($rows[0]);
		}
	}
	
	public function loadByTitle($title = '') {
		$context = array(
			'table' => 'files',
			'where' => array(
				'title like \''.$title.'\'' => NULL,
				'type' => $this->type
			)
		);
		if ($rows = $this->_CI->entity_model->getItem($context, 'id, title, description, status, item, entity, path, params')) {
			$this->_init($rows[0]);
		} else { // create a new object, save him to DB and load into $this
			$this->_init();
			$this->title = $title;
			$this->save();
		}
	}
	
	public function loadByOwner($idItem = 0, $entity='') {
		$context = array(
			'table' => 'objects',
			'where' => array(
				'item' => $idItem,
				'entity' => $entity,
				'type' => $this->type
			)
		);
		if ($rows = $this->_CI->entity_model->getItem($context, 'id, title, description, status, item, entity, path, params')) {
			$this->_init($rows[0]);
		} else { // create a new object, save him to DB and load into $this
			$this->_init();
			$this->idItem = $idItem;
			$this->entity = $entity;
			$this->save();
		}
	}
	
	public function save($values = array()) {
		if (!empty($values)) {
			$this->_init($values);
		}
		$context = array(
			'table' => 'files'
		);
		if (!empty($this->id)) {
			$context['where'] = array(
				'id' => $this->id
			);
		}

		if ($imgData = $this->uploadImage()) {
			if ($imgData['is_image']) {
				$this->path = $imgData['file_name'];
				if (!is_array($this->params)) {
					$this->params = array();
				}
				$this->params['w'] = $imgData['image_width'];
				$this->params['h'] = $imgData['image_height'];
			}
		}
		$values = array(
			'title' => $this->title,
			'description' => $this->description,
			'status' => $this->status,
			'item' => $this->item,
			'type' => $this->type,
			'entity' => $this->entity,
			'path' => $this->path,
			'params' => json_encode($this->params)
		);
		$this->id = $this->_CI->entity_model->saveItem($context, $values);
		echo '<script type="text/javascript">window.parent.closePop();</script>';
	}
	
	public function delete() {
	}
	
	public function _init($params = array()) {
		$defaults = array(
			'id' => 0,
			'title' => '',
			'description' => '',
			'status' => 0,
			'item' => 0,
			'type' => $this->type,
			'entity' => '',
			'path' => '',
			'params' => ''
		);
		foreach ($defaults as $key => $val) {
			if (isset($params[$key])) {
				$this->$key = $params[$key];
			} else {
				$this->$key = $val;
			}
		}
		if (!empty($this->params) && is_string($this->params)) {
			$this->params = json_decode($this->params, TRUE);
		}
	}
	
	public function show($params = array()) {
		if (isset($params['style']) && is_string($params['style'])) {
			$params['style'] = parseStyle($params['style']);
		}
		var_dump($params);
		// $this->dump();
		
		$params['html'] = '';
		$urls = $this->_CI->config->item('routes');
		if ($this->_CI->loggedUser) {
			if (isset($params['class'])) {
				$params['class'] .= ' admin crop';
			} else {
				$params['class'] = 'admin crop';
			}
			$params['class'] .= ' loadable cmsObject cmsImage';
			$params['src'] = site_url($urls['cmsimage']['show'].$this->id);
			$params['html'] .= '<span class="cmsTitle">'.$this->title.'</span>';
			$params['html'] .= '<a class="edit_button pop_triger" href="'.site_url($urls['cmsimage']['edit'].$this->id).'" title="Edit"><span class="ui-icon ui-icon-circle-plus"></span>edit</a>';
		}
		return showObject($params);		
		exit();
		
		if (!empty($this->path) && checkImg($this->path)) { // img validation
			$this->path = imgUrl($this->path);
		}
		if (empty($this->path)) {
			$this->path = '';
			$this->params['crop_width'] = intval($this->params['style']['width']);
			$this->params['crop_height'] = intval($this->params['style']['height']);
		} else {
			$this->scaleImage();
			$imgUrlParams = 'src='.$this->path;
			if (!empty($this->params['width']) && ($this->params['width'] !== '*')) {
				$imgUrlParams .= '&w='.$this->params['width'];
			}
			if (!empty($this->params['height']) && ($this->params['height'] !== '*')) {
				$imgUrlParams .= '&h='.$this->params['height'];
			}
		}
		$data = $this->params;
		if (!empty($this->path)) {
			$data['image']['url'] = resUrl('timthumb.php?'.$imgUrlParams);
		}

		$params['html'] = '<img src="" />';
		$urls = $this->_CI->config->item('routes');
		if ($this->_CI->loggedUser) {
			if (isset($params['class'])) {
				$params['class'] .= ' admin crop';
			} else {
				$params['class'] = 'admin crop';
			}
			$params['class'] .= ' loadable cmsObject';
			$params['src'] = site_url($urls['cmsobject']['show'].$this->id);
			$params['html'] .= '<span class="cmsTitle">'.$this->title.'</span>';
			$params['html'] .= '<a class="edit_button pop_triger" href="'.site_url($urls['cmsobject']['edit'].$this->id).'" title="Edit"><span class="ui-icon ui-icon-circle-plus"></span>edit</a>';
		}
		return showObject($params);
	}
	protected function scaleImage() {
		if (empty($this->params['originalWidth']) || empty($this->params['originalHeight'])) {
			$sizes = getimagesize($this->params['src']);
			$this->params['originalWidth'] = $sizes[0];
			$this->params['originalHeight'] = $sizes[1];
		}
		$w_ratio = 1;
		if (!empty($this->params['style']['width'])) {
			$w_ratio = $this->params['originalWidth'] / intval($this->params['style']['width']);
		} else {
			$w_ratio = FALSE;
		}
		$h_ratio = 1;
		if (!empty($this->params['style']['height'])) {
			$h_ratio = $this->params['originalHeight'] / intval($this->params['style']['height']);
		} else {
			$h_ratio = FALSE;
		}
		if ($w_ratio && $h_ratio) {
			if ($w_ratio > $h_ratio) { // crop width
				$this->params['crop_width'] = intval($this->params['style']['width']);
				$this->params['crop_height'] = intval($this->params['style']['height']);
				$this->params['width'] = '*';
				$this->params['height'] = intval($this->params['style']['height']);
			}
			if ($w_ratio < $h_ratio) { // crop height
				$this->params['crop_width'] = intval($this->params['style']['width']);
				$this->params['crop_height'] = intval($this->params['style']['height']);
				$this->params['height'] = '*';
				$this->params['width'] = intval($this->params['style']['width']);
			}
			if (abs($w_ratio - $h_ratio) < 0.01) { // no crop
				$this->params['width'] = intval($this->params['style']['width']);
				$this->params['height'] = intval($this->params['style']['height']);
			}
		} else {
			if ($w_ratio) {
				$this->params['height'] = '*';
			}
			if ($h_ratio) {
				$this->params['width'] = '*';
			}
		}
	}
	
	public function uploadImage() {
		$config = $this->_CI->config->item('image', 'upload');
		$this->_CI->load->library('upload', $config);
		if ( $this->_CI->upload->do_upload('filepath')) {
			return $this->_CI->upload->data();
		}
		return FALSE;
	}
	
	public function dialog($type = 'edit') {
		$urls = $this->_CI->config->item('routes');
		$data = array(
			'formAction' => site_url($urls['cmsimage']['save'].$this->id),
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'status' => $this->status,
			'type' => $this->type,
			'path' => $this->path
		);
		$this->_CI->load->view('loadImage', $data);
	}
	
	public function dump() {
		echo '<pre>'.print_r($this, TRUE).'</pre>';
	}
}

/* End of file CMSImage.php */