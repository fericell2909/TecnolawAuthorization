<?php

$router=$this->app->router;
$router->group(['namespace' => 'Tecnolaw\Authorization\Controllers', 'prefix' => 'auth','middleware' => []], function() use ($router) {
	$router->post('sing/in/{type}', 'AuthController@singIn');
	$router->post('sing/up', 'AuthController@singUp');
	$router->post('sing/in', 'AuthController@singIn');
	$router->post('recovery/password', 'ForgotController@recoveryPassword');
	$router->post('change/password', 'ForgotController@changePassword');
	$router->post('recovery/username', 'ForgotController@recoveryUsername');

	// facebbok
	$router->get('facebook', 'AuthWithFacebookController@facebook');
	$router->get('login/facebook', 'AuthWithFacebookController@loginFacebook');
	$router->get('receive/from/facebook2', 'AuthWithFacebookController@receiveFromFacebook2');
	// Google
	$router->get('google', 'AuthWithFacebookController@google');

	// Profile
	$router->group(['middleware' => 'TecnolawAuth'], function () use ($router) {
		$router->get('profile', 'ProfileController@index');
		$router->put('profile', 'ProfileController@update');

		$router->post('phone', 'PhoneController@create');
		$router->put('phone/{id}', 'PhoneController@update');
		$router->delete('phone/{id}', 'PhoneController@delete');

		$router->post('address', 'AddressController@create');
		$router->put('address/{id}', 'AddressController@update');
        $router->delete('address/{id}', 'AddressController@delete');

        $router->put('change/rpassword', 'ForgotController@updatePassword');

	});

});
