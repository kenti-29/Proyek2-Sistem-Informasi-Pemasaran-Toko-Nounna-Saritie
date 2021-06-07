<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agen extends CI_Controller
{
    public function index () {
        $this->template->load('home/template','home/agen/view_semua_agen');
    }

    public function detail()
    {
        $id = $this->uri->segment(3);
        $dat = $this->db->query("SELECT * FROM tb_agen where id_agen='$id'")->row_Array();
        $this->template->load('home/template','home/agen/view_agen', [ 'record' => $dat,$data ]);
    }
}
