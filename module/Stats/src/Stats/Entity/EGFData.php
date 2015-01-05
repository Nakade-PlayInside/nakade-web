<?php
namespace Stats\Entity;

/**
 * Class EGFData
 *
 * @package Stats\Entity
 */
class EGFData
{
    private $firstName;
    private $lastName;
    private $pin;
    private $country;
    private $city;
    private $grade;
    private $noTournaments;
    private $rating;

    /**
     * @param array $egfData
     */
    public function __construct(array $egfData)
    {
        $this->populate($egfData);
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    }

    /**
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param int $noTournaments
     */
    public function setNoTournaments($noTournaments)
    {
        $this->noTournaments = $noTournaments;
    }

    /**
     * @return int
     */
    public function getNoTournaments()
    {
        return $this->noTournaments;
    }

    /**
     * @param int $pin
     */
    public function setPin($pin)
    {
        $this->pin = $pin;
    }

    /**
     * @return int
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function populate(array $data)
    {
        if (array_key_exists('Pin_Player', $data)) {
            $pin = intval($data['Pin_Player']);
            $this->setPin($pin);
        }

        if (array_key_exists('Grade', $data)) {
            $this->setGrade($data['Grade']);
        }

        if (array_key_exists('Country_Code', $data)) {
            $this->setCountry($data['Country_Code']);
        }

        if (array_key_exists('Club', $data)) {
            $this->setCity($data['Club']);
        }

        if (array_key_exists('Gor', $data)) {
            $rating = intval($data['Gor']);
            $this->setRating($rating);
        }

        if (array_key_exists('Tot_Tournaments', $data)) {
            $noTournaments = intval($data['Tot_Tournaments']);
            $this->setNoTournaments($noTournaments);
        }

        if (array_key_exists('Last_Name', $data)) {
            $this->setLastName($data['Last_Name']);
        }

        if (array_key_exists('Name', $data)) {
            $this->setFirstName($data['Name']);
        }

        return $this;

    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function exchangeArray($data)
    {
        return $this->populate($data);

    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}