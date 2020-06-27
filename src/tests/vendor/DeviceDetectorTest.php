<?php

namespace App\Tests\vendor;

use DeviceDetector\DeviceDetector;
use PHPUnit\Framework\TestCase;

class DeviceDetectorTest extends TestCase
{
    /**
     * @param $userAgent
     * @param $browserName
     *
     * @dataProvider provideUserAgentData
     */
    public function testUserAgent($userAgent, $browserName)
    {
        $browser = new DeviceDetector($userAgent);
        $browser->parse();
        $this->assertEquals(
            $browserName,
            $browser->getClient('name')
        );
    }

    public function provideUserAgentData()
    {
        return [
            [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36',
                'Chrome',
            ],
            [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36 OPR/68.0.3618.173',
                'Opera',
            ],
            [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:69.0) Gecko/20100101 Firefox/69.0',
                'Firefox',
            ],
            [
                'Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko',
                'Internet Explorer',
            ],
            [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 Edg/83.0.478.56',
                'Microsoft Edge',
            ],
        ];
    }
}
