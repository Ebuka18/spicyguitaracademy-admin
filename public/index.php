<?php
namespace App;
use Framework\Http;
use Framework\Request;
use Framework\Response;
// use Initframework\TestAutoloader;
// use ESAPI;

use App\Auth as AppAuth;
use App\User;

// Include autoload for composer packages
include_once '../vendor/autoload.php';

// Setup Configurations
include_once '../config.php';

class App
{
   public function __construct()
   {
   
      $http = new Http();

      // set the route for the application

      // set route for controller methods
      // $http->get('/','HomeController@index');
      // $http->get('/users','HomeController@users');

      // set route that are handled here
      // test put from the html
      // $http->put('/test-put-from-html', function ( Request $req, Response $res ) {
      //    $res->send($req->body()->putvar);
      // });
      // // test put from an api client
      // $http->put('/users','HomeController@users');

      // // test the route parameter datatype
      // $http->put('/users/{id:d}', function ( Request $req, Response $res ) {
      //    $res->send($req->uri(). " - " .$req->params()->id);
      // });

      $http->auth("None")->get('/auth-none', function ( Request $req, Response $res ) {
         $res->send("No Auth");
      });

      $http->auth("Basic")->get('/auth-basic', function ( Request $req, Response $res ) {
         $res->send("Basic Auth");
      });

      $http->auth("Digest")->get('/auth-digest', function ( Request $req, Response $res ) {
         $res->send("Digest Auth");
      });

      $http->auth('Session')->get('/auth-session', function ( Request $req, Response $res) {
         $res->send("Session Auth");
      });

      $http->get('/login', function ($req, $res) {
         $res->send(View::render('login.html'));
      });

      $http->post('/auth', function (Request $req, Response $res) {
         
         if ((new AppAuth())->auth_session_login($req, $res)) {

            $res->redirect("dashboard");

         } else {
            $res->send(View::render('login.html'), 400);
         }
         
      });

      $http->auth('Session')->get('/dashboard', function (Request $req, Response $res) {
         $res->send(View::render('dashboard.html'));
         // $user = User::user();
         // $res->send("Welcome " . ucfirst($user->username) . "<br>You have " . $user->privileges . " privileges." );
      });

      $http->post('/logout', function ($req, $res) {
         (new AppAuth())->auth_session_logout($req, $res);
      });

      $http->end();
   }

}

// foreach ($_SERVER as $key => $value) {
//    echo sprintf("%s ==========> %s <br>", $key, $value);
// }

// echo isset($_SESSION['AUTH']) ? 'Yes' : 'No';
// die;

// Start Application 😉
new App();
