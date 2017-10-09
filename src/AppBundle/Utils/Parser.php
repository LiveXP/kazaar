<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Utils;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Parser used to transform text
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class Parser
{
    /** @var \Parsedown $parser */
    private $parser;

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        $this->parser = new \Parsedown();
    }

    /**
     * Parse the Markdown to HTML
     *
     * @param string $text
     *
     * @return string
     */
    public function toHtml($text)
    {
        $html = $this->parser->text($text);

        return $html;
    }

    /**
     * Format a string|array of string to mailto: html tag
     * (ex: test@email.com to <a href="mailto:test@email.com">test@email.com</a>)
     *
     * @param $email
     *
     * @return array|string
     */
    public function mailTo($email)
    {
        $model = "<a href='mailto:%s'>%s</a>";
        if (is_array($email)) {
            $mails = [];
            foreach ($email as $mail) {
                $mails[] = sprintf($model, $mail, $mail);
            }

            return $mails;
        }

        return sprintf($model, $email, $email);
    }

    /**
     * Get the Base64 of a File
     *
     * @param File $file
     *
     * @return string
     */
    public function getBase64(File $file)
    {
        $fullPath = $file->getPathname();
        if (!$file->isFile() || 0 !== strpos($file->getMimeType(), 'image/')) {
            return false;
        }
        $binary = file_get_contents($fullPath);

        return sprintf('data:image/%s;base64,%s', $file->guessExtension(), base64_encode($binary));
    }
}
