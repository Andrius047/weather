<?php

namespace App\Http\Controllers;

use App\city;
use App\emails;
use App\Info;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;


class WeatherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new Client();
        $city = \DB::table('cities')->where('selected', 1)->get();
        $api_response = $client->get('api.openweathermap.org/data/2.5/weather?id='.$city[0]->public_id.'&APPID=1aa6a957498b340d0d3ad4ef4327698c');
        $response = json_decode($api_response->getBody());

        $cities = \DB::table('cities')->select('id','selected','public_id','city')->get();

        return view('city/show')->with(['cities' => $cities, 'city_info' => $response]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $email = new emails;

        $email->email = $request->input('email');

        $email->save();

        return \Redirect::to('weather');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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

    public function setSelected($city)
    {
        city::where('selected', 1)->update(['selected' => 0]);
        city::where('public_id', $city)->update(['selected' => 1]);
        emails::truncate();

        $client = new Client();

        $api_response = $client->get('api.openweathermap.org/data/2.5/weather?id='.$city.'&APPID=1aa6a957498b340d0d3ad4ef4327698c');
        $response = json_decode($api_response->getBody());

        Info::where('id', 1)->update(['city_id' => $city, 'temp' => $response->main->temp, 'deg' => $response->wind->deg, 'speed' => $response->wind->speed]);

        $res ='<div>'.$response->main->temp.'<br>'.$response->wind->deg.'<br>'.$response->wind->speed.'</div>';

        return response()->json($res);
    }

    public function mail()
    {
        $client = new Client();

        $city = \DB::table('cities')->where('selected', 1)->get();
        $api_response = $client->get('api.openweathermap.org/data/2.5/weather?id='.$city[0]->public_id.'&APPID=1aa6a957498b340d0d3ad4ef4327698c');
        $response = json_decode($api_response->getBody());

        $users = \DB::table('emails')->get();
        $info = \DB::table('infos')->get();

        if(($info[0]->speed < 10 && $response->wind->speed > 10) || $info[0]->speed > 10 && $response->wind->speed < 10)
        {
            foreach($users as $user)
            {
                Mail::to($user->email)->queue('wind speed has changed');;
            }
        }
    }
}
