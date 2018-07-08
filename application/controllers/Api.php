<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        //untuk memuat model M_produk.php agar dapat dipakai di controller ini
        $this->load->model(array('m_produk', 'admin_model', 'home_model', 'keranjang_model'));
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

    //Menampilkan data obat
    function obat_get() {
        $kode_obat = $this->uri->segment('3');

        if ($kode_obat == '') {
            $obat = $this->home_model->getObat();
            } else {
            $obat = $this->home_model->getObat($kode_obat);
        }   $this->response($obat, 200);
    }
    
    //Mengubah data obat
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
    
    //Menambah data obat
	function obat_put() {
        //mengambil data yang dikirim melalui method put
        $insert = $this->admin_model->insertObat();

        //pengecekan apakah proses insert berhasil atau tidak
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
    
    //Menghapus salah satu data obat
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

    function beli_get() {

        $uri = $this->uri->segment('3');

        if ($uri == 'info') {
            $id = $this->uri->segment('4');
            $keranjang = $this->keranjang_model->getKeranjangInfo($id);
            } else {
            $id = $this->uri->segment('3');
            $keranjang = $this->keranjang_model->getKeranjang($id);
        }   $this->response($keranjang, 200);
    }

    function beli_post() {
        $uri = $this->uri->segment('3');

        if ($uri == 'checkIdentity') {
             $identitas = $this->input->post('identitas');
             $beli = $this->keranjang_model->checkIdentitas($identitas);
            }   

            $this->response($beli, 200);
    }

    function keranjang_get() {

        $uri = $this->uri->segment('3');

        if ($uri == 'info') {
            $id = $this->uri->segment('4');
            $keranjang = $this->keranjang_model->getKeranjangInfo($id);
            } else if ($uri == 'pembeli') {
                $id = $this->uri->segment('4');
                $keranjang = $this->keranjang_model->getPembeli($id);
            } else if ($uri == 'keranjangSubTotal') {
                $kode = $this->uri->segment('4');
                $id = $this->uri->segment('5');
                $keranjang = $this->keranjang_model->getKeranjangSubtotal($kode, $id);
            } else if ($uri == 'keranjangInfo') {
                $id = $this->uri->segment('4');
                $keranjang = $this->keranjang_model->getKeranjangInfo($id);
            } else if ($uri == 'cek') {
                $kode = $this->uri->segment('4');
                $id = $this->uri->segment('5');
                $keranjang = $this->keranjang_model->checkKeranjang($kode, $id);
            } else if ($uri == 'cekStatus') {
                $kode_pesan = $this->uri->segment('4');
                $identitas = $this->uri->segment('5');
                $keranjang = $this->home_model->getStatusPemesananByKode($kode_pesan, $identitas);
            } else if ($uri == 'userInfo') {
                $id = $this->uri->segment('4');
                $keranjang = $this->home_model->getUserInfo($id);
            } else if ($uri == 'infoPemesanan') {
                $kode = $this->uri->segment('4');
                $keranjang = $this->home_model->getInfoPemesanan($kode);
            } else if ($uri == 'detailPemesanan') {
                $kode = $this->uri->segment('4');
                $keranjang = $this->home_model->getDetailPemesanan($kode);
            } else {
            $id = $this->uri->segment('3');
            $keranjang = $this->keranjang_model->getKeranjang($id);
        }   $this->response($keranjang, 200);
    }

    function keranjang_delete() {

        $uri = $this->uri->segment('3');
        $kode = $this->delete('kode');
        $id = $this->delete('id');

        if ($uri == 'deleteItem') {
            $keranjang = $this->keranjang_model->deleteItemKeranjang($kode, $id);
        } else {
            $keranjang = $this->keranjang_model->deleteKeranjang($id);
        }   
        $this->response($keranjang, 200);
    }

    function keranjang_put() {
        //mengambil data yang dikirim melalui method put
        $uri = $this->uri->segment('3');
        
        $id = $this->put('id'); 
         $kode = $this->put('kode');
        $nama = $this->put('nama');
        $identitas = $this->put('identitas');
        $kode_pesan = $this->put('kode_pesan'); 

        if($uri == 'insertPemesanan') {
         $insert = $this->keranjang_model->insertPemesanan($identitas, $id);
        } else if($uri == 'insertDetailPemesanan') {
         $insert = $this->keranjang_model->insertDetailPemesanan($kode_pesan, $id);
        } else if($uri == 'insertPembeli') {
         $insert = $this->keranjang_model->insertPembeli($identitas, $nama);
       } else {
         $insert = $this->keranjang_model->insertKeranjang($kode,$id);
       }
        //pengecekan apakah proses insert berhasil atau tidak
        if ($insert) {
            $this->response($insert, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }  
    }

    function keranjang_post() {
        //mengambil ID yang dikirim melalui method post
        $uri = $this->uri->segment('3');

        $kode = $this->input->post('kode');
        $jumlah = $this->input->post('jumlah');
        $id = $this->input->post('id');

        if($uri == 'updateItem') {
            $update = $this->keranjang_model->updateItemKeranjang($kode, $jumlah, $id);
        } else if ($uri == 'updateStatus') {
            $update = $this->home_model->updateStatusPemesanan($kode);
        } else {
            $update = $this->keranjang_model->updateKeranjang($kode,$id);
        }
        
        //pengecekan apakah proses update berhasil atau tidak
        if ($update) {
            $this->response($update, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function konfirmasi_post() {

        $kode_pesan = $this->input->post('kode_pesan');
        $identitas = $this->input->post('identitas');
        $identitas = $this->input->post('kode');
        $uri = $this->uri->segment('3');

        if($uri == 'cekKode') {
        $update = $this->home_model->checkKodeByKode($kode);
        } else {
        $update = $this->home_model->checkKode($kode_pesan, $identitas);
        }

        if ($update) {
            $this->response($update, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
    //Masukan function selanjutnya disini
}
?>
