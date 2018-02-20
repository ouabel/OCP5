<?php

namespace AppBundle\Entity;

/**
 * District
 */
class District
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
     * @var int
     */
    private $zip;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $profiles;

    /**
     * @var \AppBundle\Entity\Province
     */
    private $province;

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
     * @return District
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
     * @return District
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
     * Set zip.
     *
     * @param int $zip
     *
     * @return District
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip.
     *
     * @return int
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Add profile.
     *
     * @param \AppBundle\Entity\Profile $profile
     *
     * @return District
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

    /**
     * Set province.
     *
     * @param \AppBundle\Entity\Province $province
     *
     * @return District
     */
    public function setProvince(\AppBundle\Entity\Province $province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province.
     *
     * @return \AppBundle\Entity\Province
     */
    public function getProvince()
    {
        return $this->province;
    }
	
	public function __toString()
	{
		return $this->name;
	}
}
