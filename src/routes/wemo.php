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
 * Turn all light bulbs to a given status
 */
$app->get('/api/control/status/{status}',function(Request $request, Response $response){
    $status = $request->getAttribute('status');
    $command = "../../../../bin/wemo light all ".$status;
    echo shell_exec($command);
});

/**
 * Activate a bulb preset
 */
$app->get('/api/control/preset/{preset}',function(Request $request, Response $response){
    $preset = $request->getAttribute('preset');
    if($preset == "movie"){
        $command = "../../../../bin/wemo light office off && ../../../../bin/wemo light room on 1";
    }
    echo shell_exec($command);
});