<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $search         = $request->search;
            $start          = intval($request->start);
            $length         = intval($request->length);
            $order          = $request->order;
            $column_array = array(
                '0' => 'id',
                '1' => 'name',
                '2' => 'price',
                '3' => 'UPC',
                '4' => 'status',
                '5' => 'image',
            );

            $product  = Product::select(["products.*"]);

            $order_field = 'products.id';
            $order_sort  = 'DESC';
            if (!empty($order)) {
                if (isset($order[0]['column']) && $order[0]['column'] != '') {
                    $order_field = $column_array[$order[0]['column']];
                    $order_sort  = $order[0]['dir'];
                }
            }

            $product->orderBy($order_field, $order_sort);

            if (isset($search['value']) && ($search['value'] != '')) {
                $q = $search['value'];
                $product->where(function ($query)  use ($q) {
                    $query->orWhere('id', 'LIKE', '%' . $q . '%');
                    $query->orWhere('name', 'LIKE', '%' . $q . '%');
                    $query->orWhere('price', 'LIKE', '%' . $q . '%');
                    $query->orWhere('UPC', 'LIKE', '%' . $q . '%');
                    $query->orWhere('status', 'LIKE', '%' . $q . '%');
                });
            }
            $total_rows     = $product->get()->count();
            $filter_count   = $product->skip($start)->take($length)->get()->count();
            $records        = $product->skip($start)->take($length)->get();
            $products   = array();

            foreach ($records as $key => $value) {
                $row_data = array();
                $row_data['id']     = '<input type="checkbox" class="single_checkbox" value="' . $value->id . '">';
                $row_data['name']   = $value->name;
                $row_data['price']  = $value->price;
                $row_data['upc']    = $value->UPC;
                $row_data['status'] = $value->status;
                $row_data['image']  = '<img width="100px" src="' . url('uploads/' . $value->image) . '">';
                $row_data['action'] = "";
                $row_data['action'] = "<a class='mr-2' href='" . url('product/update/' . $value->id) . "'><i class='fa fa-edit'></i></a>";
                $row_data['action'] .= "<a href='javascript:void(0);'><i product_id='" . $value->id . "' class='fa fa-trash product-delete'></i></a>";
                $products[]         = $row_data;
            }

            $data['recordsTotal']       = $total_rows;
            $data['recordsFiltered']    = $filter_count;
            $data['data']               = $products;

            return $data;
        }
        return view('product.list');
    }

    public function setup(Request $request)
    {
        $product_id = $request->id;
        if ($product_id != '') {
            $product = product::where('id', $product_id)->first();
            $data['product'] = $product;
        }
        $data['product_id'] = $product_id;
        return view('product.form', $data);
    }

    public function commit(Request $request)
    {
        $rules = array(
            'name'      => 'required',
            'price'     => 'required|numeric',
            'upc'       => 'required',
            'status'    => 'required',
        );

        $product_id = $request->product_id;
        if ($product_id == '') {
            $rules['image'] = 'required|mimes:jpeg,jpg,png,gif|max:10000';
        }
        $request->validate($rules);
        $file_name = '';
        $result = Product::where('id', $product_id)->first();
        if (!empty($result)) {
            $file_name = $result->image;
        }
        $file = $request->file('image');
        if ($file != '') {
            if ($file_name != '') {
                unlink(public_path('uploads/' . $file_name));
            }
            $file_name = $file->getClientOriginalName();
            $file->move('uploads', $file_name);
        }

        $product_data = array(
            'name'      => $request->name,
            'price'     => $request->price,
            'UPC'       => $request->upc,
            'status'    => $request->status,
            'image'     => $file_name,
        );

        if ($product_id != '') {
            Product::where('id', $product_id)->update($product_data);
        } else {
            Product::create($product_data);
        }

        return redirect('product');
    }

    public function delete(Request $request)
    {
        $return = array();
        $product_id     = $request->id;
        $product_ids    = $request->ids;
        if ($product_id != '') {
            $product = Product::where('id', $product_id)->first();
        }
        if (!empty($product_ids)) {
            $product = Product::whereIn('id', $product_ids)->get();
        }

        if (!empty($product)) {
            if ($product_id != '') {
                $product_delete = $product->delete();
                if ($product->image != '') {
                    unlink(public_path('uploads/' . $product->image));
                }
            }
            if (!empty($product_ids)) {
                foreach ($product as $v) {
                    if ($v->image != '') {
                        unlink(public_path('uploads/' . $v->image));
                    }
                }
                $product_delete = Product::whereIn('id', $product_ids)->delete();
            }

            if ($product_delete) {
                $return['status']   = true;
                $return['message']  = 'Product deleted successfully';
            } else {
                $return['status']   = false;
                $return['message']  = 'Something went wrong';
            }
        } else {
            $return['status']   = false;
            $return['message']  = 'Something went wrong';
        }
        echo json_encode($return);
    }
}
