<?php
namespace Tecnolaw\Authorization\Services;

class SessionService
{
	protected $user = null;
	protected $token = null;

	function __construct($user,$token) {
		$this->user = $user;
		$this->token = $token;
	}

	/**
	 * Funcion para generar y guardar un token, en el guardado
	 * tambien se incluye la ip mediante $_SERVER
	 *
	 * @param  App\User $user
	 * @return stirng token
	 */
	public function get()
	{
		$session= [
			'authorization' => $this->token->generate(),
			'status' 		=> $this->user->status,
			'full_name' 	=> $this->user->full_name,
			'username' 		=> $this->user->username,
		];
		return $session;
	}
}