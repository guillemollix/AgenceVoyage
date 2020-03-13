<?php

namespace App\Controller;

use App\Entity\Region;
use App\Entity\Room;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\RoomType;
use App\Repository\CommentRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

/**
 * @Route("/room")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/", name="room_index", methods={"GET"})
     */
    public function index(RoomRepository $roomRepository, UserRepository $userRepository): Response
    {
        $rooms = $roomRepository->findAll();
        $users = $userRepository->findAll();
        
        foreach($users as $user){
            if(in_array('ROLE_ADMIN', $user->getRoles())){
                $admins[] = $user;
            }
        }
        
        return $this->render('room/list.html.twig', [
            'rooms' => $rooms,
            'likes' => $this->get('session')->get('likes'),
            'admins' => $admins
        ]);
    }

    /**
     * @Route("/likes", name="room_likes", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
     */
    public function viewLikes(UserRepository $userRepository): Response
    {
        $roomRepository = $this->getDoctrine()->getRepository(Room::class);
        $users = $userRepository->findAll();

        foreach($users as $user){
            if(in_array('ROLE_ADMIN', $user->getRoles())){
                $admins[] = $user;
            }
        }
        $rooms = $roomRepository->findAll();
        $roomLike = [];
        $likes = $this->get('session')->get('likes');
        if (is_null($likes)) {
            $likes = [];
        }
        foreach ($rooms as $room) {
            if (in_array($room->getId(), $likes)) {
                $roomLike[] = $room;
            }
        }

        return $this->render('room/likes.html.twig', [
            'rooms' => $roomLike,
            'admins' => $admins
        ]);
    }

    /**
     * @Route("/region/{regionString}", name="room_by_region")
     * @param $regionString
     * @return Response
     */
    public function show_room_by_region($regionString, UserRepository $userRepository)
    {
        $repo = $this->getDoctrine()->getRepository(Region::class);
        $region = $repo->findOneBy(['name' => $regionString]);
        $rooms = $region->getRooms();
        $criteria = "Région";

        $users = $userRepository->findAll();

        foreach($users as $user){
            if(in_array('ROLE_ADMIN', $user->getRoles())){
                $admins[] = $user;
            }
        }

        return $this->render( 'room/byregion.html.twig', array(
            'region' => $regionString,
            'rooms' => $rooms,
            'likes' => $this->get('session')->get('likes'),
            'admins' => $admins
        ));
    }

    /**
     * @Route("/new", name="room_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        if(is_null($user)){
            return new Response("<h1> Erreur 403 </h1>
                                          <p> Vous n'êtes pas un utilisateur</p>", 403);
        }


        $owner = $this->getUser()->getOwner();
        $errorResponse = new Response("<h1> Erreur 403</h1>
                                               <p> Vous n'êtes pas un propriétaire</p>",
            403);
        if(is_null($owner)){
            return $errorResponse;
        }
        else {
            $room = new Room();
            $form = $this->createForm(RoomType::class, $room);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //$imagefile = $room->getImageFile();
                //if($imagefile) {
                //    $mimetype = $imagefile->getMimeType();
                //    $imagefile->setContentType($mimetype);
                //}
                $room->setOwner($owner);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($room);
                $entityManager->flush();

                // Make sure message will be displayed after redirect
                $this->get('session')->getFlashBag()->add('message', 'Chambre ajoutée avec succès');

                return $this->redirectToRoute('room_index');
            }

            return $this->render('room/new.html.twig', [
                'room' => $room,
                'form' => $form->createView()
            ]);
        }
        
    }

    /**
     * @Route("/{id}", name="room_show", methods={"GET", "POST"})
     */
    public function show(Room $room, UserRepository $userRepository, CommentRepository $commentRepository, Request $request): Response
    {
        $users = $userRepository->findAll();

        foreach($users as $user){
            if(in_array('ROLE_ADMIN', $user->getRoles())){
                $admins[] = $user;
            }
        }

        $comments = $commentRepository->findBy(['room' => $room], ['postTime' => 'DESC']);

        $newComment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $newComment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $newComment->setPostTime(new \DateTime());
            $newComment->setUser($this->getUser());
            $newComment->setRoom($room);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newComment);
            $entityManager->flush();

            $this->get('session')->getFlashBag()->add('message', 'Commentaire ajouté avec succès');
        }

        return $this->render('room/show.html.twig', [
            'room' => $room,
            'admins' => $admins,
            'comments' => $comments,
            'form' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="room_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Room $room, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $errorResponse = new Response("<h1> Erreur 403</h1>
                                               <p> Vous n'avez pas accès à cette zone du site</p>",
                                        403);
        if(is_null($user)){
            return $errorResponse;
        }
        if(! in_array('ROLE_ADMIN', $user->getRoles()) and $user->getOwner() != $room->getOwner()){
            return $errorResponse;
        }

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('room_index');
        }

        $users = $userRepository->findAll();

        foreach($users as $user){
            if(in_array('ROLE_ADMIN', $user->getRoles())){
                $admins[] = $user;
            }
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
            'admins' => $admins
        ]);
    }

    /**
     * @Route("/{id}", name="room_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Room $room): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('room_index');
    }

    /**
     * @Route("/{id}/like", name="room_like", methods={"GET"})
     * @param $id
     * @return Response
     */
    public function like($id): Response
    {
        $likes = $this->get('session')->get('likes');
        if (is_null($likes)) {
            $likes = [];
        }
        if (! in_array($id, $likes) )
        {
            $likes[] = $id;
        }
        else
        {
            $likes = array_diff($likes, array($id));
        }
        $this->get('session')->set('likes', $likes);

        return $this->redirectToRoute('room_index');
    }
}
