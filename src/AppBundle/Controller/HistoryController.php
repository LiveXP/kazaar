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


use AppBundle\Entity\Search;
use AppBundle\Form\SearchType;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * History Controller
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class HistoryController extends Controller
{
    /**
     * @Route("/historique", name="history_index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $choices = $this->getDoctrine()->getRepository("AppBundle:MorningCheck")->findNames();
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search, ['choices' => $choices, 'method' => 'GET']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $results = $this->getDoctrine()->getRepository("AppBundle:MorningCheck")->findBySearch($search);
            $adapter = new ArrayAdapter($results);
            $pagerfanta = new Pagerfanta($adapter);
            $pagerfanta->setMaxPerPage(15)->setCurrentPage($request->query->getInt('page', 1));
            $parameters['results'] = $pagerfanta->getCurrentPageResults();
            $parameters['pager'] = $pagerfanta;
        }

        $parameters['form'] = $form->createView();

        return $this->render("AppBundle:History:index.html.twig", $parameters);
    }

    /**
     * @Route("/search_names", name="ajax_search_names", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ajaxNamesAction(Request $request)
    {
        if ($request->query->has('date')) {
            $date = strlen($request->query->get('date') > 1) ? \DateTime::createFromFormat("d/m/Y", $request->query->get('date')) : null;
            $names = $this->getDoctrine()->getRepository("AppBundle:MorningCheck")->findNames($date);
            $final = [];

            foreach ($names as $name) {
                $final[] = ['id' => $name, 'text' => $name];
            }

            return new JsonResponse($final);
        }

        return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/historique/stats", name="history_stats")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function statsAction(Request $request)
    {
        $parameters = [];
        if ($request->query->has('stats_date')) {
            $date = \DateTime::createFromFormat("m/Y", $request->query->get('stats_date'));
            $parameters['results'] = $this->getDoctrine()->getRepository("AppBundle:Checking")->findStatsByMonth($date);
        }

        return $this->render('AppBundle:History:stats.html.twig', $parameters);
    }

}