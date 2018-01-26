<?php

namespace Queo\Typo3\SoftwareCache\Request\Rule;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\Request;

final class IpRule implements RequestRule
{
    /**
     * @var array
     */
    private $ipList;

    /**
     * @param array $ips
     */
    public function __construct(array $ips)
    {
        $this->verifyIps($ips);

        $this->ipList = $ips;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function validate(Request $request)
    {
        return $this->isInIpList($request->getClientIp());
    }

    /**
     * @param array $ips
     * @throws \Assert\InvalidArgumentException
     *
     * @return void
     */
    private function verifyIps(array $ips)
    {
        foreach ($ips as $ip)
        {
            Assertion::ip($ip);
        }
    }

    /**
     * @param $clientIp
     *
     * @return bool
     */
    private function isInIpList($clientIp)
    {
        return in_array($clientIp, $this->ipList);
    }
}