<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collect extends CI_Controller {
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
            $this->load->view('collect', $data);
        }
        else {
			redirect('login', 'refresh');
		}
	}
	public function browse() {
		$this->load->model('ppi','',TRUE);
		$data = array();
		if( $this->session->userdata( 'logged_in' ) ) {
            $session_data = $this->session->userdata( 'logged_in' );
            $data['id'] = $session_data['id'];
            $data['username'] = $session_data['username'];
            if( $session_data['usertype'] == 1 ) $data['usertype'] = "Expert";
            else $data['usertype'] = "Source";
            $data['lits'] = $this->ppi->getAllLiteratures();
            $this->load->view('browse', $data);
        }
        else {
			redirect('login', 'refresh');
		}
	}
	public function insert() {
		$this->load->helper('form');
		$data = array();
		if( $this->session->userdata( 'logged_in' ) ) {
            $session_data = $this->session->userdata( 'logged_in' );
            $data['id'] = $session_data['id'];
            $data['username'] = $session_data['username'];
            if( $session_data['usertype'] == 1 ) $data['usertype'] = "Expert";
            else $data['usertype'] = "Source";
            $this->load->view('insert', $data);
        }
        else {
			redirect('login', 'refresh');
		}
	}
	public function insertppi() {
		$this->load->model('user','',TRUE);
		$this->load->model('ppi','',TRUE);
		$this->load->model('source','',TRUE);
		$data = array();
		if( $this->session->userdata( 'logged_in' ) ) {
            $session_data = $this->session->userdata( 'logged_in' );
            $data['id'] = $session_data['id'];
            $data['username'] = $session_data['username'];
            if( $session_data['usertype'] == 1 ) $data['usertype'] = "Expert";
            else $data['usertype'] = "Source";
            
            $sourceID = $this->user->getSourceID( $data['id'] );

            for( $i = 1; $i <= 5; $i++ ) {
            	$pro = $this->input->post('protein'.$i);
            	$int = $this->input->post('interactor'.$i);
            	$org = $this->input->post('organism'.$i);
            	$typ = $this->input->post('type'.$i);
            	//echo $pro . " " . $int . " " . $org . " " . $typ . "<br>";
            	if( $pro == "" || $int == "" || $org == "" || $typ == "" ) break;
            	//$pID = $this->ppi->insertProtein( $pro, $org );
            	//$iID = $this->ppi->insertProtein( $int, $org );
				$sv = $this->ppi->checkInteraction( $pro, $int, $org, $typ );
				$nos = $this->source->getNoOfSources();
				if( $sv == false ) {					
					$sv = $this->makeSourceVector( $nos, $sourceID );
					//echo $pro . " " . $int . " " . $org . " " . $typ . "<br>";
					$this->ppi->insertInteraction2Predict( $pro, $int, $org, $typ, $sv );
				}
				else {
					$sv[$sourceID] = "1";
					$this->ppi->updateSV2Predict( $pro, $int, $org, $typ, $sv );					
				}
				$this->source->increaseNoOfPrediction( $sourceID );
            }
            $data['nos'] = $nos;
            $data['sID'] = $sourceID;
            $data['postData'] = $this->input->post(NULL, true);
            $this->load->view('insertSuccess', $data);
        }
        else {
			redirect('login', 'refresh');
		}
	}
	function makeSourceVector( $nos, $sid ) {
		$sv = "0";
		for( $i = 1; $i <= $nos; $i++ ) {
			if( $i == $sid ) $sv = $sv . "1";
			else $sv = $sv . "0";
		}
		return $sv;
	}	
}
