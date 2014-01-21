<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A Pub
 *
 * @ORM\Entity()
 * @ORM\Table(name="mfdata_pubs")
 * @property integer $id
 */
class Pub
{
    /**
     * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $parent_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $deleted;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $main_pubname;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_region;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_street;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_city;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_zip;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_account_name;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_account_phone;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_account_mail;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_phone;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_website;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_email;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_openinghours;

    /**
     * @ORM\Column(type="integer", nullable=true);
     * @var integer
     */
    private $main_top_pub;

    /**
     * @ORM\Column(type="integer", nullable=true);
     * @var integer
     */
    private $portfolio_10_pale;

    /**
     * @ORM\Column(type="integer", nullable=true);
     * @var integer
     */
    private $portfolio_12_pale;

    /**
     * @ORM\Column(type="integer", nullable=true);
     * @var integer
     */
    private $portfolio_12_dark;

    /**
     * @ORM\Column(type="integer", nullable=true);
     * @var integer
     */
    private $portfolio_12_krouzek;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $lat;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $lng;

    /** UNUSED NOODLE PROPERTIES */
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $inserted;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $inserted_by;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $updated;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $updated_by;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $deleted_by;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $revision;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $revision_id;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_number;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_entrepreneur;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_address;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_account;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_other_info;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_main_photo;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_places;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_places_count;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_garden;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_garden_places;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_lounge;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_lounge_places;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_nonsmoking;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_kids_corner;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_disabled_access;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_parking;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_food_checks;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_credit_cards;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_wifi;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_airco;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_tv_shows;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_kitchen;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_has_kitchen;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_budvar;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $branding_notes;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $main_kitchentype;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $pubrameters_kitchen_type;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $branding_exterier;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $branding_interier;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $branding_tap;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $branding_tap_type;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $branding_external_materials;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_pardal;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_10_pardal_pale;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_11_pardal_pale;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_competition;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_10_pale_packaging;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_12_pale_packaging;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_12_dark_packaging;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_12_krouzek_packaging;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_10_pale_monthly;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_12_pale_monthly;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_12_dark_monthly;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_12_krouzek_monthly;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_10_pale_price;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_12_pale_price;
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    private $portfolio_12_dark_price;

