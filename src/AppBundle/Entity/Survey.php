<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Survey
 *
 * @ORM\Table(name="survey")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SurveyRepository")
 */
class Survey
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uiduser", type="string", length=255)
     */
    private $uiduser;

    /**
     * @var string
     *
     * @ORM\Column(name="uidmanager", type="string", length=255)
     */
    private $uidmanager;



    /**
     * @var string
     *
     * @ORM\Column(name="surveyname", type="string", length=255)
     */
    private $surveyname;


    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;



    /** 
     * @ORM\Column(name="hasvoted", type="json_array")
     */
    private $hasvoted = array();


    /** 
     * @ORM\Column(name="votereceipt", type="json_array")
     */
    private $votereceipt = array();


    public function addTohasvoted($value) {
        array_push($this->hasvoted, $value);
    }



    /** 
     * @ORM\Column(name="replies", type="json_array")
     */
    private $replies = array();

    /** 
     * @ORM\Column(name="mergedreplies", type="json_array")
     */
    private $mergedreplies = array();


    public function addToreplies($value) {
        array_push($this->replies, $value);
    }
    

/*
    public function setReply($name, $value) {
        $this->replies[$name] = $value;
    }

    public function getReply($name) {
        return $this->replies[$name];
    }
*/

    /**
     * @var int
     *
     * @ORM\Column(name="numberofvoters", type="integer")
     */
    private $numberofvoters;



    /**
     * @ORM\OneToMany(targetEntity="Person", mappedBy="survey")
     */
    private $persons;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="survey", cascade={"persist"})
     */
    private $questions;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Survey
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Survey
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set replies
     *
     * @param array $replies
     * @return Survey
     */
    public function setReplies($replies)
    {
        $this->replies = $replies;

        return $this;
    }

    /**
     * Get replies
     *
     * @return array 
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * Add persons
     *
     * @param \AppBundle\Entity\Person $persons
     * @return Survey
     */
    public function addPerson(\AppBundle\Entity\Person $persons)
    {
        $this->persons[] = $persons;

        return $this;
    }

    /**
     * Remove persons
     *
     * @param \AppBundle\Entity\Person $persons
     */
    public function removePerson(\AppBundle\Entity\Person $persons)
    {
        $this->persons->removeElement($persons);
    }

    /**
     * Get persons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * Add questions
     *
     * @param \AppBundle\Entity\Question $questions
     * @return Survey
     */
    public function addQuestion(\AppBundle\Entity\Question $question)
    {
        //$this->questions[] = $questions;
	$this->questions->add($question);
        return $this;
    }

    /**
     * Remove questions
     *
     * @param \AppBundle\Entity\Question $questions
     */
    public function removeQuestion(\AppBundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set hasvoted
     *
     * @param array $hasvoted
     * @return Survey
     */
    public function setHasvoted($hasvoted)
    {
        $this->hasvoted = $hasvoted;

        return $this;
    }

    /**
     * Get hasvoted
     *
     * @return array 
     */
    public function getHasvoted()
    {
        return $this->hasvoted;
    }



    /**
     * Set surveyname
     *
     * @param string $surveyname
     * @return Survey
     */
    public function setSurveyname($surveyname)
    {
        $this->surveyname = $surveyname;

        return $this;
    }

    /**
     * Get surveyname
     *
     * @return string 
     */
    public function getSurveyname()
    {
        return $this->surveyname;
    }

    /**
     * Set uiduser
     *
     * @param string $uiduser
     * @return Survey
     */
    public function setUiduser($uiduser)
    {
        $this->uiduser = $uiduser;

        return $this;
    }

    /**
     * Get uiduser
     *
     * @return string 
     */
    public function getUiduser()
    {
        return $this->uiduser;
    }

    /**
     * Set uidmanager
     *
     * @param string $uidmanager
     * @return Survey
     */
    public function setUidmanager($uidmanager)
    {
        $this->uidmanager = $uidmanager;

        return $this;
    }

    /**
     * Get uidmanager
     *
     * @return string 
     */
    public function getUidmanager()
    {
        return $this->uidmanager;
    }

    /**
     * Set numberofvoters
     *
     * @param integer $numberofvoters
     * @return Survey
     */
    public function setNumberofvoters($numberofvoters)
    {
        $this->numberofvoters = $numberofvoters;

        return $this;
    }

    /**
     * Get numberofvoters
     *
     * @return integer 
     */
    public function getNumberofvoters()
    {
        return $this->numberofvoters;
    }

    /**
     * Set mergedreplies
     *
     * @param array $mergedreplies
     * @return Survey
     */
    public function setMergedreplies($mergedreplies)
    {
        $this->mergedreplies = $mergedreplies;

        return $this;
    }

    /**
     * Get mergedreplies
     *
     * @return array 
     */
    public function getMergedreplies()
    {
        return $this->mergedreplies;
    }

    /**
     * Set votereceipt
     *
     * @param array $votereceipt
     * @return Survey
     */
    public function setVotereceipt($votereceipt)
    {
        $this->votereceipt = $votereceipt;

        return $this;
    }

    /**
     * Get votereceipt
     *
     * @return array 
     */
    public function getVotereceipt()
    {
        return $this->votereceipt;
    }
}
