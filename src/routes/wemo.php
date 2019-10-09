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
    if($device == "all"){
       $command =  "../../../../bin/wemo light office on $user_brightness && ../../../../bin/wemo light room on $user_brightness";
    }
    shell_exec($command);
});

/**
 * Turn all light bulbs to a given status
 */
$app->get('/api/control/status/{status}',function(Request $request, Response $response){
    $status = $request->getAttribute('status');
    $bulb_one = "../../../../bin/wemo light office ".$status;
    $bulb_two = "../../../../bin/wemo light room ".$status;
    $command = $bulb_one." && ".$bulb_two;
    shell_exec($command);
});