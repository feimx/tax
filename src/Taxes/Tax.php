<?php

namespace FeiMx\Tax\Taxes;

class Tax
{
    protected $retention = false;

    /**
     * Create new instance of taxes.
     *
     * @param bool $retention
     */
    public function __construct(bool $retention = false)
    {
        $this->retention = $retention;
    }

    /**
     * Get amount percentage for defined tax.
     *
     * @param string $type Type of the tax amount
     *
     * @return int $percentage Amount of the tax
     */
    public function percentage($type = 'default'): float
    {
        $taxName = $this->getTaxName();

        $type = $this->parseTypePercentage($type);

        return $percentage = config(
            "tax.taxes.{$taxName}.{$type}",
            config(
                "tax.taxes.{$taxName}.".config('tax.fallback')
            )
        );
    }

    /**
     * Convert class to qualified Tax name.
     *
     * @return string Tax name in lower case
     */
    protected function getTaxName(): string
    {
        $className = explode('\\', get_called_class());

        return strtolower(array_pop($className));
    }

    /**
     * Return retention instead of a given type if retention flag is activated.
     *
     * @param string $type Type of the tax amount
     *
     * @return string Type of the tax amount
     */
    protected function parseTypePercentage($type = 'default')
    {
        return $this->retention ? 'retention' : $type;
    }

    public function __get($property)
    {
        if ('name' == $property) {
            return $this->getTaxName();
        }

        throw new \Exception('Property not exists');
    }

    public function __toString()
    {
        return $this->getTaxName();
    }
}
