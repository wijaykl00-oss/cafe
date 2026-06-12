<?php

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model', 'auth_model');
    }

    public function index()
    {
        redirect('auth/login');
    }

    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->load->view('auth/v_login');
    }

    public function proses_login()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');

        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Username dan password wajib diisi!');
            redirect('auth/login');
        }

        $user = $this->auth_model->get_user($username);

        if ($user && password_verify($password, $user['password'])) {
            $this->session->set_userdata([
                'logged_in' => TRUE,
                'user_id'   => $user['id'],
                'nama'      => $user['nama'],
                'username'  => $user['username'],
                'role'      => $user['role'],
            ]);
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah!');
            redirect('auth/login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}