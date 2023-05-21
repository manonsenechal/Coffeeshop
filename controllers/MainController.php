<?php

class MainController 
{

    public function homeAction()
    {
        // préparer les données

        // lancer l'affichage de la bonne vue
        // $this permet de faire référence à l'objet qui exécute cette méthode
        $this->show('home');
    }

    public function productsAction()
    {
        // préparer les données

        // lancer l'affichage de la bonne vue
        $this->show('products');
    }

    public function aboutAction()
    {
        // préparer les données

        // lancer l'affichage de la bonne vue
        $this->show('about');
    }

    public function storeAction()
    {
        // préparer les données

        $weekOpeningHours = [
            [
                'day' => 'Sunday',
                'open_hours' => 'Closed',
            ],
            [
                'day' => 'Monday',
                'open_hours' => '7:00 AM to 8:00 PM',
            ],
            [
                'day' => 'Tuesday',
                'open_hours' => '7:00 AM to 8:00 PM',
            ],
            [
                'day' => 'Wednesday',
                'open_hours' => '7:00 AM to 8:00 PM',
            ],
            [
                'day' => 'Thursday',
                'open_hours' => '7:00 AM to 8:00 PM',
            ],
            [
                'day' => 'Friday',
                'open_hours' => '7:00 AM to 8:00 PM',
            ],
            [
                'day' => 'Saturday',
                'open_hours' => '9:00 AM to 5:00 PM',
            ],
            [
                'day' => 'Un we de 3 jour ca fait du bien',
                'open_hours' => 'barbecue',
            ],
        ];
        
        // ajouter l'adresse actuelle du magasin
        // a terme on imagine que c'est récupéré depuis la BDD
        $currentAddress = [
            'street' => '1116 Orchard Street',
            'city' => 'Golden Valley, New York',
        ];


        // on range toutes nos données dans un seul tableau 
        // en précisant des clefs que l'on réutilisera dans nos vues
        $datasForView = [
            'current_address' => $currentAddress,
            'opening_hours' => $weekOpeningHours, 
        ];
        // lancer l'affichage de la bonne vue
        $this->show('store', $datasForView);
    }

    // ici on a une fonction générique réutilisable dans n'importe quel projet
    // ( sous réserve de respecter quelques règles comme le nom du dossier de templates, ...)
    public function show($viewName, $viewData = [])
    {
        // les variables qui existent dans la méthode show
        // sont accessibles dans les templates 
        require_once 'views/_header.tpl.php';
        require_once 'views/' . $viewName . '.tpl.php';
        require_once 'views/_footer.tpl.php';
    }
}