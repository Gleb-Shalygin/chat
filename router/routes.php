<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\controllers\RoomController;
use App\models\Room;

$loader = new FilesystemLoader('client/views');
$view = new Environment($loader);

$app = AppFactory::create();
/**
 * This method outputs view for connect
 */
$app->get('/', function (Request $request, Response $response, array $args) use ($view) {
    $body = $view->render('main.twig');
    $response->getBody()->write($body);
    return $response;
});
/**
 * This method connects to the chat
 */
$app->get('/main/chat', function (Request $request, Response $response, array $args) use ($view, $app) {
    $room = new RoomController(new Room());
    $array = $request->getQueryParams(); // Данные, которые приходят с запроса
    $result = $room->connection($array);
    if(isset($result['type']) == 'connection_room' && isset($result['error'])) {
        $body = $view->render('main.twig', ['error' => $result['error']]);
    } else {
        $body = $view->render('chat.twig', [ 'room' => $result['room'], 'name' => $result['name'] ]);
    }
    $response->getBody()->write($body);
    return $response;
});
/**
 * This method creates new room
 */
$app->get('/store/chat', function (Request $request, Response $response, array $args) use ($view, $app) {
    $room = new RoomController(new Room());
    $array = $request->getQueryParams(); // Данные, которые приходят с запроса
    $result = $room->connection($array);
    if(isset($result['type']) == 'store_room' && isset($result['error'])) {
        $body = $view->render('store_room.twig', ['error' => $result['error']]);
    }  else {
        $body = $view->render('chat.twig', [ 'room' => $result['room'], 'name' => $result['name'] ]);
    }
    $response->getBody()->write($body);
    return $response;
});
/**
 * This route outputs a view with for create room
 */
$app->get('/store', function (Request $request, Response $response, array $args) use ($view) {
    $body = $view->render('store_room.twig');
    $response->getBody()->write($body);
    return $response;
});

$app->run();