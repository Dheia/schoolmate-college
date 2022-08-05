<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	

    	
        // echo $routeCollection[20]['as'];
        $routeCollection = array_map(function (\Illuminate\Routing\Route $route) 
                            { return $route->action; }, (array) \Route::getRoutes()->getIterator());

        foreach ($routeCollection as $route) {
        	# code...
	        if (isset($route['as']) && strpos($route['as'], 'crud') !== false) {

	            $extract1 = substr($route['as'], 5);
		        $extract2 = strpos($extract1, '.');
		        $slug = substr($extract1, 0, $extract2);

		        for($i = 0; $i < count(Config::get('helpers.action')); $i++) {
		        	$is_existing = count(DB::table('permissions')
		        							->where('page_name', $slug)
		        							->where('name', Config::get('helpers.action')[$i])
		        							->get()
		        						);
		        	// dd($is_existing);
		        	if($is_existing == 0) {
			        	DB::table('permissions')->insert([
				            'name' => Config::get('helpers.action')[$i],
				            'page_name' => $slug,
				            'guard_name' => "web",
				        ]);
		        	}
		        }

	        }
        }

    }
}
