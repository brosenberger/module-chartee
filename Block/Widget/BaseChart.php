<?php

namespace BroCode\Chartee\Block\Widget;

use BroCode\Chartee\Api\ChartDataViewModelInterface;
use BroCode\Chartee\Api\Exception\CharteeException;
use BroCode\Chartee\Block\ViewModel\BaseChartDataViewModelFactory;
use BroCode\Chartee\Model\ChartDataService;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\TestFramework\Serialize\Serializer;
use Magento\Widget\Block\BlockInterface;

class BaseChart extends Template implements BlockInterface
{
    protected $_template = "BroCode_Chartee::widget/base_chart.phtml";
    /**
     * @var ChartDataViewModelInterface
     */
    private $viewModel;
    /**
     * @var Json
     */
    private $serializer;
    /**
     * @var ChartDataService
     */
    private $chartDataService;

    /**
     * @param ChartDataService $chartDataService
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Json $serializer,
        ChartDataService $chartDataService,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->serializer = $serializer;
        $this->chartDataService = $chartDataService;
    }

    public function setViewModel($viewModel)
    {
        $this->viewModel = $viewModel;
    }
    public function getChartDataViewModel()
    {
        return $this->viewModel;
    }

    public function getConfiguration()
    {
        if ($this->viewModel !== null) {
            return $this->viewModel->getConfigurationJson();
        }

        if ($this->getData('chartDataBuilder')) {
            return $this->serializer->serialize(
                $this->getChartData()->getConfiguration()
            );
        }

        throw new CharteeException('No chart data configured for this chart');
    }

    /**
     * @return \BroCode\Chartee\Api\Data\ChartDataConfigurationInterface|null
     */
    public function getChartData()
    {
        if ($this->getData('chartDataBuilder')) {
            if (!$this->getData('chartData')) {
                $this->setData('chartData', $this->chartDataService->getChartData($this->getData('chartDataBuilder')));
            }
        }

        return $this->getData('chartData');
    }
}