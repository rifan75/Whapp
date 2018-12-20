<?php

namespace Modules\Delete\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Delete\Entities\User;
use Modules\Delete\Entities\Profile;
use Modules\Delete\Entities\Brand;
use Modules\Delete\Entities\Measure;
use Modules\Delete\Entities\Supplier;
use Modules\Delete\Entities\Warehouse;
use Modules\Delete\Entities\Product;
use Modules\Delete\Entities\Purchase;
use Modules\Delete\Entities\Send;
use Modules\Delete\Entities\Inventory;

use Gate;

class DeleteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function user()
    {
        if (!Gate::allows('isSuperAdmin'))
        {
           return response()->view('error.404', [], 404);
        }
        $datas = User::onlyTrashed()->paginate(10);
        return view('delete::user',compact('datas'));
    }

    public function brand()
    {
        if (!Gate::allows('isSuperAdmin'))
        {
           return response()->view('error.404', [], 404);
        }
        $datas =Brand::onlyTrashed()->paginate(10);
        return view('delete::brand',compact('datas'));
    }

    public function measure()
    {
        if (!Gate::allows('isSuperAdmin'))
        {
           return response()->view('error.404', [], 404);
        }
        $datas =Measure::onlyTrashed()->paginate(10);
        return view('delete::measure',compact('datas'));
    }

    public function supplier()
    {
        if (!Gate::allows('isSuperAdmin'))
        {
           return response()->view('error.404', [], 404);
        }
        $datas =Supplier::onlyTrashed()->paginate(10);
        return view('delete::supplier',compact('datas'));
    }

    public function warehouse()
    {
        if (!Gate::allows('isSuperAdmin'))
        {
           return response()->view('error.404', [], 404);
        }
        $datas =Warehouse::onlyTrashed()->paginate(10);
        return view('delete::warehouse',compact('datas'));
    }

    public function product()
    {
        if (!Gate::allows('isSuperAdmin'))
        {
           return response()->view('error.404', [], 404);
        }
        $datas =Product::onlyTrashed()->paginate(10);
        return view('delete::product',compact('datas'));
    }

    public function purchase()
    {
        if (!Gate::allows('isSuperAdmin'))
        {
           return response()->view('error.404', [], 404);
        }
        $datas =Purchase::onlyTrashed()->paginate(10);
        return view('delete::purchase',compact('datas'));
    }

    public function send()
    {
        if (!Gate::allows('isSuperAdmin'))
        {
           return response()->view('error.404', [], 404);
        }
        $datas =Send::onlyTrashed()->paginate(10);
        return view('delete::send',compact('datas'));
    }

    public function inventory()
    {
        if (!Gate::allows('isSuperAdmin'))
        {
           return response()->view('error.404', [], 404);
        }
        $datas =Inventory::onlyTrashed()->paginate(10);
        return view('delete::inventory',compact('datas'));
    }

    public function restore($id, $model)
    {
        $model_link = "Modules\Delete\Entities\\".$model;
        $deleted=$model_link::onlyTrashed()->where('id',$id)->first();
        $deleted->restore();

        return back();
    }


    public function delete($id, $model)
    {

        $model_link = "Modules\Delete\Entities\\".$model;
        $deleted=$model_link::onlyTrashed()->where('id',$id)->first();
        $deleted->forceDelete();

        return back();
    }

}
