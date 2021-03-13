
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tecnolaw\Authorization\Models\User;
use Tecnolaw\Authorization\Models\Role;
use Tecnolaw\Authorization\Models\RolesUsers;

class SeedersTecnolawPackageAuthorization extends Migration 
{

	public function up()
	{

		$list=[
			[
				'name' => 'Marco',
				'paternal_surname' => 'Estrada Lopez',
				'document' => '44577092',
				'email' => 'info@devmarcoestrada.com',
				'phone' => '+51 920200067',
				'birth_date' => '1987-01-01 01:01:01',
				'password' => 'secret1234',
				'status' => 1,
			],
			[
				'name' => 'Samuel',
				'paternal_surname' => 'Gonzales Villa',
				'document' => '46344124',
				'email' => 'samuelgonzales90@gmail.com',
				'phone' => '+51 965288046',
				'birth_date' => '1990-01-01 01:01:01',
				'password' => 'secret1234',
				'status' => 1,
			]
		];
		foreach ($list as $key => $user) {
			User::create($user);
		}

		$list=[
            [
                'name' => 'Administrator',
                'back_office' => 1,
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Buyer',
                'back_office' => 0,
                'status' => 1,
                'created_by' => 1,
            ],
        ];
        foreach ($list as $key => $role) {
            Role::create($role);
		}

			RolesUsers::insert([
				'user_id'=>1,
				'assigned_by'=>1,
				'role_id'=> 1 // admin
            ]);

            RolesUsers::insert([
				'user_id'=>2,
				'assigned_by'=>1,
				'role_id'=> 1 // admin
			]);

	}

	public function down()
	{

	}
	

}



