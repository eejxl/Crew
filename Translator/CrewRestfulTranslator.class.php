<?php
namespace Crew\Translator;

use Common\Translator\RestfulTranslatorTrait;

use Crew\Model\Crew;
use Crew\Model\NullCrew;

use User\Translator\UserRestfulTranslator;

use Role\Translator\RoleRestfulTranslator;

class CrewRestfulTranslator extends UserRestfulTranslator
{
    use RestfulTranslatorTrait;

    protected function getRoleRestfulTranslator() : RoleRestfulTranslator
    {
        return new RoleRestfulTranslator();
    }

    public function arrayToObject(array $expression, $crew = null)
    {
        return $this->translateToObject($expression, $crew);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function translateToObject(array $expression, $crew = null)
    {
        if (empty($expression)) {
            return new NullCrew();
        }

        if ($crew == null) {
            $crew = new Crew();
        }

        $crew = parent::translateToObject($expression, $crew);

        $data = $expression['data'];

        if (isset($data['attributes'])) {
            $attributes = $data['attributes'];
            
            if (isset($attributes['workNumber'])) {
                $crew->setStaffNumber($attributes['workNumber']);
            }
        }

        $relationships = isset($data['relationships']) ? $data['relationships'] : array();

        if (isset($expression['included'])) {
            $relationships = $this->relationship($expression['included'], $relationships);
        }
    
        if (isset($relationships['roles']['data'])) {
            foreach ($relationships['roles']['data'] as $value) {
                $role = $this->changeArrayFormat($value);
                $crew->addRole($this->getRoleRestfulTranslator()->arrayToObject($role));
            }
        }

        return $crew;
    }

    public function objectToArray($crew, array $keys = array())
    {
        $user = parent::objectToArray($crew, $keys);

        if (!$crew instanceof Crew) {
            return array();
        }

        if (empty($keys)) {
            $keys = array(
                'staffNumber',
                'roles',
                'passport'
            );
        }

        $expression = array(
            'data'=>array(
                'type'=>'crews',
                'id' => $crew->getId()
            )
        );

        $attributes = array();

        if (in_array('passport', $keys)) {
            $attributes['passport'] = $crew->getCellphone();
        }

        if (in_array('staffNumber', $keys)) {
            $attributes['workNumber'] = $crew->getStaffNumber();
        }

        $expression['data']['attributes'] = array_merge($user['data']['attributes'], $attributes);

        if (in_array('roles', $keys)) {
            $role = array();

            if (!empty($crew->getRoles())) {
                foreach ($crew->getRoles() as $each) {
                    $role[] = array(
                        'type' => 'roles',
                        'id' => $each->getId()
                    );
                }
            }

            $expression['data']['relationships']['roles']['data'] = $role;
        }

        return $expression;
    }
}
