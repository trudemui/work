<?php

namespace App\Http\Controllers;

use App\Template;
use App\Variety;
use App\Crop;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $crops = Crop::paginate(10);
        $varieties = Variety::with('crop')->orderBy('crop_id')->where('del_flg', 0)->paginate(10);
        return [
            'varieties' => $varieties,
            'crops' => $crops
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
        $template = new Template();
        $template->category_id = $request->category_id;
        $template->body = $request->body;
        $template->save();
        return [
            'template' => $template
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_table()
    {
        //
        $crops = Crop::all();
        return [
            'crops' => $crops
        ];
    }

    public function select_table(Request $request)
    {
        $template = Template::where('category_id', $request->id)->where('del_flg', 0)->paginate(10);
        return [
            'template' => $template
        ];
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
        if($request->number == 0) {
            $template = Template::find($request->id);
            $template->body = $request->body;
            $template->save();
        } else if($request->number == 1) {
            $count = Crop::where('crop_name', $request->crop_name)->count();
            if($count == 0) {
                $crop = new Crop();
                $crop->crop_name = $request->crop_name;
                $crop->save();
                $new_crop = Crop::where('crop_name', $request->crop_name)->pluck('id');
                $variety = Variety::find($request->id);
                $variety->crop_id = $new_crop[0];
                $variety->variety_name = $request->variety_name;
                $variety->save();
            } else {
                $find_crop = Crop::where('crop_name', $request->crop_name)->pluck('id');
                $variety = Variety::find($request->id);
                $variety->crop_id = $find_crop[0];
                $variety->variety_name = $request->variety_name;
                $variety->save();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if($request->number == 0) {
            $templates = Variety::where('id', $request->id)->first();
            $templates->del_flg = 1;
            $templates->save();
        } else if($request->number == 1) {
            $templates = Template::where('id', $request->id)->first();
            $templates->del_flg = 1;
            $templates->save();
        }
    }
}
