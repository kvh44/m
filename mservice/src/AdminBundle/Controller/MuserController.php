<?php

namespace AdminBundle\Controller;

use ApiBundle\Entity\Muser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Muser controller.
 *
 */
class MuserController extends Controller
{
    /**
     * Lists all muser entities.
     *
     */
    public function indexAction()
    {
        //$em = $this->getDoctrine()->getManager();

        //$musers = $em->getRepository('ApiBundle:Muser')->getUserListBo();
        $musers = array();
        return $this->render('admin/muser/index.html.twig', array(
            'musers' => $musers,
        ));
    }
    
    public function userListAjaxAction(Request $request)
    {
        if(!$request->getSession()->get('draw_users')){
            $request->getSession()->set('draw_users', 1);
        } else {
            $draw = $request->getSession()->get('draw_users');
            $request->getSession()->set('draw_users', (int)$draw + 1);
        }
        
        if(strlen($request->query->get('start')) > 0){
            $offset = $request->query->get('start');
        } else {
            $offset = 0;
        }
        
        if(strlen($request->query->get('length')) > 0){
            $limit = $request->query->get('length');
        } else {
            $limit = 15;
        }
        
        $search = $request->query->get('search');
        if(is_array($search)){
            if(array_key_exists('value', $search)){
                $word = $search['value'];
            }
        }
        $em = $this->getDoctrine()->getManager();
        $total = $em->getRepository('ApiBundle:Muser')->getUserListBo(true);
        $users = $em->getRepository('ApiBundle:Muser')->getUserListBo(false, $offset, $limit, null, null, null, null, $word);

        return new JsonResponse(
            array(
                'data' => $users, 
                'draw' => $request->getSession()->get('draw_users'), 
                'recordsTotal' => $total, 
                'recordsFiltered' => $total,
                //'iTotalRecords' => $total,
                //'iTotalDisplayRecords' => $total,
                //'aaData' => $users
            )
        );
    }        

    /**
     * Creates a new muser entity.
     *
     */
    public function newAction(Request $request)
    {
        $muser = new Muser();
        $form = $this->createForm('AdminBundle\Form\MuserType', $muser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($muser);
            $em->flush($muser);

            return $this->redirectToRoute('muser_show', array('id' => $muser->getId()));
        }

        return $this->render('admin/muser/new.html.twig', array(
            'muser' => $muser,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a muser entity.
     *
     */
    public function showAction(Muser $muser)
    {
        $deleteForm = $this->createDeleteForm($muser);

        return $this->render('admin/muser/show.html.twig', array(
            'muser' => $muser,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing muser entity.
     *
     */
    public function editAction(Request $request, Muser $muser)
    {
        $deleteForm = $this->createDeleteForm($muser);
        $editForm = $this->createForm('AdminBundle\Form\MuserType', $muser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('muser_edit', array('id' => $muser->getId()));
        }

        return $this->render('admin/muser/edit.html.twig', array(
            'muser' => $muser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a muser entity.
     *
     */
    public function deleteAction(Request $request, Muser $muser)
    {
        $form = $this->createDeleteForm($muser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($muser);
            $em->flush($muser);
        }

        return $this->redirectToRoute('muser_index');
    }

    /**
     * Creates a form to delete a muser entity.
     *
     * @param Muser $muser The muser entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Muser $muser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('muser_delete', array('id' => $muser->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
