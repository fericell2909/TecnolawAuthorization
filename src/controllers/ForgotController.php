<?php
namespace Tecnolaw\Authorization\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Tecnolaw\Authorization\Models\User;
use Tecnolaw\Authorization\Models\RecoveryPassword;
use Illuminate\Support\Str;

class ForgotController extends BaseController
{
    protected $user=null;

    function __construct()
    {
        $this->user = new User;
    }

    public function recoveryPassword(Request $request)
    {
        $rules = [
            'email' => 'required|exists:'.$this->user->getTable().',email|email',
        ];
        $this->validate($request, $rules);
        $user=$this->user->where('email',$request->email)->first();

        $recovery=RecoveryPassword::create([
            'user_id'=>$user->id,
            'token'=>Str::random(100),
            'user_agent'=>'none',
            'expiration_date'=>null,
            'email_send'=>$user->email,
        ]);

        sendMail([
            'data'=>(object)[
                'title' => 'Has solicitado recuperar tu contraseña!',
                'subject'=>'Recuperar contraseña! ['.env('GET_NAME_APP').']',
                'body' => 'Recientemente solicitaste restablecer tu contraseña,solo tienes que hacer <strong>click en cambiar</strong>.
                    <br> Si tu no solicitaste un cambio de contraseña entonces solo omite este este mensaje',
                'link' => env('APP_URL_SITE').env('RESET_PASS').'?token='.$recovery->token,
                'textBtn' => 'Cambiar',
            ],
            'user'=> (object)[
                'fullName' => $user->full_name,
                'email' => $user->email,
            ]
        ]);

        return response([
            'message'   => trans('TecnolawAuth::auth.recovery.password.success'),
            'status'    => 'success',
        ],200);
    }
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6|required_with:password_confirmacion|same:password_confirmacion',
            'password_confirmacion' => 'min:6|required',
            'token' => 'required'
        ]);
        $recovery=RecoveryPassword::where(['token'=>$request->token])->first();
        if ($recovery) {
            $user =  $recovery->user;
            $user->password=$request->password;
            $user->save();
            $recovery->delete();
            return response()->json([
                'message'   => 'Felicidades cambiaste tu password exitosamente',
                'status'    => 'success',
            ]);
        }else{
            return response([
                'errors'   => ['Ups! link invalido o usado'],
            ],200);
        }
    }
    public function recoveryUsername(Request $request)
    {
        return "Hola singIn";
    }

    public function updatePassword(Request $request) {

        $this->validate($request, [
            'password' => 'required|min:6|required_with:rpassword|same:rpassword',
            'rpassword' => 'min:6|required'
        ]);

        $u = \Auth::user();

        if ($u) {
            $user = User::where('id',$u->id)->first();

            $user->password = $request->password;
            $user->save();

            return response()->json([
                'message'   => 'Contraseña actualizada',
                'status'    => 'success',
            ]);
        }else{
            return response()->json([
                'message'   => 'Usuario no authenticado',
                'status'    => 'error',
            ]);
        }

    }
}
