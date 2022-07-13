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
          <h5 class="title">New Employee</h5>
        </div>
        <div class="card-body">
            <form action="{{route('store.employee')}}" method="POST">
                @csrf
            <div class="row">
              <div class="col-md-3 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label>First Name</label>
                  <input type="text" name="first_name" class="form-control">
                </div>
              </div>
              <div class="col-md-3 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label>Last Name</label>
                  <input type="text" name="last_name" class="form-control">
                </div>
              </div>
              <div class="col-md-6 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                    <label> E-mail </label>
                    <input type="email" name="email" class="form-control">
                </div>
              </div>
              <div class="col-md-3 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                    <label>phone</label>
                    <input type="text" name="phone" placeholder="+9715xxxxxxxx" class="form-control">
                </div>
              </div>
              <div class="col-md-3 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                  <label> Address </label>
                  <input type="text" name="address" class="form-control">
                </div>
              </div>
              <div class="col-md-6 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                    <label> Password </label>
                    <input type="password" name="password" class="form-control">
                </div>
              </div>

              <div class="col-md-6 pr-1">
                <div style="display: flex; flex-direction: column;" class="form-group">
                    <label> Store </label>
                    <select name="store_id" class="form-control">
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
              </div>

            </div>
            <button id="edit-user-btn" class="btn btn-primary">Create</button>
            </form>
        </div>
      </div>
    </div>

  </div>
</div>
@section('scripts')

@endsection

@endsection
