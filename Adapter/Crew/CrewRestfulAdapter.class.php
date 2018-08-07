<?php
namespace Crew\Adapter\Crew;

use Common\Adapter\AsyncFetchAbleRestfulAdapterTrait;
use Common\Adapter\FetchAbleRestfulAdapterTrait;

use System\Adapter\Restful\GuzzleAdapter;
use System\Classes\Translator;

use Marmot\Core;

use Crew\Translator\CrewRestfulTranslator;
use Crew\Model\Crew;
use Crew\Model\NullCrew;

class CrewRestfulAdapter extends GuzzleAdapter implements ICrewAdapter
{
    use FetchAbleRestfulAdapterTrait, AsyncFetchAbleRestfulAdapterTrait;

    private $translator;
    private $resource;

    const SCENARIOS = [
        'CREW_LIST'=>[
            'fields'=>[
                'crews'=>'realName,cellphone,updateTime,status,workNumber',
            ]
        ],
        'CREW_FETCH_ONE'=>[
            'fields'=>['roles'=>'name'],
            'include'=>'roles'
        ]
    ];
    
    public function __construct()
    {
        parent::__construct(
            Core::$container->get('services.huizhonglianhe.url')
        );
        $this->translator = new CrewRestfulTranslator();
        $this->resource = 'crews';
        $this->scenario = array();
    }

    protected function getTranslator() : Translator
    {
        return $this->translator;
    }

    protected function getResource() : string
    {
        return $this->resource;
    }

    public function scenario($scenario) : void
    {
        $this->scenario = isset(self::SCENARIOS[$scenario]) ? self::SCENARIOS[$scenario] : array();
    }

    public function fetchOne($id)
    {
         return $this->fetchOneAction($id, new NullCrew());
    }
}
