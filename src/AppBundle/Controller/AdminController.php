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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Admin Controller
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class AdminController extends CRUDController
{
    /**
     * @Route("/reload_categories", name="reload_categories")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function reloadCategoriesAction(Request $request)
    {
        $mcm = $this->getDoctrine()->getRepository("AppBundle:MorningCheckModel")
            ->find($request->request->get('id'));
        $categories = $this->getDoctrine()->getRepository("AppBundle:Category")->findBy(['morningCheckModel' => $mcm]);
        $final = [];
        foreach ($categories as $category) {
            $final[] = ['id' => $category->getId(), 'text' => $category->getName()];
        }

        return new JsonResponse($final);
    }

}