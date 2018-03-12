<?php

namespace StorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serial;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="StorageBundle\Repository\TaskRepository")
 */
class Task
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @Serial\ReadOnly()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $completed = false;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description = '';

    /**
     * @var int
     *
     * @ORM\Column(name="time", type="integer", nullable=true)
     */
    private $time;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $due;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id = null)
    {
        $this->id = $id;
    }

    public function isCompleted() : bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed) : void
    {
        $this->completed = $completed;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Task
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
     * Set description
     *
     * @param string $description
     *
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set time
     *
     * @param integer $time
     *
     * @return Task
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    public function getDue() : ? \DateTime
    {
        return $this->due;
    }

    public function setDue(\DateTime $DateTime = null) : void
    {
        $this->due = $DateTime;
    }
}
