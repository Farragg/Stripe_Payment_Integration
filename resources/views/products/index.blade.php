@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Products
                    <a href="{{ route('product.create') }}" class="float-right btn-sm btn-success">Add New</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        @if($products)
                            <?php $i=0; ?>
                            @foreach($products as $product)
                                <?php $i++;?>
                                <tbody>
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>
                                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                        @can('update', $product)
                                        <a href="{{ url('product/' . $product->id) . '/edit'}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        @endcan
                                        @can('delete', $product)
                                            <a onclick="return confirm('Are You Sure to Delete This Data?')" href="{{ url('product/' . $product->id . '/delete') }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
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
