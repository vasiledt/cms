<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item {
	protected $_CI;
	protected $obj = array();
	protected $params = array();

    public function __construct() {
		$this->_CI = & get_instance();
    }
	public function load($sourceArray) {
		$this->params = $sourceArray;
		if (!empty($this->params['style']) && is_string($this->params['style'])) { // parse style string and convert to array
			$this->params['style'] = parseStyle($this->params['style']);
		}
		if (!empty($this->params['id'])) {
			$this->obj = $this->searchObject($this->params['id']);
			if (empty($this->obj)) {
				$this->obj = $this->saveObject(array(
					'title' => $this->params['id'],
					'type' => array_search($this->params['type'], $this->_CI->config->item('obj_type'))
				));
			}
			if ($this->params['type'] == 'image') {
				$this->obj['image'] = $this->searchFile($this->obj['id'], 'objects');
				if (empty($this->obj['image'])) {
					$file = array(
						'item' => $this->obj['id'],
						'entity' => 'objects'
					);
					if (!empty($this->params['src'])) {
						$file['path'] = $this->params['src'];
					}
					$this->obj['image'] = $this->saveFile($file);
				}
			}
		}
	}
	public function show() {
		switch ($this->params['type']) {
			case 'image':
				return $this->showImage();
				break;
			default:
				return 'item processed!';
		}
	}
	
/* 
 -----------------------------------
|				IMAGES				|
 -----------------------------------
*/	
	protected function showImage() {
		$src = '';
		if (!empty($this->params['src']) && checkImg($this->params['src'])) { // img validation
			$this->params['src'] = imgUrl($this->params['src']);
		}
		if (empty($this->params['src'])) {
			$this->params['src'] = '';
			$this->params['crop_width'] = intval($this->params['style']['width']);
			$this->params['crop_height'] = intval($this->params['style']['height']);
		} else {
			$this->scaleImage();
			$imgUrlParams = 'src='.$this->params['src'];
			if (!empty($this->params['width']) && ($this->params['width'] !== '*')) {
				$imgUrlParams .= '&w='.$this->params['width'];
			}
			if (!empty($this->params['height']) && ($this->params['height'] !== '*')) {
				$imgUrlParams .= '&h='.$this->params['height'];
			}
		}
		$data = $this->params;
		$data['image'] = $this->obj['image'];
		if (!empty($this->params['src'])) {
			$data['image']['url'] = resUrl('timthumb.php?'.$imgUrlParams);
		}
		return $this->_CI->load->view('loadItem', $data, TRUE);
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

/* 
 -----------------------------------
|				OBJECTS				|
 -----------------------------------
*/	
	protected function searchObject($title) {
		$context = array(
			'table' => 'objects',
			'where' => array(
				'title' => $title
			),
			'limit' => array(
				'from' => 0,
				'count' => 1
			)
		);
		if ($rows = $this->_CI->entity_model->getItem($context, "*")) {
			return $rows[0];
		}
		return FALSE;
	}
	protected function saveObject($values) {
		$context = array(
			'table' => 'objects'
		);
		if (!empty($values['id'])) { // if id provided...update!
			$context['where'] = array(
				'id' => $values['id']
			);
			unset($values['id']);
		} else {
			if ($row = $this->searchObject($values['title'])) { // item with $values['title'] already exists...update!
				$context['where'] = array(
					'id' => $row[0]['id']
				);
				unset($values['title']);
			}
		}
		if (!empty($values)) {
			if ($id = $this->_CI->entity_model->saveItem($context, $values)) {
				$context['where'] = array(
					'id' => $id
				);
				if ($rows = $this->_CI->entity_model->getItem($context, '*')) {
					return $rows[0];
				}
			}
		}
		return FALSE;
	}
	
/* 
 -----------------------------------
|				FILES				|
 -----------------------------------
*/	
	protected function searchFile($item, $entity) {
		$context = array(
			'table' => 'files',
			'where' => array(
				'item' => $item,
				'entity' => $entity
			),
			'limit' => array(
				'from' => 0,
				'count' => 1
			)
		);
		if ($rows = $this->_CI->entity_model->getItem($context, "*")) {
			return $rows[0];
		}
		return FALSE;
	}
	public function saveFile($values) {
		$context = array(
			'table' => 'files'
		);
		if (!empty($values['id'])) { // if id provided...update!
			$context['where'] = array(
				'id' => $values['id']
			);
			unset($values['id']);
		} else {
			if ($row = $this->searchFile($values['item'], $values['entity'])) { // item with $values['item'] and $values['title'] already exists...update!
				$context['where'] = array(
					'id' => $row[0]['id']
				);
				unset($values['item']);
				unset($values['entity']);
			}
		}
		if (!empty($values)) {
			if ($id = $this->_CI->entity_model->saveItem($context, $values)) {
				$context['where'] = array(
					'id' => $id
				);
				if ($rows = $this->_CI->entity_model->getItem($context, '*')) {
					return $rows[0];
				}
			}
		}
		return FALSE;
	}
}

/* End of file Item.php */