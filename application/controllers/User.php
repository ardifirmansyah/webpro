<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->library(array('session'));
    $this->load->helper(array('url'));
    $this->load->model('user_model');
  }

  public function index() {

  }

  public function register() {
    // create data object
    $data = new stdClass();

    // load form helper and validation library
    $this->load->helper('form');
    $this->load->library('form_validation');

    // set validation rules
    $this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[users.username]', array('is_unique' => 'This username already exists. Please choose another one.'));
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
    $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|min_length[6]|matches[password]');

    if ($this->form_validation->run() === false) {
      // validation not ok, send validation errors to the view
      $this->load->view('user/register/register', $data);
    } else {
      // set variables from the form
      $username = $this->input->post('username');
      $email    = $this->input->post('email');
      $password = $this->input->post('password');

      if ($this->user_model->create_user($username, $email, $password)) {
        // user creation ok
        $this->load->view('header');
        $this->load->view('user/register/register_success', $data);
        $this->load->view('footer');
      } else {
        // user creation failed, this should never happen
        $data->error = 'There was a problem creating your new account. Please try again.';

        // send error to the view
        $this->load->view('header');
        $this->load->view('user/register/register', $data);
        $this->load->view('footer');
      }
    }
  }

  public function login() {

  }
}
?>
