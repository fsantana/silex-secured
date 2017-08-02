<?php

// web/index.php
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});


$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render(
        'login.twig',
        [
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username')
        ]
    );
})->bind('login');

$app->get('/logout', function(Request $request) use ($app) {
    return $app->redirect($app['url_generator']->generate('login'));
})->bind('logout');


$app->get('/account', function () use ($app) {
    return $app['twig']->render('account.twig', [
        'content' => ($app['security.authorization_checker']->isGranted('ROLE_USER') ? 'logged in' : 'not logged in'),

    ]);
    $user = $app['session']->get('user');


    return "Welcome {$user['username']}!";
});


$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.default_encoder' => function () {
        return new PlaintextPasswordEncoder();
    },
    'security.firewalls' => [
        'login' => array(
            'pattern' => '^/login$',
        ),
        'secured' => array(
            'pattern' => '^.*$',
            'form' => [
                'login_path' => '/login',
                'logout' => [
                    'logout_path' => '/logout',
                    'invalidate_session' => true
                ],
                'default_target_path' => '/account',
                'check_path' => 'login_check'
            ],
            'users' => new ExtratoLista\Auth\UserProvider(),
        ),
    ]
));

$app->register(new \Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/resources/template',
));
$app->run();