# Dernière étape ? En fait, non :smiling_imp:

Il nous reste quelques petites choses à voir ensemble aujourd'hui avant de vous lancer sur le challenge !

## Les routes, le routeur, et le dispatcheur

Qu'est-ce que c'est que ça ?
Ce qu'on appelle une route, c'est le **chemin du FrontController jusqu'à la méthode du contrôleur** qui répond à la demande du visiteur de notre site.

Plus précisément, le visiteur veut accéder à une page de notre site, via une URL.
Pour lui répondre, on va devoir déterminer quel chemin prendre (quel contrôleur si on en a plusieurs, et quelle méthode), et **c'est ça la route** !

Dans notre site, on a 3 pages : home, products et store.
Le chemin à emprunter (donc la route) pour atteindre ces pages va être le suivant :

- `index.php` :arrow_right: MainController :arrow_right: méthode home()
- `index.php?page=store` :arrow_right: MainController :arrow_right: méthode store()
- `index.php?page=products` :arrow_right: MainController :arrow_right: méthode products()

Pour l'instant, on a un grand if/else qui nous permet d'instancier le bon contrôleur et appeler la bonne méthode en fonction du paramètre d'URL `page`.
On a que 3 pages, mais imaginez si on en avait 10, 50, ou 500 :scream:
*spoiler : le if/else serait très long* :sweat_smile:

Il faut donc qu'on trouve une meilleure façon de gérer ça :muscle:

### Étape 1 : Tableau de routes, v1

Pour stocker nos routes, on va faire un tableau, qu'on va placer au début de notre FrontController :

```php
$routes = [
    'home' => 'home',
    'products' => 'products',
    'store' => 'store'
];
```

Ce tableau nous sert à faire correspondre la valeur du paramètre d'URL `page` avec la méthode à appeler dans notre MainController.

> Mais à quoi ça sert, nos méthodes ont le même nom que notre paramètre `page` !

:arrow_right: Ce ne sera pas toujours le cas, et on pourrait aussi décider d'appeler nos méthodes autrement ! (homeAction, storeAction et productsAction par exemple)

Pour utiliser ce tableau, il va falloir qu'on modifie notre if/else :

```php
// ce if ne change pas beaucoup : 
if(isset($_GET['page']) && !empty($_GET['page']))
{
    $page = $_GET['page'];
}
else
{
    $page = 'home';
}

// celui-ci un peu plus !!!
if (isset($routes[$page])) 
{
    $controller = new MainController();
    
    // on récupère notre méthode dans notre tableau de routes
    $method = $routes[$page];

    // on appelle cette méthode
    $controller->$method();
}
else
{
    $controller = new MainController();
    $controller->error404();
}
```

### Étape 2 : et si on a plusieurs contrôleurs ?

Pour l'instant, on a fait seulement un seul contrôleur MainController. Dans la réalité, on peut avoir plein de contrôleurs différents pour un même site !

Par exemple, pour gérer les erreurs (404 ou d'autres qu'on verra après), on pourrait tout à fait créer un ErrorController ! *spoiler : c'est ce qu'on va faire !*

- **créer une classe `ErrorController`** dans notre sous-répertoire `controllers`
- **déclarer une méthode `error404()`** dans ce contrôleur, pour gérer l'erreur 404 sur notre site. **Compléter cette méthode** en suivant le modèle de nos méthodes du MainController.
- **même chose, déclarer et compléter une méthode `error401()`**, pour gérer l'erreur 401 (accès à une page interdit sans autorisation).
- **copier/coller la méthode `show`** de notre MainController, ce sera la même !

La méthode `error401()` va nous servir à avertir l'utilisateur qu'il n'a pas le droit d'accéder à une page de notre site sans autorisation, par exemple pour une page d'administration (`index.php?page=admin` dans notre cas) !

Avec notre nouveau contrôleur, le code qu'on a fait ci-dessus ne fonctionne plus :confused: 

### Étape 3 : La v2 du tableau de routes !

Il va donc falloir qu'on fasse une v2 de notre tableau, dans laquelle on va aussi stocker le contrôleur à utiliser :

```php
$routes = [
    'home' => [
        'controller' => 'MainController',
        'method' => 'home'
    ],
    'products' => [
        'controller' => 'MainController',
        'method' => 'products'
    ],
    'store' => [
        'controller' => 'MainController',
        'method' => 'store'
    ],
    'admin' => [
        'controller' => 'ErrorController',
        'method' => 'error401'
    ]
];
```

Grâce à ce tableau, on sait donc quelle méthode appeller, dans quel contrôleur, en fonction du paramètre d'URL `page`.

#### Comment on utilise ça ?

On va toujours vérifier ce que contient notre paramètre d'URL `page`, ça ça ne change pas !
Mais ensuite, en fonction de son contenu, on va instancier le bon contrôleur et appeler la bonne méthode automatiquement :grin:

Ça va donner le code suivant :

```php
// ce if ne change pas beaucoup : 
if(isset($_GET['page']) && !empty($_GET['page']))
{
    $page = $_GET['page'];
}
else
{
    $page = 'home';
}

// celui-ci un peu plus !!!
if (isset($routes[$page])) 
{
    $currentRoute = $routes[$page];
    $controller = new $currentRoute['controller']();
    $method = $currentRoute['method'];

    $controller->$method();
}
else
{
    $controller = new MainController();
    $controller->error404();
}
```

Ne vous inquiétez pas, je vais vous l'expliquer et on va le commenter ensemble :wink:

### Le routeur vs. le dispatcheur

Le routeur, c'est **la partie de notre FrontController qui va déterminer quelle route on doit prendre** pour trouver la bonne page de notre site, celle que veut voir notre visiteur.

