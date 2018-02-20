<?php

namespace AppBundle\Entity;

/**
 * Province
 */
class Province
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $districts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->profiles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->districts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Province
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
     * @return Province
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
     * @return Province
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
     * @return Province
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
     * Add district.
     *
     * @param \AppBundle\Entity\District $district
     *
     * @return Province
     */
    public function addDistrict(\AppBundle\Entity\District $district)
    {
        $this->districts[] = $district;

        return $this;
    }

    /**
     * Remove district.
     *
     * @param \AppBundle\Entity\District $district
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDistrict(\AppBundle\Entity\District $district)
    {
        return $this->districts->removeElement($district);
    }

    /**
     * Get districts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDistricts()
    {
        return $this->districts;
    }
	
	public function __toString()
	{
		return $this->name;
	}
}
