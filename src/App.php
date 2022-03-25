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

/** Register The Auto Loader (composer)
 * 
 */
require __DIR__.'/../vendor/autoload.php';

/** Use extra classes
 * 
 */
use App\Controller;
use App\Kernel;
use App\Viewer;
use App\Google;

/** Class for manage the workflow of the app
 * 
 */
class App extends Kernel{

    /** Cconstruct
     * 
     */
    public function __construct(){

        /** Start chrono
         * 
         */
        $this->chronoStart();

        /** Define roots contants of the app
         * Define global variable :
         *  __ROOT_APP__ root of the app 
         *  __ROOT_WWW__ root of the www folder
         *  __ROOT_LUCKYPHP__ root of the vendor LuckyPHP
         * 
         */
        self::rootsDefine();
        
        /** Check if the app is useable
         * Check php
         * Check if the host host is allowed or not allowed
         * Check database
         * 
         */
        self::sanityCheck();

        /** Read the config of the app
         * - Set config in $this->config
         * 
         */
        $this->configSet();

        /** Connection to google api
         * 
         */
        try {
            new Google();
        } catch (Exception $e) {
            Google::exceptionCheck($e);
        }

        /** Set context
         * - Set context of the current request in __CONTEXT__
         * 
         */
        $this->contextSet();

        /** Read the application kernel
         * (search cache or generate it)
         *  - Execute the construct function in parent
         *  - Set cache in $this->cache
         * 
         */
        parent::__construct();

        /** Execute the controller
         *  - Construct and register modal & middleware action
         * 
         */
        $this->controller = new Controller(
            $this->config,
            $this->cache,
        );

        /** Get view
         * 
         */
        $this->viewer = new Viewer(
            $this->controller,
            $this->config,
            $this->cache,
            function($p){
                $this->chronoStop($p);
            }
        );

    }

}

/** Create app instance
 * 
 */
$app = new App();

/** Return App
 * 
 */
return $app;