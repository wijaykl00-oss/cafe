<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Midtrans {
    protected $CI;
    protected $server_key;
    protected $is_production;
    protected $api_url;

    public function __construct() {
        $this->CI =& get_instance();
        // Load the config file 'midtrans.php'
        $this->CI->config->load('midtrans', TRUE);
        $this->server_key = $this->CI->config->item('server_key', 'midtrans');
        $this->is_production = $this->CI->config->item('is_production', 'midtrans');
        
        if ($this->is_production) {
            $this->api_url = 'https://api.midtrans.com/v2';
        } else {
            $this->api_url = 'https://api.sandbox.midtrans.com/v2';
        }
    }

    /**
     * Charge QRIS payment
     * @param string $order_id Unique transaction code (e.g. TRX-20260611-XYZ12)
     * @param int $gross_amount Total transaction amount
     * @param array $item_details Optional item breakdown
     * @return array API Response
     */
    public function charge_qris($order_id, $gross_amount, $item_details = []) {
        $url = $this->api_url . '/charge';
        
        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => (int)$gross_amount
            ]
        ];

        if (!empty($item_details)) {
            $payload['item_details'] = $item_details;
        }

        return $this->send_request($url, 'POST', $payload);
    }

    /**
     * Get transaction status from Midtrans
     * @param string $order_id
     * @return array API Response
     */
    public function get_status($order_id) {
        $url = $this->api_url . '/' . urlencode($order_id) . '/status';
        return $this->send_request($url, 'GET');
    }

    /**
     * Send HTTP cURL Request
     */
    protected function send_request($url, $method = 'GET', $payload = NULL) {
        $auth_string = base64_encode($this->server_key . ':');
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . $auth_string
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        // Bypass SSL verification for local development
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($response === false) {
            $curl_err = curl_error($ch);
            curl_close($ch);
            return [
                'status_code' => '500',
                'status_message' => 'Internal cURL Error: ' . $curl_err
            ];
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
