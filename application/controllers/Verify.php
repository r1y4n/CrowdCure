<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verify extends CI_Controller {
	public function index() {
        $this->load->helper('form');
        $this->load->model('ppi','',TRUE);
		$data = array();
		if( $this->session->userdata( 'logged_in' ) ) {
            $session_data = $this->session->userdata( 'logged_in' );
            $data['id'] = $session_data['id'];
            $data['username'] = $session_data['username'];
            if( $session_data['usertype'] == 1 ) $data['usertype'] = "Expert";
            else $data['usertype'] = "Source";
            $data['ppis'] = $this->ppi->getAllInteractions();
            $this->load->view('verify', $data);
        }
		else {
            redirect('login', 'refresh');
        }
	}
    public function verifyppi() {
        $this->load->model('ppi','',TRUE);
        $this->load->model('source','',TRUE);
        $data = array();
        if( $this->session->userdata( 'logged_in' ) ) {
            //echo "<pre>"; print_r( $this->input->post() ); echo "</pre>";
            $corrected = $this->input->post('correct');
            foreach( $corrected as $crct ) {
                $ppiData = $this->ppi->verifyppi( $crct );
                $sv = $ppiData['sv'];
                $nos = $this->source->getNoOfSources();
                $nsv = "1";
                for( $i = 1; $i <= $nos; $i++ ) {
                    if( $sv[$i] == "1" ) {
                        $this->source->addPositiveScore( $i );
                    }
                    $nsv = $nsv . "0";
                }
                $this->ppi->add2Archive( $ppiData );
                $ppiData['sv'] = $nsv;
                $this->ppi->add2Fact( $ppiData );
                $this->ppi->moveOthers2Archive( $ppiData['protein'], $ppiData['interactor'], $ppiData['organism'] );
            }
            $this->load->view('verifySuccess', $data);
        }
        else {
            redirect('login', 'refresh');
        }
    }	
}
