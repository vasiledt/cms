<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class checkLogin {
	protected $user = 'admin';
	protected $pass = '123456';
	protected $rights = 2;

	public function checkLogin() {
		$_CI = & get_instance();
		$_CI->load->helper('url');
	}
	
	public function check() {
		$_CI = & get_instance();
		$session_id = $_CI->session->userdata('id');
		if (empty($session_id)) { // user not logged in, free session and continue
			// $_CI->session->sess_destroy();
		} else { // user logged in, exit
			$_CI->loggedUser = $_CI->session->all_userdata();
			$this->checkPageAccessRights();
			return TRUE;
		}
		
		$username = $_CI->input->post('username', FALSE);
		$password = $_CI->input->post('password', FALSE);
		if (!empty($username) && !empty($password)) { // from login form
			if ( (strtolower($username) == $this->user) && (strtolower($password) == $this->pass) ) {
				$_CI->loggedUser = 'admin';
				$_CI->session->set_userdata(array(
					'id' => 'admin',
					'id_company' => 'all',
					'name' => $username,
					'password' => $password,
					'role' => $this->rights
				));
				redirect(site_url());
			} else {
				$context = array(
					'table' => 'users',
					'where' => array(
						'title' => strtolower($username),
						'password' => strtolower($password),
						'status' => 1
					)
				);
				if ($rows = $_CI->entity_model->getItem($context, 'id,id_company,rights')) { // normal user
					$_CI->loggedUser = 'user';
					$_CI->session->set_userdata(array(
						'id' => $rows[0]['id'],
						'id_company' => $rows[0]['id_company'],
						'name' => $username,
						'password' => $password,
						'role' => $rows[0]['rights']
					));
					redirect(site_url());
				}
				if ($submit = $_CI->input->post('submit', FALSE)) {
					redirect(site_url());
				} else {
					return FALSE;
				}
			}
		}
	}
	
	protected function checkPageAccessRights() {
		$_CI = & get_instance();
		$_CI->load->helper('system');
		if (checkAccessRights($_CI->loggedUser['role']) === FALSE) {
			redirect(site_url());
		}
	}
}

/* End of file checkLogin.php */
/* Location: ./application/hooks/checkLogin.php */