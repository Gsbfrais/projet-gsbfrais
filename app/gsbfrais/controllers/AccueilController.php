<?php
namespace Gsbfrais\Controllers;

class AccueilController  extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function accueil():void
    {
        $this->render('accueil/accueil', [
            'title' => 'Accueil',
        ]);
    }
}
