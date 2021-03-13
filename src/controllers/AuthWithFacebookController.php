<?php
namespace Tecnolaw\Authorization\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Tecnolaw\Authorization\Models\User;
use Tecnolaw\Authorization\Models\AuthenticateWith;
use Illuminate\Http\RedirectResponse;
use Tecnolaw\Authorization\Services\TokenService;
use Tecnolaw\Authorization\Services\SessionService;

class AuthWithFacebookController extends BaseController
{
	protected $user=null;

	protected $facebook = "https://www.facebook.com/v7.0/dialog/oauth";
	protected $facebook_access_token = "https://graph.facebook.com/oauth/access_token";
	protected $facebook_graph = "https://graph.facebook.com/v8.0/";

	protected $fields = [];

	function __construct()
	{
		$this->user = new User;
		$this->fields=[
			'client_id' => env('FB_CLIENT_ID'),
			'redirect_uri' => 'http://localhost/thermomix-api/public/auth/login/facebook',
			'state' => '',
		];
	}
	public function facebook()
	{
		$redirectUrl = $this->facebook . '?' . http_build_query($this->fields);
		return new RedirectResponse($redirectUrl);
	}
	public function loginFacebook(Request $request)
	{
		$this->fields['client_secret']=env('FB_CLIENT_SECRET');
		$this->fields['code']=rawurldecode($request->code);
		// post token type  bearer
		$bearer=$this->newCurl('POST',$this->facebook_access_token,$this->fields);
		$profile=null;
		if ( isset($bearer['access_token']) ) {
			// busco perfil en facebook
			$profile = $this->callProfileFacebook($bearer['access_token']);
			// uso el id para ubicarlo si existe
			$user=$this->user->where('fb_id', (int) $profile['id'])->first();
			if($user){
				// actualizo el token
				$user->fb_access_token=$bearer['access_token'];
				$user->save();
			}else{
				// creo el nuevo usuario
				$user=$this->user->create([
					'status'=>1,
					'name'=>$profile['first_name'] ?? null,
					'paternal_surname'=>$profile['last_name'] ?? null,
					'username'=>$profile['name'] ?? null,
					'email'=>$profile['email'] ?? null,
					'birth_date'=>$profile['birthday'] ?? null,
					'fb_id'=> (int) $profile['id'],
					'fb_access_token'=>$bearer['access_token']
				]);
				$user->roles()->attach(2); //comprador
			}
			// genero un nuevo token de auth
			$token = new TokenService($user);
			$session 	= new SessionService($user,$token);
		}
		// redirecciono al front con la info del token
		return new RedirectResponse( env('APP_URL_SITE') . '/auth/facebook?' . http_build_query($session->get()) );
		//return response(,200);
	}
	private function callProfileFacebook($token)
	{
		$fields='first_name,last_name,id,birthday,about,address,email,picture';

		$body=['access_token' => $token ,'fields'=>$fields];
		$profile=$this->newCurl('POST',$this->facebook_graph.'/me',$body);
		return $profile;
	}


	public function google()
	{
		$details = AuthenticateWith::where('slug','google')->first();
		if ($details) {
			return response(['status'=>'success','message'=>'encontrado'],401);
		}else{
			return response(['status'=>'error','message'=>'Google no habilitado '],401);
		}
	}
	private function newCurl($method='GET',$url,$data)
	{

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => http_build_query($data),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response,true);
	}
}
