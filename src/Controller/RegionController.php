<?php

namespace App\Controller;

use App\Entity\Region;
use App\Entity\User;
use App\Form\RegionType;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/region")
 */
class RegionController extends AbstractController
{
    /**
     * @Route("/", name="region_index", methods={"GET"})
     * @param RegionRepository $regionRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(RegionRepository $regionRepository, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $regions = $regionRepository->findAll();

        foreach($regions as $region){
            $regionsUrl[$region->getId()] = $this->generateUrl('room_by_region', [ 'regionString' => $region->getName()]);
        }

        foreach($users as $user){
            if(in_array('ROLE_ADMIN', $user->getRoles())){
                $admins[] = $user;
            }
        }

        return $this->render('region/index.html.twig', [
            'regions' => $regions,
            'admins' => $admins,
            'regionsUrl' => $regionsUrl
        ]);
    }

    /**
     * @Route("/new", name="region_new", methods={"GET","POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {

        $user = $this->getUser();
        $errorResponse = new Response("<h1>Erreur 403</h1>
                                               <p>Vous devez être administrateur</p>", 403);

        if(is_null($user)){
            return $errorResponse;
        }
        if(! in_array('ROLE_ADMIN', $user->getRoles())){
            return $errorResponse;
        }

        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($region);
            $entityManager->flush();

            return $this->redirectToRoute('region_index');
        }

        return $this->render('region/new.html.twig', [
            'region' => $region,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="region_show", methods={"GET"})
     * @param Region $region
     * @param UserRepository $userRepository
     * @return Response
     */
    public function show(Region $region, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        foreach($users as $user){
            if(in_array('ROLE_ADMIN', $user->getRoles())){
                $admins[] = $user;
            }
        }

        return $this->render('region/show.html.twig', [
            'region' => $region,
            'admins' => $admins
        ]);
    }

    /**
     * @Route("/{id}/edit", name="region_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Region $region): Response
    {
        $user = $this->getUser();
        $errorResponse = new Response("<h1> Erreur 403</h1>
                                               <p> Vous n'avez pas accès à cette zone du site</p>",
            403);
        if(is_null($user)){
            return $errorResponse;
        }
        if(! in_array('ROLE_ADMIN', $user->getRoles())){
            return $errorResponse;
        }

        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('region_index');
        }

        return $this->render('region/edit.html.twig', [
            'region' => $region,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="region_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Region $region): Response
    {
        if ($this->isCsrfTokenValid('delete'.$region->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($region);
            $entityManager->flush();
        }

        return $this->redirectToRoute('region_index');
    }
}
