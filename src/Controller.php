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
namespace App;

/** Dependances
 * 
 */
use LuckyPHP\Base\Router;
use LuckyPHP\Http\Request;

/** Class of the controller
 * 
 */
class Controller{

    /** Constructor
     * 
     */
    public function __construct($config = [], $cache = []){

        # New Request
        $this->request = new Request();

        # New Rooter
        $this->router = new Router($this->request);

        # Set response
        $this->response = $this->router->getResponse();

        # Set callback
        $this->callback = $this->router->getCallback();

    } 

}