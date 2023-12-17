<?php

namespace Database\Seeders;

use App\Models\Collective;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddCollectiveFounderRoles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collective_rows = DB::table("collectives")->get();
		foreach($collective_rows as $collective_row) {
			$collective = Collective::where("id", $collective_row->id)->first();
			$collective->members()->updateExistingPivot($collective_row->founder_id, ["role_id" => 2]);
		}
    }
}
