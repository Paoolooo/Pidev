<?php

namespace App\Controller;

use App\Entity\CategorieExercice;
use App\Form\CategorieExerciceType;
use App\Repository\CategorieExerciceRepository;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorieexercice")
 */
class CategorieExerciceController extends AbstractController
{
    /**
     * @Route("/", name="categorie_exercice_index", methods={"GET"})
     */
    public function index(CategorieExerciceRepository $categorieExerciceRepository): Response
    {


        return $this->render('categorie_exercice/index.html.twig', [
            'categorie_exercices' => $categorieExerciceRepository->findAll(),
        ]);
    }
    /**
     * @Route("/backend", name="categorie_exercice_back", methods={"GET"})
     */
    public function backendCat(CategorieExerciceRepository $categorieExerciceRepository): Response
    {
        return $this->render('categorie_exercice/backend/categorie/index.html.twig', [
            'categorie_exercices' => $categorieExerciceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="categorie_exercice_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieExercice = new CategorieExercice();
        $form = $this->createForm(CategorieExerciceType::class, $categorieExercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieExercice);
            $entityManager->flush();
            $this->addFlash('info', 'Categorie Ajoutée');
            return $this->redirectToRoute('categorie_exercice_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_exercice/backend/categorie/new.html.twig', [
            'categorie_exercice' => $categorieExercice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_exercice_show", methods={"GET"})
     */
    public function show(CategorieExercice $categorieExercice, ExerciceRepository  $repository, Request $request,PaginatorInterface $paginator): Response
    {
        $ex = $repository->findAll();
        $exercices = $paginator->paginate(
            $ex,
            $request->query->getInt('page',1),
            3
        );
        return $this->render('categorie_exercice/show.html.twig', [
            'Exercices' => $exercices, 'categorie_exercice' =>$categorieExercice,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="categorie_exercice_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,$id, CategorieExercice $categorieExercice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieExerciceType::class, $categorieExercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'Categorie '.$id.' Modifié');
            return $this->redirectToRoute('categorie_exercice_back', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('categorie_exercice/backend/categorie/edit.html.twig', [
            'cat' => $categorieExercice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_exercice_delete", methods={"POST"})
     */
    public function delete(Request $request,$id, CategorieExercice $categorieExercice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieExercice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieExercice);
            $entityManager->flush();
            $this->addFlash('info', 'Categorie '.$id.' Supprimé');
        }

        return $this->redirectToRoute('categorie_exercice_back', [], Response::HTTP_SEE_OTHER);
    }
}
