<?php

namespace App\Http\Controllers;

use App\Variety;
use App\Crop;
use Illuminate\Http\Request;

class VarietyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $varieties = Variety::with('crop')->get();
        return [
            'varieties' => $varieties
        ];
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
        //
        $variety = new Variety();
        $variety->variety_name = $request->variety_name;
        //作物名が存在するかチェック
        $count = Crop::where('crop_name', $request->crop_name)->count();
        //ない場合作成
        if($count == 0) {
            $crop = new Crop();
            $crop->crop_name = $request->crop_name;
            $crop->save();
            $new_crop = Crop::where('crop_name', $request->crop_name)->pluck('id');
            $variety->crop_id = $new_crop[0];
        } else {
            $find_crop = Crop::where('crop_name', $request->crop_name)->pluck('id');
            $variety->crop_id = $find_crop[0];
        }
        $variety->save();

        return [
            'variety' => $variety
        ];
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
    public function update(Request $request)
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

    public function find_varieties(Request $request)
    {
        //
        $varieties = Variety::where('crop_id', $request->id)->get();
        return [
            'varieties' => $varieties
        ];
    }
}
