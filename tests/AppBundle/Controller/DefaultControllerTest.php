<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;
use tests\AppBundle\BaseTest;

class DefaultControllerTest extends BaseTest
{
    public function testMariaDBConnexion()
    {
        try {
            $this->getManager()->getConnection()->connect();
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function testHomepage()
    {
        $crawler = $this->getClient()->request("GET", "/");
        $this->assertEquals($this->getClient()->getResponse()->getStatusCode(), 200);
        //Navbar title
        $this->assertGreaterThan(0, $crawler->filter('h3')->count());
        //Homepage link list
        $this->assertGreaterThan(0, $crawler->filter('.container .row.marketing h4')->count());
    }

    public function testCheckingPage()
    {
        $crawler = $this->getClient()->request("GET", "/");
        //Commencer/Reprendre page
        $link = $crawler->filter(".row.marketing h4 a")->first()->link();
        $this->getClient()->click($link);
        //Redirecting
        $this->assertEquals($this->getClient()->getResponse()->getStatusCode(), 302);
        //Redirected
        $crawler = $this->getClient()->followRedirect();

        $this->assertEquals($this->getClient()->getResponse()->getStatusCode(), 200);

        //H1 title
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
        $sidebar = trim($crawler->filter(".list-group-item.disabled")->text());
        $this->assertEquals($sidebar, trim($crawler->filter('h1')->text()));

        $this->assertGreaterThan(0, $crawler->filter('form[name="appbundle_checking"]')->count());

        //Test form
        $form = $crawler->filter('form[name="appbundle_checking"]')->form([], "POST");
        $values = $this->getFormValues();
        $form->setValues($values);
        $this->getClient()->submit($form);

        $checking = $this->getManager()->getRepository("AppBundle:Checking")
            ->findOneBy(['internalComment' => $values['appbundle_checking[internalComment]']]);

        $this->assertGreaterThan(0, $checking->getImages()->count());
    }

    public function testStartFromModel()
    {
        $this->getClient()->followRedirects();

        $mcm = $this->getManager()->getRepository("AppBundle:MorningCheckModel")->findOneBy(['name' => '[CORP] Enterprise3']);
        //Fill all checkings for this morning check (3)

        for ($i = 0; $i < $mcm->getCheckingModels()->count(); $i++) {
            //Test form
            $crawler = $this->getClient()->request("GET", sprintf("/modele/%s", $mcm->getId()));
            $form = $crawler->filter('form[name="appbundle_checking"]')->form([], "POST");
            $values = $this->getFormValues();
            $form->setValues($values);
            $this->getClient()->submit($form);
        }


        $this->getClient()->followRedirects(false);
        $this->getClient()->request("GET", sprintf("/modele/%s", $mcm->getId()));
        $crawler = $this->getClient()->followRedirect();
        $this->assertContains("/final", $this->getClient()->getRequest()->getRequestUri());
        $link = $crawler->filter("#send-button")->link();

        // Enable the profiler for the next request (it does nothing if the profiler is not available)
        $this->getClient()->enableProfiler();
        $this->getClient()->click($link);
        //TEST mail
        /** @var MessageDataCollector $mailCollector */
        $mailCollector = $this->getClient()->getProfile()->getCollector('swiftmailer');


        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        /** @var \Swift_Message $message */
        $message = $collectedMessages[0];


        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertContains('- Morning Check ', $message->getSubject());
    }

    private function getFormValues()
    {
        $path = sys_get_temp_dir() . "/temp_logo.png";
        $file = $this->createImage($path);

        return [
            'appbundle_checking[internalComment]' => "Test de commentaire interne",
            "appbundle_checking[status]" => 1,
            "appbundle_checking[comment]" => "# Test de commentaire",
            "image[0]" => $this->getContainer()->get('app.parser')->getBase64($file)
        ];
    }

    public function testAdminAccess()
    {
        $this->getClient()->request("GET", "/admin/dashboard");
        $this->assertEquals($this->getClient()->getResponse()->getStatusCode(), 200);
    }

}
