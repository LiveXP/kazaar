<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\AppBundle\Controller;

use tests\AppBundle\BaseTest;

class HistoryControllerTest extends BaseTest
{
    public function testIndexHistory()
    {
        $this->getClient()->request("GET", "/historique");
        $this->assertEquals($this->getClient()->getResponse()->getStatusCode(), 200);
    }

    public function testAjaxNames()
    {
        $date = (new \DateTime())->format("d/m/Y");
        $this->getClient()->request("GET", "/search_names", ['date' => $date]);
        $this->assertEquals($this->getClient()->getResponse()->getStatusCode(), 200);
    }

}