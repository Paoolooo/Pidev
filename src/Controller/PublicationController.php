<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\PostLike;
use App\Entity\Publication;
use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Form\CommentaireType;
use App\Form\PublicationType;
use App\Repository\PostLikeRepository;
use App\Repository\PublicationRepository;
use ContainerJqVO6xJ\PaginatorInterface_82dac15;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/publication")
 */
class PublicationController extends AbstractController
{
    /**
     * @Route("/", name="app_publication_index", methods={"GET"})
     */
    public function index(PublicationRepository $publicationRepository, Request $request ,PaginatorInterface $paginator): Response
    {
        $publication=$this->getDoctrine()->getRepository(Publication::class)->findAll();

        $articles= $paginator->paginate(
            $publication,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('publication/index.html.twig', [
            'articles' => $articles,
            'publicationFavoris' => $publicationRepository->listPublicationfavoris(),
        ]);
    }

    /**
     * @Route("/new", name="app_publication_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PublicationRepository $publicationRepository): Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publicationRepository->add($publication);
            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="app_publication_show", methods={"GET", "POST"})
     */
    public function show( $id,PublicationRepository $publicationRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $publication= $this->getDoctrine()->getRepository(Publication::class)->find($id);
        $commentairess= $this->getDoctrine()->getRepository(Commentaire::class)->listCommentaireByPub($publication->getId());

        $commentaires= $paginator->paginate(
            $commentairess,
            $request->query->getInt('page',1),
            2
        );

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setPublication($publication);

            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('app_publication_show', ['id'=>$id]);
        }

        return $this->render('publication/show.html.twig', [
            'commentForm' => $form->createView(),
            'commentaires' => $commentaires,
            'publication' => $publication,
        ]);


    }


    /**
     * @Route("/{id}/edit", name="app_publication_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Publication $publication, PublicationRepository $publicationRepository): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publicationRepository->add($publication);
            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_publication_delete", methods={"POST"})
     */
    public function delete(Request $request, Publication $publication, PublicationRepository $publicationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $publicationRepository->remove($publication);
        }

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     *  perment de like ou unlike une publication
     *
     * @Route("/{id}/like", name="post_like")
     * @param Publication $publication
     * @param ObjectManager $manager
     * @param PostLikeRepository $likeRepo

     * @return Response
     */
    public function like(Publication $publication , EntityManagerInterface $manager,PostLikeRepository $likeRepo ) :Response
    {
        $users = $this->getUsers();
        if(!$users) return $this->json([
            'code'=>403,
            'message' => "Unauthorized"
        ],403);
        if($publication->isLikedByUser($users)){
            $like = $likeRepo->findOneBy([
                'publication'=>$publication,
                'users'=> $users
            ]);
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code'=>200,
                'message'=>'like bien supprimer',
                'likes'=>$likeRepo->count(['publication'=>$publication])
            ],200) ;
        }
        $like = new PostLike() ;
        $like->setPublication($publication)
            ->setUsers($users);
        $manager->persist($like);
        $manager->flush();



      return $this->json([
          'code'=>200,
          'message'  => 'like bien ajouter',
          'likes'=>$likeRepo->count(['publication'=>$publication])
         ],200);

    }
}
