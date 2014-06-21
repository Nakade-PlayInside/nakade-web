<?php
namespace League\Form\Hydrator;

use League\Entity\Result;
use Season\Entity\Match;
use User\Entity\User;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;

/**
 * Class SeasonHydrator
 *
 * @package Season\Form
 */
class ResultHydrator implements HydratorInterface
{
    private $entityManager;
    private $authenticationService;

    /**
     * @param EntityManager         $em
     * @param AuthenticationService $auth
     */
    public function __construct(EntityManager $em, AuthenticationService $auth)
    {
        $this->entityManager = $em;
        $this->authenticationService = $auth;
    }

    /**
     * @param object $match
     *
     * @return array
     */
    public function extract($match)
    {
        $resultId = $winnerId = -1;
        $points = null;

        /* @var $match \Season\Entity\Match */
        if ($match->hasResult()) {

            $resultId = $match->getResult()->getResultType()->getId();
            $points = $match->getResult()->getPoints();

            if ($match->hasResult() && $match->getResult()->hasWinner()) {
                $winnerId = $match->getResult()->getWinner()->getId();
            }

        }

        return array(
          'id' => $match->getId(),
          'winnerId' => $winnerId,
          'resultId' => $resultId,
          'points' => $points
        );
    }

    /**
     * @param array  $data
     * @param object $match
     *
     * @return object
     */
    public function hydrate(array $data, $match)
    {
        $result = new Result();

        /* @var $match \Season\Entity\Match */
        if (!empty($data['winnerId'])) {
            $data['winner'] = $this->getWinner($match, $data['winnerId']);
        }
        if (!empty($data['resultId'])) {
            $data['result'] = $this->getResult($data['resultId']);
        }
        $data['date'] = new \DateTime();
        $data['enteredBy'] = $this->getEditor();

        $result->exchangeArray($data);
        $match->setResult($result);

        return $match;
    }

    /**
     * @param Match $match
     * @param int   $winnerId
     *
     * @return null|\User\Entity\User
     */
    private function getWinner(Match $match, $winnerId)
    {
        $winner = null;
        if ($winnerId == $match->getBlack()->getId()) {
            $winner = $match->getBlack();
        } elseif ($winnerId == $match->getWhite()->getId()) {
            $winner = $match->getWhite();
        }
        return $winner;
    }

    /**
     * @param int $resultId
     *
     * @return null|\League\Entity\ResultType
     */
    private function getResult($resultId)
    {
        return $this->getEntityManager()->getReference('League\Entity\ResultType', intval($resultId));
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @return User
     */
    private function getEditor()
    {
        $authService = $this->getAuthenticationService();
        if (!$authService->hasIdentity()) {
            return null;
        }

        $userId = $authService->getIdentity()->getId();
        return $this->getEntityManager()->getReference('User\Entity\User', intval($userId));
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }


}
