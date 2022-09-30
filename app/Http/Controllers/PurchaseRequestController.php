<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Colour;
use App\Models\location;
use App\Models\Prefix;
use App\Models\PurchaseRequest;
use App\Models\ships;
use App\Models\Master_Item;
use App\Models\Satuan;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\Powder;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{

    // *******************
    //     Index Home
    // ********************
    public function index(Request $request){
        if ($request->filled('search')) {
            $purchase_requests = PurchaseRequest::search($request->search)->get();
        }else{
            $items = Item::get();
            $powders = Powder::get();
            $purchase_requests = PurchaseRequest::get();
        }

        return view('purchases.index', compact('items','powders','purchase_requests'));
    }

    public function track(Request $request){
        if ($request->filled('search')) {
            $purchase_requests = PurchaseRequest::search($request->search)->get();
        }else{
            $purchase_requests = PurchaseRequest::with('Prefixe')->get();
        }

        return view('Tracking.approval', compact('purchase_requests'));
    }


    // *******************
    //     Index Create
    // ********************
    public function create(){
        $colour = Colour::get();
        $purchase_requests = PurchaseRequest::with('Prefixe');
        $Location = location::get();
        $Ship = ships::get();
        $Prefixe = Prefix::get();
        $master_item = Master_Item::get();
        $satuan = Satuan::get();
        $Grade = Grade::get();
        $color = Supplier::get();
        $Powder = powder::get();
        
        return view('purchases.create', compact('colour','Location','Ship', "Prefixe",'master_item','satuan', 'Grade'));
    }


    // *******************
    //     Store Other Good
    // ********************
    public function item_store(Request $request){


        $validateData = $request->validate([
            'deadline_date'=>'required',
            'requester'=>'required|max:100',
            'project'=>'max:100',
            'attachment' => 'mimes:jpeg,img,jpg,png|max:20000',
            'locations_id' => 'required',
            'prefixes_id' => 'required',
            'ships_id' => 'required'

        ], [
            'deadline_date.required'=>"Deadline Date field is required ",
            'requester.required'=>"Requester field is required ",
            'project.required'=>"Project field is required ",
            'attachment.required'=>"Attachment field is required ",
        ]);
        if($request->hasFile('attachment'))
        {
            $destination_path = 'public/assets/images/products';
            $image = $request->file('attachment');
            $image_name = $image->getClientOriginalName();
            $path = $request -> file('attachment')->storeAs($destination_path,$image_name);

            $purchase_requests = PurchaseRequest::create([
                'deadline_date'=>$request->deadline_date,
                'type'=>$request->type,
                'requester'=>$request->requester,
                'prefixes_id'=>$request->prefixes_id,
                'project'=>$request->project,
                'locations_id'=>$request->locations_id,
                'ships_id'=>$request->ships_id,
                'note'=>$request->note,
                'attachment'=>$image_name,
            ]);
        }
        else
        {
            $purchase_requests = PurchaseRequest::create([
                // 'no_pr'=>$request->no_pr,
                'deadline_date'=>$request->deadline_date,
                'type'=>$request->type,
                'requester'=>$request->requester,
                'prefixes_id'=>$request->prefixes_id,
                'project'=>$request->project,
                'locations_id'=>$request->locations_id,
                'ships_id'=>$request->ships_id,
                'note'=>$request->note,
            ]);

        }
            foreach ((array)$request->addMoreInputFields as $key => $value) {
            
                $item = Item::create($value);
                $request_id = $item->id;
                $request_now = Item::find($request_id);
                $purchase_requests->item()->attach($request_now);
            }
           
        return redirect('/purchase_request');
    }

    // *******************
    //     Store Powder
    // ********************
    public function powder_store(Request $request){
        $validateData = $request->validate([
            'deadline_date'=>'required',
            'requester'=>'required|max:100',
            'project'=>'max:100',
            'attachment' => 'mimes:jpeg,img,jpg,png|max:20000',
            'locations_id' => 'required',
            'prefixes_id' => 'required',
            'ships_id' => 'required'

        ], [
            'deadline_date.required'=>"Deadline Date field is required ",
            'requester.required'=>"Requester field is required ",
            'project.required'=>"Project field is required ",
            'attachment.required'=>"Attachment field is required ",
        ]);
        if($request->hasFile('attachment'))
        {
            $destination_path = 'images/products';
            $image = $request->file('attachment');
            $image_name = $image->getClientOriginalName();
            $path = $request -> file('attachment')->move($destination_path,$image_name);

            $purchase_requests = PurchaseRequest::create([
                'deadline_date'=>$request->deadline_date,
                'type'=>$request->type,
                'requester'=>$request->requester,
                'prefixes_id'=>$request->prefixes_id,
                'project'=>$request->project,
                'locations_id'=>$request->locations_id,
                'ships_id'=>$request->ships_id,
                'note'=>$request->note,
                'attachment'=>$image_name,
            ]);
        }
        else
        {
            $purchase_requests = PurchaseRequest::create([
                // 'no_pr'=>$request->no_pr,
                'deadline_date'=>$request->deadline_date,
                'type'=>$request->type,
                'requester'=>$request->requester,
                'prefixes_id'=>$request->prefixes_id,
                'project'=>$request->project,
                'locations_id'=>$request->locations_id,
                'ships_id'=>$request->ships_id,
                'note'=>$request->note,
            ]);

        }

        $powder = new powder;
        $powder->grades_id = $request->grades_id;
        $powder->suppliers_id = $request->suppliers_id;
        $powder->warna =$request->warna;
        $powder->color_id =$request->color_id;
        $powder->finish =$request->finish;
        $powder->finishing =$request->finishing;
        $powder->quantity =$request->quantity;
        $powder->m2 =$request->m2;
        $powder->estimasi =$request->estimasi;
        $powder->fresh =$request->fresh;
        $powder->recycle =$request->recycle;
        $powder->alokasi =$request->alokasi;
        $powder->save();

        $request_id = $powder->id;
        $request_now = powder::find($request_id);
        $purchase_requests->powder()->attach($request_now);

        return redirect('/purchase_request');

    }

    public function edit($id){
        $purchase_requests = PurchaseRequest::findOrFail($id);
        $Location=location::get();
        $Ship=ships::get();
        $Prefixe=Prefix::get();
        $Grade = Grade::get();
        $Supplier = Supplier::get();

        return view('purchases.edit', compact('purchase_requests', 'Location', 'Ship', 'Prefixe', 'Grade', 'Supplier'));
    }

    public function view($id){
        $purchase_requests = PurchaseRequest::find($id);
        $item = Item::with('master_item','satuan')->get();


        $Location=location::get();
        $Ship=ships::get();
        $Prefixe=Prefix::get();
        $Grade = Grade::get();
        $Supplier = Supplier::get();

        return view('view', compact('purchase_requests', 'Location', 'Ship', 'Prefixe','item', 'Grade', 'Supplier'));
    }

    public function detail($id){
        $purchase_requests = PurchaseRequest::find($id);
        $powder = powder::with('grade','supplier')->get();

       

        $Location=location::get();
        $Ship=ships::get();
        $Prefixe=Prefix::get();
        $Grade = Grade::get();
        $Supplier = Supplier::get();

        return view('detail', compact('purchase_requests', 'Location', 'Ship', 'Prefixe', 'Grade', 'Supplier'));
    }

    public function plus($id){
        $purchase_requests = PurchaseRequest::findOrFail($id);
        
        $item = DB::table('items')
                ->join('purchase_requests', 'id_request', '=', 'purchase_requests.id')
                ->join('master_items', 'id_master_item', '=', 'master_items.id')
                ->join('satuans', 'id_satuan', '=', 'satuans.id')
                ->select('items.*','master_items.item_name','satuans.unit')
                ->get();
        $satuan = Satuan::get();
        $master_item = Master_Item::get();

        return view('item.add', compact('purchase_requests','item','master_item','satuan'));
    }

    public function storeplus(Request $request, $id)
    {
        foreach ($request->addMoreInputFields as $key => $value) {
            Item::create($value);
        }
        return redirect('/purchase_request');
    }


    public function update(Request $request, $id){
        $purchase_requests = PurchaseRequest::findOrFail($id);
        $purchase_requests->update([
            'no_pr'=>$request->no_pr ?? $purchase_requests->no_pr,
            'deadline_date'=>$request->deadline_date ?? $purchase_requests->deadline_date,
            'requester'=>$request->requester ?? $purchase_requests->requester,
            'prefixes_id'=>$request->prefixes_id ?? $purchase_requests->prefixes_id,
            'project'=>$request->project ?? $purchase_requests->project,
            'locations_id'=>$request->locations_id ?? $purchase_requests->locations_id,
            'ships_id'=>$request->ships_id ?? $purchase_requests->ships_id,
            'note'=>$request->note ?? $purchase_requests->note,
            'attachment'=>$request->attachment ?? $purchase_requests->attachment,
            
        ]);
        return redirect('/purchase_request');

    }

    public function destroy($id){

        $purchase_requests = PurchaseRequest::findOrFail($id);
        $purchase_requests->delete();

        return redirect("/purchase_request");
     }

    public function show($id){

        
    }


    public function create_location(){
        return view('purchases.location.add');
    }

    public function store_location(Request $request)
    {
        

        $location = Location::create([
            'location_name' => $request->location_name,
            'address' => $request->address
        ]);

        return redirect('/purchase_request/create');
    }

    public function read_location(Request $request)
    {
        $Location = Location::get();

        return view('purchases.location.read', compact('Location'));
    }



    public function create_supplier(){
        return view('purchases.supplier.add');
    }

    public function store_supplier(Request $request)
    {
        

        $Supplier = Supplier::create([
            'vendor' => $request->vendor,
        ]);

        return redirect('/purchase_request/create');
    }

    public function read_supplier(Request $request)
    {
        $Supplier = Supplier::get();

        return view('purchases.supplier.read', compact('Supplier'));
    }


    public function create_color(){
        return view('purchases.color.add');
    }

    public function store_color(Request $request)
    {
        

        $colour = Colour::create([
            'name' => $request->name,
        ]);

        return redirect('/purchase_request/create');
    }

    public function read_color(Request $request)
    {
        $colour = Colour::get();

        return view('purchases.color.read', compact('colour'));
    }

    
    // prefix
    public function create_prefix(){
        return view('purchases.prefix.add');
    }

    public function store_prefix(Request $request)
    {
        

        $Prefixe = Prefix::create([
            'prefix' => $request->prefix,
            'divisi' => $request->divisi,
        ]);

        return redirect('/purchase_request/create');
    }

    public function read_prefix(Request $request)
    {
        $Prefixe = Prefix::get();

        return view('purchases.prefix.read', compact('Prefixe'));
    }

    // grade
    public function create_grade(){
        return view('purchases.grade.add');
    }

    public function store_grade(Request $request)
    {
        

        $Grade = Grade::create([
            'tipe' => $request->tipe,
        ]);

        return redirect('/purchase_request/create');
    }

    public function read_grade(Request $request)
    {
        $Grade = Grade::get();

        return view('purchases.grade.read', compact('Grade'));
    }

    // ships
    public function create_ships(){
        return view('purchases.ships.add');
    }

    public function store_ships(Request $request)
    {
        $Ship = New ships;
        $Ship->tipe = $request->tipe;
        $Ship->save();
        return redirect('/purchase_request/create');
    }

    public function read_ships(Request $request)
    {
        $Ship = ships::get();

        return view('purchases.ships.read', compact('Ship'));
    }

    // item
    public function create_item(){
        return view('purchases.item.add');
    }

    public function store_item(Request $request)
    {
        $master_item = New Master_Item;
        $master_item->item_name = $request->item_name;
        $master_item->save();
        return redirect('/purchase_request/create');
    }

    public function read_item(Request $request)
    {
        $master_item = Master_Item::get();

        return view('purchases.item.read', compact('master_item'));
    }

    // unit
    public function create_unit(){
        return view('purchases.unit.add');
    }

    public function store_unit(Request $request)
    {
        $satuan = New Satuan;
        $satuan->name = $request->name;
        $satuan->unit = $request->unit;
        $satuan->save();
        return redirect('/purchase_request/create');
    }

    public function read_unit(Request $request)
    {
        $satuan = Satuan::get();

        return view('purchases.unit.read', compact('satuan'));
    }
}