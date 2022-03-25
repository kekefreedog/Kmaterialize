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
use LuckyPHP\Front\Template;
use LuckyPHP\Http\Records;
use App\GoogleDrive;

/** Class for manage the workflow of the app
 *
 */
class FileDriveAction extends ControllerBase implements ControllerInterface{

    /** Constructor
     *
     */
    public function __construct(...$arguments){

        # Parent constructor
        parent::__construct(...$arguments);

        # Set name
        $this->name="SidenavAction";

        # Prepare Cookie
        $this->action();

    }

    # Prepare Data
    private function action(){

        # Set id
        $id = $this->parameters['id'];

        # New model
        $this->newModel();

        /**
         *  Push data in model
         */

        # new google_drive
        $this->google_drive = new GoogleDrive();

        # Get current record
        $raw = $this->google_drive->getDataInfoById($id, array_keys(self::FIELDS));

        # Prepare records
        $record = Records::formatRecord([
            "id"        =>  $raw['id'],
            "entity"    =>  $raw['entity'],
        ]);

        # Iteration raw
        foreach($raw as $k => $v)

            # Check id isn't id or entity
            if(
                !in_array($k, ["id", "entity"]) &&
                isset(self::FIELDS[$k])
            ){

                # Prepare current value
                $valueTemp = self::FIELDS[$k];
                $valueTemp["attributes"] = [
                    "value" =>  $k,
                    "name"  =>  $v,
                ];

                # Push value in children
                $record["relationships"]["fields"][] = $valueTemp;

            }

        // Push records
        $this->model->pushRecords($record, "single");

        /**
         *  Push template
         */

        # Load template
        $template = new Template();

        # Load layout
        $content = $template
            ->loadLayouts([
                "popup/header",
                "popup/main/info"
            ])
            ->build()
        ;

        $this->model
            ->pushDataInUserInterface([
                "actions"    =>  [
                    [
                        "type"      =>  "hbs",
                        "target"    =>  "Swal",
                        "content"   =>  $content
                    ]
                ]
            ])
        ;

        # Push data in model
        $this->pushData($this->model->execute());


    }

    /** Response
     *
     */
    public function response(){

        # Return reponse
        return $this->name;

    }

    /** List fields to display
     * 
     */
    private const FIELDS = [
        "id"                    =>  [
            "_style"    =>  [
                "icon"  =>  [
                    "class" =>  "",
                    "icon"  =>  ""
                ],
                "href"  =>  null,
            ],
        ],
        "name"                  =>  [
            "_style"    =>  [
                "icon"  =>  [
                    "class" =>  "",
                    "icon"  =>  ""
                ],
                "href"  =>  null,
            ],
        ],
        "createdTime"           =>  [
            "_style"    =>  [
                "icon"  =>  [
                    "class" =>  "",
                    "icon"  =>  ""
                ],
                "href"  =>  null,
            ],
        ],
        "modifiedTime"          =>  [
            "_style"    =>  [
                "icon"  =>  [
                    "class" =>  "",
                    "icon"  =>  ""
                ],
                "href"  =>  null,
            ],
        ],
        "fileExtension"         =>  [
            "_style"    =>  [
                "icon"  =>  [
                    "class" =>  "",
                    "icon"  =>  ""
                ],
                "href"  =>  null,
            ],
        ],
        "size"                  =>  [
            "_style"    =>  [
                "icon"  =>  [
                    "class" =>  "",
                    "icon"  =>  ""
                ],
                "href"  =>  null,
            ],
        ],
        "webContentLink"        =>  [
            "_style"    =>  [
                "icon"  =>  [
                    "class" =>  "",
                    "icon"  =>  ""
                ],
                "href"  =>  null,
            ],
        ],
        "mimeType"              =>  [
            "_style"    =>  [
                "icon"  =>  [
                    "class" =>  "",
                    "icon"  =>  ""
                ],
                "href"  =>  null,
            ],
        ],
        "lastModifyingUser"     =>  [
            "_style"    =>  [
                "icon"  =>  [
                    "class" =>  "",
                    "icon"  =>  ""
                ],
                "href"  =>  null,
            ],
        ],
    ];

}