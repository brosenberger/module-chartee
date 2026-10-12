<?php

namespace BroCode\Chartee\Model\DataBuilder;

use BroCode\Chartee\Api\ChartDataBuilderInterface;
use Magento\Framework\DataObject;

/**
 * @method string getLabel()
 * @method array getDataValues()
 * @method string|array|null getBackgroundColor()
 * @method setBackgroundColor(string|array|null $backgroundColor)
 * @method string|array|null getBorderColor()
 * @method setBorderColor(string|array|null $borderColor)
 */
class DataSetBuilder extends DataObject
{
    /**
     * @var ChartDataBuilderInterface
     */
    private $builder;

    /**
     * @param ChartDataBuilderInterface $builder
     * @param array $data
     */
    public function __construct(ChartDataBuilderInterface $builder, array $data = [])
    {
        parent::__construct($data);
        $this->builder = $builder;
    }

    public function setLabel($label)
    {
        $this->setData('label', $label);
        return $this;
    }

    public function setDataValues(...$data)
    {
        $this->setData('data', $data);
        return $this;
    }

    public function setBackgroundColors(...$colors)
    {
        $this->setData('backgroundColor', $colors);
        return $this;
    }

    public function setBorderColors(...$colors)
    {
        $this->setData('borderColor', $colors);
        return $this;
    }

    public function setBorderWidth($width)
    {
        $this->setData('borderWidth', $width);
        return $this;
    }

    public function build()
    {
        return $this->builder->addDataSet($this->toArray());
    }

    /**
     * Magic accessors keep the camelCase chart.js property names (setBorderColor() -> borderColor) instead of
     * Magento's snake_case. Handled here instead of overriding _underscore(), whose input differs between
     * Magento versions and whose cache is shared with every other DataObject.
     */
    public function __call($method, $args)
    {
        $key = lcfirst(substr($method, 3));
        switch (substr($method, 0, 3)) {
            case 'get':
                return $this->getData($key);
            case 'set':
                return $this->setData($key, $args[0] ?? null);
            case 'uns':
                return $this->unsetData($key);
            case 'has':
                return isset($this->_data[$key]);
        }
        return parent::__call($method, $args);
    }
}