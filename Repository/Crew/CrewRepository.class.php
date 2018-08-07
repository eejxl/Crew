<?php
namespace Crew\Repository\Crew;

use Common\Repository\FetchRepositoryTrait;
use Common\Repository\AsyncRepositoryTrait;

use System\Interfaces\INull;

use Crew\Adapter\Crew\CrewRestfulAdapter;
use Crew\Adapter\Crew\ICrewAdapter;
use Crew\Model\Crew;

class CrewRepository implements ICrewAdapter
{
    use AsyncRepositoryTrait, FetchRepositoryTrait;

    const LIST_MODEL_UN = 'CREW_LIST';
    const FETCH_ONE_MODEL_UN = 'CREW_FETCH_ONE';
    const CREW_PURVIEW = 'CREW_PURVIEW';

    private $adapter;

    public function __construct()
    {
        $this->adapter = new CrewRestfulAdapter();
    }

    public function setAdapter(ICrewAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function scenario($scenario)
    {
        $this->getAdapter()->scenario($scenario);
        return $this;
    }
}
