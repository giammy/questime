<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Person;
use AppBundle\Entity\Question;
use AppBundle\Entity\Survey;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }


    /**
     * @Route("/faq", name="faq")
     */
    public function faqAction(Request $request)
    {
        return $this->render('default/faq.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }


    /**
     * @Route("/smallprint", name="smallprint")
     */
    public function smallprintAction(Request $request)
    {
        return $this->render('default/smallprint.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }



   /**
     * @Route("/manage", name="manage")
     */
    public function theRootAction()
    {
     return $this->render('default/manage.html.twig'); 
    }


    /**
     * @Route("/public", name="public")
     */
    public function publicAction() 
    {
        return $this->render('default/public.html.twig');
    }

    /**
     * @Route("/private", name="private")
     */
    public function privateAction() 
    {
        return $this->render('default/private.html.twig');
    }


    /**
     * @Route("/create", name="create")
     */
    public function createAction() 
    {
	$p1 = new Person();
	$p1->setUsername('demousername1');
	$p1->setName('demoname1');
	$p1->setSurname('demosurname1');
	$p1->setEmail('demoemail1');
	$p1->setToken('demotoken1');

	$p2 = new Person();
	$p2->setUsername('demousername2');
	$p2->setName('demoname2');
	$p2->setSurname('demosurname2');
	$p2->setEmail('demoemail2');
	$p2->setToken('demotoken2');


	$q1 = new Question();
	$q1->setTitle("Do you like Symfony?");
	$q1->setOption("0", "NO");
	$q1->setOption("1", "YES");
	$q1->setOption("2", "Is it music?");

	$q2 = new Question();
	$q2->setTitle("Do you like Php?");
	$q2->setOption("0", "NO");
	$q2->setOption("1", "YES");
	$q2->setOption("2", "What is it?");

	$s = new Survey();
	$s->setTitle("Web developing");
	$s->setSurveyname("webdeveloping");
	$s->setStatus("open");
	$s->addTohasvoted("voter1");
	$s->addTohasvoted("voter2");

	$s->addToreplies(array("date" => "20160726123423", "rep" => "1,0"  ));
	$s->addToreplies(array("date" => "20160727125959", "rep" => "0,2"  ));

	$p1->setSurvey($s);
	$p2->setSurvey($s);
	$q1->setSurvey($s);
	$q2->setSurvey($s);

	$em = $this->getDoctrine()->getManager();
	$em->persist($p1);
	$em->persist($p2);
	$em->persist($q1);
	$em->persist($q2);
	$em->persist($s);
	$em->flush();

        return $this->render('default/createNew.html.twig');
    }


    /**
     * @Route("/manage/show", name="showall")
     */
    public function showallAction() 
    {
	$repo = $this->getDoctrine()->getrepository('AppBundle:Survey');	
	//$s = $repo->findOneByTitle("Web developing");
	$ss = $repo->findAll();

        return $this->render('default/displaySome.html.twig',
				array(	
				    'surveys' => $ss,
        			    ));
    }


    /**
     * @Route("/manage/show/{uidmanager}", name="showone")
     */
    public function showoneAction($uidmanager) 
    {
	$repo = $this->getDoctrine()->getrepository('AppBundle:Survey');	
	$s = $repo->findOneByUidmanager($uidmanager);

	if (is_null($s)) {
            return $this->render('default/error.html.twig',
				array(	
				    'error' => "Survey '" . $uidmanager . "' not found.",
        			    ));
	} else {	
	    $ss = array ( $s );
            return $this->render('default/displaySome.html.twig',
				array(	
				    'surveys' => $ss,
        			    ));
        }
    }





}
