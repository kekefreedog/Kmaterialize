<?php declare(strict_types=1);
/*******************************************************
 * Copyright (C) 2019-2022 Kévin Zarshenas
 * kevin.zarshenas@gmail.com
 *
 * This file is part of LuckyPHP.
 *
 * This code can not be copied and/or distributed without the express
 * permission of Kévin Zarshenas @kekefreedog
 *******************************************************/

/** Namespace
 *
 */
namespace App\Controllers;

/** Dependances
 *
 */
use LuckyPHP\Base\Controller as ControllerBase;
use LuckyPHP\Interface\Controller as ControllerInterface;

/** Class for manage the workflow of the app
 *
 */
class SidenavChangeAction extends ControllerBase implements ControllerInterface{

    # Conditions
    private $conditions = [
        "statusAllowed" =>  [
            "expanded",
            "collapse"
        ]
    ];

    /** Constructor
     *
     */
    public function __construct(...$arguments){

        # Parent constructor
        parent::__construct(...$arguments);

        # Set name
        $this->name="SidenavAction";

        # Prepare Cookie
        $this->prepareCookie();

        # Set cookie
        $this->setCookie();

    }

    # Prepare Cookie
    private function prepareCookie(){

        # Check if parameters status is in statusAllowed
        if(!in_array($this->parameters['status'], $this->conditions["statusAllowed"]))
            exit();

        # Prepare new cookie
        $this->setCookie([
            "name"      =>  "SIDENAV_STATUS",
            "value"     =>  $this->parameters['status'],
            "secure"    =>  false,
        ]);

        # Push cookie in data
        $this->pushData($_COOKIE);


    }

    /** Response
     *
     */
    public function response(){

        # Return reponse
        return $this->name;

    }
}