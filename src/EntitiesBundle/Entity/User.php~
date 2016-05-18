<?php

namespace EntitiesBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $Contacts;


    /**
     * Add Contacts
     *
     * @param \EntitiesBundle\Entity\Contacts $contacts
     * @return User
     */
    public function addContact(\EntitiesBundle\Entity\Contacts $contacts)
    {
        $this->Contacts[] = $contacts;

        return $this;
    }

    /**
     * Remove Contacts
     *
     * @param \EntitiesBundle\Entity\Contacts $contacts
     */
    public function removeContact(\EntitiesBundle\Entity\Contacts $contacts)
    {
        $this->Contacts->removeElement($contacts);
    }

    /**
     * Get Contacts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContacts()
    {
        return $this->Contacts;
    }
}
