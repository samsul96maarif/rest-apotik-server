<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_produk extends CI_Model{
    private $table = "produk";
    
    function getProduk(){        
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    function getProdukById($id){
        $this->db->where('id',$id);
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    function updateProduk($id,$data){
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    function insertProduk($data){
        return $this->db->insert($this->table,$data);
    }
    
    function deleteProduk($id){
        $this->db->where('id', $id);
        $query = $this->db->delete($this->table);
        if($this->db->affected_rows() == '1'){
            return true;
        }else{
            return false;
        }
    }
}
?>