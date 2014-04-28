<?php
namespace User\View\Helper;

use Nakade\Abstracts\AbstractViewHelper;

/**
 * shows the sex of the user by an image
 */
class Salutation extends AbstractViewHelper
{
    /**
     * @param string $sex
     *
     * @return string
     */
    public function __invoke($sex)
    {
        //default
        $placeholder = array(
            'sex'   => 'female',
            'image' => 'images/small_female.png',
        );

        if (strtolower($sex) == 'm') {
            $placeholder['sex']   = 'male';
            $placeholder['image'] = 'images/small_male.png';
        }

        $img = '<img alt="%sex" src="%image%">';

        return $this->setPlaceholders($img, $placeholder);

    }


}
