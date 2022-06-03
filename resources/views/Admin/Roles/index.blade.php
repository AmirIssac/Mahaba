@extends('Layouts.dashboard_main')
@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
  <div class="row">
    <div class="col-md-12 ml-auto mr-auto">
      <div class="card card-upgrade">
        <div class="card-header text-center">
          <h4 class="card-title">Roles <span class="badge badge-primary"> {{$roles->count()}} </span> </h3>
            <p class="card-category"></p>
        </div>
        <div class="card-body">
          <div class="table-responsive table-upgrade">
            <table class="table">
              <thead>
                <th style="font-weight: bold">#</th>
                <th style="font-weight: bold">Roles</th>
                <th style="font-weight: bold"  class="text-center">Action</th>
              </thead>
              <tbody>
                @foreach($roles as $role)
                <tr style="font-weight: bold">
                <td>{{$loop->index + 1}}</td>
                <td>{{$role->name}}</td>
                <td class="text-center">edit</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12 ml-auto mr-auto">
        <div class="card card-upgrade">
          <div class="card-header text-center">
            <h4 class="card-title">Permissions</h3>
              <p class="card-category"></p>
          </div>
          <div class="card-body">
            <div class="table-responsive table-upgrade">
              <table class="table">
                <thead>
                  <th style="font-weight: bold">#</th>
                  <th style="font-weight: bold">Permission</th>
                  <th style="font-weight: bold"  class="text-center">Action</th>
                </thead>
                <tbody>
                  @foreach($permissions as $permission)
                  <tr style="font-weight: bold">
                  <td>{{$permission->id}}</td>
                  <td>{{$permission->name}}</td>
                  <td class="text-center">edit</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              {!! $permissions->links() !!}
            </div>
          </div>
        </div>
      </div>

  </div>
</div>
@endsection