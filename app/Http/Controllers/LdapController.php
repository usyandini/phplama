<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\AdldapInterface;

class LdapController extends Controller
{
     /**
     * @var Adldap
    */
    protected $adldap;
    
    /**
     * Constructor.
     *
     * @param AdldapInterface $adldap
    */

    public function __construct(AdldapInterface $adldap)
    {
        $this->adldap = $adldap;
    }
    
    /**
     * Displays the all LDAP users.
     *
     * @return \Illuminate\View\View
    */

    public function ldap()
    {
        $users = $this->adldap->search()->users()->get();
        dd($users);
        // return view('users.index', compact('users'));
    }
}
