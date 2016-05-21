<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
   		parent::__construct();
 	}
	public function index() {
		$data = array();
		if( $this->session->userdata( 'logged_in' ) ) {
            $session_data = $this->session->userdata( 'logged_in' );
            $data['id'] = $session_data['id'];
            $data['username'] = $session_data['username'];
            if( $session_data['usertype'] == 1 ) $data['usertype'] = "Expert";
            else $data['usertype'] = "Source"; 
        }
		$this->load->view('home', $data);
	}
	public function login() {
		$this->load->helper(array('form'));
		$this->load->view('login');
	}
	public function verifyLogin() {
		$this->load->model('user','',TRUE);
		$this->load->library('form_validation');
 
    	$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
   		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

   		if($this->form_validation->run() == FALSE) {  
   			$this->load->view('login');
   		}
    	else {
     		$session_data = $this->session->userdata( 'logged_in' );
     		if( $session_data['usertype'] == 1 ) {
     			redirect('verify', 'refresh');
     		}
     		else {
     			redirect('collect', 'refresh');
     		}
   		}
	}
	function check_database($password) {
		//Field validation succeeded.  Validate against database
		$username = $this->input->post('username');

		//query the database
		$result = $this->user->login($username, $password);

		if($result) {
			$session_array = array();
			foreach($result as $row) {
				$session_array = array(
					'id' => $row->id,
					'username' => $row->username,
					'usertype' => $row->user_type
				);
				$this->session->set_userdata('logged_in', $session_array);
			}
			return true;
		}
		else {
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
		}
	}
	public function logout() {
		if( $this->session->userdata( 'logged_in' ) ) {
			$this->session->unset_userdata( 'logged_in' );
			session_destroy();
			redirect( 'home' );
		}
        else {
            redirect( 'home' );
        }
	}
}
