<?php
namespace Crew\Model;

use Marmot\Core;

use User\Model\User;

use Role\Model\Role;

class Crew extends User
{
    private $roles;

    private $staffNumber;

    public function __construct(int $id = 0)
    {
        parent::__construct($id);
        $this->roles = array();
        $this->staffNumber = '';
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->roles);
        unset($this->staffNumber);
    }

    public function addRole(Role $role) : void
    {
        $this->roles[] = $role;
    }

    public function clearRoles() : void
    {
        $this->roles = [];
    }

    public function getRoles() : array
    {
        return $this->roles;
    }

    public function setStaffNumber(string $staffNumber) : void
    {
        $this->staffNumber = $staffNumber;
    }

    public function getStaffNumber() : string
    {
        return $this->staffNumber;
    }
}
