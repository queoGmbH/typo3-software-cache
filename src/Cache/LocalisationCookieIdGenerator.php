<?php

namespace Queo\Typo3\SoftwareCache\Cache;

use Symfony\Component\HttpFoundation\Request;

class LocalisationCookieIdGenerator implements IdGenerator
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function generate(Request $request)
    {
        $data = $this->readLocalisationCookie($request);
        $location = $data['location'];
        $client = $data['client'];
        if (is_null($location) && is_null($client)) {
            return '';
        } else {
            if (is_null($location) && !is_null($client)) {
                reset($client);
                $clientId = key($client);
                return sprintf('null_%s', $clientId);
            }
        }

        $id = sprintf(
            '%s_%s_%s',
            $location['zip'],
            array_shift($location['city']),
            array_shift($location['region'])
        );

        return $id;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    private function readLocalisationCookie(Request $request)
    {
        $data = json_decode($request->cookies->get('localisation'), true);

        return $data;
    }
}