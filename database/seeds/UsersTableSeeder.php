<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'email' => 'juanperez_2015@hotmail.com',
                'password' => bcrypt('123456'),
                'name' => 'Juan Peres Martinez',
                'puesto' => 'Empleado',
                'created_at' => '2019-03-06 12:49:11',
                'updated_at' => '2019-03-06 12:49:11'
            ],
            [
                'id' => 2,
                'email' => 'jsanchez_cor@grupoempresarial.com',
                'password' => bcrypt('123456'),
                'name' => 'José Miguel Sanchez',
                'puesto' => 'Empleado',
                'created_at' => '2019-03-06 12:49:11',
                'updated_at' => '2019-03-06 12:49:11'
            ],
            [
                'id' => 3,
                'email' => 'l.urrutia@comercioexterno.com',
                'password' => bcrypt('123456'),
                'name' => 'Laura Ramos Urrutia',
                'puesto' => 'Empleado',
                'created_at' => '2019-03-06 12:49:11',
                'updated_at' => '2019-03-06 12:49:11'
            ],
            [
                'id' => 4,
                'email' => 'mromnez.128@gmail.com',
                'password' => bcrypt('123456'),
                'name' => 'Mario Romero Martinez',
                'puesto' => 'Empleado',
                'created_at' => '2019-03-06 12:49:11',
                'updated_at' => '2019-03-06 12:49:11'
            ],
            [
                'id' => 5,
                'email' => 'godinez_1520@outloock.com',
                'password' => bcrypt('123456'),
                'name' => 'Juan Godinez Palomares',
                'puesto' => 'Empleado',
                'created_at' => '2019-03-06 12:49:11',
                'updated_at' => '2019-03-06 12:49:11'
            ],
            [
                'id' => 6,
                'email' => 'carlos85g@gmail.com',
                'password' => bcrypt('123456'),
                'name' => 'Carlos Eduardo González López',
                'puesto' => 'Programador BackEnd',
                'created_at' => '2019-03-06 12:49:11',
                'updated_at' => '2019-03-06 12:49:11'
            ],
        ]);
    }
}
