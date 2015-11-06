<?php
namespace InMotivClient;

class SandboxEndpointProvider implements EndpointProviderInterface
{
    public function getDVS()
    {
        return 'https://acc-services.rdc.nl/dvs/1.0/acc/wsdl';
    }

    public function getVTS()
    {
        return 'https://acc-services.rdc.nl/voertuigscan/2.0/acc/wsdl';
    }
}
