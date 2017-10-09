<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\AppBundle\Utils;

use Symfony\Component\HttpFoundation\File\File;
use tests\AppBundle\BaseTest;

/**
 * Class ParserTest
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class ParserTest extends BaseTest
{
    public function testMailTo()
    {
        $emails = ['fake@fake.fr', 'fake2@fake.fr'];
        $expected = ['<a href=\'mailto:fake@fake.fr\'>fake@fake.fr</a>', '<a href=\'mailto:fake2@fake.fr\'>fake2@fake.fr</a>'];
        $results = $this->getContainer()->get('app.parser')->mailTo($emails);

        //Multiple
        foreach ($results as $key => $result) {
            $this->assertEquals($expected[$key], $result);
        }

        //Unique
        $expected = "<a href='mailto:fake3@fake.fr'>fake3@fake.fr</a>";
        $result = $this->getContainer()->get('app.parser')->mailTo("fake3@fake.fr");
        $this->assertEquals($expected, $result);
    }

    public function testToHtml()
    {
        $text = "# H1";
        $expected = "<h1>H1</h1>";
        $result = $this->getContainer()->get("app.parser")->toHtml($text);
        $this->assertEquals($expected, $result);
    }

    public function testGetBase64()
    {
        $path = sys_get_temp_dir()."/temp_logo_b64.png";
        $file = $this->createImage($path);
        $result = $this->getContainer()->get('app.parser')->getBase64($file);
        $this->assertContains("base64", $result);

        $path = sys_get_temp_dir()."/temp_logo_b64.txt";
        file_put_contents($path, "Test");
        $file = new File($path);
        $result = $this->getContainer()->get('app.parser')->getBase64($file);
        $this->assertFalse($result);
    }

}