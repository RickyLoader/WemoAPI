<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Update the brightness of a light bulb
 */
$app->post('/api/control/brightness/update',function(Request $request, Response $response){
    $device = $request->getParam('device');
    $user_brightness = $request->getParam('brightness');
    $command = "../../../../bin/wemo light ".$device." on ".$user_brightness;
    shell_exec($command);
});

/**
 * Turn bulb to a given status
 */
$app->get('/api/control/status/{room}/{status}',function(Request $request, Response $response){
    $status = $request->getAttribute('status');
    $room = $request->getAttribute('room');

    $command = "../../../../bin/wemo light ".$room." ".$status;
    echo shell_exec($command);
});
