<?php 

require 'controllers/MainController.php';

// récupération de la page demandée par l'utilisateur
$requestedPage = filter_input(INPUT_GET, 'page');

// gérer le cas ou aucune page n'est fournie ( $requestedPage vaut nul )
// dans ce cas on affiche la page d'accueil
if ($requestedPage === null)
{
    $requestedPage = 'home';
}

// TODO Que faire si la page demandée est inconnue
// Liste des routes de notre application
$routes = [
    'home' => [
        'controller' => 'MainController',
        'action' => 'homeAction',
    ],
    'about' => [
        'controller' => 'MainController',
        'action' => 'aboutAction',
    ],
    'products' => [
        'controller' => 'MainController',
        'action' => 'productsAction',
    ],
    'store' => [
        'controller' => 'MainController',
        'action' => 'storeAction',
    ],
];
// si la page demandée est inconnue 
// if (in_array($requestedPage, $routes) === false)

if (! array_key_exists($requestedPage, $routes))
// if (! in_array($requestedPage, array_keys($routes)))
{
    // alors on affiche un message d'erreur
    // todo changer le code http de réponse à 404
    die('page non trouvée');
}
// sinon on laisse le traitement normal


// à partir de cette ligne on est sur que la page demandée existe dans notre application
// sinoon on serait arriver sur le die

// ROUTING / ROUTAGE
// récupération du nom de la classe à instancier
$controllerName = $routes[$requestedPage]['controller'];
// récupération du nom de la méthode à exécuter
$actionName = $routes[$requestedPage]['action'];


// index.php?page=store
// récupérer le nom de la page
// pour inclure le bon template


// show($requestedPage);

// DISPATCH 
// instancier un objet de la classe MainController
$controller = new $controllerName();
// le nom de la méthode à exécuter est dans la variable $requestedPage
// on peut utiliser la variable pour appeler la bonne méthode ! 
$controller->$actionName();

// $controller->home();