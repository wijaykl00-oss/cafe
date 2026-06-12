<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Midtrans API Configuration
| -------------------------------------------------------------------------
|
| Konfigurasi credentials Midtrans.
| Dapatkan Server Key & Client Key dari Dashboard Midtrans:
| https://dashboard.sandbox.midtrans.com/ (Sandbox)
|
*/

// Ganti dengan Server Key Sandbox Anda
$config['server_key'] = 'SB-Mid-server-zS9R4G-F8Y1VpZ9o8b4H7hN-'; 

// Ganti dengan Client Key Sandbox Anda
$config['client_key'] = 'SB-Mid-client-8k9E_Xw2eBqZ-X7O';

// FALSE untuk Sandbox, TRUE untuk Production
$config['is_production'] = FALSE;
