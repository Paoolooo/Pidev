<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Form\ExerciceType;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/exercice")
 */
class ExerciceController extends AbstractController
{
    /**
     * @Route("/", name="exercice_index", methods={"GET"})
     */
    public function index( ExerciceRepository  $repository,Request $request, PaginatorInterface $paginator): Response
    {
        $ex = $repository->findAll();
        $exercices = $paginator->paginate(
            $ex,
            $request->query->getInt('page',1),
            3
        );
        return $this->render('exercice/index.html.twig', [
            'exercices' => $exercices,
        ]);
    }
    /**
     * @Route("/backend", name="exercice_back", methods={"GET"})
     */
    public function execicesIndex(ExerciceRepository  $exerciceRepository): Response
    {
        return $this->render('exercice/backend/exercice/index.html.twig', [
            'exercises' => $exerciceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="exercice_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $exercice = new Exercice();
        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($exercice);
            $entityManager->flush();
            $this->addFlash('info', 'Exercice Ajouté');

            return $this->redirectToRoute('exercice_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('exercice/backend/exercice/new.html.twig', [
            'exercice' => $exercice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="exercice_show", methods={"GET"})
     */
    public function show(Exercice $exercice): Response
    {
        return $this->render('exercice/show.html.twig', [
            'exercice' => $exercice,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="exercice_edit", methods={"GET", "POST"})
     */
    public function edit($id,Request $request, Exercice $exercice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'Exercice '.$id.' Modifié');

            return $this->redirectToRoute('exercice_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('exercice/backend/exercice/edit.html.twig', [
            'exercice' => $exercice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="exercice_delete", methods={"POST"})
     */
    public function delete($id,Request $request, Exercice $exercice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exercice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($exercice);
            $entityManager->flush();
            $this->addFlash('info', 'Exercice '.$id.' Supprimé');

        }

        return $this->redirectToRoute('exercice_back', [], Response::HTTP_SEE_OTHER);
    }
}
