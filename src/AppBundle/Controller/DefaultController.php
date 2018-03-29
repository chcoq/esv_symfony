<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Competence;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueilAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('accueil/accueil.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/competence", name="competence_form")
     */
    public function competenceFormAction(Request $request)
    {
        $competence = new Competence();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $competence);
        // ajout des champs du formulaire
        $formBuilder->add('titre');
        $formBuilder->add('designation');
        $formBuilder->add('valider', SubmitType::class);
        //on récupère l'objet form
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($competence);
                $em->flush();
                $this->addFlash('success', "l'article a bien été ajouté");
                return $this->redirectToRoute('competence_form');
            }
        }
        return $this->render('accueil/competence.html.twig', ['formCompetence' => $form->createView()]);
    }

}
