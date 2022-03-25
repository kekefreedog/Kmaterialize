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
use LuckyPHP\Interface\Controller as ControllerInterface;
use LuckyPHP\Base\Controller as ControllerBase;
use LuckyPHP\Server\Exception;

/** Class for manage the workflow of the app
 *
 */
class PageNotFoundAction extends ControllerBase implements ControllerInterface{

    /** Constructor
     *
     */
    public function __construct(...$arguments){

        # Parent constructor
        parent::__construct(...$arguments);

        # Set name
        $this->name="PageNotFoundAction";

        # New Exception
        $e = new Exception("The page you are looking for doesn't exist", 404);

        # Display html
        $e->getHtml();

    }

    /** Response
     *
     */
    public function response(){

        # Return reponse
        return $this->name;

    }
}