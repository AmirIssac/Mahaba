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
      <form action="{{ route('update.attribute.value' , $attr_val->id) }}" method="POST">
          @csrf
      <div id="new-product-form" class="card">
        <div class="card-header">
          <h4 class="card-title">Edit {{ $attr_val->value_en }}</h4>
            <p class="category"></p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead class=" text-primary">
                <th>
                  Class
                </th>
                <th>
                  Name AR
                </th>
                <th>
                  Name EN
                </th>
                <th>
                  Type
                </th>
                <th>
                  Price
                </th>
              </thead>
              <tbody>
               <tr>
                <td>
                    <select class="form-control" name="attribute_id">
                        @foreach($attributes as $attr)
                            <option value="{{ $attr->id }}" {{ $attr->id == $attr_val->attribute_id ? 'selected' : '' }}>{{ $attr->name_ar }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="value" value="{{ $attr_val->value }}" class="form-control" required>
                </td>
                <td>
                    <input type="text" name="value_en" value="{{ $attr_val->value_en }}" class="form-control">
                </td>
                <td>
                    <select name="type" class="form-control">
                        <option value="value" {{ $attr_val->value_type == 'value' ? 'selected' : '' }}>Value</option>
                        <option value="percent" {{ $attr_val->value_type == 'percent' ? 'selected' : '' }}>Percent</option>
                    </select>
                </td>
                <td>
                     <input type="number" min="0" step="0.1" name="price" value="{{ $attr_val->price }}" class="form-control" required>
                </td>
               </tr>
               <tr>
                   <td>
                       <button type="submit" class="btn btn-danger">update</button>
                   </td><td></td><td></td><td></td><td></td>
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
