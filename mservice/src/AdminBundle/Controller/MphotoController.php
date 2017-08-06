<?php

namespace AdminBundle\Controller;

use ApiBundle\Entity\Mphoto;
use ApiBundle\Entity\Muser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Mphoto controller.
 *
 */
class MphotoController extends Controller
{
	/**
     * Lists all mphoto entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $mphotos = $em->getRepository('ApiBundle:Mphoto')->getPhotoListBo();
        
        $path['title'] ='Photo list';
        $path['url'] = $this->generateUrl('mphoto_index');
        $paths[] = $path;
        
        return $this->render('admin/mphoto/index.html.twig', array(
            'mphotos' => $mphotos,
            'paths' => $paths
        ));
    }
	
    public function photoListAjaxAction(Request $request)
    {
        if(strlen($request->query->get('start')) > 0){
            $offset = $request->query->get('start');
        } else {
            $offset = 0;
        }
        
        if(strlen($request->query->get('length')) > 0){
            $limit = $request->query->get('length');
        } else {
            $limit = 25;
        }
        
        $search = $request->query->get('search');
        if(is_array($search)){
            if(array_key_exists('value', $search)){
                $word = $search['value'];
            }
        }
        $em = $this->getDoctrine()->getManager();
        $total = $em->getRepository('ApiBundle:Mphoto')->getPhotoListBo(true);
        $totalFiltered = $em->getRepository('ApiBundle:Mphoto')->getPhotoListBo(true, null, null, null, null, $word);
        $photos = $em->getRepository('ApiBundle:Mphoto')->getPhotoListBo(false, $offset, $limit, null, null, $word);

        return new JsonResponse(
            array(
                'data' => $photos, 
                'recordsTotal' => $total, 
                'recordsFiltered' => $totalFiltered
            )
        );
    }
    
    public function showAction(Request $request,Mphoto $mphoto)
    {
        
        
        $path['title'] ='Photo list';
        $path['url'] = $this->generateUrl('mphoto_index');
        $paths[] = $path;
        
        $path['title'] = 'Show User ' . $mphoto->getUser()->getUsername();
        $path['url'] = $this->generateUrl('muser_show', array('id' => $mphoto->getUser()->getId()));
        $paths[] = $path;
        
        $path['title'] = 'Show Photo ' . $mphoto->getId() . ' '. $mphoto->getUser()->getUsername();
        $path['url'] = $this->generateUrl('mphoto_show', array('id' => $mphoto->getId()));
        $paths[] = $path;
        
        return $this->render('admin/mphoto/show.html.twig', array(
                    'mphoto' => $mphoto,
                    'paths' => $paths
        ));
    }
	
    public function batchDeleteAction(Request $request) {
        $photos = $request->query->get('photos');

        $result = $this->get('api_massage.PhotoService')->deletePhotosBatchBo($photos);

        return new JsonResponse(
            $result
        );
    }
    
    public function deleteAction(Request $request, Mphoto $mphoto) {
        $result = $this->get('api_massage.PhotoService')->deletePhoto($mphoto->getInternalId(), $mphoto->getUser()->getInternalId(),$mphoto->getUser()->getInternalToken());

        return $this->redirectToRoute('mphoto_show', array('id' => $mphoto->getId()));
    }
    
    public function enableAction(Request $request, Mphoto $mphoto) {
        $result = $this->get('api_massage.PhotoService')->enablePhoto($mphoto->getInternalId(), $mphoto->getUser()->getInternalId(),$mphoto->getUser()->getInternalToken());

        return $this->redirectToRoute('mphoto_show', array('id' => $mphoto->getId()));
    }
     
}