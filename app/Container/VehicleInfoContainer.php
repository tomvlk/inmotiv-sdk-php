<?php
namespace InMotivClient\Container;

class VehicleInfoContainer
{
    const CLASS_MOTORCYCLE = 12;
    const CLASS_MOTORCYCLE_WITH_SIDECAR = 13;

    /** @var string */
    private $brand;

    /** @var int */
    private $productionYear;

    /** @var float */
    private $engineCC;

    /**
     * @param string $brand
     * @param int $productionYear
     * @param float $engineCC
     * @param int $rdwClass
     */
    public function __construct($brand, $productionYear, $engineCC, $rdwClass)
    {
        $this->brand = $brand;
        $this->productionYear = $productionYear;
        $this->engineCC = $engineCC;
        $this->rdwClass = $rdwClass;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @return int
     */
    public function getProductionYear()
    {
        return $this->productionYear;
    }

    /**
     * @return float
     */
    public function getEngineCC()
    {
        return $this->engineCC;
    }

    /**
     * @return bool
     */
    public function isMotorcycle()
    {
        return $this->rdwClass === self::CLASS_MOTORCYCLE || $this->rdwClass === self::CLASS_MOTORCYCLE_WITH_SIDECAR;
    }
}
