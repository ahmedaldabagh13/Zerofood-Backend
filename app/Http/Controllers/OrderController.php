<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

    protected $db;

    /**
     * Create a new controller instance.
     *
     * @param string $id
     */
    public function __construct()
    {
        $this->db = DB::table('orders');
    }


    /**
     * get all orders
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        $orders = $this->db->orderByDesc('id')->get();
        $response = [];

        foreach ($orders as $order){
            array_push($response,
                [
                    'order' => $order,
                    'details' => DB::table('order_meal')
                        ->join('meals','meals.id','order_meal.meal_id')
                        ->select('meals.name as meal','quantity','price')
                        ->where('order_meal.order_id',$order->id)
                        ->get()
                ]
            );

        }

        return response()->json(['orders'=> $response],200);
    }

    /**
     * store new order function
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $total_price = 0;

        for ($i=0; $i < count($request->meal); $i++) {
            $total_price += DB::table('meals')->find($request->meal[$i])->price * $request->quantity[$i];
        }
        $orderId = $this->db->insertGetId([
                    'customer_id' => Auth::id() ?? Auth::guard('api')->id(),
                    'total_price'=>$total_price,
                    ]);

        for ($i=0; $i < count($request->meal); $i++) {

            DB::table('order_meal')->insert([
                'meal_id'=>$request->meal[$i],
                'quantity'=>$request->quantity[$i],
                'order_id'=>$orderId,
            ]);
        }

        $user = Auth::user() ?? Auth::guard('api')->user();

        $user->balance -= $total_price;
        $user->save();

        return response()->json(['msg'=>'Order Done'],200);

    }

    /**
     * accept order
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept($id)
    {
        try {

            $this->db->where('id',$id)->update(['stage'=>1]);

        }   catch (\Exception $exception) {

            return response()->json(['msg'=>$exception],500);
        }

        return response()->json(['msg'=>'Updated Done'],200);
    }


    /**
     * accept order
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete($id)
    {
        try {

            $this->db->where('id',$id)->update(['stage'=>2]);

        }   catch (\Exception $exception) {

            return response()->json(['msg'=>'Updated Err'],500);
        }

        return response()->json(['msg'=>'Updated Done'],200);
    }


    /**
     * accept order
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        try {

            $this->db->where('id',$id)->update(['stage'=>0]);

        }   catch (\Exception $exception) {

            return response()->json(['msg'=>'Updated Err'],500);
        }

        return response()->json(['msg'=>'Updated Done'],200);
    }


}
