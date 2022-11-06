<?php

class NadzornaplocaController extends AutorizacijaController

{
    private $phtmlDir = 'privatno' . DIRECTORY_SEPARATOR . 'nadzornaploca' . DIRECTORY_SEPARATOR;
    private $operateri = null;
    private $trenutniOperater = null;
    private $poruka = '';

    public function index()
    {
        if (isset($_SESSION['autoriziran'])) {
        $this->trenutniOperater = $_SESSION['autoriziran'];
    }

    $this->view->render(
        $this->phtmlDir . 'index',
        [
          //  'statistika' => Nadzornaploca::getStatistika(),
            'operateri' => Operater::read(),
            'trenutniOperater' => $this->trenutniOperater,
            //'skriptaJquery' => '<script src="' . App::config('url') . 'public/js/jquery-3.6.1.min.js"></script>',
            //'skriptaChartJs' => '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>',
            //'skriptaGrafAjax' => '<script src="' . App::config('url') . 'public/js/nadzornaploca.js"></script>'
        ]
    );
}

public function novi()
{
    if ($_SESSION['autoriziran']->uloga != 'administrator') {
        header('location: ' . App::config('url') . 'nadzornaploca');
        return;
    }

    if (!isset($_POST['ime'])) {
        $this->pripremiUnos();
        $this->view->render(
            $this->phtmlDir . 'detalji',
            [
                'operateri' => $this->operateri,
                'poruka' => 'Unesite sve podatke',
                'noviunos' => true
            ]
        );
        return;
    }

    $this->operateri = (object) $_POST;

    if ($this->kontrolirajUnosIzmjenu()) {
        Operater::create((array)$this->operateri);
        header('location: ' . App::config('url') . 'nadzornaploca');
        return;
    }

    $this->view->render(
        $this->phtmlDir . 'detalji',
        [
            'operateri' => $this->operateri, 'poruka' => $this->poruka,
            'noviunos' => true
        ]
    );
}

public function izmjenaoperatera($sifra = null)
{
    if ($_SESSION['autoriziran']->uloga != 'administrator') {
        header('location: ' . App::config('url') . 'nadzornaploca');
        return;
    }

    if (!isset($_POST['ime'])) {
        $operateri = Operater::readOne($sifra);

        if ($operateri == NULL) {
            header('location: ' . App::config('url') . 'nadzornaploca');
        }

        $this->view->render(
            $this->phtmlDir . 'detalji',
            [
                'operateri' => $operateri, 'poruka' => 'Unesite sve podatke',
                'noviunos' => false
            ]
        );
        return;
    }

    $this->operateri = (object) $_POST;
    $this->operateri->sifra = $sifra;

    if ($this->kontrola(true)) {
        Operater::update((array)$this->operateri);
        header('location: ' . App::congif('url') . 'nadzornaploca');
        return;
    }

    $this->view->render(
        $this->phtmlDir . 'detalji',
        [
            'operateri' => $this->operateri, 'poruka' => $this->poruka,
            'noviunos' => false
        ]
    );
}

public function brisanje($sifra = NULL)
{

    if ($_SESSION['autoriziran']->uloga != 'administrator') {
        header('location: ' . App::config('url') . 'nadzornaploca');
        return;
    }

    if (!Operater::readOne($sifra)) {
        header('location: ' . App::config('url') . 'nadzornaploca');
        return;
    }

    Operater::delete($sifra);

    if ($_SESSION['autoriziran']->sifra == $sifra) {
        unset($_SESSION['autoriziran']);
        session_destroy();
        header('location: ' . App::config('url'));
        return;
    }

    header('location: ' . App::config('url') . 'nadzornaploca');
}

private function kontrola($izmjenaUnosa = false)
{
    return $this->kontrolirajIme()
        && $this->kontrolirajPrezime()
        && $this->kontrolirajEmail($izmjenaUnosa)
        && $this->kontrolirajLozinku($izmjenaUnosa)
        && $this->kontrolirajUlogu();
}

private function kontrolirajIme()
{
    $this->operateri->ime = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->operateri->ime))));

    if ($this->operateri->ime == '') {
        $this->poruka = 'Ime je obavezno';
        return false;
    }

    if (strlen($this->operateri->ime) > 50) {
        $this->poruka = 'Ograničenje za ime je 50 znakova';
        return false;
    }

    $this->operateri->ime = mb_strtolower($this->operateri->ime);
    $this->operateri->ime = ucfirst($this->operateri->ime);

    return true;
}

private function kontrolirajPrezime()
{
    $this->operateri->prezime = trim(str_replace(' ', '', (str_replace('&nbsp;', '', ($this->operateri->prezime)))));

    if ($this->operateri->prezime == '') {
        $this->poruka = 'Prezime je obavezno';
        return false;
    }

    if (strlen($this->operateri->prezime) > 50) {
        $this->poruka = 'Ograničenje za prezime je 50 znakova';
        return false;
    }

    $this->operateri->prezime = mb_strtolower($this->operateri->prezime);
    $this->operateri->prezime = ucfirst($this->operateri->prezime);

    return true;
}

private function kontrolirajEmail($izmjenaUnosa = false)
{
    if ($this->operateri->email == '') {
        $this->poruka = 'Email je obavezan';
        return false;
    }

    $this->operateri->email = filter_var($this->operateri->email, FILTER_SANITIZE_EMAIL);

    if (strlen($this->operateri->email) > 50) {
        $this->poruka = 'Ograničenje za e-mail je 50 znakova';
        return false;
    }

    if (!filter_var($this->operateri->email, FILTER_VALIDATE_EMAIL)) {
        $this->poruka = 'Email je neispravan';
        return false;
    }

    if (in_array($this->operateri->email, Operater::getPostojeciEmailovi())) {
        if (!$izmjenaUnosa) {
            $this->poruka = 'Email već postoji';
            return false;
        }
    }

    return true;
}

private function kontrolirajLozinku()
{
    if ($this->operateri->lozinka == '') {
        $this->poruka = 'Lozinka je obavezna';
        return false;
    }

    $velikoSlovo = preg_match('@[A-Z]@', $this->operateri->lozinka);
    $maloSlovo = preg_match('@[a-z]@', $this->operateri->lozinka);
    $broj = preg_match('@[0-9]@', $this->operateri->lozinka);

    if (!$velikoSlovo || !$maloSlovo || !$broj || strlen($this->operateri->lozinka) < 8) {
        $this->poruka = 'Lozinka mora sadržavati najmanje 8 znakova, jedno veliko slovo, te jedan broj';
        return false;
    }

    if ($this->operateri->lozinka != $this->operateri->ponoviLozinku) {
        $this->poruka = 'Unesene lozinke se ne podudaraju';
        return false;
    }

    $this->operateri->lozinka = password_hash($this->operateri->lozinka, PASSWORD_BCRYPT);

    return true;
}

private function kontrolirajUlogu()
{
    if (!isset($this->operateri->uloga) || $this->operateri->uloga == '') {
        $this->poruka = 'Uloga je obavezna';
        return false;
    }

    if ($this->operateri->uloga != 'administrator' & $this->operateri->uloga != 'operater') {
        $this->poruka = 'Uloga je neispravna';
        return false;
    }

    return true;
}
// Kontrole END

private function pripremiUnos()
{
    $this->operateri = new stdClass();
    $this->operateri->email = '';
    $this->operateri->lozinka = '';
    $this->operateri->ime = '';
    $this->operateri->prezime = '';
    $this->operateri->uloga = '';
}

}