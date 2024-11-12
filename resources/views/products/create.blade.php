@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Create Product
                    <a href="{{ url('products') }}" class="float-right btn-sm btn-success">View All</a>
                </h6>
            </div>
            <div class="card-body">

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <p class="text-danger">{{$error}}</p>
                    @endforeach
                @endif

                @if(Session::has('success'))
                    <p class="text-success">{{session('success')}}</p>
                @endif
                <div class="container">
                    <h1 class="text-center mb-4">Create Product</h1>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form id="productForm" method="POST" action="{{ route('product.store') }}">
                                @csrf
                                @method('POST')
                                <table class="table table-bordered" >
                                    <tr>
                                        <th>Product Name</th>
                                        <td > <input id="name" name="name" type="text" class="form-control" required></td>
                                    </tr>
                                    <tr>
                                        <th> Product Price</th>
                                        <td > <input id="price" name="price" type="number" class="form-control" required></td>
                                    </tr>
                                    <tr>
                                        <th>Product Quantity</th>
                                        <td > <input id="quantity" name="quantity" type="number" class="form-control" required></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><input type="submit" class="btn btn-primary"></td>
                                    </tr>
                                </table>
                            </form>
{{--                            for displaying error messages--}}
                            <div id="responseMessage"></div>
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

    <!-- JavaScript to handle the AJAX request -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Ajax -->
    <script>
        $(document).ready(function() {
            $('#productForm').submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('product.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#responseMessage').html('<div class="alert alert-success">Product added successfully!</div>');

                        $('#productForm')[0].reset();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '<br>';
                        });
                        $('#responseMessage').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                    }
                });
            });
        });
    </script>
@endsection
