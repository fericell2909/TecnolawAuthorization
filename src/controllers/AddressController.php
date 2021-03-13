<?php
namespace Tecnolaw\Authorization\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Tecnolaw\Authorization\Models\Addresses;
use Tecnolaw\Authorization\Models\Region;
use Tecnolaw\Authorization\Models\Commune;

class AddressController extends BaseController
{
	protected $address=null;
	protected $rules=null;

	function __construct()
	{
		$this->address = new Addresses;
		$this->rules = [
			'commune_id'=>'required|exists:'.(new Commune)->getTable().',id',
			'region_id'=>'required|exists:'.(new Region)->getTable().',id',
			'street'=>'required|max:200|min:2',
			'type'=>'required',
			/* 'lat'=>'max:250|min:2',
			'log'=>'max:250|min:2',
			'postal_code'=>'max:50|min:2', */
			'main_default'=>'required|boolean'
		];
	}
	public function create(Request $request)
	{
		$this->validate($request, $this->rules);
		$data=$request->all();
		$data['user_id']=\Auth::id();
        $address=$this->address->create([
            'user_id'=>\Auth::id(),
            'commune_id'=>$request->commune_id,
            'region_id'=>$request->region_id,
            'number'=>'',
            'description'=>'',
            'street' => $request->street,
            'department_number'=>'',
            'lat'=>null,
            'log'=>null,
            'postal_code'=>'56',
            'main_default'=>1,
            'dpto_home_office_aditional' => $request->dpto_home_office_aditional ?? ''
        ]);

        self::CheckMainAddress($request,$address->id);

        $result = Addresses::where('user_id',\Auth::id())->get();

		return response([
			'status'=>'success',
			'message'=>'Creado exitosamente!',
			'data'=>$result,
		],200);
	}
	public function update($id,Request $request)
	{
		$address=$this->address->where([
			['id','=',$id],
			['user_id','=',\Auth::id()]
        ])->first();

		if ($address) {

            $this->validate($request, $this->rules);

			$address->update($request->all());
            self::CheckMainAddress($request,$address->id);

            $result = Addresses::where('user_id',\Auth::id())->get();

			return response([
				'status'=>'success',
				'message'=>'¡Dirección actualizada correctamente!',
				'data'=>$result,
			],200);
		}
		return response([
			'status'=>'error',
			'message'=>'Dirección no encontrada',
			'data'=>[],
		],200);
	}
	public function delete($id)
	{
		$address=$this->address->where([
			['id','=',$id],
			['user_id','=',\Auth::id()]
		])->first();

		if ($address) {

                $address->delete();
                $result = Addresses::where('user_id',\Auth::id())->get();

                return response([
                    'status'=>'success',
                    'message'=>'Dirección eliminada',
                    'data'=>$result,
                ],200);

        }

		return response([
			'status'=>'error',
			'message'=>'Dirección no encontrada',
			'data'=>[],
        ],200);

	}
	private function CheckMainAddress($request,$id)
	{
		if($request->main_default){
			$current=$this->address->where([
				['user_id','=',\Auth::id()],
				['main_default','=',1],
				['id','!=',$id]
			])->first();
			if ($current) {
				$current->main_default=0;
				$current->save();
			}
		}
	}
}
