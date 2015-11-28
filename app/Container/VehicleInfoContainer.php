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

    /** @var bool */
    private $isStolen;

    /**
     * @param string $brand
     * @param int $productionYear
     * @param float $engineCC
     * @param int $rdwClass
     * @param bool $isStolen
     */
    public function __construct($brand, $productionYear, $engineCC, $rdwClass, $isStolen)
    {
        $this->brand = $brand;
        $this->productionYear = $productionYear;
        $this->engineCC = $engineCC;
        $this->rdwClass = $rdwClass;
        $this->isStolen = $isStolen;
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

    /**
     * @return bool
     */
    public function isStolen()
    {
        return $this->isStolen;
    }
}
