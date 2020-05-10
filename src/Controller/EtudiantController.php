<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    /**
     * @Route("/etudiant", name="etudiant")
     */
    public function index(EtudiantRepository $repo)
    {
        $etudiants = $repo->findAll();
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
            'etudiants' => $etudiants
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('etudiant/home.html.twig');
    }

    /**
     * @Route("/etudiant/new", name="etudiant_create")
     * @Route("/etudiant/{id}/edit", name="etudiant_edit")
     */
    public function create(Etudiant $etudiant = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$etudiant) {
            $etudiant = new Etudiant();
        }

        $form = $this->createFormBuilder($etudiant)
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le nom'
                ]
            ])
            ->add('matricule', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le matricule'
                ]
            ])
            ->add('age', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Entrez l\' age'
                ]
            ])
            ->add('classe', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Entrez la classe (niveau)'
                ]
            ])
            ->add('sexe', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez la sexe (M ou F)'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($etudiant);
            $manager->flush();

            return $this->redirectToRoute('etudiant_show', ['id' => $etudiant->getId()]);
        }

        return $this->render('etudiant/create.html.twig',[
            'formEtudiant' => $form->createView(),
            'editMode' => $etudiant->getId() !==null
        ]);
    }

    /**
     * @Route("/etudiant/{id}/delete", name="etudiant_delete")
     */
    public function delete(Etudiant $etudiant, EntityManagerInterface $manager)
    {
        $manager->remove($etudiant);
        $manager->flush();

        return $this->redirectToRoute("etudiant");
    }

    /**
     * @Route("/etudiant/{id}", name="etudiant_show")
     */
    public function show(Etudiant $etudiant)
    {
        return $this->render('etudiant/show.html.twig',
            [
                'etudiant' => $etudiant
            ]);
    }

}
