<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lefebv_b
 * Date: 01/12/13
 * Time: 19:00
 * To change this template use File | Settings | File Templates.
 */

namespace Chat;

/**
 * @Entity @Table(name="chat_message")
 */
class Message extends \Model
{
    /**
     * @Id @GeneratedValue(strategy="AUTO") @Column(type="integer")
     */
    public $id;

    /**
     * @ManyToOne(targetEntity="\User")
     */
    private $owner;

    /**
     * @ManyToMany(targetEntity="\User")
     */
    private $participants;

    /**
     * @Column(type="text")
     */
    private $content;

    /**
     * @Column(type="text")
     */
    private $data;

    public function __construct()
    {
        $this->owner = \User::current_user();
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->content = "";
        $this->data = json_encode(array());
    }

    public function toArray()
    {
        $participants = array();
        foreach ($this->participants as $participant)
        {
            $participants[] = $participant->toArray();
        }
        $participants[] = $this->getOwner()->toArray();
        $array = array(
            "id" => $this->id,
            "content" => $this->content,
            "owner" => $this->getOwner() != null ? $this->getOwner()->toArray() : null,
            "participants" => $participants
        );

        return $array;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    public function addParticipant($participant)
    {
        $this->participants[] = $participant;
    }

    public function removeParticipant($participant)
    {
        $this->participants->removeElement($participant);
    }

    public function getParticipants()
    {
        return $this->participants;
    }
}