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
use LuckyPHP\Interface\Viewer as ViewerInterface;
use LuckyPHP\Base\Viewer as ViewerBase;

/** Class of the viewer
 * 
 */
class Viewer extends ViewerBase implements ViewerInterface {

    /** Constructor
     * 
     */
    public function __construct(...$arguments){

        /** Parent constructor
         * 
         * - Ingest arguments
         * - Create response object
         * - Define constructor depending of the type of content
         * 
         */
        parent::__construct(...$arguments);

        /** Set global content in response 
         * 
         */
        $this->setResponseContent();

        /** Set global cookie in response
         * 
         */
        $this->setResponseCookies();

        /** Send the response to the client
         * 
         */
        $this->reponseExecute();

    } 

}