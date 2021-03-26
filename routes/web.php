<?php

$router->post('/certificate', 'CertificateController@create');
$router->post('/certificate/bulk', 'CertificateController@createMany');
