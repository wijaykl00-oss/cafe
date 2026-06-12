<?php

class menu_model extends CI_Model
{
    protected $table = 'menu';

    public function get_all()
    {
        return $this->db->order_by('kategori', 'ASC')
                        ->order_by('nama', 'ASC')
                        ->get($this->table)
                        ->result_array();
    }

    public function get_aktif()
    {
        return $this->db->where('is_active', 1)
                        ->order_by('kategori', 'ASC')
                        ->get($this->table)
                        ->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}