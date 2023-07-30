<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomersImport;
use App\Exports\CustomerExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Arr;

use App\Traits\InviteTrait;


class CustomerController extends Controller
{
    use InviteTrait;


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(['name' => 'Abigail', 'state' => 'CDO']); // test
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
          
            $validated = $request->validate([
                'csv_file' => 'required|mimes:csv',
            ]);


            if ($request->hasFile('csv_file')) {
                $array = Excel::toArray(new CustomersImport, request()->file('csv_file'));
                
                $collapsed = Arr::collapse($array);
                $invitedArray = InviteTrait::processInvite($collapsed);

                $returnData = array(
                    'uploaded_data' => $collapsed,
                    'parsed_data' => $invitedArray
                );

                
                return response()->json($returnData);
            } else {
                return response()
                ->json(['name' => 'Abigail', 'state' => 'CDO']); // test
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
