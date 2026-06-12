<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth
$route['auth']               = 'auth/login';
$route['auth/login']         = 'auth/login';
$route['auth/proses_login']  = 'auth/proses_login';
$route['auth/logout']        = 'auth/logout';

// Dashboard
$route['dashboard']          = 'dashboard/index';

// Menu
$route['menu']               = 'menu/index';
$route['menu/tambah']        = 'menu/tambah';
$route['menu/edit/(:num)']   = 'menu/edit/$1';
$route['menu/update/(:num)'] = 'menu/update/$1';
$route['menu/hapus/(:num)']  = 'menu/hapus/$1';

// Transaksi
$route['transaksi']                        = 'transaksi/index';
$route['transaksi/buat']                   = 'transaksi/buat';
$route['transaksi/simpan']                 = 'transaksi/simpan';
$route['transaksi/buat_qris']              = 'transaksi/buat_qris';
$route['transaksi/cek_status_pembayaran/(:any)'] = 'transaksi/cek_status_pembayaran/$1';
$route['transaksi/konfirmasi_manual_qris/(:any)'] = 'transaksi/konfirmasi_manual_qris/$1';
$route['transaksi/detail/(:num)']          = 'transaksi/detail/$1';
$route['transaksi/batal/(:num)']           = 'transaksi/batal/$1';

// Webhook Callback Midtrans
$route['payment_callback']                 = 'payment_callback/index';

// Laporan
$route['laporan'] = 'laporan/index';