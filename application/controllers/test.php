<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once('admin.php');

class Test extends Admin {
	public $loggedUser = FALSE;
	protected $data = '';
	
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'html', 'array'));
	}

	public function test1() {
		if ($this->_init()) {
		}
		$this->load->helper('string');
		$text = 'lorem ipsum et dolor <item id="test1" title="test_title" type="image" src="weak-1600x1200.jpg" style="width: 200px; height: 100px;" class="test_class">alt for test</item> sic amet. Si ALTA DATA si-alta data <item type="text" title="test2" src="source2">lorem <strong>ipsum</strong> et dolor</item> o sa o facem si mai si mai lata!';
		$text .= '<br />Cate-un pic pic pic...cate-un strop,<item id="test3" type="image" style="width: 300px; height: 300px;" class="class_test_3">alt for test 3</item> strop strop...pana n-o mai ramanea deloc!';
		$this->data['test'] = processItems($text);
		$this->load->view('front', $this->data);
	}
	
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */