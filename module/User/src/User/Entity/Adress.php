<?php
namespace User\Entity;

class Adress
{
  
  protected $username="HALLO";
  protected $email;
  
  /**
   * Sets the User Name
   *
   * @param string $name
   * @access public
   * @return User
   */
  public function setUsername($name)
  {
    $this->username = $name;
    return $this;
  }

  /**
   * Returns the userName
   *
   * @access public
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }
  
  
  /**
   * Sets the email
   *
   * @param string $email
   * @access public
   * @return user
   */
  public function setEmail($email)
  {
    $this->email = $email;
    return $this;
  }

  /**
   * Returns the email
   *
   * @access public
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }
  
  /**
   * populating data as an array.
   * key of the array is getter methods name. 
   * 
   * @param array $data
   */
  
  public function populate($data) 
  {
       foreach($data as $key => $value) {
            
           $method = 'set'.ucfirst($key);
            if(method_exists($this, $method))
                $this->$method($value);
       }
       
  }
  
   public function exchangeArray($data)
   {
       
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->email  = (isset($data['email']))  ? $data['email']  : null;
  
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