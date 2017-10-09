<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig\Extension;

use AppBundle\Entity\Image;
use AppBundle\Entity\MorningCheck;
use AppBundle\Utils\Parser;
use AppBundle\Utils\Worker;

/**
 * Class AppExtension
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class AppExtension extends \Twig_Extension
{
    /**
     * The directory where the Image are uploaded
     *
     * @var string $path
     */
    private $path;

    /**
     * The parser service used to transform text
     *
     * @var Parser $parser
     */
    private $parser;

    /**
     * The worker service is used to treat some MorningCheck informations
     *
     * @var Worker $worker
     */
    private $worker;

    /**
     * AppExtension constructor.
     *
     * @param Worker $worker
     * @param Parser $parser
     * @param string $path
     */
     public function __construct(Worker $worker, Parser $parser, $path)
    {
        $this->path = $path;
        $this->parser = $parser;
        $this->worker = $worker;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('base64', [$this, 'getBase64']),
            new \Twig_SimpleFilter('image', [$this, 'getImage']),
            new \Twig_SimpleFilter('md2html', [$this, 'markdownToHtml'], ['is_safe' => ['html'], 'pre_escape' => 'html']),
            new \Twig_SimpleFilter('mail_to', [$this, 'mailto']),
            new \Twig_SimpleFilter('color_fixer', [$this, 'colorFixer']),
            new \Twig_SimpleFilter('mail_title', [$this, 'mailTitle']),
        ];
    }

    /**
     * Transform and return an image to a base64 string
     *
     * @param Image $image
     *
     * @return string
     */
    public function getBase64(Image $image)
    {
        return $this->parser->getBase64($image->getFile());
    }

    /**
     * Return the image ful path to use in html (<img src="..."/>)
     *
     * @param Image $image
     *
     * @return string
     */
    public function getImage(Image $image)
    {
        return $this->path.$image->getPath();
    }

    /**
     * Parse the Markdown to HTML
     *
     * @param string $content
     *
     * @return string
     */
    public function markdownToHtml($content)
    {
        return $this->parser->toHtml($content);
    }

    /**
     * Format a string|array of string to mailto: html tag

     * @param $content
     *
     * @return string
     */
    public function mailto($content)
    {
        return $this->parser->mailTo($content);
    }

    /**
     * Transform color class/name into color code to use in the mail template
     *
     * @param string $color
     *
     * @return string
     */
    public function colorFixer($color)
    {
        $data = [
            'danger' => '#a94442',
            'success' => '#3c763d',
            'warning' => '#8a6d3b',
            'info' => '#31708f',
            'primary' => '#337ab7',
            'muted' => '#777'
        ];

        return isset($data[$color]) ? $data[$color] : $color;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_extension';
    }

    /**
     * Generate a formated string for the mail title with the $date parameter of the current date if null
     *
     * @param MorningCheck $morningCheck
     * @param \DateTime|null $date
     *
     * @return string
     */
    public function mailTitle(MorningCheck $morningCheck, \DateTime $date = null)
    {
        return $this->worker->generateMailTitle($morningCheck, $date);
    }
}