@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Payment Product
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
                    <h1 class="text-center mb-4">Stripe Payment</h1>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form id="payment-form" method="POST" action="{{ route('payment.submission') }}">
                                @csrf
                                @method('POST')
                                <table class="table table-bordered" >
                                    <tr>
                                        <th>Amount (USD):</th>
                                        <td ><input id="amount" name="amount" type="number" class="form-control" required></td>
                                    </tr>
                                    <tr>
                                        <th>Card Details:</th>
                                        <td class="form-control">
                                            <div id="card-element" ></div>
                                            <div id="card-errors" class="text-danger mt-2"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button id="submit" type="submit" class="btn btn-primary">Pay</button></td>
                                    </tr>
                                    <div id="error-message"></div>
                                </table>
                            </form>
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

    <script src="https://js.stripe.com/v3/"></script>

    <script>
        var stripe = Stripe("{{ config('services.stripe.key') }}");
        var elements = stripe.elements();

        var card = elements.create("card");

        // Mount the card Element to the DOM
        card.mount("#card-element");

        var form = document.getElementById("payment-form");
        var errorMessage = document.getElementById("error-message");

        form.addEventListener("submit", async (event) => {
            event.preventDefault();

            // create a PaymentMethod and send it to the server
            const {token, error} = await stripe.createPaymentMethod("card", card);

            if (error) {
                errorMessage.textContent = error.message;
            } else {
                // send token to the backend for processing
                const response = await fetch("{{ route('payment.submission') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({payment_method_id: token.id}),
                });

                const result = await response.json();

                if (result.success) {
                    alert("Payment Successful!");
                } else {
                    alert(result.error || "Payment failed");
                }
            }
        });
    </script>
@endsection
