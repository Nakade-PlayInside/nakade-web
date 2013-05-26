<?php
namespace League\Controller;

/**
 * Interface for implementing setter and getter for a service.
 * 
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
interface MyServiceInterface {
    
     public function setService($service);
     public function getService();   
}
