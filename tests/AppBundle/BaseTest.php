<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\AppBundle;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;

abstract class BaseTest extends WebTestCase
{
    /** @var Client $client */
    private $client;

    public function setUp()
    {
        //Fix for Error 500 "Cannot send session cookie - headers already sent"
        // @see https://stackoverflow.com/questions/16657101/phpunit-cannot-send-session-cookie-headers-already-sent
        @session_start();
        parent::setUp();
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (empty($this->client)) {
            $this->client = static::createClient();
        }

        return $this->client;
    }

    /**
     * @return null|ContainerInterface
     */
    public function getContainer()
    {
        return $this->getClient()->getContainer();
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->getClient()->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @param string $pathname
     * @return File
     */
    public function createImage($pathname)
    {
        $im = imagecreatetruecolor(120, 20);
        $text_color = imagecolorallocate($im, 233, 14, 91);
        imagestring($im, 1, 5, 5, 'Un texte simple', $text_color);
        imagejpeg($im, $pathname);
        imagedestroy($im);

        return new File($pathname);
    }

}