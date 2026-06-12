<?php

class Transaksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');
        $this->load->model('Transaksi_model');
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['transaksi_list'] = $this->Transaksi_model->get_all();
        $this->load->view('template/head');
        $this->load->view('template/sidebar');
        $this->load->view('admin/v_transaksi', $data);
        $this->load->view('template/footer');
    }

    public function buat()
    {
        $data['menu_list'] = $this->Menu_model->get_aktif();
        $data['struk']     = $this->session->flashdata('struk');

        $this->load->view('template/head');
        $this->load->view('template/sidebar');
        $this->load->view('admin/v_kasir', $data);
        $this->load->view('template/footer');
    }

    public function simpan()
    {
        $metode     = $this->input->post('metode_bayar');
        $bayar      = (float)$this->input->post('bayar');
        $items_json = $this->input->post('items_json');
        $status     = $this->input->post('status') === 'pending' ? 'pending' : 'selesai';

        if (empty($items_json)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu menu!');
            redirect('transaksi/buat');
        }

        $items = json_decode($items_json, true);
        if (empty($items)) {
            $this->session->set_flashdata('error', 'Data pesanan tidak valid!');
            redirect('transaksi/buat');
        }

        $total  = 0;
        $detail = [];

        foreach ($items as $item) {
            $menu = $this->Menu_model->get_by_id($item['menu_id']);
            if ($menu) {
                $qty      = (int)$item['qty'];
                $subtotal = $menu['harga'] * $qty;
                $total   += $subtotal;
                $detail[] = [
                    'menu_id'   => $menu['id'],
                    'nama_menu' => $menu['nama'],
                    'harga'     => $menu['harga'],
                    'qty'       => $qty,
                    'subtotal'  => $subtotal,
                ];
            }
        }

        if ($status === 'pending') {
            $bayar     = 0;
            $kembalian = 0;
        } else {
            if ($bayar < $total) {
                $this->session->set_flashdata('error', 'Jumlah bayar kurang dari total!');
                redirect('transaksi/buat');
            }
            $kembalian = $bayar - $total;
        }

        $kode   = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        $trx_id = $this->Transaksi_model->insert([
            'kode_transaksi' => $kode,
            'user_id'        => $this->session->userdata('user_id'),
            'total'          => $total,
            'bayar'          => $bayar,
            'kembalian'      => $kembalian,
            'metode_bayar'   => $metode,
            'status'         => $status,
        ]);

        foreach ($detail as &$d) {
            $d['transaksi_id'] = $trx_id;
        }
        $this->Transaksi_model->insert_detail($detail);

        if ($status === 'selesai') {
            $this->session->set_flashdata('struk', [
                'kode'      => $kode,
                'total'     => $total,
                'bayar'     => $bayar,
                'kembalian' => $kembalian,
                'metode'    => $metode,
                'detail'    => $detail,
                'kasir'     => $this->session->userdata('nama'),
                'waktu'     => date('d/m/Y H:i'),
            ]);
        } else {
            $this->session->set_flashdata('success', 'Pesanan pending disimpan! Kode: ' . $kode);
        }

        redirect('transaksi/buat');
    }

    public function detail($id)
    {
        $data['transaksi'] = $this->Transaksi_model->get_by_id($id);
        if (!$data['transaksi']) show_404();
        $data['detail'] = $this->Transaksi_model->get_detail($id);

        $this->load->view('template/head');
        $this->load->view('template/sidebar');
        $this->load->view('admin/v_transaksidetail', $data);
        $this->load->view('template/footer');
    }

    public function bayar($id)
    {
        $transaksi = $this->Transaksi_model->get_by_id($id);
        if (!$transaksi || $transaksi['status'] !== 'pending') {
            $this->session->set_flashdata('error', 'Transaksi tidak valid.');
            redirect('transaksi');
        }

        $bayar  = (float)$this->input->post('bayar');
        $metode = $this->input->post('metode_bayar');
        $total  = (float)$transaksi['total'];

        if ($bayar < $total) {
            $this->session->set_flashdata('error', 'Jumlah bayar kurang dari total!');
            redirect('transaksi');
        }

        $this->Transaksi_model->update_bayar($id, [
            'bayar'        => $bayar,
            'kembalian'    => $bayar - $total,
            'metode_bayar' => $metode,
            'status'       => 'selesai',
        ]);

        $this->session->set_flashdata('success', 'Transaksi ' . $transaksi['kode_transaksi'] . ' berhasil dibayar!');
        redirect('transaksi');
    }

    public function hapus($id)
    {
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('transaksi');
        }
        $this->Transaksi_model->delete_detail($id);
        $this->Transaksi_model->delete($id);
        $this->session->set_flashdata('success', 'Transaksi berhasil dihapus.');
        redirect('transaksi');
    }

    public function buat_qris()
    {
        $items_json = $this->input->post('items_json');

        if (empty($items_json)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Pilih minimal satu menu!']));
            return;
        }

        $items = json_decode($items_json, true);
        if (empty($items)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Data pesanan tidak valid!']));
            return;
        }

        $total  = 0;
        $detail = [];
        $item_details = [];

        foreach ($items as $item) {
            $menu = $this->Menu_model->get_by_id($item['menu_id']);
            if ($menu) {
                $qty      = (int)$item['qty'];
                $subtotal = $menu['harga'] * $qty;
                $total   += $subtotal;
                $detail[] = [
                    'menu_id'   => $menu['id'],
                    'nama_menu' => $menu['nama'],
                    'harga'     => $menu['harga'],
                    'qty'       => $qty,
                    'subtotal'  => $subtotal,
                ];

                // Item breakdown for Midtrans
                $item_details[] = [
                    'id'       => $menu['id'],
                    'price'    => (int)$menu['harga'],
                    'quantity' => $qty,
                    'name'     => substr($menu['nama'], 0, 50)
                ];
            }
        }

        if ($total <= 0) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Total belanja tidak valid!']));
            return;
        }

        $kode   = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        
        // Simpan transaksi di database sebagai pending
        $trx_id = $this->Transaksi_model->insert([
            'kode_transaksi' => $kode,
            'user_id'        => $this->session->userdata('user_id'),
            'total'          => $total,
            'bayar'          => 0,
            'kembalian'      => 0,
            'metode_bayar'   => 'qris',
            'status'         => 'pending',
        ]);

        foreach ($detail as &$d) {
            $d['transaksi_id'] = $trx_id;
        }
        $this->Transaksi_model->insert_detail($detail);

        // Load Midtrans library
        $this->load->library('midtrans');
        
        // Charge QRIS via Midtrans API
        $response = $this->midtrans->charge_qris($kode, $total, $item_details);

        if (isset($response['status_code']) && ($response['status_code'] == '201' || $response['status_code'] == '200')) {
            $qr_url = '';
            if (isset($response['actions'])) {
                foreach ($response['actions'] as $action) {
                    if ($action['name'] === 'generate-qr-code') {
                        $qr_url = $action['url'];
                        break;
                    }
                }
            }

            if (!empty($qr_url)) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode([
                                 'success' => true,
                                 'qr_url' => $qr_url,
                                 'kode_transaksi' => $kode,
                                 'total' => $total,
                                 'id' => $trx_id
                             ]));
            } else {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode([
                                 'success' => false,
                                 'message' => 'Gagal mendapatkan QR Code dari Midtrans.'
                             ]));
            }
        } else {
            $err_msg = isset($response['status_message']) ? $response['status_message'] : 'Gagal membuat transaksi QRIS.';
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => false,
                             'message' => 'Midtrans Error: ' . $err_msg
                         ]));
        }
    }

    public function cek_status_pembayaran($kode)
    {
        $trx = $this->Transaksi_model->get_by_kode($kode);
        if (!$trx) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'status' => 'tidak_ditemukan']));
            return;
        }

        $detail = $this->Transaksi_model->get_detail($trx['id']);

        if ($trx['status'] === 'selesai') {
            $this->set_struk_session($trx, $detail);

            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'status' => 'selesai']));
            return;
        }

        // Jika status masih pending, kita query ke Midtrans langsung (polling fallback untuk localhost)
        $this->load->library('midtrans');
        $response = $this->midtrans->get_status($kode);

        if (isset($response['transaction_status'])) {
            $status = $response['transaction_status'];
            
            if ($status === 'settlement' || $status === 'capture') {
                // Update local status ke selesai
                $this->Transaksi_model->update_bayar($trx['id'], [
                    'bayar'     => $trx['total'],
                    'kembalian' => 0,
                    'status'    => 'selesai'
                ]);

                // Update data trx lokal
                $trx['status'] = 'selesai';
                $trx['bayar'] = $trx['total'];
                $trx['kembalian'] = 0;

                $this->set_struk_session($trx, $detail);

                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => true, 'status' => 'selesai']));
                return;
            } else if ($status === 'expire' || $status === 'cancel' || $status === 'deny') {
                $this->Transaksi_model->update_bayar($trx['id'], [
                    'status' => 'batal'
                ]);
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => true, 'status' => 'batal']));
                return;
            }
        }

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => true, 'status' => $trx['status']]));
    }

    public function konfirmasi_manual_qris($kode)
    {
        $trx = $this->Transaksi_model->get_by_kode($kode);
        if (!$trx || $trx['status'] !== 'pending') {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Transaksi tidak valid.']));
            return;
        }

        $detail = $this->Transaksi_model->get_detail($trx['id']);

        // Update local status ke selesai
        $this->Transaksi_model->update_bayar($trx['id'], [
            'bayar'     => $trx['total'],
            'kembalian' => 0,
            'status'    => 'selesai'
        ]);

        $trx['status'] = 'selesai';
        $trx['bayar'] = $trx['total'];
        $trx['kembalian'] = 0;

        $this->set_struk_session($trx, $detail);

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => true]));
    }

    private function set_struk_session($trx, $detail)
    {
        if (!$this->session->flashdata('struk')) {
            $this->session->set_flashdata('struk', [
                'kode'      => $trx['kode_transaksi'],
                'total'     => $trx['total'],
                'bayar'     => $trx['bayar'],
                'kembalian' => $trx['kembalian'],
                'metode'    => $trx['metode_bayar'],
                'detail'    => $detail,
                'kasir'     => $trx['kasir'],
                'waktu'     => date('d/m/Y H:i', strtotime($trx['created_at'])),
            ]);
        }
    }
}