<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

  public function save(){
    $data = [
      'email' => $this->input->post('email'),
      'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
    ];

    if ($this->db->insert('users', $data)){
      return [
        'id' => $this->db->insert_id(),
        'success' => true,
        'message' => 'Data successfully insert',
      ];
    }
  }
}