Si vous êtes perdus et que vous demandez votre chemin à quelqu'un, cette personne sera votre routeur !

C'est donc cette partie là de notre code :

```php
if (isset($routes[$page])) 
{
    $currentRoute = $routes[$page];
    $controller = new $currentRoute['controller']();
    $method = $currentRoute['method'];
    ...
```

Et enfin le dispatcheur, c'est **la partie de notre FrontController qui va interpréter cette route**, suivre ce chemin, ces directions, afin d'afficher la bonne page à notre visiteur.

Si je reprends l'analogie précédente, une fois que quelqu'un (le routeur) vous a donné les directions à suivre pour atteindre votre destination, le *dispatching* c'est suivre ces directions !

Le code qui joue le rôle de dispatcheur dans notre FrontController, c'est cette unique ligne, juste après notre routeur :

```php
$controller->$method();
```

Pfiou, ça fait quand même beaucoup de notions tout ça ! :cold_sweat: 
Ne vous inquiétez pas, vous allez revoir tout ça pendant le reste de la saison :wink:

## Avoir de belles URL :star_struck: et sécuriser un poil notre site :no_entry:

Vous l'avez peut-être remarqué, sur la plupart des sites internet de nos jours il est rare de croiser `index.php?page=store` dans l'URL !

Exemple, le blog O'clock : [](https://oclock.io/blog)

On voit bien sur ce site qu'il n'y a jamais `index.php` dans l'URL, ni de paramètres !

> C'est bien gentil tout ça, mais comment on fait ?

### URL Rewriting

On va réécrire nos URL à la volée, faire de **l'URL Rewriting** ! Enfin c'est pas vraiment nous qui allons le faire, c'est notre serveur Apache qui va s'en charger :sweat-smile:

Pour dire à Apache de réécrire nos URL, on va créer un fichier à la racine de notre projet : le **.htaccess**.

- **Créer un fichier `.htaccess`** à la racine de votre projet (au même endroit que index.php). **Attention**, le nom du fichier commence par un `.` et n'a pas d'extension.
- **Copier/coller les lignes suivantes** dans ce fichier : 

```apacheconf
RewriteEngine On

# dynamically setup base URI
RewriteCond %{REQUEST_URI}::$1 ^(/.+)/(.*)::\2$
RewriteRule ^(.*) - [E=BASE_URI:%1]

# redirect every request to index.php
# and give the relative URL in "page" GET param
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php?page=/$1 [QSA,L]
```

C'est pas beau comme syntaxe ça, n'est pas ? :joy: Ne vous inquiétez pas, rare sont les gens qui maîtrisent cette syntaxe, et la plupart des développeurs, moi y compris, vont simplement chercher sur Internet la bonne syntaxe à copier/coller :grin:

Le fichier `.htaccess` sert à configurer notre serveur Apache. On peut s'en servir pour faire de l'URL rewriting comme on vient de le faire, mais pas que !

Si vous essayez d'accéder à notre site pour voir le résultat, vous aurez des erreurs :confused: Il faut qu'on modifie notre tableau de routes de la façon suivante : 

```php
$routes = [
    '/' => [
        'controller' => 'MainController',
        'method' => 'home'
    ],
    '/products' => [
        'controller' => 'MainController',
        'method' => 'products'
    ],
    '/store' => [
        'controller' => 'MainController',
        'method' => 'store'
    ],
    '/admin' => [
        'controller' => 'ErrorController',
        'method' => 'error401'
    ]
];
```

Si on fait un `var_dump($_GET['page']`, on peut remarquer qu'avec l'URL rewriting, notre paramètre `page` a maintenant un `/` devant sa valeur !

Il faut donc aussi modifier le premier if/else de la sorte : 

```php
if(isset($_GET['page']) && !empty($_GET['page']))
{
    $page = $_GET['page'];
}
else
{
    $page = '/';
}
```

<details><summary>En bonus, s'il nous reste un peu de temps !</summary>

On peut simplifier drastiquement notre if/else ci-dessus depuis PHP7, en utilisant **l'opérateur de coallescence nulle** : 

```php
$page = $_GET['page'] ?? '/';
```

Cet opérateur nous permet d'attribuer la valeur `/` à notre variable `$page` si `$_GET['page']` est nul ou non-défini. En revanche si `$_GET['page']` est défini, on attribura sa valeur à `$page`.

</details>

### Empêcher l'accès à certains dossiers

Vous vous rappelez, on avait dit que les visiteurs de notre site n'auraient jamais accès à nos vues/templates ! Or là, si on va sur `/views/home.tpl.php` en tapant directement dans la barre d'adresse de notre navigateur, ça fonctionne :confused:

Pour restreindre l'accès à certains dossiers, on va donc de nouveau utiliser un `.htaccess` !

- **Créer un fichier `.htaccess`** dans le dossier `views`.
- **Ajouter l'instruction suivante** dans ce fichier :

```apacheconf
Deny From All
```

Plus simple que la syntaxe du `.htaccess` précédent non ? :joy:

Vérifiez ensuite dans votre navigateur, est-ce que vous pouvez encore accéder à `/views/home.tpl.php` ? Vous pouvez copier ce fichier dans le dossier `controllers`, les visiteurs n'ont pas besoin d'y accéder non plus !

## C'est fini, enfin :tada:

Un petit récap' pour finir ! Aujourd'hui nous avons découvert :

- les pull requests sur Github
- une nouvelle façon de structurer notre code PHP

Pour cette structure, on va utiliser :

- des vues (Views)
- un FrontController, point d'entrée unique
- des routes

Et enfin nous avons vu comment faire pour avoir de belles URL, avec l'URL rewriting !

Avant de vous libérer, je vais vous présenter le challenge de la journée :wink: