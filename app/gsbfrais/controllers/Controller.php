<?php
namespace Gsbfrais\Controllers;

class Controller 
{
    protected $mois; // mois en cours au format aaaamm

    public function __construct()
    {
        $this->mois = date("Ym"); 
    }

    public function render(string $view, array $data = [])
	{
        $file = ROOT_PATH . "views/$view" . ".php";
        $template = ROOT_PATH . 'views/template.php';

		if (file_exists($file) == true) {
			extract($data);
			ob_start();
			require_once($file);
			$content = ob_get_clean();
			require_once($template);
		} else {
			http_response_code(500);
			throw new \Exception('Erreur interne');
		}
	}

}