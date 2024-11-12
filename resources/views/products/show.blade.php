@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Show Details
                    <a href="{{ route('product.index') }}" class="float-right btn-sm btn-success">View All</a>
                </h6>
            </div>
            <div class="card-body">
                <h1 class="text-center mb-4">Show Product Details</h1>
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <tr>
                                <th>Product Name</th>
                                <td>{{$product ->name}}</td>
                            </tr>
                            <tr>
                                <th> Product Price</th>
                                <td>{{$product->price}}</td>
                            </tr>
                            <tr>
                                <th>Product Quantity</th>
                                <td>{{$product->quantity}}</td>
                            </tr>
                        </table>
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
