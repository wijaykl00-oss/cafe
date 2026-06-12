<?php

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $dari   = $this->input->get('dari')   ?? date('Y-m-01');
        $sampai = $this->input->get('sampai') ?? date('Y-m-d');

        // Ringkasan
        $q1 = $this->db->query(
            "SELECT COUNT(*) as total_transaksi, SUM(total) as total_pendapatan, AVG(total) as rata_rata
             FROM transaksi
             WHERE DATE(created_at) BETWEEN ? AND ? AND status = 'selesai'",
            [$dari, $sampai]
        );
        $data['ringkasan'] = $q1->row();

        // Per hari
        $q2 = $this->db->query(
            "SELECT DATE(created_at) as tgl, COUNT(*) as jumlah, SUM(total) as total
             FROM transaksi
             WHERE DATE(created_at) BETWEEN ? AND ? AND status = 'selesai'
             GROUP BY DATE(created_at) ORDER BY tgl ASC",
            [$dari, $sampai]
        );
        $data['per_hari'] = $q2->result_array();

        // Per metode
        $q3 = $this->db->query(
            "SELECT metode_bayar, COUNT(*) as jumlah, SUM(total) as total
             FROM transaksi
             WHERE DATE(created_at) BETWEEN ? AND ? AND status = 'selesai'
             GROUP BY metode_bayar",
            [$dari, $sampai]
        );
        $data['per_metode'] = $q3->result_array();

        // Menu terlaris - JOIN ke tabel menu untuk ambil kategori
        $q4 = $this->db->query(
            "SELECT td.nama_menu, m.kategori, SUM(td.qty) as total_qty, SUM(td.subtotal) as total_pendapatan
             FROM transaksi_detail td
             JOIN transaksi t ON t.id = td.transaksi_id
             JOIN menu m ON m.id = td.menu_id
             WHERE DATE(t.created_at) BETWEEN ? AND ? AND t.status = 'selesai'
             GROUP BY td.menu_id, td.nama_menu, m.kategori
             ORDER BY total_qty DESC
             LIMIT 10",
            [$dari, $sampai]
        );
        $data['menu_terlaris'] = $q4->result_array();

        // Data grafik
        $labels_grafik = [];
        $nilai_grafik  = [];
        foreach ($data['per_hari'] as $row) {
            $labels_grafik[] = date('d/m', strtotime($row['tgl']));
            $nilai_grafik[]  = (float)$row['total'];
        }
        $data['labels_grafik'] = $labels_grafik;
        $data['nilai_grafik']  = $nilai_grafik;
        $data['dari']   = $dari;
        $data['sampai'] = $sampai;

        $this->load->view('template/head');
        $this->load->view('template/sidebar');
        $this->load->view('admin/v_laporan', $data);
        $this->load->view('template/footer');
    }
}