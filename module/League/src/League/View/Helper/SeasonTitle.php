<?php
namespace League\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * View helper getting a season title.
 */
class SeasonTitle extends AbstractHelper
{
    /**
     * return season title
     * 
     * @param Season $season
     * @return string
     */
    public function __invoke($season)
    {
     
       if( null == $season )
            return;
      
       return  $season->getTitle() . ' ' .
               $this->getView()->translate('No.') . 
               $season->getNumber();
    }
}
