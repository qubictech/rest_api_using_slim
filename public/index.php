<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';
require '../includes/db_connect.php';
require '../includes/db_operations.php';

$app = new \Slim\App;

/**
 * endpoint: createuser
 * parameters: email,password,name,school
 * method: POST
 */

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name<br>");

    $db = new DbOperations;

    return $response;
});

/**
 * endpoint: createuser
 * parameters: email,password,name,school
 * method: POST
 */

$app->post('/createuser', function (Request $request, $response) {

    if (!haveEmptyParams(array('email', 'password', 'name', 'school'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $email = $request_data['email'];
        $password = $request_data['password'];
        $name = $request_data['name'];
        $school = $request_data['school'];

        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        $db = new DbOperations;

        $result = $db->createUser($email, $hash_password, $name, $school);

        if ($result == USER_CREATED) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "User created successfully";

            $response->write(json_encode($message));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(201);
        } else if ($result == USER_FAILURE) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Some error occured";

            $response->write(json_encode($message));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(422);
        } else if ($result == USER_EXIST) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "User already exist!";

            $response->write(json_encode($message));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(422);
        }

        $message = array();
        $message['error'] = true;
        $message['message'] = "An error occured";

        $response->write(json_encode($message));

        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(422);
    }
});

function haveEmptyParams($required_params, $request, $response)
{
    $error = false;

    $error_params = '';

    $request_params = $_REQUEST;

    if (!$request_params) {
        $request_params = $request->getParsedBody();;
        print_r($request_params);
    }

    foreach ($required_params as $param) {
        if (!isset($request_params[$param]) || strlen($request_params[$param]) < 0) {
            $error = true;
            $error_params .= $param . ', ';
        }
    }

    if ($error) {
        $error_detail = array();

        $error_detail['error'] = true;
        $error_detail['message'] = 'Required Parameters ' . substr($error_params, 0, -2) . ' are missing';
        // $error_detail['data'] = $required_params;

        $response->write(json_encode($error_detail));
    }

    return $error;
}

$app->run();
