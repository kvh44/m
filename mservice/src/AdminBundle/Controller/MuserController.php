<?php

namespace AdminBundle\Controller;

use ApiBundle\Entity\Muser;
use ApiBundle\Entity\Mpassword;
use ApiBundle\Services\UsersService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Muser controller.
 *
 */
class MuserController extends Controller {

    /**
     * Lists all muser entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $musers = $em->getRepository('ApiBundle:Muser')->getUserListBo();

        $path['title'] = 'User list';
        $path['url'] = $this->generateUrl('muser_index');
        $paths[] = $path;

        return $this->render('admin/muser/index.html.twig', array(
                    'musers' => $musers,
                    'paths' => $paths
        ));
    }

    public function userListAjaxAction(Request $request) {
        /*
          if(!$request->getSession()->get('draw_users')){
          $request->getSession()->set('draw_users', 1);
          } else {
          $draw = $request->getSession()->get('draw_users');
          $request->getSession()->set('draw_users', (int)$draw + 1);
          }
         * 
         */

        if (strlen($request->query->get('start')) > 0) {
            $offset = $request->query->get('start');
        } else {
            $offset = 0;
        }

        if (strlen($request->query->get('length')) > 0) {
            $limit = $request->query->get('length');
        } else {
            $limit = 25;
        }

        $word = null;
        $search = $request->query->get('search');
        if (is_array($search)) {
            if (array_key_exists('value', $search)) {
                $word = $search['value'];
            }
        }
        $em = $this->getDoctrine()->getManager();
        $total = $em->getRepository('ApiBundle:Muser')->getUserListBo(true);
        $totalFiltered = $em->getRepository('ApiBundle:Muser')->getUserListBo(true, null, null, null, null, null, null, $word);
        $users = $em->getRepository('ApiBundle:Muser')->getUserListBo(false, $offset, $limit, null, null, null, null, $word);

