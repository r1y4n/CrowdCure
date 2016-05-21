<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Ppi extends CI_Model {
    function checkInteraction( $p, $i, $o, $t ) {
        $this -> db -> select('source_vector');
        $this -> db -> from('interactions_predict');
        $this -> db -> where('protein', $p);
        $this -> db -> where('interactor', $i);
        $this -> db -> where('organism', $o);
        $this -> db -> where('type', $t);
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1) {
            $result = $query->result();
            foreach($result as $row) {
                $sv = $row->source_vector;
            }
            return $sv;
        }
        else {
            return false;
        }
    }
    function insertInteraction2Predict( $p, $i, $o, $t, $s ) {
        //echo $p . " " . $i . " " . $o . " " . $t . "<br>";
        $insertData = array(
            'id' => '',
            'protein' => $p,
            'interactor' => $i,
            'organism' => $o,
            'type' => $t,
            'source_vector' => $s
        );
        $this->db->insert( 'interactions_predict', $insertData );
    }
    function updateSV2Predict( $p, $i, $o, $t, $s ) {
        $updateData = array(
            'source_vector' => $s
        );
        $this->db->where( 'protein', $p );
        $this->db->where( 'interactor', $i );
        $this->db->where( 'organism', $o );
        $this->db->where( 'type', $t );
        $this->db->update( 'interactions_predict', $updateData );
    }
    function getAllInteractions() {
        $this -> db -> select('*');
        $this -> db -> from('interactions_predict');
        $query = $this -> db -> get();

        if( $query -> num_rows() > 0 ) {
            return $query->result();
        }
        else {
            return false;
        }
    }
    function verifyppi( $id ) {
        $this->db->select( '*' );
        $this->db->from( 'interactions_predict' );
        $this->db->where( 'id', $id );
        $query = $this->db->get();
        if( $query->num_rows() == 1 ) {
            $result = $query->result();
            $ppiData = array();
            foreach( $result as $row ) {
                $ppiData['id'] = $row->id;
                $ppiData['protein'] = $row->protein;
                $ppiData['interactor'] = $row->interactor;
                $ppiData['organism'] = $row->organism;
                $ppiData['type'] = $row->type;
                $ppiData['sv'] = $row->source_vector;                
            }
            $this->db->delete( 'interactions_predict', array( 'id' => $id ) ); 
            return $ppiData;
        }
        else {
            return false;
        }
    }
    function add2Fact( $ppiData ) {
        $insertData = array(
            'id' => '',
            'protein' => $ppiData['protein'],
            'interactor' => $ppiData['interactor'],
            'organism' => $ppiData['organism'],
            'type' => $ppiData['type'],
            'source_vector' => $ppiData['sv']
        );
        $this->db->insert( 'interactions_fact', $insertData );
    }
    function add2Archive( $ppiData ) {
        $insertData = array(
            'id' => '',
            'protein' => $ppiData['protein'],
            'interactor' => $ppiData['interactor'],
            'organism' => $ppiData['organism'],
            'type' => $ppiData['type'],
            'source_vector' => $ppiData['sv']
        );
        $this->db->insert( 'interactions_archive', $insertData );
    }
    function moveOthers2Archive( $p, $i, $o ) {
        $this -> db -> select('*');
        $this -> db -> from('interactions_predict');
        $this -> db -> where('protein', $p);
        $this -> db -> where('interactor', $i);
        $this -> db -> where('organism', $o);
        $query = $this -> db -> get();

        if($query -> num_rows() > 0) {
            $result = $query->result();
            foreach($result as $row) {
                $ppiData['id'] = $row->id;
                $ppiData['protein'] = $row->protein;
                $ppiData['interactor'] = $row->interactor;
                $ppiData['organism'] = $row->organism;
                $ppiData['type'] = $row->type;
                $ppiData['sv'] = $row->source_vector;
                $this->add2Archive( $ppiData );
                $this->db->delete( 'interactions_predict', array( 'id' => $row->id ) );
            }
            return true;
        }
        else {
            return false;
        }
    }
    function getInteractionsOf($protein) {
        //echo $protein;
        $this -> db -> select('*');
        $this -> db -> from('interactions_predict');
        $this -> db -> where('protein', $protein);
        $this -> db -> or_where('interactor', $protein);
        $query = $this -> db -> get();

        if( $query -> num_rows() > 0 ) {
            $result = $query->result();
            $ppiData = array();
            $i = 0;
            foreach( $result as $row ) {
                $ppiData[$i++] = array(
                    'id' => $row->id,
                    'protein' => $row->protein,
                    'interactor' => $row->interactor,
                    'organism' => $row->organism,
                    'type' => $row->type,
                    'sv' => $row->source_vector,
                    'rel' => ''
                );              
            }
            return $ppiData;
        }
        else {
            return false;
        }
    }
    function getInteractionsFactOf($protein) {
        $this -> db -> select('*');
        $this -> db -> from('interactions_fact');
        $this -> db -> where('protein', $protein);
        $this -> db -> or_where('interactor', $protein);
        $query = $this -> db -> get();

        if( $query -> num_rows() > 0 ) {
            $result = $query->result();
            $ppiData = array();
            $i = 0;
            foreach( $result as $row ) {
                $ppiData[$i++] = array(
                    'id' => $row->id,
                    'protein' => $row->protein,
                    'interactor' => $row->interactor,
                    'organism' => $row->organism,
                    'type' => $row->type,
                    'sv' => $row->source_vector
                );              
            }
            return $ppiData;
        }
        else {
            return false;
        }
    }
    function getAllLiteratures() {
        $this -> db -> select('*');
        $this -> db -> from('literatures');
        $query = $this -> db -> get();

        if( $query -> num_rows() > 0 ) {
            return $query->result();
        }
        else {
            return false;
        }
    }
}
?>