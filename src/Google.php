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
use Symfony\Component\Finder\Finder;
use LuckyPHP\Server\Exception;
use Google\Service\Drive;
use Google\Client;

/** Class for manage Google client
 * 
 */
class Google{

    # Client
    public $client;
    private $pathJson = null;
    private $pathTokken = __ROOT_APP__."resources/json/tokken.json";

    /** Constructor
     *  
     */
    public function __construct(){

        # Set pathJson
        $this->setPathJson();

        # Prepare Client
        $this->prepareClient();

        # Check tokken
        $this->checkTokken();

    }

    /** Set path json
     * 
     */
    private function setPathJson(){

        # New finder
        $finder = new Finder();

        # Search file
        $finder->files()->name("*.apps.googleusercontent.com.json")->in(__ROOT_APP__."resources/json/");

        # Check finder has result
        if(!$finder->hasResults())
        
            # New exception
            $exception = new Exception("Please download oauth json file from https://console.cloud.google.com", 401);

        # Iteration of folders
        foreach($finder as $file):

            # Get path
            $result = $file->getRealPath();

            # Break loop
            break;

        endforeach;

        # Push result
        $this->pathJson = $result;

    }

    /** Prepare client
     * 
     */
    private function prepareClient(){

        # New client
        $this->client = new Client();
        $this->client->setApplicationName('Fixstudio Wiki');
        $this->client->setScopes(Drive::DRIVE_READONLY);
        $this->client->setAuthConfig($this->pathJson);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

    }

    private function checkTokken(){

        # Set redirect
        $redirect_uri = $_SERVER['REQUEST_SCHEME'].'://'. $_SERVER['HTTP_HOST'].explode('?',$_SERVER['REQUEST_URI'])[0];

        # Check if code as get content
        if(isset($_GET['code'])){

            # Push in client
            $this->client->setRedirectUri($redirect_uri);
        
            # Exchange authorization code for an access token.
            $accessToken = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);

            # Check not NULL
            if($accessToken !== null)
                $this->client->setAccessToken($accessToken);

            // Save the token to a file.
            if (!file_exists(dirname($this->pathTokken))) {
                mkdir(dirname($this->pathTokken), 0700, true);
            }

            file_put_contents($this->pathTokken, json_encode($this->client->getAccessToken()));

        }else if (file_exists($this->pathTokken)){

            $accessToken = json_decode(file_get_contents($this->pathTokken), true);

            // Check not NULL
            if($accessToken !== null)
                $this->client->setAccessToken($accessToken);

        }

        // If there is no previous token or it's expired.
        if ($this->client->isAccessTokenExpired()) {

            // Refresh the token if possible, else fetch a new one.
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            } else {

                # Set redirect uri
                $this->client->setRedirectUri($redirect_uri);

                # Create Auth url
                $authUrl = $this->client->createAuthUrl();

                # Go to connection page
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));

            }

            # Check if file of tokken exists
            if (!file_exists(dirname($this->pathTokken))) {
                mkdir(dirname($this->pathTokken), 0700, true);
            }

            # Put content in path tokken file
            file_put_contents($this->pathTokken, json_encode($this->client->getAccessToken()));
        }

        # Redirect to page
        if(isset($_GET['code'])){

            # Set redirect uri
            $this->client->setRedirectUri($redirect_uri);

            # Redirect to current page
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));

        }

    }

    /** Get client
     * 
     */
    public function get(){

        # Return client
        return $this->client;

    }

    /** exepction check
     * 
     */
    public static function exceptionCheck($e){

        # Set message
        $message = $e->getMessage();

        # check if .json" does not exist
        if(strpos($message, ".json\" does not exist") !== false):

            # New exception
            $exception = new Exception("Please download oauth json file from https://console.cloud.google.com", 401);

        # Else unkown message
        else:

            $exception = new Exception($message, 500);

        endif;

        # Print message

            $exception->getHtml();

        # Stop script
        exit();

    }

}