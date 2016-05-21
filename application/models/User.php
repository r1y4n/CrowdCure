<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class User extends CI_Model {
    function login( $username, $password ) {
        $this -> db -> select('id, username, password, user_type');
        $this -> db -> from('credentials');
        $this -> db -> where('username', $username);
        $this -> db -> where('password', SHA1($password));
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1) {
            return $query->result();
        }
        else {
            return false;
        }
    }
    function getSourceID( $userID ) {
        $this -> db -> select('id');
        $this -> db -> from('sources');
        $this -> db -> where('user_id', $userID);
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1) {
            $result = $query->result();
            foreach($result as $row) {
                $sID = $row->id;
            }
            return $sID;
        }
        else {
            return false;
        }
    }
}
?>