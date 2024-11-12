@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit {{ $product->name}} Product
                    <a href="{{ route('product.index') }}" class="float-right btn-sm btn-success">View All</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <p class="text-danger">{{$error}}</p>
                        @endforeach
                    @endif

                    @if(Session::has('success'))
                        <p class="text-success">{{session('success')}}</p>
                    @endif
                    <div class="container">
                            <h1 class="text-center mb-4">Update Product</h1>
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('product.update', $product->id) }}" >
                                        @csrf
                                        @method('PATCH')
                                        <table class="table table-bordered" >
                                            <tr>
                                                <th>Product Name</th>
                                                <td > <input name="name" type="text" class="form-control" value="{{ old('name', $product->name) }}"></td>
                                            </tr>
                                            <tr>
                                                <th> Product Price</th>
                                                <td > <input name="price" type="number" class="form-control" value="{{ old('price', $product->price) }}"></td>
                                            </tr>
                                            <tr>
                                                <th>Product Quantity</th>
                                                <td > <input name="quantity" type="number" class="form-control" value="{{ old('quantity', $product->quantity) }}"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><input type="submit" class="btn btn-primary"></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection


@section('scripts')
    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>

@endsection
