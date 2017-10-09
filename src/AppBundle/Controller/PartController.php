<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\MorningCheck;
use AppBundle\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Part Controller
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class PartController extends Controller
{
    /**
     * @return Response
     */
    public function globalProgressAction()
    {
        $done = count($this->getDoctrine()->getRepository("AppBundle:MorningCheck")->findClosed());
        $total = count($this->getDoctrine()->getRepository("AppBundle:MorningCheckModel")->findAll());
        $progress = $this->get('app.worker')->percentage($done, $total);

        return $this->render("AppBundle:Part:progress.html.twig", ['progress' => $progress]);
    }

    /**
     * @param MorningCheck $morningCheck the current MorningCheck
     *
     * @return Response
     */
    public function morningCheckProgressAction(MorningCheck $morningCheck)
    {
        $checkingList = $this->getDoctrine()->getRepository("AppBundle:Checking")
            ->findByMorningCheckAndWithStatus($morningCheck);

        $done = count($checkingList);
        $total = $morningCheck->getCheckings()->count();
        $progress = $this->get('app.worker')->percentage($done, $total);

        return $this->render("AppBundle:Part:progress.html.twig", ['progress' => $progress]);
    }

    /**
     * @param MorningCheck $morningCheck the current MorningCheck
     * @param int $current_checking_id the current checking id (optional)
     *
     * @return Response
     */
    public function sidebarAction(MorningCheck $morningCheck, $current_checking_id = null)
    {
        $data = $this->get('app.worker')->groupCheckingByCategory($morningCheck);
        $bootbox = false;
        foreach ($morningCheck->getCheckings() as $checking) {
            if (!$checking->getStatus() instanceof Status) {
                $bootbox = true;
                break;
            }
        }

        return $this->render('AppBundle:Part:sidebar.html.twig', [
            'data' => $data,
            'morningCheck' => $morningCheck,
            'current_checking_id' => $current_checking_id,
            'mcms' => $this->getDoctrine()->getRepository("AppBundle:MorningCheckModel")->findByPosition(),
            'currentPath' => $this->get('request_stack')->getMasterRequest()->get('_route'),
            'bootbox' => $bootbox
        ]);
    }
}