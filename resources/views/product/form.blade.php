@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>Product Form</h2>
    <form action="{{ route('product.commit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" class="product_id" value="{{$product_id}}" />
        <div class="row">
            <div class="form-group col-md-6">
                <label for="email">Name:</label>
                <input type="test" class="form-control" value="{{ isset($product->name) ? $product->name : ''}}" id="name" placeholder="Enter Name" name="name">
                @if($errors->has('name'))
                <div class="error">{{ $errors->first('name') }}</div>
                @endif
            </div>
            <div class="form-group col-md-6">
                <label for="pwd">price:</label>
                <input type="text" class="form-control" value="{{ isset($product->price) ? $product->price : ''}}" id="price" placeholder="Enter Price" name="price">
                @if($errors->has('price'))
                <div class="error">{{ $errors->first('price') }}</div>
                @endif
            </div>
            <div class="form-group col-md-6">
                <label for="pwd">UPC:</label>
                <input type="text" class="form-control" value="{{ isset($product->UPC) ? $product->UPC : ''}}" id="upc" placeholder="Enter upc" name="upc">
                @if($errors->has('upc'))
                <div class="error">{{ $errors->first('upc') }}</div>
                @endif
            </div>
            <div class="form-group col-md-6">
                <label for="pwd">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="">Select Status</option>
                    <option {{ (isset($product->status) && $product->status == 'Available') ? 'selected' : '' }} value="Available">Available</option>
                    <option {{ (isset($product->status) && $product->status == 'Unavailable') ? 'selected' : '' }} value="Unavailable">Unavailable</option>
                </select>
                @if($errors->has('status'))
                <div class="error">{{ $errors->first('status') }}</div>
                @endif
            </div>
            <div class="form-group col-md-6">
                <label for="pwd">Image</label>
                <input type="file" class="form-control" id="image" name="image">
                @if(isset($product->image) && $product->image != '')
                <img class="mt-3" src="{{url('uploads/' . $product->image)}}" width="100">
                @endif
                @if($errors->has('image'))
                <div class="error">{{ $errors->first('image') }}</div>
                @endif
            </div>
        </div>


        <button type="submit" class="btn btn-primary">Submit</button> <a class="btn btn-warning" href="{{ route('product') }}">Cancel</a>
    </form>
</div>
@endsection