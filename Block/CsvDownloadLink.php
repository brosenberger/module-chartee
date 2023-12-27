<?php

namespace BroCode\Chartee\Block;

use BroCode\Chartee\Api\Constants;
use BroCode\Chartee\Api\DownloadLinkTemplateInterface;

use League\Csv\Writer;
use Magento\Backend\Block\Template;

class CsvDownloadLink extends Template implements DownloadLinkTemplateInterface
{
    protected $_template = 'BroCode_Chartee::csv_download_link.phtml';
    /**
     * @var null | array
     */
    protected $data = null;

    /** @var null | string  */
    protected $filename = null;

    public function getDownloadFileType()
    {
        return 'file/csv';
    }

    public function getDownloadFilename()
    {
        return $this->filename;
    }

    public function setDownloadFilenameConfigPath($filenameConfig)
    {
        if ($filenameConfig === null || ($filename = $this->_scopeConfig->getValue($filenameConfig)) === null) {
            $filename = "{date}_" . md5(rand(0, 1000)) . ".csv";
        }
        $this->filename = str_replace('{date}', date('Y-m-d'), $filename);
        return $this;
    }

    public function getDownloadData()
    {
        $writer = Writer::createFromString();
        $first = array_slice($this->data, 0, 1);
        $first = array_shift($first);
        $writer->insertOne(array_keys($first));
        $writer->insertAll($this->data);
        return 'base64,' . base64_encode($writer->toString());
    }

    public function hasDownloadData()
    {
        return !empty($this->data) && !empty($this->filename);
    }

    public function setDownloadData($data)
    {
        $this->data = $data;
        return $this;
    }
}