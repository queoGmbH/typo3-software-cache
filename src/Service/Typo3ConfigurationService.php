<?php

namespace Queo\Typo3\SoftwareCache\Service;

class Typo3ConfigurationService
{
    /**
     * @return array
     */
    public static function getConfiguredHeaders()
    {
        $additionalTypo3Headers = [];
        foreach ($GLOBALS['TSFE']->config['config']['additionalHeaders.'] as $headerItem) {
            $headerStringSegments = explode(':', $headerItem['header']);
            $additionalTypo3Headers[trim($headerStringSegments[0])] = trim($headerStringSegments[1]);
        }
        
        return $additionalTypo3Headers;
    }    
}
