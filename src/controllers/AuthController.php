<?php
namespace Tecnolaw\Authorization\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Tecnolaw\Authorization\Services\TokenService;
use Tecnolaw\Authorization\Services\SessionService;
use Tecnolaw\Authorization\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
	protected $user=null;

	function __construct()
	{
		$this->user = new User;
	}

	public function singUp(Request $request)
	{
		$rules = [
			'name' => 'required',
			/* 'lastname' => 'required', */
			'email' 				=> 'required|unique:'.$this->user->getTable().',email|email',
			'password' 				=> 'required|min:6|max:15|confirmed', //disable confirmed
			'password_confirmation' => 'required',
			'rut' => [
				// 'required',
				'max:45',
				'unique:'.$this->user->getTable().',rut',
				function ($attribute, $value, $fail) {
					if (validaRut($value)===false) {
						$fail(
							trans('TecnolawAuth::auth.field.rut',['attribute'=>$attribute])
						);
					}
				},
			]
		];
		$rename=[
			'name' => 'nombre',
            /* 'lastname' => 'apellido', */
            'email' => 'correo',
            'password'=> 'contraseña',
            'password_confirmation'=> 'confirmacion de contraseña',
		];
        // $this->validate($request, $rules,[],$rename);

        $bresult_validation= false;
        $result_json=null;

        $this->validations($request,$bresult_validation,  $result_json);

        if(!$bresult_validation){

            return $result_json;
        }


		$user=$this->user->create([
			'name' 		       => $request->name,
			'paternal_surname' => '',
			'email'		       => $request->email,
			'password'	       => $request->password,
			'rut'		       => $request->rut ?? null,
            'status'	       => 1, // enable
            'notifications' => $request->notifications ?? 0,
		]);
		$user->roles()->attach(2); //comprador
		$token 		= new TokenService($user);
		$session 	= new SessionService($user,$token);

		try {
			sendMail([
				'data'=>(object)[
					'title' => 'Te has registrado de forma exitosa!',
					'subject'=>'Registro exitoso! ['.env('GET_NAME_APP').']',
					'body' => 'Binvenido a '.env('GET_NAME_APP').', espero  que disfrute todas la opciones que proporciona la plataforma, gracias por preferirnos!',
					'link' => env('APP_URL_SITE'),
					'textBtn' => 'ir al sitio',
				],
				'user'=> (object)[
					'fullName' => $user->full_name,
					'email' => $user->email,
				]
			]);
		} catch (\Exception $e) {
			return response([
				'message'	=> $e->getMessage(),
				'status'	=> 'error',
				'session' 	=> $session->get(),
			],422);
		}

		return response([
			'message'	=> trans('TecnolawAuth::auth.sing.up.success'),
			'status'	=> 'success',
			'session' 	=> $session->get(),
		],200);
	}
	public function singIn(Request $request,$type='user')
	{

		$this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6|max:15|',
        ]);
        $email = $request->input('email');

        $password = $request->input('password');
        $user = User::where('email', '=', $email)->first();

        $response = [];
        if ($user) {
            if (password_verify($password, $user->password)) {
                if($user->status == env('USER_ENABLE')) {
                	$back_office=false;//define en el token que es para la web pulica
                	if ( $type=='admin' ) {
                		$allow=false;
                		foreach ($user->roles as $key => $role) {
                			if ($role->back_office==1) {
                				$allow=true;
                				$back_office=true;//define en el token que es para el back office
                				break;
                			}
                		}
                		if ($allow==false) {
		                	return response([
				                'errors'=> ['¡Administrador invalido!'],
				                'data' => []
				            ], 422);
                		}
                	}
                    $token = new TokenService($user,$back_office);
                    $session 	= new SessionService($user,$token);
                    $response['message'] = trans('TecnolawAuth::auth.sing.in.success');
                    $response['status'] = 'success';
	                $response['session'] = $session->get();
                    $code = 200;
				}else{
                    $code = 422;
	            	$error = trans('TecnolawAuth::auth.check.status.disabled');
	            }
            }else{
                $code = 422;
            	$error = trans('TecnolawAuth::auth.check.status.incorrect');
            }
        }else{
            $code = 422;
            $error = trans('TecnolawAuth::auth.check.status.notFound');
        }
        if (isset($error))
        {
            $response['message'] = trans('TecnolawAuth::auth.check.message.generic');
            $response['status'] = 'danger';
            $response['errors'][]=$error;
        }
        return response($response,$code);
    }

    private function validations(Request $request , &$bvalidation_result , &$response_json) {

        $bresult_validation = true;

        $validations = [
			'name' => 'required',
			/* 'lastname' => 'required', */
			'email' 				=> 'required|unique:tecnolaw_users,email|email',
            'password' 				=> 'required|min:5|max:15', //disable confirmed
            'password_confirmation' => 'required|min:5|max:15|same:password'
        ];

        $messages_validations = [
            'email.required' => 'El correo electrónico es requerido.',
            'email.unique' => 'El correo electrónico ya existe.',
            'name.required' => 'El Nombre y Apellido es requerido.',
            'password.required' => 'La contraseña es requerida.',
            'password.min' => 'La contraseña debe ser mínimo de 6 caracteres.',
            'password.max' => 'La Contraseña debe ser máximo de 15 caracteres.',
            'password_confirmation.required' => 'La contraseña es requerida.',
            'password_confirmation.min' => 'El repetir contraseña debe ser mínimo de 6 caracteres.',
            'password_confirmation.max' => 'El repetir contraseña debe ser máximo de 15 caracteres.',
            'password_confirmation.same' => 'Las contraseñas no coinciden'
        ];


        $validator = Validator::make($request->all(), $validations,$messages_validations);

        if(!$validator->fails()){
            $bresult_validation = true;
            $bvalidation_result = true;

        } else {
            $errores = $validator->errors();
            $mensaje_errores ="";

            foreach ($errores->all() as $error){
                if($mensaje_errores !== "") {
                    $mensaje_errores =   $mensaje_errores . '. ' . $error;
                }else {
                    $mensaje_errores = $error;
                }
            }

            $bresult_validation = false;
            $bvalidation_result = false;

            $response_json = response([
                'status'=>'danger',
                'message'=>$mensaje_errores,
                'data'=>[],
            ]);
        }

        return $bresult_validation;
    }
}
