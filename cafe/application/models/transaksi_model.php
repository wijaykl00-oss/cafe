<?php

class Transaksi_model extends CI_Model
{
    protected $table = 'transaksi';

    public function get_all($limit = 100)
    {
        $this->db->select('t.*, u.nama as kasir');
        $this->db->from('transaksi t');
        $this->db->join('users u', 'u.id = t.user_id');
        $this->db->order_by('t.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        $this->db->select('t.*, u.nama as kasir');
        $this->db->from('transaksi t');
        $this->db->join('users u', 'u.id = t.user_id');
        $this->db->where('t.id', $id);
        return $this->db->get()->row_array();
    }

    public function get_by_kode($kode)
    {
        $this->db->select('t.*, u.nama as kasir');
        $this->db->from('transaksi t');
        $this->db->join('users u', 'u.id = t.user_id');
        $this->db->where('t.kode_transaksi', $kode);
        return $this->db->get()->row_array();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function insert_detail($data)
    {
        return $this->db->insert_batch('transaksi_detail', $data);
    }

    public function get_detail($transaksi_id)
    {
        return $this->db->where('transaksi_id', $transaksi_id)
                        ->get('transaksi_detail')
                        ->result_array();
    }

    public function update_bayar($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_detail($transaksi_id)
    {
        return $this->db->where('transaksi_id', $transaksi_id)->delete('transaksi_detail');
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}