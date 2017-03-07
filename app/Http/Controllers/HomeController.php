<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
    	$options = [
			'http' => [
				'method' => 'GET',
				'header' => [
					'User-Agent: PHP'
				]
			]
		];

		$context = stream_context_create($options);

		$response = file_get_contents('https://api.github.com/repos/laravel/laravel/tags', false, $context);

  		dd(
  			json_decode($response, true)[0]['name']
  		);
  		
		/*
			Sample response
			[
				{
					"name":"v5.4.15",
					"zipball_url":"https://api.github.com/repos/laravel/laravel/zipball/v5.4.15",
					"tarball_url":"https://api.github.com/repos/laravel/laravel/tarball/v5.4.15",
					"commit":{
						"sha":"48f44440f7713d3267af2969ed84297455f3787e",
						"url":"https://api.github.com/repos/laravel/laravel/commits/48f44440f7713d3267af2969ed84297455f3787e"
					}
				}
			]
  		*/
  	
    }
}
