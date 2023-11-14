<?php

namespace BroCode\Chartee\Api;

interface DownloadLinkTemplateInterface
{
    /**
     * @return string
     */
    public function getDownloadFilename();

    /**
     * @param $filenameConfig
     * @return DownloadLinkTemplateInterface
     */
    public function setDownloadFilenameConfigPath($filenameConfig);

    /**
     * @return string
     */
    public function getDownloadFileType();

    /**
     * @return string
     */
    public function getDownloadData();

    /**
     * @param array $data
     * @return DownloadLinkTemplateInterface
     */
    public function setDownloadData($data);

    /**
     * @return bool
     */
    public function hasDownloadData();

    public function toHtml();
}