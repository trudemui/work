<?php

namespace App\Http\Controllers;

use App\GrowingCrop;
use App\ProductionControl;
use App\JasControl;
use App\Farm;
use App\Crop;
use App\Variety;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class GrowingCropController extends Controller
{
    public function index()
    {
        $crops = GrowingCrop::with('user', 'variety.crop', 'farm')->where('del_flg', 0)->orderBy('starting_date', 'desc')->paginate(10);

        return ['crops' => $crops];
    }

    public function store(Request $request)
    {
        $g_crop = new GrowingCrop();
        $g_crop->user_id = $request->user_id;
        $g_crop->variety_id = $request->variety_id;
        $g_crop->starting_date = $request->starting_date;
        $g_crop->farm_id = $request->farm_id;
        $g_crop->cultivation_area = $request->cultivation_area;
        $g_crop->save();
    }

    public function show(Request $request)
    {
        $g_crop = GrowingCrop::where('id', $request->id)->with('variety.crop', 'farm')->first();

        return ['g_crop' => $g_crop];
    }

    public function find_crop(Request $request)
    {
        $g_crop = GrowingCrop::where('id', $request->id)->with('variety.crop', 'farm')->get();

        return ['g_crop' => $g_crop];
    }

    public function find_crops(Request $request)
    {
        $g_crop = [];

        foreach($request->datas as $data) {
            array_push($g_crop, GrowingCrop::where('id', $data['id'])->with('variety.crop', 'farm')->where('del_flg', 0)->get());
        } 

        return ['g_crop' => $g_crop];
    }

    public function update(Request $request)
    {
        $g_crop = GrowingCrop::find($request->id);

        if(isset($request->user_id)) {
            $g_crop->user_id = $request->user_id;
        }
        if(isset($request->variety_id)) {
            $g_crop->variety_id = $request->variety_id;
        }
        if(isset($request->starting_date)) {
            $g_crop->starting_date = $request->starting_date;
        }
        if(isset($request->farm_id)) {
            $g_crop->farm_id = $request->farm_id;
        }
        if(isset($request->cultivation_area)) {
            $g_crop->cultivation_area = $request->cultivation_area;
        }

        $g_crop->save();
    }

    public function delete(Request $request)
    {
        foreach($request->id as $row) {
            $g_crop = GrowingCrop::where('id', $row)->first();
            $g_crop->del_flg = 1;
            $g_crop->save();
        }
    }

    public function search(Request $request)
    {
        $query = GrowingCrop::query()->with(['user', 'variety.crop', 'farm']);

        if(isset($request->crop_id)) {
            $crop_id = Variety::where('crop_id', $request->crop_id)->pluck('id');
            $query->wherein('variety_id', $crop_id);
        }
        if(isset($request->farm_id)) {
            $query->where('farm_id', $request->farm_id);
        }
        if(isset($request->variety_id)) {
            $query->where('variety_id', $request->variety_id);
        }
        if(isset($request->starting_date)) {
            $query->where('starting_date', 'like', '%'.$request->starting_date. '%');
        }
        if(isset($request->harvest_flg)) {
            $query->where('harvest_flg', $request->harvest_flg);
        }

        $search = $query->where('del_flg', 0)->orderBy('starting_date', 'desc')->paginate(10);

        return ['search' => $search];
    }

    public function get_list()
    {
        $farms = Farm::all();
        $crops = Crop::all();
        $varieties = Variety::all();
        return [
            'farms' => $farms,
            'crops' => $crops,
            'varieties' => $varieties
        ];
    }

    public function sort(Request $request)
    {   
        if($request->class == 'asc') {
        //ソート（昇順）
            $value = GrowingCrop::with('user', 'variety.crop', 'farm')->where('del_flg', 0);
            
            if($request->type == 'year') {
                $crops = $value->orderBy('starting_date', 'asc')->paginate(10);
            } else {
                $crops = $value->orderBy('farm_id', 'asc')->paginate(10);
            }

            return ['crops' => $crops];

        } else if($request->class == 'desc') {
        //ソート（降順）
            $value = GrowingCrop::with('user', 'variety.crop', 'farm')->where('del_flg', 0);

            if($request->type == 'year') {
                $crops = $value->orderBy('starting_date', 'desc')->paginate(10);
            } else {
                $crops = $value->orderBy('farm_id', 'desc')->paginate(10);
            }

            return ['crops' => $crops];

        } else if($request->class == 'searchAsc') {
        //ソート＋検索
            $query = GrowingCrop::query()->with(['user', 'variety.crop', 'farm']);

            if(isset($request->crop_id)) {
                $crop_id = Variety::where('crop_id', $request->crop_id)->pluck('id');
                $query->wherein('variety_id', $crop_id);
            }
            if(isset($request->farm_id)) {
                $query->where('farm_id', $request->farm_id);
            }
            if(isset($request->variety_id)) {
                $query->where('variety_id', $request->variety_id);
            }
            if(isset($request->starting_date)) {
                $query->where('starting_date', 'like', '%'.$request->starting_date. '%');
            }
            if(isset($request->harvest_flg)) {
                $query->where('harvest_flg', $request->harvest_flg);
            }

            $value = $query->where('del_flg', 0);

            if($request->type == 'year') {
                $crops = $value->orderBy('starting_date', 'asc')->paginate(10);
            } else {
                $crops = $value->orderBy('farm_id', 'asc')->paginate(10);
            }

            return ['crops' => $crops];
        }
    }

    public function print(Request $request)
    {       
        $crops = [];

        if($request->class == 'production') {
            foreach($request->datas as $data) {
                array_push($crops, ProductionControl::where('growing_crop_id', $data['id'])->orderBy('work_date', 'asc')->where('del_flg', 0)->get());
            }  
        } else {
            foreach($request->datas as $data) {
                array_push($crops, JasControl::where('growing_crop_id', $data['id'])->orderBy('work_date', 'asc')->where('del_flg', 0)->get());
            }
        }

        return ['crops' => $crops];
    }

    public function nothing_print(Request $request)
    {       
        $crops = [];

        if($request->class == 'production') {
            foreach($request->datas as $data) {
                $count = 0;
                $count = ProductionControl::where('growing_crop_id', $data['id'])->count();
                if ($count == 0) {
                    array_push($crops, GrowingCrop::with('variety.crop')->where('id', $data['id'])->get());
                }
            }  
        } else {
            foreach($request->datas as $data) {
                $count = 0;
                $count = JasControl::where('growing_crop_id', $data['id'])->count();
                if ($count == 0) {
                    array_push($crops, GrowingCrop::with('variety.crop')->where('id', $data['id'])->get());
                }
            }
        }

        return ['crops' => $crops];
    }
}
