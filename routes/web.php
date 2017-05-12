<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

if ( ! session_id() ) session_start();


$app->get('/', function() use ($app) {
    $md5 = md5( session_id() );
    if (! isset($_SESSION['mode'])) {
        $_SESSION['mode'] = 'participant';
    }

    if (! isset($_SESSION['nickname'])) {
        $faker = Faker\Factory::create('pt_BR');

        do {
            $nickname = $faker->userName;
        } while (\Cache::has($nickname));

        \Cache::forever($nickname, '1');
        $_SESSION['nickname'] = $nickname;
    }

    $mode = $_SESSION['mode'];
    

    if (! \Cache::has($md5)) {
        \Cache::forever($md5, [
            'mode' => $mode,
            'nickname' => $_SESSION['nickname'],
        ]);
    }
    
    //Caso seja rodado no localhost:8000, por exemplo
    //retira os : do nome do servidor
    $host = $_SERVER['HTTP_HOST'];
    $hostExplode = explode(':', $host);
    $host = $hostExplode[0];

    $data = [
        'host' => $_SERVER['HTTP_HOST'],
        'mode' => $mode,
        'websocketsAddress' => $host . ':777?session=' . $md5
    ];

    return view('home', $data);
});
$app->get('/logout', function() use ($app) {
    
      session_destroy();
      \Cache::flush();
       return redirect('/');
});
$app->get('/presenter', function() use ($app) {
    $host = $_SERVER['HTTP_HOST'];
    $hostExplode = explode(':', $host);
    $host = $hostExplode[0];


    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="Presenter Mode"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Você não é o apresentador?';
        exit;
    } else {
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        //O usuário é 'admin' e a senha é 'phprules'?
        if ('admin' === $username && '27da247ac3132070bfe88338846955adf41955fc' === sha1($password)) {
            $md5 = md5( session_id() );

            //Grava na sessão que o visitante é o apresentador
           $_SESSION['mode'] = 'presenter';

            if (\Cache::has($md5)) {
                $cache = \Cache::get('$md5');
                $cache['mode'] = 'presenter';
                $cache['nickname'] = 'Bob';

                \Cache::forever($md5, $cache);
            }

            
            //Redireciona de volta para a apresentação
            return redirect('/');
        } else {
            header('HTTP/1.0 401 Unauthorized');
            echo 'Aqui não tem biscoito!';
            exit;
        }
    }
});
