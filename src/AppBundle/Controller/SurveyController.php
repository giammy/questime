<?php

// src/AppBundle/Controller/SurveyController.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Survey;
use AppBundle\Entity\Question;
use AppBundle\Entity\Person;
use AppBundle\Form\Type\SurveyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SurveyController extends Controller
{

    private function getUniqId() {
        // md5(32chars->25base_convert) - sha1(40chars)
	return(base_convert(md5(uniqid(mt_rand(), false)),16,36)); 
    }

    /**
     * @Route("/newsurvey", name="newsurvey")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $s = new Survey();

	$s->setNumberofvoters(0); // set default value	
        $form = $this->createForm(SurveyType::class, $s);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // ... maybe do some form processing, like saving the Task and Tag objects

	    $s->setUiduser($this->getUniqId());
	    $s->setUidmanager($this->getUniqId());
	    $s->setSurveyname(preg_replace("[^A-Za-z0-9]", "", $s->getTitle()));
	    $s->setStatus("OPEN");

	    $hasvoted = $s->getHasvoted();

	    /* manage voters */
	    $nofvoters = $s->getNumberofvoters();
	    if (($nofvoters > 0) and ($nofvoters < 1024)) {
	    	for ($i=0; $i<$nofvoters; $i++) {
            	    $p = new Person();
		    $p->setSurvey($s);
		    $unqd = $this->getUniqId();
		    $p->setUsername($this->getUniqId());
		    $hasvoted[$unqd] = false;
		    $p->setName("name");
		    $p->setSurname("surname");
		    $p->setEmail("email@email.com");
		    $p->setToken($unqd);
		    $s->getPersons()->add($p);
	            $em->persist($p);
	        }	    
		$s->setHasvoted($hasvoted);
	    }	    

	    // get questions
	    $questsarray = $s->getQuestions()->getValues();

	    if (count($questsarray) == 0) {
	        //$session = $this->getRequest()->getSession();
		//$session->set('error', 'bar');
		$this->addFlash(
                  'error',
                  'You tried to save a survey without questions: please insert at least 1 question');
                // $this->addFlash is equivalent to $request->getSession()->getFlashBag()->add
 	        return $this->redirect($request->headers->get('referer'));
	    }

	    $i = 0;
	    $mergedreplies = array();
	    foreach ($questsarray as $q) {
	        //var_dump($q->getTitle());
            	$nq = new Question();
		$nq->setTitle($q->getTitle());
		$nq->setSurvey($s);
		$s->getQuestions()->add($nq);
	        $em->persist($nq);

		// initialize mergedreplies
		$mergedreplies[$i] = array( 'null' => 0, 'Yes' => 0, 'No' => 0 );

		$i = $i + 1;
	    }	    
	    $s->setMergedreplies($mergedreplies);

	    $em->persist($s);
            $em->flush();

            // redirect back to some edit page
            return $this->redirectToRoute('managesurvey', array(
                'uidmanager' => $s->getUidmanager(),
		));
        }

        return $this->render('default/newsurvey.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/m/{uidmanager}", name="managesurvey", defaults={"uidmanager": ""})
     */
    public function managesurveyAction(Request $request, $uidmanager)
    {
	$repo = $this->getDoctrine()->getRepository('AppBundle:Survey');
	$s = $repo->findOneByUidmanager($uidmanager);

	if (is_null($s)) {
            return $this->render('default/error.html.twig',
				array(	
				    'error' => "Survey '" . $uidmanager . "' not found.",
        			    ));
	} 

	$personstokens = array_map(function($p){return $p->getToken();},$s->getPersons()->getValues());
	$questionstitles= array_map(function($p){return $p->getTitle();},$s->getQuestions()->getValues());


        return $this->render('default/managesurveyshow.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
	    'uidmanager' => $uidmanager,
	    'survey' => $s,
	    'personstokens' => $personstokens,
	    'questionstitles' => $questionstitles,
        ));

    }   


    /**
     * @Route("/q/{uiduser}/{token}", name="vote", defaults={"uiduser": "", "token": ""},)
     */
    public function voteAction(Request $request, $uiduser, $token)
    {
	$repo = $this->getDoctrine()->getRepository('AppBundle:Survey');
        $em = $this->getDoctrine()->getManager();
	$s = $repo->findOneByUiduser($uiduser);

	if (is_null($s)) {
	    $sm = $repo->findOneByUidmanager($uiduser);
	    if (is_null($sm)) {
                return $this->render('default/error.html.twig',
				array('error' => "Survey '" . $uiduser . "' not found."));
            } else {
                return $this->redirectToRoute('managesurvey', array(
                    'uidmanager' => $uiduser,
		));

            }
	} 

	$surveyIsFixed = ( $s->getNumberofvoters() > 0 ) ? true : false ;

	if ($surveyIsFixed) {
	    if ($token == "") {
	        $emsg = "Only authorized user can vote. Sorry.";
                return $this->render('default/error.html.twig',array('error'=>$emsg));
	    } 

	    $hasvotedArray = $s->getHasvoted();
	    //echo "<pre>"; var_dump($hasvotedArray);    echo "</pre>"; exit;
	    if (array_key_exists ($token, $hasvotedArray)) {
	        if (is_null($hasvotedArray[$token]) or $hasvotedArray[$token]) {
	            $emsg = "You (or someone using your link) has already voted. Sorry.";
                    return $this->render('default/error.html.twig',array('error'=>$emsg));
	        }
	    }

	    $personstokens = array_map(function($p){return $p->getToken();},$s->getPersons()->getValues());
	    //echo "<pre>"; var_dump($personstokens);    echo "</pre>"; exit;
	    if (!in_array($token, $personstokens)) {
	        $emsg = "You are not included in the voters group. Sorry.";
                return $this->render('default/error.html.twig',array('error'=>$emsg));
	    }
	}

	$questionstitles=array_map(function($p){return $p->getTitle();},$s->getQuestions()->getValues());

	//echo "<pre>" ; var_dump(count($questionstitles)); exit;

	if (count($questionstitles) == 0) {
            return $this->render('default/error.html.twig',
				array(	
				    'error' => "Survey HAS NO QUESTIONS ...",
        			    ));
	} 

	//exit;


        $form = $this->createForm(SurveyType::class, $s);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // ... maybe do some form processing, like saving the Task and Tag objects
	    //$arr = $s->getQuestions()->getValues();
	    //var_dump($arr[0]->getSelect());

	    $rawListOfAnswers = $request->request->all()['survey']['questions'];
	    $listOfAnswers = array();
	    for ($i=0; $i<count($rawListOfAnswers); $i++) {
	    	$listOfAnswers[$i] = $rawListOfAnswers[$i]['select'];
	    }	    
	    
	    // save replies
	    $rpl = $s->getReplies();
	    array_push($rpl, $listOfAnswers);
	    $s->setReplies($rpl);

	    // merge the replies
	    $mergedreplies = $s->getMergedreplies();	 
	    //echo "<pre>"; var_dump($mergedreplies);    echo "</pre>";
	    for ($i=0; $i<count($mergedreplies); $i++) {
	    	$mergedreplies[$i][$listOfAnswers[$i]]++;
	    }
	    $s->setMergedreplies($mergedreplies);	    
	    //echo "<pre>"; var_dump($mergedreplies); exit;

 	    if ($surveyIsFixed) {
	        $vtd = $s->getHasvoted();
		if (array_key_exists($token, $vtd)) {
	            $vtd[$token] = true;
		}
	        $s->setHasvoted($vtd);
            }

	    $myReceipt = $this->getUniqId();
	    $receipt = $s->getVotereceipt();
	    array_push($receipt, $myReceipt);
	    $s->setVotereceipt($receipt);
	    

	    $em->persist($s);
            $em->flush();

            // redirect back to some edit page
            return $this->redirectToRoute('votedone', array(
                'token' => $myReceipt,
		));
	}	    

        return $this->render('default/vote.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'form' => $form->createView(),
	    'survey' => $s,
	    'questionstitles' => $questionstitles,
        ));

    }   


    /**
     * @Route("/votedone/{token}", name="votedone")
     */
    public function votedoneAction(Request $request, $token)
    {
        return $this->render('default/votedone.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
	    'token' => $token,
        ));

    }   

}
