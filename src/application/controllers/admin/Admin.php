<?php

class Admin extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        
        //Yazıların listesini veritabanından getirme
        $itiraf_listesi = $this->pitiraflar_model->admin_son_itiraf();
        $viewData["itiraf_listesi"] = $itiraf_listesi;
        
        $data = array(
        'itiraf_listesi' => $itiraf_listesi,
        'istatistikler' => $this->admin_model->istatistikler(),
        'title' => "Admin Paneli"
        );

        $user = $this->session->userdata("user");
        
        
        if(!$user){
        $this->load->view("admin/giris_yap", $data);
        } else {
           $this->load->view("admin/dashboard", $data);
        }
        
        
    }


    public function giris_yap(){

        $user = $this->session->userdata("user");

        if($user){
            redirect(base_url("admin"));
        } 

        $email   = $this->input->post("email");
        $sifre   = $this->input->post("sifre");

        if(!$email || !$sifre){

            $alert = array(
                "title"     => "İşlem Başarısız",
                "message"   => "Lütfen Tüm alanları eksiksiz olarak doldurunuz.!!!",
                "type"      => "danger"
            );


        }else{

                // Database İşlemleri..

                $this->load->model("admin/admin_model");

                $where = array(
                    "email"  => $email,
                    "sifre"  => md5($sifre),
                    "isActive"  => 1,
                    "user_role"  => 4
                );

                $row = $this->admin_model->get($where);

                if($row){

                    $user = array(
                      "id"   => $row->id
                    );

                    $this->session->set_userdata("user", $user);

                    redirect(base_url("admin"));

                }else{

                    $alert = array(
                        "title"     => "İşlem Başarısız",
                        "message"   => "Böyle bir kullanıcı bulunamadı!!!",
                        "type"      => "danger"
                    );

                }



        }

        $this->session->set_flashdata("alert", $alert);

        redirect(base_url("admin"));

    }

    public function cikis_yap(){

        $this->session->unset_userdata("user");

        redirect(base_url("admin"));

    }

}
