<?php

namespace AdminBundle\Controller;

use ApiBundle\Entity\Mcategory;
use ApiBundle\Services\UtileService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class McategoryController extends Controller {
    
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $mcategories = $em->getRepository('ApiBundle:Mcategory')->loadBoCategories();

        $path['title'] = 'Category list';
        $path['url'] = $this->generateUrl('mcategory_index');
        $paths[] = $path;

        return $this->render('admin/mcategory/index.html.twig', array(
                    'mcategories' => $mcategories,
                    'paths' => $paths
        ));
    }
    
    public function showAction(Mcategory $mcategory) {
        $path['title'] = 'Category list';
        $path['url'] = $this->generateUrl('mcategory_index');
        $paths[] = $path;

        $path['title'] = 'Show Category ' . $mcategory->getCategoryEn();
        $path['url'] = $this->generateUrl('mcategory_show', array('id' => $mcategory->getId()));
        $paths[] = $path;

        return $this->render('admin/mcategory/show.html.twig', array(
                    'mcategory' => $mcategory,
                    'paths' => $paths
        ));
    }
    
    public function editAction(Request $request, Mcategory $mcategory) {
        $editForm = $this->createForm('AdminBundle\Form\McategoryType', $mcategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mcategory_show', array('id' => $mcategory->getId()));
        }

        $path['title'] = 'Category list';
        $path['url'] = $this->generateUrl('mcategory_index');
        $paths[] = $path;

        $path['title'] = 'Edit Category ' . $mcategory->getCategoryEn();
        $path['url'] = $this->generateUrl('mcategory_edit', array('internalId' => $mcategory->getInternalId()));
        $paths[] = $path;

        return $this->render('admin/mcategory/edit.html.twig', array(
                    'mcategory' => $mcategory,
                    'edit_form' => $editForm->createView(),
                    'paths' => $paths
        ));
    }
    
    public function newAction(Request $request) {
        $mcategory = new Mcategory();

        $mcategory->setInternalId($this->get('api_massage.UsersService')->prepareInternalId());
        $mcategory->setCreated(new \DateTime('now'));
        $mcategory->setUpdated(new \DateTime('now'));
        $form = $this->createForm('AdminBundle\Form\McategoryType', $mcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mcategory);
            $em->flush($mcategory);

            return $this->redirectToRoute('mcategory_show', array('id' => $mcategory->getId()));
        }

        $path['title'] = 'Category list';
        $path['url'] = $this->generateUrl('mcategory_index');
        $paths[] = $path;

        $path['title'] = 'New Category';
        $path['url'] = $this->generateUrl('mcategory_new');
        $paths[] = $path;

        return $this->render('admin/mcategory/new.html.twig', array(
                    'mcategory' => $mcategory,
                    'form' => $form->createView(),
                    'paths' => $paths
        ));
    }
    
    public function deleteAction(Request $request, Mcategory $mcategory) {
        $em = $this->getDoctrine()->getManager();
        
        if($mcategory){
            $mcategory->setIsDeleted(UtileService::TINY_INT_TRUE);
            $em->flush();
        }
        
        return $this->redirectToRoute('mcategory_index');
    }
    
    public function enableAction(Request $request, Mcategory $mcategory) {
        $em = $this->getDoctrine()->getManager();
        
        if($mcategory){
            $mcategory->setIsDeleted(UtileService::CONST_NULL);
            $em->flush();
        }
        
        return $this->redirectToRoute('mcategory_index');
    }
    
}