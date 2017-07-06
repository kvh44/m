<?php

namespace AdminBundle\Controller;

use ApiBundle\Entity\Muser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        $em = $this->getDoctrine()->getManager();

        $musers = $em->getRepository('ApiBundle:Muser')->findAll();

        return $this->render('muser/index.html.twig', array(
            'musers' => $musers,
        ));
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

        return $this->render('muser/new.html.twig', array(
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

        return $this->render('muser/show.html.twig', array(
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

        return $this->render('muser/edit.html.twig', array(
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
