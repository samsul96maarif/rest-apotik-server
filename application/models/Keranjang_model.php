<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keranjang_model extends CI_Model {

  public function checkKeranjang($kode, $id){
    $query = "SELECT * FROM keranjang WHERE kode_obat = '". $kode ."' AND id_session = '". $id ."'";
    return $this->db->query($query)->num_rows();
  }

  public function getKeranjang($id){
    $q = "SELECT k.kode_obat, k.jumlah, o.harga, o.nama, (k.jumlah * o.harga) AS subtotal
          FROM keranjang k
          INNER JOIN obat o
            ON k.kode_obat = o.kode_obat
          WHERE k.id_session = '". $id ."'";

    $query = $this->db->query($q);

    return $query->result();
  }

  public function getKeranjangByKode($kode){
    $q = "SELECT k.kode_obat, k.jumlah, o.harga, o.nama, (k.jumlah * o.harga) AS subtotal
          FROM keranjang k
          INNER JOIN obat o
            ON k.kode_obat = o.kode_obat
          WHERE k.id_session = '". $this->session->userdata('id_session') ."' AND o.kode_obat = '". $kode ."'";

    $query = $this->db->query($q);
    return $query->row();
  }

  public function getKeranjangSubtotal($kode, $id){
    $q = "SELECT (k.jumlah * o.harga) AS subtotal
          FROM keranjang k
          INNER JOIN obat o
            ON k.kode_obat = o.kode_obat
          WHERE k.id_session = '". $id ."' AND o.kode_obat = '". $kode ."'";

    $query = $this->db->query($q);
    return $query->row();
  }

  public function getKeranjangInfo($id){
    $q = "SELECT SUM(k.jumlah * o.harga) AS total
          FROM keranjang k
          INNER JOIN obat o
            ON k.kode_obat = o.kode_obat
          WHERE id_session = '". $id ."'";
    $query = $this->db->query($q);

    return $query->row();
  }

  public function updateKeranjang($kode,$id){
    $query = "UPDATE keranjang SET jumlah = jumlah + 1 WHERE kode_obat = '". $kode ."' AND id_session = '". $id ."'";
    if($this->db->query($query))
      return true;
    else
      return false;
  }

  public function insertKeranjang($kode, $id){
    // kolomnya -> (kode_obat, jumlah, id_session)
    $query = "INSERT INTO keranjang VALUES (NULL, '". $kode ."', 1, '". $id ."')";
    if($this->db->query($query))
      return true;
    else
      return false;
  }

  public function deleteKeranjang($id){
    $query = "DELETE FROM keranjang WHERE id_session = '". $id ."'";
    if($this->db->query($query))
      return true;
    else
      return false;
  }

  public function updateItemKeranjang($kode, $jumlah, $id){
    $q = "UPDATE keranjang SET jumlah = $jumlah WHERE kode_obat = '". $kode ."' AND id_session = '". $id ."'";

    if($this->db->query($q))
      return true;
    else
      return false;
  }

  public function deleteItemKeranjang($kode, $id){
    $q = "DELETE FROM keranjang WHERE kode_obat = '". $kode ."' AND id_session = '". $id ."'";

    if($this->db->query($q))
      return true;
    else
      return false;
  }

  public function checkIdentitas($id){
    $q = "SELECT id FROM pembeli WHERE id = '". $id ."'";
    return $this->db->query($q)->num_rows();
  }

  public function getPembeli($id){
    $q = "SELECT * FROM pembeli WHERE id = '". $id ."'";
    $query = $this->db->query($q);
    return $query->row();
  }

  public function insertPembeli($id, $nama){
    $q = "INSERT INTO pembeli VALUES ('". $id ."', '". $nama ."')";
    if($this->db->query($q))
      return true;
    else
      return false;
  }

  public function insertPemesanan($identitas, $id){
    $kode_pesan = "";
    $kar = "1234567890QWERTYUIOPASDFGHJKLZXCVBNM";
    //membuat kode pesan acak
    for($x = 1; $x <= 7; $x++){
      $kode_pesan .= $kar[rand(0, strlen($kar) -1)];
    }

    //kolom -> kode_pesan, id_pemesan, harga, tanggal, status, konfirmasi
    // INSERT INTO pemesanan
    // SELECT 'AHGQE12', '21120116140068',
    //   (SELECT SUM(k.jumlah * o.harga)
    //   FROM keranjang k
    //   INNER JOIN obat o
    //     ON k.kode_obat = o.kode_obat
    //   WHERE id_session = 'pg2jbbv72cid0mv0r8hzl12iqtu2ql4ga8i4i3kp'),
    // '2017-06-21', 'B';

    $q = "INSERT INTO pemesanan
          SELECT '". $kode_pesan ."', '". $identitas ."',
            (SELECT SUM(k.jumlah * o.harga)
            FROM keranjang k
            INNER JOIN obat o
              ON k.kode_obat = o.kode_obat
            WHERE id_session = '". $id ."'),
          '". date('Y-m-d') ."', 'B', NULL";

    $this->db->query($q);

    //untuk digunakan di tabel detail_pesan
    return $kode_pesan;
  }

  public function insertDetailPemesanan($kode_pesan, $id){
    // INSERT INTO detail_pemesanan VALUES
    // SELECT NULL, 'AHG788U', kode_obat, jumlah
    // FROM keranjang
    // WHERE id_session = '1234567';

    $q = "INSERT INTO detail_pemesanan
          SELECT NULL, '". $kode_pesan ."', kode_obat, jumlah
          FROM keranjang
          WHERE id_session = '". $id ."'";

    if($this->db->query($q))
      return true;
    else
      return false;
  }


}