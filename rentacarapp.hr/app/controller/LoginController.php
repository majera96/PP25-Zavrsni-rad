<?php

class LoginController extends Controller
{
    public function prijava()
    {
        $this->prijavaView('operater@rentacar.hr','Popunite tražene podatke0');
    }

    public function autorizacija()
    {
        if(!isset($_POST['email']) || !isset($_POST['password'])){
            $this->prijava();
            return;
        }
    }

    if(strlen(trim($_POST['email']))===0){
        $this->prijavaView('','Email obavezno');
        return;
    }

    if(strlen(trim($_POST['password']))===0){
        $this->prijavaView($_POST['email'],'Lozinka obvezno');
        return;
    }

    $operater = Operater::autorizacija($_POST['email'],$_POST['password']);
    if($operater==null){
        $this->prijavaView($_POST['email'],'Email i/ili lozinka neispravni');
        return;
    }

    $_SESSION['autoriziran']=$operater;
    header('location:' . App::config('url') . 'nadzornaploca');

    private function prijavaView($email,$poruka)
    {
        $this->view->render('prijava',[
            'poruka'=>$poruka,
            'email'=>$email
        ]);
    }

    public function odjava()
    {
        unset($_SESSION['autoriziran']);
        session_destroy();
        $this->prijavaView('','Uspješno ste odjavljeni');
    }

}




