<?php

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/vendor/autoload.php';

// Create App
$app = AppFactory::create();
// $app->setBasePath("public");

// Create Twig
$twig = Twig::create('templates', [
    'debug' => TRUE,
    'cache' => FALSE // 'cache' // FALSE
]);

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

$customErrorHandler = function (
    Psr\Http\Message\ServerRequestInterface $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    $view = Twig::fromRequest($request);

    return $view->render($response, 'pages/404.html')->withStatus(404);
};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(Slim\Exception\HttpNotFoundException::class, $customErrorHandler);

$app->get('/', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'pages/home.html');
});

$app->get('/about', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'pages/about.html');
});

// $app->get('/products', function ($request, $response, $args) {
//     $view = Twig::fromRequest($request);
//     return $view->render($response, 'pages/products.html');
// });

// $app->get('/product/{product}', function ($request, $response, $args) {
//     $view = Twig::fromRequest($request);
//     return $view->render($response, 'pages/products/product.html', [
//         'product' => $args['product']
//     ]);
// })->setName('product');

// $app->get('/contact', function ($request, $response, $args) {
//     $view = Twig::fromRequest($request);
//     return $view->render($response, 'pages/contact.html');
// });

// $app->get('/signup', function ($request, $response, $args) {
//     $view = Twig::fromRequest($request);
//     return $view->render($response, 'pages/signup.html');
// });

// $app->get('/signup/{type}', function ($request, $response, $args) {
//     $view = Twig::fromRequest($request);
//     return $view->render($response, 'pages/signup.html', [
//         'type' => $args['type']
//     ]);
// })->setName('type');

// Run app
$app->run();

?>