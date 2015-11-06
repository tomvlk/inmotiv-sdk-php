<?php
namespace InMotivClient;

class ProductionEndpointProvider implements EndpointProviderInterface
{
    public function getDVS()
    {
        return 'https://services.rdc.nl/dvs/1.0/wsdl';
    }

    public function getVTS()
    {
        return 'https://services.rdc.nl/voertuigscan/2.0/wsdl';
    }
}
