<?php
use Dotenv\Dotenv;
use InMotivClient\InMotivClient;
use InMotivClient\ProductionEndpointProvider;
use InMotivClient\XmlBuilder;

require 'vendor/autoload.php';

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$endpointProvider = new ProductionEndpointProvider();
$xmlBuilder = new XmlBuilder();

$client = new InMotivClient(
    $endpointProvider,
    $xmlBuilder,
    getenv('INMOTIV_CLIENT_NUMBER'),
    getenv('INMOTIV_USERNAME'),
    getenv('INMOTIV_PASSWORD'),
    true
);

$result = $client->isDriverLicenceValid(
    getenv('DRIVER_LICENCE_NUMBER'),
    getenv('BIRTHDAY_YEAR'),
    getenv('BIRTHDAY_MONTH'),
    getenv('BIRTHDAY_DAY')
);

var_dump($result);
