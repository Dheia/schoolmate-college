<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TurnstileRequest as StoreRequest;
use App\Http\Requests\TurnstileRequest as UpdateRequest;
use Illuminate\Http\Request;
use App\Models\TurnsTile;
/**u 
 * Class TurnstileCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TurnstileCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setDefaultPageLength(10);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Turnstile');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/turnstile');
        $this->crud->setEntityNameStrings('turnstile', 'turnstiles');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in TurnstileRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->setListView('turnstile');
    }



    public $turnstile   = [];
    public $id        = '';
    public $ip_address  = '';
    public $cmd_message = '';

    public function ping (Request $request) {

        if(!isset($request->name) && !isset($request->ip_address)) {
            $this->turnstile = [
                'TURNSTILE_MESSAGE' => 'Name or Ip Address is missing',
                'TURNSTILE_STATUS'  => false
            ];
            return response()->json($this->turnstile);
        }

        $this->ip_address = $request->ip_address;
        $this->name = $request->name;

        dd(\SSH::getDefaultConnection());

        \SSH::into($request->name)->run([
         // 'ls -l -a',
         'ping ' . $this->ip_address,
        ], function ($line) {

            $this->cmd_message = $line;

            if( strpos($line, 'Destination Host Unreachable') == true || strpos($line, 'Request Timeout') == true ) {
                $message = 'Not connected';
                $status = false;

            } else {
                $message = 'Connected';
                $status = true;
            }

            $this->turnstile = [
                'TURNSTILE_NAME'        => $this->name,
                'TURNSTILE_IPADDRESS'   => $this->ip_address,
                'TURNSTILE_CMD_MESSAGE' => $this->cmd_message, 
                'TURNSTILE_MESSAGE'     => $message, 
                'TURNSTILE_STATUS'      => $status
            ];

        });

        return response()->json($this->turnstile);
    }

    public function reboot (Request $request) {
        \SSH::into($request->name)->run([
            'ls -l',
            'sudo reboot now',
        ], function($line) {
            dd($line);
        });
    }

    public function reboot_program (Request $request) {
        \SSH::into($request->name)->run([
            'ls -l',
            'python3 /wisid/rfid/rfid/rfid.py',
        ], function($line) {
            dd($line);
        });
    }
    

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }
}
