<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\User;
use App\Reservation;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        DB::transaction(function () {
            $id = Auth::user()->id;
            $reservations = DB::table('reservations')
            ->where('user_id','=',"$id")->get();
            if ($reservations->isEmpty()){
                $reservation = new Reservation;
                $reservation->user_id = $id;
                $reservation->state = 'booked';
                $reservation->save();
            }else{
                foreach ($reservations as $reservation) {
                    echo$reservation->state;
                    if($reservation->state != 'booked' && $reservation->state != 'in progress' ){
                        $reservation = new Reservation;
                        $reservation->user_id = $id;
                        $reservation->state = 'booked';
                        $reservation->save();
                    }
            }
        });
    }

    // risolvere problema doppio booked

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // crea elenco prenotazioni da inviare
        $reservations = Reservation::all();
        return view('welcome', compact('reservations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update()
    {
        $id = Auth::user()->id;
        DB::table('reservations')
            ->where('user_id', $id)
            ->update(['state' => 'complete']);

        $reservation = DB::table('reservations')->where('state','=', 'booked')->get()->first();
        if ($reservation){
            DB::table('reservations')
            ->where('id',$reservation->id)
            ->update(['state' => 'in progress']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