        return new JsonResponse(
                array(
            'data' => $users,
            //'draw' => $request->getSession()->get('draw_users'), 
            'recordsTotal' => $total,
            'recordsFiltered' => $totalFiltered,
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
    public function newAction(Request $request) {
        $muser = new Muser();

        $muser->setToken($this->get('api_massage.UsersService')->prepareToken());
        $muser->setInternalToken($this->get('api_massage.UsersService')->prepareInternalToken());
        $muser->setExternalToken($this->get('api_massage.UsersService')->prepareExternalToken());
        $muser->setInternalId($this->get('api_massage.UsersService')->prepareInternalId());
        $muser->setCreated(new \DateTime('now'));
        $muser->setUpdated(new \DateTime('now'));
        $form = $this->createForm('AdminBundle\Form\MuserType', $muser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($muser);
            $em->flush($muser);

            return $this->redirectToRoute('muser_show', array('id' => $muser->getId()));
        }

        $path['title'] = 'User list';
        $path['url'] = $this->generateUrl('muser_index');
        $paths[] = $path;

        $path['title'] = 'New User';
        $path['url'] = $this->generateUrl('muser_new');
        $paths[] = $path;

        return $this->render('admin/muser/new.html.twig', array(
                    'muser' => $muser,
                    'form' => $form->createView(),
                    'paths' => $paths
        ));
    }

    /**
     * Finds and displays a muser entity.
     *
     */
    public function showAction(Muser $muser) {
        $allUserPhotos = $this->get('api_massage.PhotoService')->getAllPhotosByUserId($muser->getId());
        

        $path['title'] = 'User list';
        $path['url'] = $this->generateUrl('muser_index');
        $paths[] = $path;

        $path['title'] = 'Show User ' . $muser->getUsername();
        $path['url'] = $this->generateUrl('muser_show', array('id' => $muser->getId()));
        $paths[] = $path;

        return $this->render('admin/muser/show.html.twig', array(
                    'muser' => $muser,
                    'allUserPhotos' => array_key_exists('data', $allUserPhotos) ? $allUserPhotos['data'] : array(), 
                    'paths' => $paths
        ));
    }

    /**
     * Displays a form to edit an existing muser entity.
     *
     */
    public function editAction(Request $request, Muser $muser) {

        $editForm = $this->createForm('AdminBundle\Form\MuserType', $muser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('muser_show', array('id' => $muser->getId()));
        }

        $path['title'] = 'User list';
        $path['url'] = $this->generateUrl('muser_index');
        $paths[] = $path;

        $path['title'] = 'Edit User ' . $muser->getUsername();
        $path['url'] = $this->generateUrl('muser_edit', array('internalToken' => $muser->getInternalToken()));
        $paths[] = $path;

        return $this->render('admin/muser/edit.html.twig', array(
                    'muser' => $muser,
                    'edit_form' => $editForm->createView(),
                    'paths' => $paths
        ));
    }
    
    public function managePasswordAction(Request $request, Muser $muser) {
        
        $em = $this->getDoctrine()->getManager();
        $passwords = $em->getRepository('ApiBundle:Mpassword')->loadUserPasswordsByUserId($muser->getId());
        
        $mpassword = new Mpassword();
        $mpassword->setUser($muser);
        $mpassword->setUserId($muser->getId());
        $mpassword->setEncryptionMethod(UsersService::ENCRYPTION_METHOD);
        $mpassword->setSalt('');
        $mpassword->setIndication('xxx');
        $mpassword->setInternalId($this->get('api_massage.UsersService')->prepareInternalId());
        $mpassword->setCreated(new \DateTime('now'));
        $mpassword->setUpdated(new \DateTime('now'));
        
        $form = $this->createForm('AdminBundle\Form\MpasswordType', $mpassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $mpassword->setIndication($this->get('api_massage.UsersService')->preparePasswordIndication($mpassword->getPassword()));
            $mpassword->setPassword($this->get('api_massage.UsersService')->encryptPassword($mpassword->getPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($mpassword);
            $em->flush($mpassword);

            return $this->redirectToRoute('mpassword_manage', array('internalToken' => $muser->getInternalToken()));
        }
        
        $path['title'] = 'User list';
        $path['url'] = $this->generateUrl('muser_index');
        $paths[] = $path;

        $path['title'] = 'Edit User ' . $muser->getUsername();
        $path['url'] = $this->generateUrl('muser_edit', array('internalToken' => $muser->getInternalToken()));
        $paths[] = $path;
        
        $path['title'] = 'Password ' . $muser->getUsername();
        $path['url'] = $this->generateUrl('mpassword_manage', array('internalToken' => $muser->getInternalToken()));
        $paths[] = $path;
        
        return $this->render('admin/mpassword/newPassword.html.twig', array(
                    'muser' => $muser,
                    'passwords' => $passwords,
                    'form' => $form->createView(),
                    'paths' => $paths
        ));
        
    }

    /**
     * Deletes a muser entity.
     *
     */
    public function deleteAction(Request $request, Muser $muser) {
        $this->get('api_massage.UsersService')->deleteUser($muser->getInternalId(), $muser->getInternalToken());
        return $this->redirectToRoute('muser_index');
    }

    /**
     * Enable a muser entity
     * @param Request $request
     * @param Muser $muser
     * @return type
     */
    public function enableAction(Request $request, Muser $muser) {
        $this->get('api_massage.UsersService')->enableUser($muser->getInternalId(), $muser->getInternalToken());
        return $this->redirectToRoute('muser_index');
    }

    public function managePhotoAction(Request $request, Muser $muser) {
        $allUserPhotos = $this->get('api_massage.PhotoService')->getAllPhotosByUserId($muser->getId());

        $path['title'] = 'User list';
        $path['url'] = $this->generateUrl('muser_index');
        $paths[] = $path;

        $path['title'] = 'Show User ' . $muser->getUsername();
        $path['url'] = $this->generateUrl('muser_show', array('id' => $muser->getId()));
        $paths[] = $path;
        
        $path['title'] = 'Manage User Photo ' . $muser->getUsername();
        $path['url'] = $this->generateUrl('mphoto_manage', array('internalToken' => $muser->getInternalToken()));
        $paths[] = $path;
        
        return $this->render('admin/mphoto/managePhoto.html.twig', array(
                    'muser' => $muser,
                    'allUserPhotos' => array_key_exists('data', $allUserPhotos) ? $allUserPhotos['data'] : array(), 
                    'paths' => $paths
        ));
    }

    /**
     * Creates a form to delete a muser entity.
     *
     * @param Muser $muser The muser entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    /*
      private function createDeleteForm(Muser $muser)
      {
      return $this->createFormBuilder()
      ->setAction($this->generateUrl('muser_delete', array('id' => $muser->getId())))
      ->setMethod('DELETE')
      ->getForm()
      ;
      }
     * 
     */
}
