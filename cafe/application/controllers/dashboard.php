<?php

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $today = date('Y-m-d');
        $bulan = date('Y-m');

        // Total penjualan hari ini
        $q1 = $this->db->query(
            "SELECT SUM(total) as total FROM transaksi 
             WHERE DATE(created_at) = ? AND status = 'selesai'",
            [$today]
        );
        $data['penjualan_hari_ini'] = $q1->row()->total ?? 0;

        // Total penjualan bulan ini
        $q2 = $this->db->query(
            "SELECT SUM(total) as total FROM transaksi 
             WHERE DATE_FORMAT(created_at, '%Y-%m') = ? AND status = 'selesai'",
            [$bulan]
        );
        $data['penjualan_bulan_ini'] = $q2->row()->total ?? 0;

        // Jumlah transaksi hari ini
        $q3 = $this->db->query(
            "SELECT COUNT(*) as jml FROM transaksi 
             WHERE DATE(created_at) = ? AND status = 'selesai'",
            [$today]
        );
        $data['jumlah_transaksi'] = $q3->row()->jml ?? 0;

        // Total menu aktif
        $q4 = $this->db->query("SELECT COUNT(*) as jml FROM menu WHERE is_active = 1");
        $data['total_menu'] = $q4->row()->jml ?? 0;

        // Grafik 7 hari terakhir
        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('d/m', strtotime($tgl));
            $q = $this->db->query(
                "SELECT SUM(total) as total FROM transaksi 
                 WHERE DATE(created_at) = ? AND status = 'selesai'",
                [$tgl]
            );
            $values[] = (float)($q->row()->total ?? 0);
        }
        $data['label_grafik'] = $labels;
        $data['data_grafik']  = $values;

        // Pie chart per kategori bulan ini
        $kategoris   = ['Makanan', 'Minuman', 'Snack'];
        $kat_values  = [];
        foreach ($kategoris as $kat) {
            $q = $this->db->query(
                "SELECT SUM(td.subtotal) as total 
                 FROM transaksi_detail td
                 JOIN menu m ON m.id = td.menu_id
                 JOIN transaksi t ON t.id = td.transaksi_id
                 WHERE m.kategori = ? 
                   AND DATE_FORMAT(t.created_at, '%Y-%m') = ?
                   AND t.status = 'selesai'",
                [$kat, $bulan]
            );
            $kat_values[] = (float)($q->row()->total ?? 0);
        }
        $data['data_kategori'] = $kat_values;

        // Transaksi terbaru
        $q5 = $this->db->query(
            "SELECT t.*, u.nama FROM transaksi t
             JOIN users u ON u.id = t.user_id
             ORDER BY t.created_at DESC LIMIT 10"
        );
        $data['transaksi_terbaru'] = $q5->result_array();

        $this->load->view('template/head');
        $this->load->view('template/sidebar');
        $this->load->view('template/content', $data);
        $this->load->view('template/footer');
    }
}