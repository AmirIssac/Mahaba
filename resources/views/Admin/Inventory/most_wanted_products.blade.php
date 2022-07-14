@extends('Layouts.dashboard_main')
@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
  <div class="row">
    <div class="col-md-12 ml-auto mr-auto">
      <div class="card card-upgrade">
        <div class="card-header text-center">
          <h4 class="card-title"><b style="color: #fa7a50">Most Wanted Products</h3>
            <p class="card-category"></p>
        </div>
        <div class="card-body">
          <div class="table-responsive table-upgrade">
            <table class="table">
              <thead>
                <th style="font-weight: bold">SKU</th>
                <th style="font-weight: bold">Name</th>
                <th style="font-weight: bold" class="text-center">requested</th>
              </thead>
              <tbody>
                @foreach($products as $product)
                    <tr style="font-weight: bold">
                        <td>{{ $product['sku'] }}</td>
                        <td>{{ $product['name'] }}</td>
                        <td class="text-center">
                            <span class="badge badge-success">
                            {{ $product['requested'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
