<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\TypeProduct;
use Validator;
use Exception;

class ProductController extends Controller
{
    public function hello(Request $req){
        return response()->json(['message'=> 'hello world this is API']);
        // return response()->json($req->all());
    }

    public function __construct(Request $req)
    {
        $this->req = $req;
        $this->code = 500;
        $this->code_str = 'ERROR';
        $this->success = false;
    }


    public function allProduct(){
        $data = [];
        try{
            $data = DB::connection()->select('SELECT p.product_id, p.title, p.price, tp.title AS product_type FROM products p INNER JOIN type_product tp ON p.type_product_id = tp.type_product_id');
            $this->code = 200;
            $this->code_str = 'OK';
            $this->success = true;
        }catch(Exception $e){
            $data = $e->getMessage();
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function oneProduct($id){
        $data = [];
        try{
            if (!is_numeric($id)) {
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }
            $data = DB::connection()->select('SELECT p.product_id, p.title, p.price, tp.title AS product_type FROM products p INNER JOIN type_product tp ON p.type_product_id = tp.type_product_id WHERE p.product_id=:product_id',[
                'product_id'=>$id
            ]);
            if(!$data){
                $this->code_str = 'NOT_FOUND';
                $this->code = 404;
                throw new Exception();
            }
            $data = $data[0];
            $this->code = 200;
            $this->code_str = 'OK';
            $this->success = true;
        }catch(Exception $e){
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function addProduct(){
        $data = [];
        try{
            $validator = Validator::make($this->req->all(), [
                'title'=> 'required|string|min:1',
                'price'=> 'required|numeric|min:0',
                'type_product_id'=> 'required|integer',
            ]);
            if($validator->fails()){
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }

            //check type_product_id
            $type = TypeProduct::where("type_product_id", $this->req->input('type_product_id'))->get()->toArray();
            if(!$type){
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }

            //insert
            $toInsert = [
                'title'=>$this->req->input('title'),
                'price'=> $this->req->input('price'),
                'type_product_id'=>$this->req->input('type_product_id')
            ];
            Product::insert($toInsert);

            $this->code = 200;
            $this->code_str = 'OK';
            $this->success = true;
        }catch(Exception $e){
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function editProduct($id){
        $data = [];
        try{
            if (!is_numeric($id)) {
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }
            $validator = Validator::make($this->req->all(), [
                'title'=> 'required|string|min:1',
                'price'=> 'required|numeric|min:0',
                'type_product_id'=> 'required|integer',
            ]);
            if($validator->fails()){
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }

            //check type_product_id
            $type = TypeProduct::where("type_product_id", $this->req->input('type_product_id'))->get()->toArray();
            if(!$type){
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }

            //edit
            $toEdit = [
                'title'=>$this->req->input('title'),
                'price'=> $this->req->input('price'),
                'type_product_id'=>$this->req->input('type_product_id')
            ];
            Product::where('product_id',$id)->update($toEdit);

            $this->code = 200;
            $this->code_str = 'OK';
            $this->success = true;
        }catch(Exception $e){
            $data = $e->getMessage();
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function delProduct($id){
        $data = [];
        try{
            if (!is_numeric($id)) {
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }

            Product::where('product_id', $id)->delete();
            
            $this->code = 200;
            $this->code_str = 'OK';
            $this->success = true;
        }catch(Exception $e){
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }


    //type
    public function addType(){
        $data = [];
        try{
            $validator = Validator::make($this->req->all(), [
                'title'=> 'required|string|min:1',
            ]);
            if($validator->fails()){
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }

            //insert
            TypeProduct::insert(['title'=>$this->req->input('title')]);

            $this->code = 200;
            $this->code_str = 'OK';
            $this->success = true;
        }catch(Exception $e){
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function allType(){
        $data = [];
        try{
            //get
            $data = TypeProduct::all()->toArray();

            $this->code = 200;
            $this->code_str = 'OK';
            $this->success = true;
        }catch(Exception $e){
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function editType($id){
        $data = [];
        try{
            if (!is_numeric($id)) {
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }
            $validator = Validator::make($this->req->all(), [
                'title'=> 'required|string|min:1',
            ]);
            if($validator->fails()){
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }

            //edit
            TypeProduct::where('type_product_id',$id)->update(['title'=>$this->req->input('title')]);

            $this->code = 200;
            $this->code_str = 'OK';
            $this->success = true;
        }catch(Exception $e){
            $data = $e->getMessage();
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }

    public function delType($id){
        $data = [];
        try{
            if (!is_numeric($id)) {
                $this->code_str = 'INPUT';
                $this->code = 400;
                throw new Exception();
            }

            //delete type
            TypeProduct::where('type_product_id', $id)->delete();

            //delete product in this type
            Product::where('type_product_id', $id)->delete();
            
            $this->code = 200;
            $this->code_str = 'OK';
            $this->success = true;
        }catch(Exception $e){
            $this->success = false;
        }
        return response()->json(['data'=>$data,'code'=>$this->code_str,'success'=> $this->success],$this->code);
    }
}
