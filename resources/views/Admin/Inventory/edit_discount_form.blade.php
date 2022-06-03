@extends('Layouts.dashboard_main')
@section('links')
<style>
.displaynone{
    display : none;
}
</style>
@endsection
@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
  <div class="row">
    <div class="col-md-12">
      <form action="{{route('update.discount',$discount->id)}}" method="POST">
          @csrf
      <div id="new-product-form" class="card">
        <div class="card-header">
          <h4 class="card-title">Edit Discount</h4>
            <p class="category"></p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead class=" text-primary">
                <th>
                  Type
                </th>
                <th>
                  Value
                </th>
                <th>
                  Products
                </th>
                <th>
                  Expired at
                </th>
                <th>
                  Status
                </th>
              </thead>
              <tbody>
               <tr>
                <td>
                    <select name="discount_type" class="form-control">
                        @if($discount->type == 'percent')
                        <option value="percent" selected>percent</option>
                        <option value="value">value</option>
                        @else
                        <option value="percent">percent</option>
                        <option value="value" selected>value</option>
                        @endif
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" name="discount_value" value="{{$discount->value}}" class="form-control">
                </td>
                <td>
                    <select name="apply_discount_on_products[]" class="form-control" multiple>
                        @foreach($products as $product)
                          @if($product->discount && $product->discount->id == $discount->id)
                             <option value="{{$product->id}}" selected>#{{$product->code}} / {{$product->name_en}}</option>
                          @else
                             <option value="{{$product->id}}">#{{$product->code}} / {{$product->name_en}}</option>
                          @endif
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="datetime" name="expired_at" value="{{$discount->expired_at}}" class="form-control">
                </td>
                <td>
                  {{--
                    <select name="status" class="form-control">
                        @if($discount->active)
                            <option value="1" selected>Enabled</option>
                            <option value="0">Disabled</option>
                        @else
                            <option value="1">Enabled</option>
                            <option value="0" selected>Disabled</option>
                        @endif
                    </select>
                  --}}
                  @if($discount->active)
                      active
                  @else
                      inactive
                  @endif
                </td>
               </tr>
               <tr>
                   <td>
                       <button type="submit" class="btn btn-danger">update</button>
                   </td>
               </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection
