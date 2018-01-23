<?php
// src/AppBundle/Entity/Message.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Message as BaseMessage;

/**
 * @ORM\Entity
 */
class Message extends BaseMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="AppBundle\Entity\Thread",
     *   inversedBy="messages"
     * )
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $sender;

    /**
     * @ORM\OneToMany(
     *   targetEntity="AppBundle\Entity\MessageMetadata",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     * @var MessageMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * Add metadata
     *
     * @param \AppBundle\Entity\MessageMetadata $metadata
     * @return Message
     */
    public function addMetadatum(\AppBundle\Entity\MessageMetadata $metadata)
    {
        $this->metadata[] = $metadata;

        return $this;
    }

    /**
     * Remove metadata
     *
     * @param \AppBundle\Entity\MessageMetadata $metadata
     */
    public function removeMetadatum(\AppBundle\Entity\MessageMetadata $metadata)
    {
        $this->metadata->removeElement($metadata);
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
