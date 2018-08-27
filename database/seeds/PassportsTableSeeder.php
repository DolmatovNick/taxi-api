<?php

use App\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_access_tokens')->insert([
            [
                'id' => '3e5bf5be80f99feb800ce29196155aacb8703dc5bcaaa68a7e9aa4da155f47e8a8a96f279252398c',
                'user_id' => 1,
                'client_id' => 1,
                'name' => 'Laravel Password Grant Client',
                'scopes' => '[]',
                'revoked' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'expires_at' => now()->addDay(30)
            ]
        ]);

        DB::table('oauth_clients')->insert([
            [
                'user_id' => 1,
                'name' => 'Laravel Password Grant Client',
                'secret' => '2scRRMYbJx2bFdjt2JHP5f8UDWZqf01QOPiDuW4b',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('oauth_personal_access_clients')->insert([
            [
                'client_id' => 1,
                'created_at' => now(),
                'updated_at' => null
            ]
        ]);

    }

}
