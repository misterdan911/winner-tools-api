<?php

$router->post('/getWinner', 'WinnerController@getWinner');
$router->post('/getHistorySuccessCharging', 'HistorySuccessChargingController@getHistorySuccessCharging');
$router->post('/resetPoint', 'ResetPointController@resetPoint');

