<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        //untuk memuat model M_produk.php agar dapat dipakai di controller ini
        $this->load->model(array('m_produk', 'admin_model', 'home_model'));
    }

    function pemesanan_get() {
        $kode_pemesanan = $this->uri->segment('3');
        $param = $this->uri->segment('3');
        /* else  else { */
        if ($kode_pemesanan == '') {
                $pemesanan = $this->admin_model->getPemesanan();
        } else {
                $pemesanan = $this->admin_model->getPemesananByKode($kode_pemesanan);
        }

        if($param == 'detail') {
            $kode_pemesanan = $this->uri->segment('4');
            $pemesanan = $this->admin_model->getDetailPemesanan($kode_pemesanan);
        } 

        if ($param == 'pembeli') {
            $kode_pemesanan = $this->uri->segment('4');
            $pemesanan = $this->admin_model->getDetailPembeliByPemesanan($kode_pemesanan);
        }
        /* } */
        $this->response($pemesanan, 200);
    }

    //Menampilkan data produk
    function obat_get() {
        $kode_obat = $this->uri->segment('3');

        if ($kode_obat == '') {
            $obat = $this->home_model->getObat();
            } else {
            $obat = $this->home_model->getObat($kode_obat);
        }   $this->response($obat, 200);
    }
    
    //Mengubah data produk
	function obat_post() {
        //mengambil ID yang dikirim melalui method post
        $kode_obat = $this->input->post('kode');
        //mengambil data yang dikirim melalui method post
        //proses update data ke dalam database
        $update = $this->admin_model->updateObat($kode_obat);
        //pengecekan apakah proses update berhasil atau tidak
        if ($update) {
            $this->response($update, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
    
    //Menambah data produk
	function obat_put() {
        //mengambil data yang dikirim melalui method put
        $update = $this->admin_model->insertObat();

        //pengecekan apakah proses insert berhasil atau tidak
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
    
    //Menghapus salah satu data produk
	function obat_delete() {
        //mengambil data ID yang dikirim melalui method post
        $kode = $this->delete('kode_obat');
        //proses delete data dari database
        $delete = $this->admin_model->deleteObat($kode);

        //pengecekan apakah proses delete berhasil atau tidak
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    //Masukan function selanjutnya disini
}
?>
