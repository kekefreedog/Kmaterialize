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
use LuckyPHP\Front\Console;
use App\GoogleDrive;

/** Class for manage the workflow of the app
 *
 */
class DriveAction extends ControllerBase implements ControllerInterface{

    # Google Drive
    private $google_drive;

    /** Constructor
     *
     */
    public function __construct(...$arguments){

        # Parent constructor
        parent::__construct(...$arguments);

        # Set name
        $this->name="DriveAction";

        # Setup layouts
        $this->setupLayouts();

        # Model action
        $this->modelAction();

    }

    /** Setup layouts
     * 
     */
    private function setupLayouts(){

        # Set layouts
        $this->setLayouts([
            'structure/head',
            'structure/sidenav',
            'structure/main',
        ]);

    }

    /** Model action
     * 
     */
    private function modelAction(){

        # New model
        $this->newModel();

        # New google drive
        $this->google_drive = new GoogleDrive();

        # Get all data
        $this->google_drive->getAllFileFromSharedDrive();

        # Set Root
        $root = "/drive/".implode("/", $this->parameters)."/";

        try{
        
            # Get value from directory
            $result = $this->google_drive->getDirectory($root);

        }catch(Exception $e){

            $e->getHtml();

        }

        # Set current file in google drive
        $this->google_drive->setCurrentfile($result['file']);

        try{

            # Get html content
            $htmlContent = $this->google_drive->getContentFile();

            # Get id
            $id = $this->google_drive->getCurrentId();

        }catch(Exception $e){

            # Message html
            $e->getHtml();

        }

        # Load app config
        $this->model
            ->loadConfig('app')
            ->setFrameworkExtra()
            ->pushDataInUserInterface(
                # Navigation
                $this->google_drive->getData() +
                # Main 
                [
                    "main"  =>  [
                        # Get content file of current
                        "<div class=\"col s12 markdown enable-anchors\">$htmlContent</div>"
                    ],
                    "header"=>  [
                        "navbar-list"   =>  [
                            "add"   =>  [
                                [
                                    "type"  =>  "nav-button-circle",
                                    "action"=>  "toggle-info",
                                    "data"  =>  [
                                        "url"   =>  "/api/file/drive/$id"
                                    ],
                                    "style" =>  [
                                        "icon"  =>  [
                                            "class" =>  "material-icons",
                                            "text"  =>  "info"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            )
            ->pushCookies(true)
            ->pushContext()
        ;

        //\LuckyPHP\Front\Console::log($this->model->execute());

    }

    /** Response
     *
     */
    public function response(){

        # Return reponse
        return $this->name;

    }

}