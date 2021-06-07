<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_app');
		$this->load->model('model_main');
		$this->load->model('model_menu');
		$this->load->model('model_members');
		$this->load->model('model_laporan');
		$this->load->model('model_rekening');
		$this->load->model('model_berita');
		$this->load->model('model_halaman');
		$this->load->model('model_artikel');
		cek_session_admin();
	}

	function index()
	{

		if ($this->session->level == 1 || $this->session->level == 2) {
			redirect('admin/home');
		} else {
			redirect('error404');
		}
	}

	function home()
	{
		if (!empty($this->session->userdata())) {

			$data['title'] = 'Admin - Nounna Saritie';
			$data['grap'] = $this->model_main->grafik_kunjungan();

			$this->template->load('admin/template', 'admin/view_dashboard', $data);
		} else {
			redirect('admin');
		}
	}

	function kategori_produk()
	{

		$data['title'] = 'Kategori Produk - Nounna Saritie';
		$data['record'] = $this->model_app->view_ordering('tb_toko_kategoriproduk', 'id_kategori_produk', 'DESC');

		$this->template->load('admin/template', 'admin/kategori_produk/view_kategori_produk', $data);
	}

	function tambah_kategori_produk()
	{

		if (isset($_POST['submit'])) {
			$data = array('nama_kategori' => $this->input->post('a'), 'kategori_seo' => seo_title($this->input->post('a')));
			$this->model_app->insert('tb_toko_kategoriproduk', $data);
			redirect('admin/kategori_produk');
		} else {

			$data['title'] = 'Tambah Kategori Produk - Nounna Saritie';
			$this->template->load('admin/template', 'admin/kategori_produk/view_kategori_produk_tambah', $data);
		}
	}

	function edit_kategori_produk()
	{

		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$data = array('nama_kategori' => $this->input->post('a'), 'kategori_seo' => seo_title($this->input->post('a')));
			$where = array('id_kategori_produk' => $this->input->post('id'));
			$this->model_app->update('tb_toko_kategoriproduk', $data, $where);
			redirect('admin/kategori_produk');
		} else {
			$edit = $this->model_app->edit('tb_toko_kategoriproduk', array('id_kategori_produk' => $id))->row_array();
			$data = array('rows' => $edit);

			$data['title'] = 'Ubah Produk - Nounna Saritie';
			$this->template->load('admin/template', 'admin/kategori_produk/view_kategori_produk_edit', $data);
		}
	}

	function delete_kategori_produk($id)
	{
		$where = array('id_kategori_produk' => $id);
		$this->model_app->delete('tb_toko_kategoriproduk', $where);
		echo json_encode(array("status" => TRUE));
	}

	function produk()
	{
		$data['title'] = 'Produk - Nounna Saritie';
		$data['record'] = $this->model_app->view_ordering('tb_toko_produk', 'nama_produk', 'ACS');
		$this->template->load('admin/template', 'admin/produk/view_produk', $data);
	}

	function tambah_produk()
	{

		if (isset($_POST['submit'])) {
			$config['upload_path'] = 'assets/images/produk/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '5000'; // kb
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->do_upload('g');
			$hasil = $this->upload->data();
			if ($hasil['file_name'] == '') {
				$data = array(
					'id_kategori_produk' => $this->input->post('a'),
					'nama_produk' => $this->input->post('b'),
					'produk_seo' => $this->db->escape_str(seo_title($this->input->post('b'))),
					'satuan' => $this->input->post('c'),
					'harga_beli' => $this->input->post('d'),
					'harga_konsumen' => $this->input->post('f'),
					'diskon' => $this->input->post('diskon'),
					'berat' => $this->input->post('berat'),
					'stok' => $this->input->post('stok'),
					'keterangan' => $this->input->post('ff'),
					'waktu_input' => date('Y-m-d H:i:s'),

				);
			} else {
				$data = array(
					'id_kategori_produk' => $this->input->post('a'),
					'nama_produk' => $this->input->post('b'),
					'produk_seo' => $this->db->escape_str(seo_title($this->input->post('b'))),
					'satuan' => $this->input->post('c'),
					'harga_beli' => $this->input->post('d'),
					'harga_konsumen' => $this->input->post('f'),
					'diskon' => $this->input->post('diskon'),
					'berat' => $this->input->post('berat'),
					'stok' => $this->input->post('stok'),
					'gambar' => $hasil['file_name'],
					'keterangan' => $this->input->post('ff'),
					'waktu_input' => date('Y-m-d H:i:s'),

				);
			}
			$this->model_app->insert('tb_toko_produk', $data);
			redirect('admin/produk');
		} else {

			$data['title'] = 'Tambah Produk - Nounna Saritie';
			$data['record'] = $this->model_app->view_ordering('tb_toko_kategoriproduk', 'id_kategori_produk', 'DESC');
			$this->template->load('admin/template', 'admin/produk/view_produk_tambah', $data);
		}
	}

	function edit_produk()
	{

		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$config['upload_path'] = 'assets/images/produk/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '5000'; // kb
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->do_upload('g');
			$hasil = $this->upload->data();
			if ($hasil['file_name'] == '') {
				$data = array(
					'id_kategori_produk' => $this->input->post('a'),
					'nama_produk' => $this->input->post('b'),
					'produk_seo' => $this->db->escape_str(seo_title($this->input->post('b'))),
					'satuan' => $this->input->post('c'),
					'harga_beli' => $this->input->post('d'),
					'harga_konsumen' => $this->input->post('f'),
					'diskon' => $this->input->post('diskon'),
					'berat' => $this->input->post('berat'),
					'stok' => $this->input->post('stok'),
					'keterangan' => $this->input->post('ff'),
				);
			} else {
				$data = array(
					'id_kategori_produk' => $this->input->post('a'),
					'nama_produk' => $this->input->post('b'),
					'produk_seo' => $this->db->escape_str(seo_title($this->input->post('b'))),
					'satuan' => $this->input->post('c'),
					'harga_beli' => $this->input->post('d'),
					'harga_konsumen' => $this->input->post('f'),
					'diskon' => $this->input->post('diskon'),
					'berat' => $this->input->post('berat'),
					'stok' => $this->input->post('stok'),
					'gambar' => $hasil['file_name'],
					'keterangan' => $this->input->post('ff')
				);
				$query = $this->db->get_where('tb_toko_produk', array('id_produk' => $this->input->post('id')));
				$row = $query->row();
				$foto = $row->gambar;
				$path = "assets/images/produk/";
				unlink($path . $foto);
			}

			$where = array('id_produk' => $this->input->post('id'));
			$this->model_app->update('tb_toko_produk', $data, $where);
			redirect('admin/produk');
		} else {

			$data['title'] = 'Edit - Nounna Saritie';
			$data['record'] = $this->model_app->view_ordering('tb_toko_kategoriproduk', 'id_kategori_produk', 'DESC');
			$data['rows'] = $this->model_app->edit('tb_toko_produk', array('id_produk' => $id))->row_array();
			$this->template->load('admin/template', 'admin/produk/view_produk_edit', $data);
		}
	}

	function delete_produk($id)
	{
		$where = array('id_produk' => $id);
		$this->model_app->delete('tb_toko_produk', $where);
		echo json_encode(array("status" => TRUE));
	}


	function rekening()
	{

		$data['title'] = 'Rekening - Nounna Saritie';
		$data['record'] = $this->model_rekening->rekening();
		$this->template->load('admin/template', 'admin/rekening/view_rekening', $data);
	}


	function tambah_rekening()
	{

		if (isset($_POST['submit'])) {
			$this->model_rekening->rekening_tambah();
			redirect('admin/rekening');
		} else {

			$data['title'] = 'Tambah Rekening - Nounna Saritie';
			//$this->load->view('admin/rekening/view_rekening_tambah');
			$this->template->load('admin/template', 'admin/rekening/view_rekening_tambah');
		}
	}

	function edit_rekening()
	{

		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$this->model_rekening->rekening_update();
			redirect('admin/rekening');
		} else {

			$data['title'] = 'Edit Rekening - Nounna Saritie';
			$data['rows'] = $this->model_rekening->rekening_edit($id)->row_array();
			$this->template->load('admin/template', 'admin/rekening/view_rekening_edit', $data);
		}
	}

	function delete_rekening($id)
	{
		$this->model_rekening->rekening_delete($id);
		echo json_encode(array("status" => TRUE));
	}


	function tracking()
	{
		cek_session();
		if ($this->uri->segment(3) != '') {
			$kode_transaksi = filter($this->uri->segment(3));
			$data['title'] = 'Tracking Order ' . $kode_transaksi;
			$data['rows'] = $this->db->query("SELECT * FROM tb_toko_penjualan a JOIN tb_pengguna b ON a.id_pembeli=b.id_pengguna JOIN tb_alamat c ON b.id_alamat=c.id_alamat JOIN tb_kota d ON c.id_kota=d.kota_id where a.kode_transaksi='$kode_transaksi'")->row_array();
			$data['record'] = $this->db->query("SELECT a.kode_transaksi, b.*, c.nama_produk, c.satuan, c.berat, c.diskon, c.produk_seo FROM `tb_toko_penjualan` a JOIN tb_toko_penjualandetail b ON a.id_penjualan=b.id_penjualan JOIN tb_toko_produk c ON b.id_produk=c.id_produk where a.kode_transaksi='" . $kode_transaksi . "'");
			$data['total'] = $this->db->query("SELECT a.id_penjualan, a.kode_transaksi, a.kurir, a.resi, a.service, a.proses, a.ongkir, sum((b.harga_jual*b.jumlah)-(c.diskon*b.jumlah)) as total, sum(c.berat*b.jumlah) as total_berat FROM `tb_toko_penjualan` a JOIN tb_toko_penjualandetail b ON a.id_penjualan=b.id_penjualan JOIN tb_toko_produk c ON b.id_produk=c.id_produk where a.kode_transaksi='" . $kode_transaksi . "'")->row_array();

			$this->template->load('admin/template', 'admin/penjualan/view_tracking', $data);
		}
	}


	function plier()
	{

		$data['title'] = 'plier - Nounna Saritie';
		$pengguna = $this->session->id_pengguna;
		$data['record'] = $this->model_app->view_ordering('tb_toko_plier', 'id_plier', 'DESC');
		$this->template->load('admin/template', 'admin/plier/view_plier', $data);
	}

	function tambah_plier()
	{

		if (isset($_POST['submit'])) {
			$data = array(
				'nama_plier' => $this->input->post('a'),
				'kontak_person' => $this->input->post('b'),
				'alamat_lengkap' => $this->input->post('c'),
				'alamat_email' => $this->input->post('e'),
				'kode_pos' => $this->input->post('f'),
				'no_telpon' => $this->input->post('g'),
				'fax' => $this->input->post('h'),
				'katerangan' => $this->input->post('i'),
				'id_pengguna' => $this->session->id_pengguna
			);
			$this->model_app->insert('tb_toko_plier', $data);
			redirect('admin/plier');
		} else {

			$data['title'] = 'Tambah plier - Nounna Saritie';
			$this->template->load('admin/template', 'admin/plier/view_plier_tambah', $data);
		}
	}

	function edit_plier()
	{

		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$data = array(
				'nama_plier' => $this->input->post('a'),
				'kontak_person' => $this->input->post('b'),
				'alamat_lengkap' => $this->input->post('c'),
				'alamat_email' => $this->input->post('e'),
				'kode_pos' => $this->input->post('f'),
				'no_telpon' => $this->input->post('g'),
				'fax' => $this->input->post('h'),
				'katerangan' => $this->input->post('i')
			);
			$where = array('id_plier' => $this->input->post('id'));
			$this->model_app->update('tb_toko_plier', $data, $where);
			redirect('admin/plier');
		} else {

			$data['title'] = 'Edit plier - Nounna Saritie';
			$proses = $this->model_app->edit('tb_toko_plier', array('id_plier' => $id))->row_array();
			$data = array('rows' => $proses);
			$this->template->load('admin/template', 'admin/plier/view_plier_edit', $data);
		}
	}

	function delete_plier($id)
	{

		$where = array('id_plier' => $id);
		$this->model_app->delete('tb_toko_plier', $where);
		echo json_encode(array("status" => TRUE));
	}

	function konsumen()
	{
		$data['title'] = 'Konsumen - Nounna Saritie';
		$data['record'] = $this->model_app->view_where_ordering('tb_pengguna', "level='2'", 'id_pengguna', 'ASC');
		$this->template->load('admin/template', 'admin/konsumen/view_konsumen', $data);
	}

	function edit_konsumen()
	{

		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$this->model_members->profile_update($this->input->post('id'));
			redirect('admin/konsumen');
		} else {

			$data['title'] = 'Ubah Konsumen - Nounna Saritie';
			$data['row'] = $this->model_app->profile_konsumen($id)->row_array();
			$data['kota'] = $this->model_app->view('tb_kota');
			$this->template->load('admin/template', 'admin/konsumen/view_konsumen_edit', $data);
		}
	}

	function detail_konsumen()
	{

		$id = $this->uri->segment(3);
		$record = $this->model_app->orders_report($id);
		$edit = $this->model_app->profile_konsumen($id)->row_array();
		$data = array('rows' => $edit, 'record' => $record);
		$data['title'] = 'Detail Konsumen - Nounna Saritie';
		$this->template->load('admin/template', 'admin/konsumen/view_konsumen_detail', $data);
	}

	function delete_konsumen($id)
	{

		$where = array('id_konsumen' => $id);
		$this->model_app->delete('tb_toko_konsumen', $where);
		echo json_encode(array("status" => TRUE));
	}


	function pesanan()
	{


		$data['title'] = 'Laporan Pesanan Masuk';

		$data['record'] = $this->model_app->orders_report_all();

		$this->template->load('admin/template', 'admin/penjualan/view_pesanan_laporan', $data);
	}

	function print_pesanan()
	{
		cek_session();
		$data['title'] = 'Laporan Pesanan Masuk';
		$data['record'] = $this->model_app->orders_report_all();
		$data['iden'] = $this->model_main->identitas()->row_array();
		$this->load->view('admin/penjualan/view_orders_report_print', $data);
	}

	function konfirmasi()
	{
		cek_session();
		$data['title'] = 'Konfirmasi Pembayaran Pesanan';
		$data['record'] = $this->model_app->konfirmasi_bayar();
		$this->template->load('admin/template', 'admin/penjualan/view_konfirmasi_bayar', $data);
	}

	function pesanan_status()
	{


		$data = array('proses' => $this->uri->segment(4));
		$where = array('id_penjualan' => $this->uri->segment(3));
		$this->model_app->update('tb_toko_penjualan', $data, $where);
		redirect('admin/pesanan');
	}

	function pesanan_status2()
	{

		$kode = $this->uri->segment(5);
		$data = array('proses' => $this->uri->segment(4));
		$where = array('id_penjualan' => $this->uri->segment(3));
		$this->model_app->update('tb_toko_penjualan', $data, $where);
		redirect('admin/tracking/' . $kode);
	}

	function pesanan_dikirim()
	{
		$data = array('proses' => $this->uri->segment(4));
		$where = array('id_penjualan' => $this->uri->segment(3));
		$this->model_app->update('tb_toko_penjualan', $data, $where);
		$data['title'] = 'Input Resi';
		$query = $this->model_app->edit('tb_toko_penjualan', array('id_penjualan' => $this->uri->segment(3)))->row_array();
		$data = array('rows' => $query);


		$data['title'] = 'Masukan Resi - Nounna Saritie';
		$this->template->load('admin/template', 'admin/penjualan/view_resi', $data);
	}

	function pesanan_dikirim2()
	{

		$data = array('proses' => $this->uri->segment(4));
		$where = array('id_penjualan' => $this->uri->segment(3));
		$this->model_app->update('tb_toko_penjualan', $data, $where);
		$data['title'] = 'Input Resi';
		$query = $this->model_app->edit('tb_toko_penjualan', array('id_penjualan' => $this->uri->segment(3)))->row_array();
		$data = array('rows' => $query);

		$data['title'] = 'Masukan Resi - Nounna Saritie';
		$this->template->load('admin/template', 'admin/penjualan/view_resi', $data);
	}

	function resi()
	{

		$kode = $this->input->post('kode');
		$uri2 = $this->input->post('uri2');
		$id = $this->input->post('id');
		if (isset($_POST['submit'])) {
			$data = array('resi' => $this->input->post('resi'));
			$where = array('id_penjualan' => $id);
			$this->model_app->update('tb_toko_penjualan', $data, $where);

			$data1 = array(
				'proses'    => '3'
			);
			$this->db->where('id_penjualan', $id);
			$this->db->update('tb_toko_penjualan', $data1);

			if ($uri2 == 'pesanan_dikirim2') {
				redirect('admin/tracking/' . $kode);
			} else {
				redirect('admin/pesanan');
			}
		}
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}

	public function download_file()
	{
		$name = $this->uri->segment(3);
		$data = file_get_contents("assets/images/bukti/" . $name);
		force_download($name, $data);
	}


	// Modul Web
	function website()
	{
		if (isset($_POST['submit'])) {
			$this->model_main->identitas_update();
			redirect('admin/website');
		} else {
			$data['record'] = $this->model_main->identitas()->row_array();

			$data['title'] = 'Identitas Website - Nounna Saritie';
			$this->template->load('admin/template', 'admin/website_identitas/view_identitas', $data);
		}
	}

	function menu()
	{
		$data['record'] = $this->model_menu->menu_website();
		$data['title'] = 'Menu Website - Nounna Saritie';
		$this->template->load('admin/template', 'admin/website_menu/view_menu', $data);
	}

	function tambah_menu()
	{
		if (isset($_POST['submit'])) {
			$this->model_menu->menu_website_tambah();
			redirect('admin/menu');
		} else {
			$data['title'] = 'Tambah Menu Website - Nounna Saritie';
			$data['record'] = $this->model_menu->menu_utama();
			$this->template->load('admin/template', 'admin/website_menu/view_menu_tambah', $data);
		}
	}

	function edit_menu()
	{
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$this->model_menu->menu_website_update();
			redirect('admin/menu');
		} else {

			$data['title'] = 'Ubah Menu Website - Nounna Saritie';
			$data['record'] = $this->model_menu->menu_utama();
			$data['rows'] = $this->model_menu->menu_edit($id)->row_array();
			$this->template->load('admin/template', 'admin/website_menu/view_menu_edit', $data);
		}
	}

	function delete_menu($id)
	{
		$this->model_menu->menu_delete($id);
		echo json_encode(array("status" => TRUE));
	}


	function logo()
	{
		if (isset($_POST['submit'])) {
			$this->model_main->logo_update();
			redirect('admin/logo');
		} else {

			$data['title'] = 'Logo Website - Nounna Saritie';
			$data['record'] = $this->model_main->logo();
			$this->template->load('admin/template', 'admin/website_logo/view_logowebsite', $data);
		}
	}


	function slider()
	{
		$data['record'] = $this->model_main->slide();
		$data['title'] = 'Slider - Nounna Saritie';
		$this->template->load('admin/template', 'admin/website_slider/view_slider', $data);
	}

	function tambah_slider()
	{
		if (isset($_POST['submit'])) {
			$this->model_main->slide_tambah();
			redirect('admin/slider');
		} else {
			$data['record'] = $this->model_app->view('tb_toko_produk');

			$data['title'] = 'Tambah Slider - Nounna Saritie';
			$this->template->load('admin/template', 'admin/website_slider/view_slider_tambah', $data);
		}
	}

	function edit_slider()
	{
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$this->model_main->slide_update();
			redirect('admin/slider');
		} else {

			$data['title'] = 'Edit Slider - Nounna Saritie';
			$data['rows'] = $this->model_main->slide_edit($id)->row_array();
			$data['record'] = $this->model_app->view('tb_toko_produk');
			$this->template->load('admin/template', 'admin/website_slider/view_slider_edit', $data);
		}
	}

	function delete_slider($id)
	{
		$this->model_main->slide_delete($id);
		echo json_encode(array("status" => TRUE));
	}

	function halaman()
	{
		$data['record'] = $this->model_halaman->halaman();

		$data['title'] = 'Halaman - Nounna Saritie';
		$this->template->load('admin/template', 'admin/website_halaman/view_halaman', $data);
	}

	function tambah_halaman()
	{
		if (isset($_POST['submit'])) {
			$this->model_halaman->halaman_tambah();
			redirect('admin/halaman');
		} else {

			$data['title'] = 'Tambah Halaman Baru - Nounna Saritie';
			$this->template->load('admin/template', 'admin/website_halaman/view_halaman_tambah');
		}
	}

	function edit_halaman()
	{
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$this->model_halaman->halaman_update();
			redirect('admin/halaman');
		} else {
			$data['title'] = 'Ubah Halaman Baru - Nounna Saritie';
			$data['rows'] = $this->model_halaman->halaman_edit($id)->row_array();
			$this->template->load('admin/template', 'admin/website_halaman/view_halaman_edit', $data);
		}
	}

	function delete_halaman($id)
	{
		$this->model_halaman->halaman_delete($id);
		echo json_encode(array("status" => TRUE));
	}

	// Modul Blog
	function artikel()
	{
		$data['title'] = 'Artikel - Nounna Saritie';
		$data['record'] = $this->model_artikel->list_artikel();
		$this->template->load('admin/template', 'admin/blog_artikel/view_artikel', $data);
	}

	function tambah_artikel()
	{

		if (isset($_POST['submit'])) {
			$this->model_artikel->list_artikel_tambah();
			redirect('admin/artikel');
		} else {
			$data['title'] = 'Tambah Artikel - Nounna Saritie';
			$data['tag'] = $this->model_artikel->tag_artikel();
			$data['record'] = $this->model_artikel->kategori_artikel();
			$this->template->load('admin/template', 'admin/blog_artikel/view_artikel_tambah', $data);
		}
	}

	function edit_artikel()
	{

		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$this->model_artikel->list_artikel_update();
			redirect('admin/artikel');
		} else {
			$data['title'] = 'Edit Artikel - Nounna Saritie';
			$data['tag'] = $this->model_artikel->tag_artikel();
			$data['record'] = $this->model_artikel->kategori_artikel();
			$data['rows'] = $this->model_artikel->list_artikel_edit($id)->row_array();
			$this->template->load('admin/template', 'admin/blog_artikel/view_artikel_edit', $data);
		}
	}

	function delete_artikel($id)
	{
		$this->model_artikel->list_artikel_delete($id);
		echo json_encode(array("status" => TRUE));
	}

	function kategori_artikel()
	{

		$data['record'] = $this->model_artikel->kategori_artikel();
		$this->template->load('admin/template', 'admin/blog_kategori/view_kategori', $data);
	}

	function tambah_kategori_artikel()
	{

		if (isset($_POST['submit'])) {
			$this->model_artikel->kategori_artikel_tambah();
			redirect('admin/kategori_artikel');
		} else {
			$this->template->load('admin/template', 'admin/blog_kategori/view_kategori_tambah');
		}
	}

	function edit_kategori_artikel()
	{

		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$this->model_artikel->kategori_artikel_update();
			redirect('admin/kategori_artikel');
		} else {
			$data['rows'] = $this->model_artikel->kategori_artikel_edit($id)->row_array();
			$this->template->load('admin/template', 'admin/blog_kategori/view_kategori_edit', $data);
		}
	}

	function delete_kategori_artikel($id)
	{
		$this->model_artikel->kategori_artikel_delete($id);
		echo json_encode(array("status" => TRUE));
	}

	function tag_artikel()
	{
		$data['title'] = 'Tag - Nounna Saritie';
		$data['record'] = $this->model_artikel->tag_artikel();
		$this->template->load('admin/template', 'admin/blog_tag/view_tag', $data);
	}

	function tambah_tag_artikel()
	{

		if (isset($_POST['submit'])) {
			$this->model_artikel->tag_artikel_tambah();
			redirect('admin/tag_artikel');
		} else {
			$data['title'] = 'Tambah Tag - Nounna Saritie';
			$this->template->load('admin/template', 'admin/blog_tag/view_tag_tambah', $data);
		}
	}

	function edit_tag_artikel()
	{

		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$this->model_artikel->tag_artikel_update();
			redirect('admin/tag_artikel');
		} else {
			$data['title'] = 'Edit Tag - Nounna Saritie';
			$data['rows'] = $this->model_artikel->tag_artikel_edit($id)->row_array();
			$this->template->load('admin/template', 'admin/blog_tag/view_tag_edit', $data);
		}
	}

	function delete_tag_artikel($id)
	{
		$this->model_artikel->tag_artikel_delete($id);
		echo json_encode(array("status" => TRUE));
	}

	// Mod Managment User
	function edit_user()
	{
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])) {
			$config['upload_path'] = 'assets/images/user/';
			$config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
			$config['max_size'] = '1000'; // kb
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->do_upload('f');
			$hasil = $this->upload->data();
			if ($hasil['file_name'] == '' and $this->input->post('b') == '') {
				$data = array(
					'nama_lengkap' => $this->db->escape_str($this->input->post('c')),
					'email' => $this->db->escape_str($this->input->post('d')),
					'no_telp' => $this->db->escape_str($this->input->post('e')),
				);
			} elseif ($hasil['file_name'] != '' and $this->input->post('b') == '') {
				$data = array(
					'nama_lengkap' => $this->db->escape_str($this->input->post('c')),
					'email' => $this->db->escape_str($this->input->post('d')),
					'no_telp' => $this->db->escape_str($this->input->post('e')),
					'foto' => $hasil['file_name'],
				);
			} elseif ($hasil['file_name'] == '' and $this->input->post('b') != '') {
				$data = array(
					'password' => password_hash($this->input->post('b'), PASSWORD_DEFAULT),
					'nama_lengkap' => $this->db->escape_str($this->input->post('c')),
					'email' => $this->db->escape_str($this->input->post('d')),
					'no_telp' => $this->db->escape_str($this->input->post('e')),
				);
			} elseif ($hasil['file_name'] != '' and $this->input->post('b') != '') {
				$data = array(
					'password' => password_hash($this->input->post('b'), PASSWORD_DEFAULT),
					'nama_lengkap' => $this->db->escape_str($this->input->post('c')),
					'email' => $this->db->escape_str($this->input->post('d')),
					'no_telp' => $this->db->escape_str($this->input->post('e')),
					'foto' => $hasil['file_name'],
				);
			}
			$where = array('nama_lengkap' => $this->input->post('id'));
			$this->model_app->update('tb_pengguna', $data, $where);

			$this->session->set_flashdata('message', '
				<div class="alert alert-success" role="alert">
            	<center>Pembaruan berhasil disimpan</center>
          		</div>');

			redirect('admin/edit_user/' . $this->input->post('id'));
		} else {
			$data['title'] = 'Edit Pengguna - Nounna Saritie';
			$proses = $this->model_app->edit('tb_pengguna', array('nama_lengkap' => $id))->row_array();
			$data = array('rows' => $proses);
			$this->template->load('admin/template', 'admin/users/view_users_edit', $data);
		}
	}

	function delete_user($id)
	{
		$where = array('nama_lengkap' => $id);
		$this->model_app->delete('tb_pengguna', $where);
		echo json_encode(array("status" => TRUE));
	}

	function users()
	{
		$data['title'] = 'Manajemen Pengguna - Nounna Saritie';
		// $data['record'] = $this->model_app->view_ordering('tb_pengguna', 'nama_lengkap', 'DESC');
		$data['record'] = $this->db->query("SELECT * FROM tb_pengguna ORDER BY level ASC,nama_lengkap ASC")->result_array();
		$this->template->load('admin/template', 'admin/users/view_users', $data);
	}

	function tambah_user()
	{

		$id = $this->session->username;
		if (isset($_POST['submit'])) {
			$config['upload_path'] = 'assets/images/user/';
			$config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
			$config['max_size'] = '1000'; // kb
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->do_upload('f');
			$hasil = $this->upload->data();
			if ($hasil['file_name'] == '') {
				$data = array(
					'password' => password_hash($this->input->post('b'), PASSWORD_DEFAULT),
					'nama_lengkap' => $this->db->escape_str($this->input->post('c')),
					'email' => $this->db->escape_str($this->input->post('d')),
					'no_telp' => $this->db->escape_str($this->input->post('e')),
					'level' => $this->db->escape_str($this->input->post('g')),
				);
			} else {
				$data = array(
					'password' => password_hash($this->input->post('b'), PASSWORD_DEFAULT),
					'nama_lengkap' => $this->db->escape_str($this->input->post('c')),
					'email' => $this->db->escape_str($this->input->post('d')),
					'no_telp' => $this->db->escape_str($this->input->post('e')),
					'foto' => $hasil['file_name'],
					'level' => $this->db->escape_str($this->input->post('g')),
				);
			}
			$this->model_app->insert('tb_pengguna', $data);
			redirect('admin/edit_user/' . $this->input->post('a'));
		} else {
			$data['title'] = 'Tambah Pengguna - Nounna Saritie';
			$this->template->load('admin/template', 'admin/users/view_users_tambah', $data);
		}
	}

	// Laporan
	function laporan()
	{
		$data['title'] = 'Laporan Penjualan - Nounna Saritie';
		$data['record'] = $this->model_laporan->laporan();
		$this->template->load('admin/template', 'admin/laporan/view_lap_penjualan', $data);
	}

	function laporan_hari()
	{
		$data['title'] = 'Laporan Penjualan - Nounna Saritie';
		$data['record'] = $this->model_laporan->laporan1();
		$this->template->load('admin/template', 'admin/laporan/view_lap_penjualan', $data);
	}

	function laporan_minggu()
	{
		$data['title'] = 'Laporan Penjualan - Nounna Saritie';
		$data['record'] = $this->model_laporan->laporan7();
		$this->template->load('admin/template', 'admin/laporan/view_lap_penjualan', $data);
	}

	function laporan_bulan()
	{
		$data['title'] = 'Laporan Penjualan - Nounna Saritie';
		$data['record'] = $this->model_laporan->laporan30();
		$this->template->load('admin/template', 'admin/laporan/view_lap_penjualan', $data);
	}

	function laporan_tahun()
	{
		$data['title'] = 'Laporan Penjualan - Nounna Saritie';
		$data['record'] = $this->model_laporan->laporan360();
		$this->template->load('admin/template', 'admin/laporan/view_lap_penjualan', $data);
	}



	// Modul Agen

	public function agen()
	{
		$data['title'] = 'Agen';
		$data['record'] = $this->db->query("SELECT * FROM tb_agen")->result_array();
		$this->template->load('admin/template', 'admin/agen/view_agen',$data);
	}

	public function edit_agen()
	{
		if (isset($_POST['submit'])) {
			$config['upload_path'] = 'assets/images/agen/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '5000'; // kb
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->do_upload('foto_agen');
			$id = $this->input->post('id_agen');
			$hasil = $this->upload->data();
			$nama = $this->input->post('nama_agen');
			$alamat = $this->input->post('alamat_agen');

			if ($hasil['file_name'] == '') {
				$this->db->query("UPDATE tb_agen SET nama = '$nama',alamat = '$alamat' WHERE id_agen='$id'");
			} else {
				$this->db->query("UPDATE tb_agen SET nama = '$nama',alamat = '$alamat',foto = '$hasil[file_name]' WHERE id_agen='$id'");
			}

			redirect('admin/agen');
		} else {
			$id = $this->uri->segment(3);
			$data['title'] = 'Agen';
			$data['record'] = $this->db->query("SELECT * FROM tb_agen WHERE id_agen='$id'")->row_array();
			$this->template->load('admin/template', 'admin/agen/view_edit_agen',$data);
		}
	}

	public function del_agen($id)
	{
		$this->db->query("DELETE FROM tb_agen where id_agen='$id'");
		echo json_encode(array("status" => TRUE));
	}

	public function tambah_agen()
	{
		if (isset($_POST['submit'])) {
			$config['upload_path'] = 'assets/images/agen/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '5000'; // kb
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->do_upload('foto_agen');
			$hasil = $this->upload->data();
			$nama = $this->input->post('nama_agen');
			$alamat = $this->input->post('alamat_agen');

			$this->db->query("INSERT INTO tb_agen (nama,alamat,foto) VALUES ('$nama','$alamat','$hasil[file_name]')");
			redirect('admin/agen');
		} else {
			$data['title'] = 'Agen';
			$this->template->load('admin/template', 'admin/agen/view_tambah_agen',$data);
		}
	}
}
