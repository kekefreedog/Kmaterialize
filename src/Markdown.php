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
use \ParsedownExtra;

/** Class for manage Google Drive
 * 
 */
class Markdown{

    # Content
    public $content = "";

    # Set result
    private $result = "";

    # Instance
    private $instance;

    /** Constructor
     * @param $client
     * @param array $input
     */
    public function __construct(string|array $input = [], array $options = []){

        # ingest input
        $this->ingestInput($input);

    }

    /** Ingest input
     * 
     */
    private function ingestInput(array|string $inputs):void{

        # Set content
        $this->content = is_array($inputs) ?
            implode("", $inputs) :
                $inputs;

    }

    /** Parse content
     *
     */
    private function parseContent(){

        # New parser
        $parsdown = new ParsedownExtra();

        $this->result = $parsdown->text($this->content);

    }

    /** Execute
     * 
     */
    public function execute():string{

        # Parse content
        $this->parseContent();

        # Return result
        return $this->result;

    }

}