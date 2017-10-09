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

use AppBundle\Entity\Checking;
use AppBundle\Entity\MorningCheck;
use AppBundle\Entity\MorningCheckModel;
use AppBundle\Entity\Status;
use AppBundle\Form\CheckingType;
use AppBundle\Security\User\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Default Controller for MorningCheck
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $parameters = [];
        if ($request->query->has('last')) {
            $parameters['last'] = $this->getDoctrine()->getRepository("AppBundle:MorningCheck")
                ->findOneBy([], ['id' => "DESC"]);
        }

        return $this->render("AppBundle:Default:homepage.html.twig", $parameters);
    }

    /**
     * @Route("/start/{id}/{checking_id}", name="morning_check", requirements={"id": "\d+", "checking_id": "\d+"})
     * @ParamConverter("checking", class="AppBundle:Checking", options={"id" = "checking_id"})
     *
     * @param MorningCheck $morningCheck the current MorningCheck
     * @param Checking $checking the current Checking
     * @param Request $request
     *
     * @return Response
     */
    public function morningCheckAction(MorningCheck $morningCheck, Checking $checking, Request $request)
    {
        $form = $this->createForm(CheckingType::class, $checking);
        $last = $this->getDoctrine()->getRepository("AppBundle:Checking")
            ->findLast($checking->getName(), $checking->getCategory(), $checking->getId());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($request->request->get('image') as $key => $image) {
                if (!empty($image)) {
                    $output = $checking->getId() . "_0" . ($key + 1);
                    $img = $this->get('upload_listener')->initImage($image, $output, $checking);
                    $this->getDoctrine()->getManager()->persist($img);
                    $this->getDoctrine()->getManager()->persist($checking);
                }
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('start_morning_check', ['id' => $morningCheck->getId()]);
        }

        $params = [
            'current' => $morningCheck,
            'checking' => $checking,
            'form' => $form->createView(),
            'last' => $last
        ];

        return $this->render("AppBundle:Default:morning_check.html.twig", $params);
    }

    /**
     * @Route("/start/{id}", name="start_morning_check", requirements={"id": "\d+"}, defaults={"id": null})
     *
     * @param Request $request
     * @param MorningCheck|null $morningCheck the MorningCheck to use (optional)
     *
     * @return Response
     */
    public function startAction(Request $request, MorningCheck $morningCheck = null)
    {
        if ($request->query->has("close")) {
            $id = $request->query->get("close");
            if (null !== $mc = $this->getDoctrine()->getRepository("AppBundle:MorningCheck")->find($id)) {
                $mc->setClosed(true);
                $this->getDoctrine()->getManager()->persist($mc);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        if (null === $morningCheck) {
            $morningCheck = $this->getDoctrine()->getRepository("AppBundle:MorningCheck")->findOneOpen();
        }

        if (null === $morningCheck) {
            $mcm = $this->getDoctrine()->getRepository("AppBundle:MorningCheckModel")->findNext();

            //Last MorningCheck reached, redirecting to homepage with an url parameter
            if (null === $mcm) {
                return $this->redirectToRoute("homepage", ['last' => true]);
            }
            $morningCheck = $this->get('app.worker')->initMorningCheck($mcm);
            $checking = $morningCheck->getCheckings()->first();
        } else {
            $checking = $this->getDoctrine()->getRepository("AppBundle:Checking")
                ->findOneByMorningCheckAndNoStatus($morningCheck);
        }

        if (null === $checking) {
            return $this->redirectToRoute("morning_check_final", ['id' => $morningCheck->getId()]);
        }

        return $this->redirectToRoute("morning_check", ['id' => $morningCheck->getId(), 'checking_id' => $checking->getId()]);
    }

    /**
     * @Route("/modele/{id}", name="start_specific_morning_check", options={"expose"=true})
     *
     * @param MorningCheckModel $mcm the MorningCheckModel to use
     *
     * @return Response
     */
    public function startFromModelAction(MorningCheckModel $mcm)
    {
        if (null === $morningCheck = $this->getDoctrine()->getRepository("AppBundle:MorningCheck")->findOneByModelNameToday($mcm->getName())) {
            $morningCheck = $this->get('app.worker')->initMorningCheck($mcm);
            $checking = $morningCheck->getCheckings()->first();
        } else {
            $checking = $this->getDoctrine()->getRepository("AppBundle:Checking")
                ->findOneByMorningCheckAndNoStatus($morningCheck);
        }

        if (null === $checking) {
            return $this->redirectToRoute("morning_check_final", ['id' => $morningCheck->getId()]);
        }

        return $this->redirectToRoute("morning_check", ['id' => $morningCheck->getId(), 'checking_id' => $checking->getId()]);
    }

    /**
     * @Route("/start/{id}/final", name="morning_check_final")
     *
     * @param Request $request
     * @param MorningCheck $morningCheck the current MorningCheck
     * @return Response
     */
    public function finalMorningCheckAction(Request $request, MorningCheck $morningCheck)
    {
        if ($request->query->has('validate')) {
            $status = $this->getDoctrine()->getRepository("AppBundle:Status")->findOneBy(['name' => 'OK']);
            foreach ($morningCheck->getCheckings() as $checking) {
                if (!$checking->getStatus() instanceof Status) {
                    $checking->setStatus($status);
                    $this->getDoctrine()->getManager()->persist($checking);
                }
            }
            $this->getDoctrine()->getManager()->flush();
        }

        $title = $this->get('app.worker')->generateMailTitle($morningCheck);

        return $this->render("AppBundle:Default:final.html.twig", [
            'current' => $morningCheck,
            'title' => $title
        ]);
    }

    /**
     * @Route("/mail/{id}", name="send_mail")
     *
     * @param MorningCheck $morningCheck the current MorningCheck
     *
     * @return Response
     */
    public function sendMailAction(MorningCheck $morningCheck)
    {
        if ($morningCheck->getDate()->format("Y-m-d") == (new \DateTime())->format("Y-m-d")) {
            $this->get('app.mailer')->sendAndCloseMorningCheck($morningCheck);
        }

        return $this->redirectToRoute('start_morning_check');
    }

    /**
     * @Route("/remove_image", name="remove_image", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeImageAction(Request $request)
    {
        if ($request->request->has('id')) {
            $image = $this->getDoctrine()->getRepository("AppBundle:Image")->find($request->request->get('id'));
            $this->getDoctrine()->getManager()->remove($image);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse("Image supprimée");
        }

        return new JsonResponse("Not all parameters were provided", 403);
    }
}
