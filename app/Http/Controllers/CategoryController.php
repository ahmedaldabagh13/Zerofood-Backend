<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    protected $db;

    /**
     * Create a new controller instance.
     *
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->db = DB::table('categories');
    }


    /**
     * get all categories
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        return $this->db->get();
    }

    /**
     * add new category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',
        ]);

        $this->db->insert([
            'name'          =>  $request->input('name'),
            'created_at'    =>  Carbon::now(),
        ]);

        return response()->json(['msg'=>'Store Done'],200);
    }

    /**
     * delete category
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->db->delete($id);
        }   catch (\Exception $exception) {
            return response()->json(['msg'=>'Delete Err'],500);
        }

        return response()->json(['msg'=>'Delete Done'],200);
    }
}
