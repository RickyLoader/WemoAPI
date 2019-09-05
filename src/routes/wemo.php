<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Update the brightness of a light bulb
 */
$app->post('/api/brightness/update',function(Request $request, Response $response){
    $device = $request->getParam('device');
    $brightness = $request->getParam('brightness');
    $command = "../../../../bin/wemo light ".$device." on ".$brightness;
    if($device == "all"){
       $command =  "../../../../bin/wemo light office on $brightness && ../../../../bin/wemo light room on $brightness";
    }
    shell_exec($command);
});

/**
 * Return the current status of a light bulb
 */
function getStatus($device, $json){
    $command = "../../../../bin/wemo light $device status";
    $response = json_decode(str_replace("'","\"",rtrim(shell_exec($command))));
    $response->device = $device;

    if(!$json){
        return $response;
    }
    return json_encode($response);
}

/**
 * Get the status of all light bulbs
 */
$app->get('/api/status/all',function(Request $request, Response $response){
    $office = getStatus("office",false)->dim;
    $room = getStatus("room",false)->dim;
    $current = min($office,$room);
    echo $current;
   // echo '['. $office . ',' . $room . ']';
});

/**
 * Turn all light bulbs to a given status
 */
$app->get('/api/{status}/all',function(Request $request, Response $response){
    $status = $request->getAttribute('status');
    $bulb_one = "../../../../bin/wemo light office ".$status;
    $bulb_two = "../../../../bin/wemo light room ".$status;

    if($status = "on"){
        $bulb_one = $bulb_one." 255";
        $bulb_two = $bulb_two." 255";
    }

    $command = $bulb_one." && ".$bulb_two;
    shell_exec($command);
});

/**
 * Toggle a light bulb on or off
 */
$app->get('/api/toggle/{device}',function(Request $request, Response $response){
    $device = $request->getAttribute('device');
    $state = getStatus($device,false)->state;
    $states = ["on","off"];
    $command = "../../../../bin/wemo light ".$device."".$states[$state];
    shell_exec($command);
});

/**
 * Get the current brightness of a light bulb
 */
$app->get('/api/brightness/device/{device}',function(Request $request, Response $response){
    $device = $request->getAttribute('device');
    echo getStatus($device,true)->dim;
});

