<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_callback extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
    }

    public function index()
    {
        // Ambil input JSON dari Midtrans
        $json_str = file_get_contents('php://input');
        $notification = json_decode($json_str, true);

        if (empty($notification)) {
            $this->output->set_status_header(400)->set_output('Invalid payload');
            return;
        }

        // Ambil data penting dari notifikasi
        $order_id     = $notification['order_id'];
        $status_code  = $notification['status_code'];
        $gross_amount = $notification['gross_amount'];
        $signature    = $notification['signature_key'];

        // Load config Midtrans untuk validasi signature
        $this->config->load('midtrans', TRUE);
        $server_key = $this->config->item('server_key', 'midtrans');

        // Validasi signature key demi keamanan
        // Signature = SHA512(order_id + status_code + gross_amount + server_key)
        $local_signature = hash("sha512", $order_id . $status_code . $gross_amount . $server_key);

        if ($signature !== $local_signature) {
            $this->output->set_status_header(403)->set_output('Invalid signature key');
            return;
        }

        // Ambil transaksi lokal berdasarkan kode_transaksi (order_id)
        $trx = $this->Transaksi_model->get_by_kode($order_id);
        if (!$trx) {
            $this->output->set_status_header(404)->set_output('Transaction not found');
            return;
        }

        $transaction_status = $notification['transaction_status'];
        $payment_type       = $notification['payment_type'];

        if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
            // Pembayaran sukses
            $this->Transaksi_model->update_bayar($trx['id'], [
                'bayar'        => $trx['total'],
                'kembalian'    => 0,
                'metode_bayar' => $payment_type, // Simpan tipe spesifik (misal: qris)
                'status'       => 'selesai'
            ]);
            $this->output->set_status_header(200)->set_output('Payment settled successfully');
        } else if ($transaction_status == 'deny' || $transaction_status == 'expire' || $transaction_status == 'cancel') {
            // Pembayaran gagal / kedaluwarsa / dibatalkan
            $this->Transaksi_model->update_bayar($trx['id'], [
                'status' => 'batal'
            ]);
            $this->output->set_status_header(200)->set_output('Payment cancelled/expired');
        } else {
            // Status lainnya (misal pending)
            $this->output->set_status_header(200)->set_output('Payment pending');
        }
    }
}
