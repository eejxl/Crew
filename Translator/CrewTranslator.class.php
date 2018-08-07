<?php
namespace Crew\Translator;

use Crew\Model\NullCrew;
use Crew\Model\Crew;

use User\Translator\UserTranslator;

use Role\Translator\RoleTranslator;

class CrewTranslator extends UserTranslator
{
    protected function getRoleTranslator() : RoleTranslator
    {
        return new RoleTranslator();
    }

    public function arrayToObject(array $expression, $crew = null)
    {
        unset($crew);
        unset($expression);
        return NullCrew::getInstance();
    }

    public function arrayToObjects(array $expression) : array
    {
        unset($expression);
        return array();
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function objectToArray($crew, array $keys = array())
    {
        $user = parent::objectToArray($crew, $keys);

        if (!$crew instanceof Crew) {
            return array();
        }

        if (empty($keys)) {
            $keys = array(
                'staffNumber',
                'roles'=>['id','name']
            );
        }

        $expression = array();

        if (in_array('staffNumber', $keys)) {
            $expression['staffNumber'] = $crew->getStaffNumber();
        }

        if (isset($keys['roles'])) {
            $expression['roles'] = array();
            foreach ($crew->getRoles() as $role) {
                $expression['roles'][] = $this->getRoleTranslator()->objectToArray(
                    $role,
                    $keys['roles']
                );
            }
        }

        $expression = array_merge($user, $expression);

        return $expression;
    }
}
