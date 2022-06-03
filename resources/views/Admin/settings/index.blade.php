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
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h5 class="title">Settings</h5>
        </div>
        <div class="card-body">
            <form action="{{route('update.settings')}}" method="POST">
                @csrf
            <div class="row">
              <div class="col-md-3 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label>Tax %</label>
                  <input type="number" name="tax" step="0.5" value="{{$tax}}" class="form-control">
                </div>
              </div>
              <div class="col-md-3 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label>Minimum order limit</label>
                  <input type="number" name="min_order_val" value="{{$min_order}}" class="form-control">
                </div>
              </div>
              <div class="col-md-6 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label>close delivery</label>
                  <input type="time" name="close_delivery_time" value="{{$close_delivery}}" class="form-control">
                </div>
              </div>
              <div class="col-md-3 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label> hours to deliver if we are free </label>
                  <input type="number" name="hours_to_deliver_free" value="{{$hours_deliver_when_free}}" class="form-control">
                </div>
              </div>
              <div class="col-md-3 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label> number of orders that increase delivery time </label>
                  <input type="number" name="number_of_orders_increase" value="{{$number_of_orders_increase_time}}" class="form-control">
                </div>
              </div>
              <div class="col-md-6 pr-1">
              </div>
              <div class="col-md-4 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label> 1% discount for each points </label>
                  <input type="number" name="one_percent_discount_for_each_points" value="{{$one_percent_discount_by_points}}" class="form-control">
                </div>
              </div>
              <div class="col-md-4 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label> Purchase value to add points </label>
                  <input type="number" step="0.01" name="purchase_value_to_add_points" value="{{$purchase_value_to_add_points}}" class="form-control">
                </div>
              </div>
              <div class="col-md-4 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label> Add points when purchase </label>
                  <input type="number" name="add_points_when_purchase" value="{{$add_points_by}}" class="form-control">
                </div>
              </div>
              <div class="col-md-3 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label> Contact phone </label>
                  <input type="text" name="contact_phone" value="{{$contact_phone}}" class="form-control">
                </div>
              </div>
              <div class="col-md-6 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label> Contact Email </label>
                  <input type="email" name="contact_email" value="{{$contact_email}}" class="form-control">
                </div>
              </div>
            </div>
            <button id="edit-user-btn" class="btn btn-primary">Update</button>
            </form>
        </div>
      </div>
    </div>

  </div>
</div>
@section('scripts')

@endsection

@endsection
