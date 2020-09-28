<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';


$container = new Container();
AppFactory::setContainer($container);

$container->set('view', function() {
    return Twig::create(__DIR__ . '/../templates',
        ['cache' => __DIR__ . '/../cache']);
});

$app = AppFactory::create();

// Add Twig-View Middleware
$app->add(TwigMiddleware::createFromContainer($app));

$app->get('/', function ($request, $response, $args) {
    $turbines = range(0, 99);
    foreach ($turbines as $k => $turbine) {
        $turbines[$k] = [];
        $turbines[$k]['id'] = $k;
        $turbines[$k]['errors'] = [];
        if($k === 0) { //division by 0
            continue;
        }
        if ($k % 3 == 0) {
            //echo "Has error!";
            $turbines[$k]['errors'][] = ['message' => 'coating damage'];
        }
        if ($k %5 == 0) {
            $turbines[$k]['errors'][] = ['message' => 'lightning strike'];
        }
    }
    return $this->get('view')->render($response, 'index.twig', [
        'turbines' => $turbines
    ]);
});

$app->run();