<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class a_controller extends CI_Controller {
	public $loggedUser = FALSE;
	protected $data = '';
	
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		if ($this->_init()) {
			// $this->data['viewToLoad']['submenu'] = 'submenu';
		}
		$this->load->view('front', $this->data);
	}

	public function loadItem() {
	}
	
	public function saveItem() {
	}
	
	public function listItems() {
	}
	
	public function showItem() {
	}
	
	public function logout() {
		$this->session->sess_destroy();
		redirect(site_url());
	}
	
	protected function _init() {
		if (!$this->loggedUser) { // not logged, show login form and exit!
			$this->data['viewToLoad'] = array(
				'login'
			);
			return FALSE;
		}
		// logged in, safe to continue
		$this->data['viewToLoad'] = array();
		$this->data['extraCss'] = array(
			cssUrl('jquery/jquery-ui-1.8.21.custom.css'),
			cssUrl('jquery/ui.jqgrid.css')
		);
		$this->data['extraJs'] = array(
			jsUrl('jquery/grid.locale-en.js'),
			jsUrl('grid.js'),
			jsUrl('jquery/jquery.jqGrid.min.js'),
			jsUrl('jquery/jquery-ui-1.8.21.custom.min.js')
		);

		return TRUE;
	}
	
	protected function _paginate($context, $count = '') {
		if (empty($count)) { // update, let custom $count if needed
			$count = 'count(1) as count';
		}
		
		// count all records
		$row = $this->entity_model->getItem($context, $count);
		$total_count = $row[0]['count'];
		$limit = $this->input->post('rows');
		$from = $this->input->post('page');
		if ( ( $total_count > 0 ) && $limit ) {
			$pages = ceil($total_count / $limit);
		} else {
			$pages = 1;
		}	
		if ( $from > $pages ) {
			$from = $pages;
		}
		
		// prepare response
		$response = new StdClass;
		$response->page = $from;
		$response->total = $pages;
		$response->records = $total_count;
		$response->limit = $limit;
		
		return $response;
	}	
}

/* End of file admin.php */
/* Location: ./application/controllers/abstract_controller.php */