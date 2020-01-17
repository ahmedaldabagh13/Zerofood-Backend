<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{

    protected $db;

    /**
     * Create a new controller instance.
     *
     * @param string $id
     */
    public function __construct()
    {
        $this->db = DB::table('meals');
    }


    /**
     * get all meals
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        return $this->db->get();
    }

    /**
     * add new meal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name'      => 'required',
            'price'     => 'required|numeric',
            'image'     => 'required|file',
            'info'      => 'nullable|string',
            'category'  => 'required|exists:categories,id',
        ]);

        $image = 'storage/'.Storage::disk('local')->put('images', $request->file('image'));

        $this->db->insert([
            'name'          =>  $request->input('name'),
            'price'         =>  $request->input('price'),
            'image'         =>  $image,
            'category_id'   =>  $request->input('category'),
            'info'          =>  $request->input('info') ?? null,
            'created_at'    =>  Carbon::now(),
        ]);

        return response()->json(['msg'=>'Store Done'],200);
    }

    /**
     * delete meal
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
