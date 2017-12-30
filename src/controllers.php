<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage')
;

$app->get('/tasks', function () use ($app) {
    $sql = "SELECT * FROM tasks";
    $tasks = $app['db']->fetchAll($sql);

    return  $app->json($tasks);
});

$app->post('/tasks', function (Request $request) {
    $name = $request->get('name');
    $desc = $request->get('description');
    $date = $request->get('date');

    $response = ["name" => $name, "description" => $desc, "date" => $date];

    $sql = ("INSERT INTO tasks(name, description, date) VALUES(?, ?, ?)");
    $app['db']->executeInsert($sql, array($name, $desc, $date));

    return $app->json($response);
});

$app->delete('/tasks/{id}', function ($id) {
    $sql = ("DELETE FROM tasks WHERE id = ?");
    $app['db']->executeDelete($sql, array($id));

    return $app->json($response);
});

$app->put('/tasks', function (Request $request) {
    $name = $request->get('name');
    $desc = $request->get('description');
    $date = $request->get('date');

    $response = ["name" => $name, "description" => $desc, "date" => $date];

    $sql = ("INSERT INTO tasks(name, description, date) VALUES(?, ?, ?)");
    $app['db']->executeInsert($sql, array($name, $desc, $date));

    return $app->json($response);
});

$app->get('/donetasks', function () use ($app) {
    $sql = "SELECT * FROM donetasks";
    $tasks = $app['db']->fetchAll($sql);

    return  $app->json($tasks);
});

$app->post('/donetasks', function (Request $request) {
    $name = $request->get('name');
    $desc = $request->get('description');
    $date = $request->get('date');

    $response = ["name" => $name, "description" => $desc, "date" => $date];

    $sql = ("INSERT INTO donetasks(name, description, date) VALUES(?, ?, ?)");
    $app['db']->executeInsert($sql, array($name, $desc, $date));

    return $app->json($response);
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
