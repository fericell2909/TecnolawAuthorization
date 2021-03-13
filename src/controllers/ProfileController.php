<?php
namespace Tecnolaw\Authorization\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Tecnolaw\Authorization\Models\User;
use Tecnolaw\Authorization\Models\Phone;

class ProfileController extends BaseController
{
	protected $user=null;

	function __construct()
	{
		$this->user = new User;
	}
	public function index()
	{
		$user_id=\Auth::id();
		if ($user_id) {
            $user=User::where('id','=',$user_id)->with(['addresses','phones'])->get();
			return response([
				'status'=>'success',
				'data'=>$user->toArray()
			],200);
		}else{
			return response([
				'status'=>'error',
				'message'=>'Usuario no encontrado y debes estar autenticado',
				'data'=>null
			],404);
		}

	}
	public function update(Request $request)
	{
        $user_id=\Auth::id();

		if($user_id) {

            try
            {

                $user = User::where('id',$user_id)->first();

                $user->name = $request->name;
                $user->phone= '+56 ' . $request->phone;
                $user->save();

                if($request->phone <> ''){
                    Phone::where('user_id',$user->id)->update(['main_default'=>0]);

                    $phone = Phone::where('user_id',$user->id)->Where('code','56')->Where('number',$request->phone)->first();

                    if($phone){
                        $phone->main_default = 1;
                        $phone->save();

                    } else {

                        Phone::create([
                            'user_id'=>$user->id,
                            'code'=>'56',
                            'number'=>$request->phone,
                            'type'=> 3,
                            'main_default'=>1,
                        ]);

                    }
                }

                $own=User::where('id','=',$user->id)->with(['addresses','phones'])->get();

                return response([
                    'status'=>'success',
                    'message' => 'Información personal actualizada',
                    'data'=>$own->toArray()
                ],200);

            } catch (\Exception $e) {

                return response([
                    'status'=>'error',
                    'message' => $e->getMessage(),
                    'data'=>$own->toArray()
                ],200);

            }

		}else{
			return response([
				'status'=>'error',
				'message'=>'Usuario no encontrado y debes estar autenticado',
				'data'=>null
			],200);
		}

	}
}
