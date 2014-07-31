<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\WebInterface;

use BotWars\Arena\Playfield;
use React\Http\Request;
use React\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;


class Application extends \Silex\Application
{

	public function react(Request $request, Response $response, $requestData = '')
	{
		$sfRequest = $this->buildSymfonyRequest($request, $requestData);
		$sfResponse = $this->getHandledResponse($sfRequest);
		$this->parseSymfonyResponse($response, $sfResponse);
	}

	private function buildSymfonyRequest(Request $request, $requestData = '')
	{
		$params = $this->extractRequestParameters($requestData);
		return SymfonyRequest::create($request->getPath(), $request->getMethod(), $params);
	}

	private function extractRequestParameters($requestData = '')
	{
		$params = array();
		if ($requestData) {
			$rawParams = explode('&', $requestData);
			foreach($rawParams as $rawParam){
				$receivedParam = explode('=', $rawParam);
				$params[urldecode($receivedParam[0])] = urldecode($receivedParam[1]);
			}
		}
		return $params;
	}

	private function getHandledResponse(SymfonyRequest $sfRequest)
	{
		$sfResponse = null;
		try {
			$sfResponse = $this->handle($sfRequest, HttpKernelInterface::MASTER_REQUEST, false);
			$this->terminate($sfRequest, $sfResponse);
		} catch(NotFoundHttpException $e){
			$sfResponse = new SymfonyResponse('Ups! ' . $e->getMessage(), 404);
		} catch (\Exception $e) {
			$sfResponse = new SymfonyResponse($this['twig']->render("exception.twig",array("exception"=>array("message"=>$e->getMessage(),"stacktrace"=>$e->getTrace()))), 500);
			echo $e->getMessage();
			//var_dump($e->getTrace());
			//$sfResponse = new SymfonyResponse("Sorry",500);
		}
		return $sfResponse;
	}

	private function parseSymfonyResponse(Response $response, SymfonyResponse $sfResponse)
	{
		$response->writeHead($sfResponse->getStatusCode(), $this->extractResponseHeaders($sfResponse));
		$response->end($sfResponse->getContent());
	}

	private function extractResponseHeaders(SymfonyResponse $sfResponse)
	{
		$headers = array();
		foreach ($sfResponse->headers->allPreserveCase() as $name => $values) {
			foreach ($values as $value) {
				$headers[$name] = $value;
			}
		}
		return $headers;
	}

}


$app = new Application();
$app['debug'] = true;
$app['version']="0.1.1";


$app->register(new \Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/views',
	'twig.options' => array (
		'debug' => true,
		'strict_variables' => true,
		'auto_reload' => true
	)
));




$app->get('/', function () use ($app) {

	return $app['twig']->render('hello.twig', array(
		'name' => "Daniel Haas",
	));

});

$app->get('/favicon.ico', function () {
	return file_get_contents(__DIR__."/static/images/favicon.ico");
});

$app->get('/humans.txt', function () {
	return "I believe you are a humanoid robot.\n";
});


/**
 * Route for static files
 */
$app->get('/static/{folder}/{filename}', function ($folder, $filename) use ($app){
	$filePath = __DIR__.'/static/'.$folder.'/'.$filename;
	$fileExtension=strtolower(substr($filePath,strrpos($filePath,".")));
	if (!file_exists($filePath))
	{
		$app->abort(404);
	}
	if ($folder == 'css')
	{
		return new SymfonyResponse(file_get_contents($filePath),200,array('Content-Type' => 'text/css'));
		//somehow this does not work: :-(
		//return $app->sendFile($filePath, 200, array('Content-Type' => 'text/css'));
	}
	else if ($folder == "js")
	{
		return new SymfonyResponse(file_get_contents($filePath),200,array('Content-Type' => 'text/javascript'));
		//somehow this does not work: :-(
		//return $app->sendFile($filePath, 200, array('Content-Type' => 'text/javascript'));
	}
	else if ($folder == "images")
	{
		if ($fileExtension="jpg" || $fileExtension=="jpeg")
		{
			return new SymfonyResponse(file_get_contents($filePath), 200,array('Content-Type' => 'image/jpg'));
		}
		if ($fileExtension="png")
		{
			return new SymfonyResponse(file_get_contents($filePath), 200,array('Content-Type' => 'image/png'));
		}
	}
	else return new SymfonyResponse(file_get_contents($filePath), 200);
});





return $app;