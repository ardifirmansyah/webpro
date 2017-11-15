<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

  // constructor for initializing CI Model
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  /**
  * function for create user
  * using username, email and password as parameter
  */
  public function create_user($username, $email, $password) {
    $data = array(
      'username'     => $username,
      'email'        => $email,
      'password'     => $this->hash_password($password),
			'created_at'   => date('Y-m-j H:i:s'),
    );

    return $this->db->insert('users', $data);
  }

  /**
  * function for resolve user login
  * using username and password input
  */
  public function resolve_user_login($username, $password) {
    $this->db->select('password');
    $this->db->from('users');
    $this->db->where('username', $username);
    $hash = $this->db->get()->row('password');

    return $this->verify_password_hash($password, $hash);
  }

  /**
  * function for get user id by username
  */
  public function get_user_id_from_username($username) {
    $this->db->select('id');
    $this->db->from('users');
    $this->db->where('username', $username);

    return $this->db->get()->row('id');
  }

  /**
  * function for get user data by user id
  */
  public function get_user($user_id) {
    $this->db->from('users');
    $this->db->where('id', $user_id);

    return $this->db->get()->row();
  }

  /**
  * function for password hashing
  */
  private function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT);
  }

  /**
  * function for verify hashed password
  */
  private function verify_password_hash($password, $hash) {
    return password_verify($password, $hash);
  }
}
