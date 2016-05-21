<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Source extends CI_Model {
    function getNoOfSources() {
        $this -> db -> select('*');
        $this -> db -> from('sources');

        $query = $this -> db -> get();

        return $query -> num_rows();
    }
    function increaseNoOfPrediction( $sid ) {
        $this -> db -> select('total_pred');
        $this -> db -> from('sources');
        $this -> db -> where('id', $sid);
        $this -> db -> limit(1);
        $query = $this -> db -> get();

        $result = $query->result();
        foreach($result as $row) {
            $tp = $row->total_pred;
        }
        $tp++;

        $updateData = array(
            'total_pred' => $tp
        );
        $this->db->where( 'id', $sid );
        $this->db->update( 'sources', $updateData );
    }
    function addPositiveScore( $id ) {
        $this -> db -> select('total_cured_pred, correct_pred');
        $this -> db -> from('sources');
        $this -> db -> where('id', $id);
        $this -> db -> limit(1);
        $query = $this -> db -> get();

        $result = $query->result();
        foreach($result as $row) {
            $tcp = $row->total_cured_pred;
            $cp = $row->correct_pred;
        }
        $tcp++;
        $cp++;

        $updateData = array(
            'total_cured_pred' => $tcp,
            'correct_pred' => $cp
        );
        $this->db->where( 'id', $id );
        $this->db->update( 'sources', $updateData );
    }
    function getPerformance( $id ) {
        $this -> db -> select('total_cured_pred, correct_pred');
        $this -> db -> from('sources');
        $this -> db -> where('id', $id);
        $this -> db -> limit(1);
        $query = $this -> db -> get();

        $result = $query->result();
        foreach($result as $row) {
            $verdict = array(
                'tcp' => $row->total_cured_pred,
                'cp' => $row->correct_pred
            );
        }
        return $verdict;
    }
}
?>