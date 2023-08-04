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

    public function build()
    {
        return $this->builder->addDataSet($this->toArray());
    }

    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }
        /*
         *  CHANGE: adaption to avoid snake-casing of properties as this would generate wrong variables for
         *          the chart.js settings. Example: $this->setBorderColor() -> borderColor instead of originally border_color
         */
        // Original Line: strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $name), '_'));
        $result = lcfirst($name);
        self::$_underscoreCache[$name] = $result;
        return $result;
    }
}