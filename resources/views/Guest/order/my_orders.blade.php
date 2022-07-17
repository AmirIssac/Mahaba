@extends('Layouts.main')
@section('links')
<style>
    .table ,  td , th {
        color: #622521 !important;
    }
    tbody , td , th {
        color: #622521 !important;
    }
</style>
@endsection
@section('body')
<!-- Header-->
<header class="bg-second py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center product-div">
            <table class="table">
                <form action="{{ route('track.order.reference') }}" method="GET">
                    @csrf
                    <thead>
                        <tr>
                            <th>Order Code</th>
                            <th>
                                <input type="text" class="form-control" name="reference" placeholder="order reference..">
                            </th>
                            <th>
                                <button class="btn btn-success">search</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </form>
            </table>
        </div>
    </div>
</header>
@section('scripts')

@endsection
@endsection
