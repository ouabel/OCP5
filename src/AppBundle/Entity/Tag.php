<?php

namespace AppBundle\Entity;

/**
 * Tag
 */
class Tag
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $profiles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->profiles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return Tag
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add profile.
     *
     * @param \AppBundle\Entity\Profile $profile
     *
     * @return Tag
     */
    public function addProfile(\AppBundle\Entity\Profile $profile)
    {
        $this->profiles[] = $profile;

        return $this;
    }

    /**
     * Remove profile.
     *
     * @param \AppBundle\Entity\Profile $profile
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProfile(\AppBundle\Entity\Profile $profile)
    {
        return $this->profiles->removeElement($profile);
    }

    /**
     * Get profiles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfiles()
    {
        return $this->profiles;
    }
	
	public function __toString()
	{
		return $this->name;
	}
}
