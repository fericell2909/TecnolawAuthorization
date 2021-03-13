<?php
namespace Tecnolaw\Authorization\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Tecnolaw\Authorization\Models\Phone;

class PhoneController extends BaseController
{
	protected $phone=null;
	protected $rules=null;

	function __construct()
	{
		$this->phone = new Phone;
		$this->rules = [
			'code' => 'required|string',
			'number' => 'required|max:50|min:7',
			'type' 	=> [
				'required',
				'integer',
				function ($attribute, $value, $fail) {
					if (intval($value)>3) {
						$fail(
							'Las opciones son 1(casa),2(trabajo o oficina),3(otro)'
						);
					}
				},
			],
			'main_default'=>'required|boolean'
		];
	}
	public function create(Request $request)
	{
		$this->validate($request, $this->rules);
		$data=$request->all();
		$data['user_id']=\Auth::id();
		$phone=$this->phone->create($data);
		self::CheckMainPhone($request,,$phone->id);
		return response([
			'status'=>'success',
			'message'=>'Creado exitosamente!',
			'data'=>$phone->toArray(),
		],200);
	}
	public function update($id,Request $request)
	{
		$phone=$this->phone->where([
			['id','=',$id],
			['user_id','=',\Auth::id()]
		])->first();
		if ($phone) {
			$this->validate($request, $this->rules);
			$phone->update($request->all());
			self::CheckMainPhone($request,$phone->id);
			return response([
				'status'=>'success',
				'message'=>'Actualizado exitosamente!',
				'data'=>$phone->toArray(),
			],200);
		}
		return response([
			'status'=>'error',
			'message'=>'Telefono no encontrado',
			'data'=>[],
		],200);
	}
	public function delete($id)
	{
		$phone=$this->phone->where([
			['id','=',$id],
			['user_id','=',\Auth::id()]
		])->first();

		if ($phone) {
			$phone->delete();
			return response([
				'status'=>'success',
				'message'=>'Telefono eliminado',
				'data'=>[],
			],200);
		}
		return response([
			'status'=>'error',
			'message'=>'Telefono no encontrado',
			'data'=>[],
		],200);
	}
	private function CheckMainPhone($request,$id)
	{
		if($request->main_default){
			$current=$this->phone->where([
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