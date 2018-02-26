<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProjectYear;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Countries CRUDController
 * @Route("/admin/years")
 */
class CRUDYearsController extends Controller
{
    /**
     * @Route("", name="obg_years_index")
     */
    public function indexAction(Request $request)
    {
        $yearsRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:ProjectYear');

        $requestData = $request->request->all();
        $limit = isset($requestData['limit']) ? $requestData['limit'] : 50;
        $page = isset($requestData['page']) ? $requestData['page'] : 1;
        $totalResults = count($yearsRepo->findAll());

        $years = $yearsRepo->findBy(
            array(),
            array('projectYear'=> 'ASC'),
            $limit,
            ($page-1)*$limit
        );

        return $this->render('years/index.html.twig',
            array(
                'years' => $years,
                'totalResults' => $totalResults,
                'perPage' =>$limit,
                'pagesCount' => ceil($totalResults/$limit)
                )
            );
    }

    /**
     * @Route("/year/{id}/delete", name="obg_years_delete_year")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $year = $em->getRepository('AppBundle:ProjectYear')->findOneBy(array('id' => $id));

        if($year) {
            $year->setRemoved(1);
            $em->flush();
        }

        return $this->redirectToRoute('obg_years_index');
    }


    /**
     * @Route("/year/{id}/edit", name="obg_years_edit_year")
     */
    public function editAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $year = $em->getRepository('AppBundle:ProjectYear')->findOneBy(array('id'=>$id));
        $form = $this->createForm('AppBundle\Form\YearsAddNewEditForm', $year);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $year->setProjectYear($request->request->get('projectYear'));
            //$em->persist($year);
            $em->flush();
            return $this->redirectToRoute('obg_years_index');
        }

        return $this->render(
            'countries/edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }


    /**
     * @Route("/year/new", name="obg_years_add_new")
     */
    public function addNewAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $year = new ProjectYear();
        $form = $this->createForm('AppBundle\Form\YearsAddNewEditForm', $year);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $year->setProjectYear($request->request->get('projectYear'));
            $year->setRemoved(0);
            $em->persist($year);
            $em->flush();
            return $this->redirectToRoute('obg_years_index');
        }

        return $this->render(
            'countries/add_new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

}
