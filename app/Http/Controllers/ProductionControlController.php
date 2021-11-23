<?php

namespace App\Http\Controllers;

use App\GrowingCrop;
use App\ProductionControl;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductionControlController extends Controller
{
    public function crop_index(Request $request)
    {
        $crops = ProductionControl::where('growing_crop_id', $request->id)->with('growing_crop.variety')->where('del_flg', 0)->orderBy('work_date', 'asc')->paginate(10);
        return [
            'crops' => $crops
        ];
    }

    public function find_data(Request $request)
    {
        $product = ProductionControl::where('id', $request->id)->with('growing_crop.variety.crop')->first();
        return [
            'product' => $product
        ];
    }

    public function store(Request $request)
    {
        $harvest_count = ProductionControl::where('growing_crop_id', $request->growing_crop_id)->where('del_flg', 0)->where('work_content_id', '収穫済み')->get()->count();
        if($harvest_count > 0 && $request->value1 == '収穫済み') {
            $response = response()->json([
                'validate' => '作業内容「収穫済み」は、既に登録されています。',
            ], 422);
            throw new HttpResponseException($response);
        }

        $product = new ProductionControl();
        $product->growing_crop_id = $request->growing_crop_id;
        $product->work_date = $request->date;
        $product->work_content_id = $request->value1;
        $product->seed_id = $request->value2;
        $product->seed_volume = $request->volume;
        $product->equipments_id = $request->value3;
        $product->equip_method_id = $request->value4;
        $product->others = $request->others;
        $product->after_harvest_id = $request->value5;

        $product->save();

        if($request->value1 == '収穫済み') {
            $harvest = GrowingCrop::find($request->growing_crop_id);
            $harvest->harvest_flg = 1;
            $harvest->save();
        }
    }

    public function crop_show(Request $request)
    {
        $crops = ProductionControl::where('growing_crop_id', $request->id)->with('growing_crop.variety')->where('del_flg', 0)->orderBy('work_date', 'asc')->get();
        return [
            'crops' => $crops
        ];
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
        $product = ProductionControl::find($request->id);

        $harvest = GrowingCrop::find($product->growing_crop_id);
        $harvest_count = ProductionControl::where('growing_crop_id', $harvest->id)->where('del_flg', 0)->where('work_content_id', '収穫済み')->get()->count();
        
        //なぜか422は通るのにメッセージは通せない？
        //収穫フラグバリデート
        if($harvest_count > 0 && $request->work_content_id == '収穫済み') {
            $value = ProductionControl::where('growing_crop_id', $harvest->id)->where('del_flg', 0)->where('work_content_id', '収穫済み')->first();
            if($value->id !== $request->id) {
                $response = response()->json([
                    'validate' => '作業内容「収穫済み」は、既に登録されています。',
                ], 422);
                throw new HttpResponseException($response);
            }
        }

        //収穫フラグの初期化
        $harvest->harvest_flg = 0;
        $harvest->save();

        //「収穫済み」を含む場合はフラグ変更
        if($request->work_content_id == '収穫済み') {
            $harvest->harvest_flg = 1;
            $harvest->save();
        }
        
        $product->work_date = $request->work_date;
        $product->work_content_id = $request->work_content_id;

        if(isset($request->seed_id)) {
            $product->seed_id = $request->seed_id;
        }
        if(isset($request->seed_volume)) {
            $product->seed_volume = $request->seed_volume;
        }
        if(isset($request->equipments_id)) {
            $product->equipments_id = $request->equipments_id;
        }
        if(isset($request->equip_method_id)) {
            $product->equip_method_id = $request->equip_method_id;
        }
        if(isset($request->others)) {
            $product->others = $request->others;
        }
        if(isset($request->after_harvest_id)) {
            $product->after_harvest_id = $request->after_harvest_id;
        }

        $product->save();
    }

    public function delete(Request $request)
    {
        //物理削除に変更するためテスト中
        $productions = ProductionControl::where('id', $request->id)->first();
        $productions->delete();

        if($request->harvest == '収穫済み') {
            $harvest = GrowingCrop::find($request->growing_crop_id);
            $harvest->harvest_flg = 0;
            $harvest->save();
        }
    }

    public function get_selectbox()
    {
        $select1 = Template::where('category_id', 1)->where('del_flg', 0)->get();
        $select2 = Template::where('category_id', 2)->where('del_flg', 0)->get();
        $select3 = Template::where('category_id', 3)->where('del_flg', 0)->get();
        $select4 = Template::where('category_id', 4)->where('del_flg', 0)->get();
        $select5 = Template::where('category_id', 5)->where('del_flg', 0)->get();
        $select6 = Template::where('category_id', 6)->where('del_flg', 0)->get();

        return [
            'select1' => $select1,
            'select2' => $select2,
            'select3' => $select3,
            'select4' => $select4,
            'select5' => $select5,
            'select6' => $select6,
        ];
    }

    public function sort(Request $request)
    {   
        $crops = ProductionControl::where('growing_crop_id', $request->id)->with('growing_crop.variety')->where('del_flg', 0);

        if($request->class == 'asc') {
            $result = $crops->orderBy('work_date', 'asc')->paginate(10);
            return ['result' => $result];
        } else {
            $result = $crops->orderBy('work_date', 'desc')->paginate(10);
            return ['result' => $result];
        }
    }
}
