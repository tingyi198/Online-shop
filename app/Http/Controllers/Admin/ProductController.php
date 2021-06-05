<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redis;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productCount = Product::count();
        $dataPerPage = 5;
        $productPages = ceil($productCount / $dataPerPage);
        $currentPage = isset($request->all()['page']) ? $request->all()['page'] : 1;
        $products = Product::orderBy('created_at', 'desc')
            ->offset($dataPerPage * ($currentPage - 1))
            ->limit($dataPerPage)
            ->get();

        return view('admin.products.index', [
            'products' => $products,
            'productCount' => $productCount,
            'productPages' => $productPages
        ]);
    }

    public function uploadImage(Request $request)
    {
        // get the uploaded file
        $file = $request->file('product_image');
        $productId = $request->input('product_id');
        if (is_null($productId)) {
            return redirect()->back()->withErrors([
                'msg' => '參數錯誤'
            ]);
        }
        $product = Product::find($productId);

        // store the image
        $path = $file->store('public/images');  // 可自訂路徑
        $product->images()->create([
            'filename' => $file->getClientOriginalName(),
            'path' => $path
        ]);

        return redirect()->back();
    }

    public function import(Request $request)
    {
        $file = $request->file('excel');
        Excel::import(new ProductsImport, $file);
        return redirect()->back();
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
}
