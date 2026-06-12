<?php

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['menu_list'] = $this->Menu_model->get_all();
        $this->load->view('template/head');
        $this->load->view('template/sidebar');
        $this->load->view('admin/v_menu', $data);
        $this->load->view('template/footer');
    }

    public function tambah()
    {
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('menu');
        }

        if (empty($this->input->post('nama'))) {
            $this->session->set_flashdata('error', 'Nama menu wajib diisi!');
            redirect('menu');
        }

        $nama_gambar = null;

        if (!empty($_FILES['gambar']['name'])) {
            $config_upload = [
                'upload_path'   => FCPATH . 'uploads/',
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size'      => 2048,
                'file_name'     => 'menu_' . time() . '_' . rand(100, 999),
            ];
            $this->load->library('upload', $config_upload);

            if (!$this->upload->do_upload('gambar')) {
                $this->session->set_flashdata('error', 'Gagal upload: ' . $this->upload->display_errors('', ''));
                redirect('menu');
            }
            $nama_gambar = $this->upload->data('file_name');
        }

        $this->Menu_model->insert([
            'nama'      => $this->input->post('nama', TRUE),
            'kategori'  => $this->input->post('kategori', TRUE),
            'harga'     => $this->input->post('harga'),
            'gambar'    => $nama_gambar,
            'is_active' => 1,
        ]);

        $this->session->set_flashdata('success', 'Menu berhasil ditambahkan!');
        redirect('menu');
    }

    public function edit($id)
    {
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('menu');
        }
        $data['menu'] = $this->Menu_model->get_by_id($id);
        if (!$data['menu']) show_404();

        $this->load->view('template/head');
        $this->load->view('template/sidebar');
        $this->load->view('admin/v_menuedit', $data);
        $this->load->view('template/footer');
    }

    public function update($id)
    {
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('menu');
        }

        $menu_lama   = $this->Menu_model->get_by_id($id);
        $nama_gambar = $menu_lama['gambar'];

        if (!empty($_FILES['gambar']['name'])) {
            $config_upload = [
                'upload_path'   => FCPATH . 'uploads/',
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size'      => 2048,
                'file_name'     => 'menu_' . time() . '_' . rand(100, 999),
            ];
            $this->load->library('upload', $config_upload);

            if ($this->upload->do_upload('gambar')) {
                if ($menu_lama['gambar'] && file_exists(FCPATH . 'uploads/' . $menu_lama['gambar'])) {
                    unlink(FCPATH . 'uploads/' . $menu_lama['gambar']);
                }
                $nama_gambar = $this->upload->data('file_name');
            }
        }

        $this->Menu_model->update($id, [
            'nama'      => $this->input->post('nama', TRUE),
            'kategori'  => $this->input->post('kategori', TRUE),
            'harga'     => $this->input->post('harga'),
            'gambar'    => $nama_gambar,
            'is_active' => $this->input->post('is_active') ? 1 : 0,
        ]);

        $this->session->set_flashdata('success', 'Menu berhasil diperbarui!');
        redirect('menu');
    }

    // ✅ Hapus menu — cek dulu apakah menu punya riwayat transaksi
    public function hapus($id)
    {
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('menu');
        }

        // Cek apakah menu masih dipakai di transaksi_detail
        $dipakai = $this->db->where('menu_id', $id)->count_all_results('transaksi_detail');

        if ($dipakai > 0) {
            // Solusi: set menu_id jadi NULL di transaksi_detail (data historis tetap aman)
            // lalu hapus menu
            $this->db->where('menu_id', $id)->update('transaksi_detail', ['menu_id' => NULL]);
        }

        // Hapus file gambar jika ada
        $menu = $this->Menu_model->get_by_id($id);
        if ($menu && !empty($menu['gambar']) && file_exists(FCPATH . 'uploads/' . $menu['gambar'])) {
            unlink(FCPATH . 'uploads/' . $menu['gambar']);
        }

        $this->Menu_model->delete($id);
        $this->session->set_flashdata('success', 'Menu berhasil dihapus!');
        redirect('menu');
    }
}