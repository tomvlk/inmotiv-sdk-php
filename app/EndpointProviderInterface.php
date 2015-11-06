<?php
namespace InMotivClient;

interface EndpointProviderInterface
{
    /**
     * @return string
     */
    public function getDVS();

    /**
     * @return string
     */
    public function getVTS();
}