    /**
     * Magic getter to expose protected properties.
     *
     * @param DateTime $property
     * @return mixed
     */
    public function __get($property)
    {
    	return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
    	$this->$property = $value;
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
     * Set deleted
     *
     * @param integer $deleted
     * @return Pub
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return integer
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set main_pubname
     *
     * @param string $mainPubname
     * @return Pub
     */
    public function setMainPubname($mainPubname)
    {
        $this->main_pubname = $mainPubname;

        return $this;
    }

    /**
     * Get main_pubname
     *
     * @return string
     */
    public function getMainPubname()
    {
        return $this->main_pubname;
    }

    /**
     * Set main_region
     *
     * @param string $mainRegion
     * @return Pub
     */
    public function setMainRegion($mainRegion)
    {
        $this->main_region = $mainRegion;

        return $this;
    }

    /**
     * Get main_region
     *
     * @return string
     */
    public function getMainRegion()
    {
        return $this->main_region;
    }

    /**
     * Set main_street
     *
     * @param string $mainStreet
     * @return Pub
     */
    public function setMainStreet($mainStreet)
    {
        $this->main_street = $mainStreet;

        return $this;
    }

    /**
     * Get main_street
     *
     * @return string
     */
    public function getMainStreet()
    {
        return $this->main_street;
    }

    /**
     * Set main_city
     *
     * @param string $mainCity
     * @return Pub
     */
    public function setMainCity($mainCity)
    {
        $this->main_city = $mainCity;

        return $this;
    }

    /**
     * Get main_city
     *
     * @return string
     */
    public function getMainCity()
    {
        return $this->main_city;
    }

    /**
     * Set main_zip
     *
     * @param string $mainZip
     * @return Pub
     */
    public function setMainZip($mainZip)
    {
        $this->main_zip = $mainZip;

        return $this;
    }

    /**
     * Get main_zip
     *
     * @return string
     */
    public function getMainZip()
    {
        return $this->main_zip;
    }

    /**
     * Set main_account_name
     *
     * @param string $mainAccountName
     * @return Pub
     */
    public function setMainAccountName($mainAccountName)
    {
        $this->main_account_name = $mainAccountName;

        return $this;
    }

    /**
     * Get main_account_name
     *
     * @return string
     */
    public function getMainAccountName()
    {
        return $this->main_account_name;
    }

    /**
     * Set main_account_phone
     *
     * @param string $mainAccountPhone
     * @return Pub
     */
    public function setMainAccountPhone($mainAccountPhone)
    {
        $this->main_account_phone = $mainAccountPhone;

        return $this;
    }

    /**
     * Get main_account_phone
     *
     * @return string
     */
    public function getMainAccountPhone()
    {
        return $this->main_account_phone;
    }

    /**
     * Set main_account_mail
     *
     * @param string $mainAccountMail
     * @return Pub
     */
    public function setMainAccountMail($mainAccountMail)
    {
        $this->main_account_mail = $mainAccountMail;

        return $this;
    }

    /**
     * Get main_account_mail
     *
     * @return string
     */
    public function getMainAccountMail()
    {
        return $this->main_account_mail;
    }

    /**
     * Set main_phone
     *
     * @param string $mainPhone
     * @return Pub
     */
    public function setMainPhone($mainPhone)
    {
        $this->main_phone = $mainPhone;

        return $this;
    }

    /**
     * Get main_phone
     *
     * @return string
     */
    public function getMainPhone()
    {
        return $this->main_phone;
    }

    /**
     * Set main_website
     *
     * @param string $mainWebsite
     * @return Pub
     */
    public function setMainWebsite($mainWebsite)
    {
        $this->main_website = $mainWebsite;

        return $this;
    }

    /**
     * Get main_website
     *
     * @return string
     */
    public function getMainWebsite()
    {
        return $this->main_website;
    }

    /**
     * Set main_email
     *
     * @param string $mainEmail
     * @return Pub
     */
    public function setMainEmail($mainEmail)
    {
        $this->main_email = $mainEmail;

        return $this;
    }

    /**
     * Get main_email
     *
     * @return string
     */
    public function getMainEmail()
    {
        return $this->main_email;
    }

    /**
     * Set main_openinghours
     *
     * @param string $mainOpeninghours
     * @return Pub
     */
    public function setMainOpeninghours($mainOpeninghours)
    {
        $this->main_openinghours = $mainOpeninghours;

        return $this;
    }

    /**
     * Get main_openinghours
     *
     * @return string
     */
    public function getMainOpeninghours()
    {
        return $this->main_openinghours;
    }

    /**
     * Set main_top_pub
     *
     * @param integer $mainTopPub
     * @return Pub
     */
    public function setMainTopPub($mainTopPub)
    {
        $this->main_top_pub = $mainTopPub;

        return $this;
    }

    /**
     * Get main_top_pub
     *
     * @return integer
     */
    public function getMainTopPub()
    {
        return $this->main_top_pub;
    }

    /**
     * Set portfolio_10_pale
     *
     * @param integer $portfolio10Pale
     * @return Pub
     */
    public function setPortfolio10Pale($portfolio10Pale)
    {
        $this->portfolio_10_pale = $portfolio10Pale;

        return $this;
    }

    /**
     * Get portfolio_10_pale
     *
     * @return integer
     */
    public function getPortfolio10Pale()
    {
        return $this->portfolio_10_pale;
    }

    /**
     * Set portfolio_12_pale
     *
     * @param integer $portfolio12Pale
     * @return Pub
     */
    public function setPortfolio12Pale($portfolio12Pale)
    {
        $this->portfolio_12_pale = $portfolio12Pale;

        return $this;
    }

    /**
     * Get portfolio_12_pale
     *
     * @return integer
     */
    public function getPortfolio12Pale()
    {
        return $this->portfolio_12_pale;
    }

    /**
     * Set portfolio_12_dark
     *
     * @param integer $portfolio12Dark
     * @return Pub
     */
    public function setPortfolio12Dark($portfolio12Dark)
    {
        $this->portfolio_12_dark = $portfolio12Dark;

        return $this;
    }

    /**
     * Get portfolio_12_dark
     *
     * @return integer
     */
    public function getPortfolio12Dark()
    {
        return $this->portfolio_12_dark;
    }

    /**
     * Set portfolio_12_krouzek
     *
     * @param integer $portfolio12Krouzek
     * @return Pub
     */
    public function setPortfolio12Krouzek($portfolio12Krouzek)
    {
        $this->portfolio_12_krouzek = $portfolio12Krouzek;

        return $this;
    }

    /**
     * Get portfolio_12_krouzek
     *
     * @return integer
     */
    public function getPortfolio12Krouzek()
    {
        return $this->portfolio_12_krouzek;
    }

    /**
     * Set lat
     *
     * @param string $lat
     * @return Pub
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return Pub
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }
}
