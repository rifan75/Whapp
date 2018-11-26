<?php

namespace Modules\Warehouse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Entities\Kirim;
use Modules\Warehouse\Entities\Product;
use Modules\Warehouse\Entities\Inventory;
use Modules\Warehouse\Entities\User;
use Hashids\Hashids;
use DateTime;
use DataTables;
use Auth;
use Carbon\Carbon;

class InventoryController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      public function index($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $inventory = Inventory::selectRaw('SUM(in_out_qty) as quantity,SUM(sub_total) as sub_total')
          ->where('warehouse',$ids)->where('deleted_at', NULL)->first();
          $warehouse = Warehouse::where('id',$ids)->first();

          return view('warehouse::inventory',compact('warehouse','inventory'));
      }

      public function getInventory($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $inventories = Inventory::selectRaw('ANY_VALUE(id),id_product,SUM(in_out_qty) as quantity,SUM(sub_total) as sub_total, measure,ANY_VALUE(warehouse)')
          ->where('warehouse',$ids)->where('deleted_at', NULL)
          ->groupBy('id_product','measure')->get();
          $no = 0;
          $data = array();
          foreach ($inventories as $inventory) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $inventory->id;
            $row[] = $inventory->product->code;
            $row[] = $inventory->product->name;
            $row[] = $inventory->quantity;
            $row[] = $inventory->measure;
            $row[] = number_format($inventory->sub_total,2);
            $row[] = "<a href='#' onclick='detailForm(\"".$inventory->hashproduct."\",\"".$inventory->product->name."\")' title='Inventory'>Details</a>";
            $row[] = number_format($inventory->sub_total/$inventory->quantity,2);
            $data[] = $row;
          }
          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function getDetailInventory($id, $idtab)
            {
              $hash = config('app.hash_key');
              $hashids = new Hashids($hash,20);
              $ids=$hashids->decode($id)[0];
              $idtabs=$hashids->decode($idtab)[0];

              $inventories = Inventory::where('id_product', $ids)->where('warehouse',$idtabs)->get();
              $no = 0;
              $data = array();
              foreach ($inventories as $inventory) {
                $no ++;
                $row = array();
                $row[] = $no;
                $row[] = $hashids->encode($inventory->id);
                $row[] = $inventory->product->code;
                $row[] = $inventory->product->name;
                $row[] = $inventory->in_out_qty;
                $row[] = $inventory->measure;
                $row[] = $inventory->note;
                $row[] = $inventory->user->name;
                $row[] = $inventory->type.'-'.$inventory->typedata->name;
                $row[] = number_format($inventory->sub_total,2);
                $data[] = $row;
              }
              return DataTables::of($data)->escapeColumns([])->make(true);
            }
}
