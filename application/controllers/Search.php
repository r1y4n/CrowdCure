<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
	public function index() {
		$this->load->helper('form');
        //$this->load->model('ppi','',TRUE);
		$this->load->view('search');
	}
	public function searchppi() {
		//$this->load->helper('form');
        $this->load->model('ppi','',TRUE);        
        $protein = $this->input->post("search");
        $data['protein'] = $protein;
        $data['ppis'] = $this->ppi->getInteractionsOf($protein);
        for( $i = 0; $i < count($data['ppis']); $i++ ) {
        	//echo $ppi['sv'];
        	$data['ppis'][$i]['rel'] = $this->calculateReliability( $data['ppis'][$i]['sv'] );
        	/*echo "<pre>";
			print_r( $ppi );
			echo "</pre>";*/
        }
        $data['ppisFact'] = $this->ppi->getInteractionsFactOf($protein);
		$this->load->view('searchResult', $data);
	}
	function calculateReliability( $sv ) {
		$this->load->model('source','',TRUE);
		$nos = $this->source->getNoOfSources();
		$rel = 1.00;
        for( $i = 1; $i <= $nos; $i++ ) {
            if( $sv[$i] == "1" ) {
                $perf = $this->source->getPerformance( $i );
                $rel = (float)$rel * ( (float)$perf['cp'] / (float)$perf['tcp'] );
            }            
        }
        return $rel * 100.00;
	}	
}
