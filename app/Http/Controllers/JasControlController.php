<?php

namespace App\Http\Controllers;

use App\JasControl;
use App\GrowingCrop;
use Illuminate\Http\Request;

class JasControlController extends Controller
{

    public function crop_index(Request $request)
    {
        $jas_values = JasControl::where('growing_crop_id', $request->id)->with('growing_crop.variety')->where('destination_id', 0)->where('del_flg', 0)->orderBy('work_date', 'asc')->paginate(10);
        return ['jas_values' => $jas_values];
    }

    public function crop_show(Request $request)
    {
        $jas_values = JasControl::where('growing_crop_id', $request->id)->with('growing_crop.variety')->where('del_flg', 0)->orderBy('work_date', 'asc')->get();
        return [
            'jas_values' => $jas_values
        ];
    }

    public function find_data(Request $request)
    {
        $jas_values = JasControl::where('id', $request->id)->first();
        return [
            'jas_values' => $jas_values
        ];
    }

    public function store(Request $request)
    {
        $jas_values = new JasControl();
        $jas_values->growing_crop_id = $request->growing_crop_id;
        $jas_values->work_date = $request->work_date;
        $jas_values->packaging_unit = $request->packaging_unit;
        $jas_values->consignments_count = $request->consignments_count;
        $jas_values->weight = $request->weight;
        $jas_values->destination_id = $request->destination_id;
        $jas_values->used_count = $request->used_count;

        $jas_values->save();
    }

    public function show(Request $request)
    {
        $jas_values = JasControl::where('growing_crop_id', $request->id)->with('growing_crop.variety')->where('destination_id', 0)->where('del_flg', 0)->orderBy('work_date', 'asc')->get();
        return ['jas_values' => $jas_values];
    }

    public function update(Request $request)
    {
        $jas_values = JasControl::find($request->id);

        if(isset($request->work_date)) {
            $jas_values->work_date = $request->work_date;
        }
        if(isset($request->packaging_unit)) {
            $jas_values->packaging_unit = $request->packaging_unit;
        }
        if(isset($request->consignments_count)) {
            $jas_values->consignments_count = $request->consignments_count;
        }
        if(isset($request->weight)) {
            $jas_values->weight = $request->weight;
        }
        if(isset($request->destination_id)) {
            $jas_values->destination_id = $request->destination_id;
        }
        if(isset($request->used_count)) {
            $jas_values->used_count = $request->used_count;
        }

        $jas_values->save();
    }

    public function delete(Request $request)
    {
        $jas_values = JasControl::where('id', $request->id)->first();
        $jas_values->del_flg = 1;
        $jas_values->save();
    }

    public function search(Request $request)
    {
        $query = JasControl::query()->where('growing_crop_id', $request->id);

        if(isset($request->destination_id)) {
            $query->where('destination_id', $request->destination_id);
        }

        $search = $query->where('del_flg', 0)->paginate(10);

        return ['search' => $search];
    }

    public function sort(Request $request)
    {   
        $jas_values = JasControl::where('growing_crop_id', $request->id)->with('growing_crop.variety')->where('destination_id', 0)->where('del_flg', 0);

        if($request->class == 'asc') {
            $result = $jas_values->orderBy('work_date', 'asc')->paginate(10);
            return ['result' => $result];
        } else {
            $result = $jas_values->orderBy('work_date', 'desc')->paginate(10);
            return ['result' => $result];
        }
    }
}
