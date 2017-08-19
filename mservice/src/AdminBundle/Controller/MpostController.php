<?php

namespace AdminBundle\Controller;

use ApiBundle\Entity\Mpost;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MpostController extends Controller {
    
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $mposts = $em->getRepository('ApiBundle:Mpost')->loadPostsBo();

        $path['title'] = 'Post list';
        $path['url'] = $this->generateUrl('mpost_index');
        $paths[] = $path;

        return $this->render('admin/mpost/index.html.twig', array(
                    'mposts' => $mposts,
                    'paths' => $paths
        ));
    }
    
    public function postListAjaxAction(Request $request) {
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
        $total = $em->getRepository('ApiBundle:Mpost')->loadPostsBo(true);
        $totalFiltered = $em->getRepository('ApiBundle:Mpost')->loadPostsBo(true, null, null, null, null, null, $word);
        $posts = $em->getRepository('ApiBundle:Mpost')->loadPostsBo(false, $offset, $limit, null, null, null, $word);

        return new JsonResponse(
                array(
                    'data' => $posts,
                    'recordsTotal' => $total,
                    'recordsFiltered' => $totalFiltered
                )
        );
    }
    
    public function showAction(Mpost $mpost) {
        $allPhotosByPostId = $this->get('api_massage.PhotoService')->getAllPhotosByPostId($mpost->getId());

        $path['title'] = 'Post list';
        $path['url'] = $this->generateUrl('mpost_index');
        $paths[] = $path;

        $path['title'] = 'Show Post ' . $mpost->getId();
        $path['url'] = $this->generateUrl('mpost_show', array('id' => $mpost->getId()));
        $paths[] = $path;

        return $this->render('admin/mpost/show.html.twig', array(
                    'mpost' => $mpost,
                    'allPhotosByPostId' => array_key_exists('data', $allPhotosByPostId) ? $allPhotosByPostId['data'] : array(),
                    'paths' => $paths
        ));
    }
    
    public function editAction(Request $request, Mpost $mpost) {
        $editForm = $this->createForm('AdminBundle\Form\MpostType', $mpost);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mpost_show', array('id' => $mpost->getId()));
        }

        $path['title'] = 'Post list';
        $path['url'] = $this->generateUrl('mpost_index');
        $paths[] = $path;

        $path['title'] = 'Edit Post ' . $mpost->getId();
        $path['url'] = $this->generateUrl('mpost_edit', array('internalId' => $mpost->getInternalId()));
        $paths[] = $path;

        return $this->render('admin/mpost/edit.html.twig', array(
                    'mpost' => $mpost,
                    'edit_form' => $editForm->createView(),
                    'paths' => $paths
        ));
    }
    
    /**
     * Deletes a mpost entity.
     *
     */
    public function deleteAction(Request $request, Mpost $mpost) {
        $this->get('api_massage.PostsService')->deletePost($mpost->getInternalId(), $mpost->getUser()->getInternalId(), $mpost->getUser()->getInternalToken());
        return $this->redirectToRoute('mpost_index');
    }

    /**
     * Enable a mpost entity
     * @param Request $request
     * @param Mpost $mpost
     * @return type
     */
    public function enableAction(Request $request, Mpost $mpost) {
        $this->get('api_massage.PostsService')->enablePost($mpost->getInternalId(), $mpost->getUser()->getInternalId(), $mpost->getUser()->getInternalToken());
        return $this->redirectToRoute('mpost_index');
    }
    
    
    
}