@extends('Layouts.dashboard_main')
@section('content')
<div class="panel-header">
    <div class="header text-center">
      <h2 class="title">Users</h2>
    </div>
</div>
<div class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Team</h4>
          </div>
          <div class="card-body">
            @foreach($super_admins as $super_admin)
            <div class="alert alert-primary">
                <span><b> {{$super_admin->name}} - </b> {{$super_admin->email}} </span>
                <a href="{{route('view.user',$super_admin->id)}}"><i style="color:white; float: right" class="fa fa-eye"></i></a>
            </div>
            @endforeach
            @foreach($admins as $admin)
            <div class="alert alert-info">
                <span><b> {{$admin->name}} - </b> {{$admin->email}} </span>
                <a href="{{route('view.user',$admin->id)}}"><i style="color: white; float: right" class="fa fa-eye"></i></a>
            </div>
            @endforeach
            @foreach($employees as $employee)
            <div class="alert alert-info">
                <span><b> {{$employee->name}} - </b> {{$employee->email}} - </span>
                @if($employee->stores->count() > 1)
                    <span><b> Multi stores </b> </span>
                @elseif($employee->stores->count() == 1)
                    <span> <b> {{$employee->stores->first()->name_en}} </b> </span>
                @else
                    <span><b> no </b> </span>
                @endif
                <a href="{{route('view.user',$employee->id)}}"><i style="color: white; float: right" class="fa fa-eye"></i></a>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Customers <span class="badge badge-success">{{$customers->count()}}</span></h4>
          </div>
          <div class="card-body">
            @foreach($customers as $customer)
                <div class="alert alert-success">
                <span><b> {{$customer->name}} - </b> {{$customer->email}} </span>
                <a href="{{route('view.user',$customer->id)}}"><i style="color: white; float: right" class="fa fa-eye"></i></a>
                </div>
            @endforeach
            {{$customers->links()}}
            {{--
            <div class="alert alert-info">
              <button type="button" aria-hidden="true" class="close">
                <i class="now-ui-icons ui-1_simple-remove"></i>
              </button>
              <span><b> Info - </b> This is a regular notification made with ".alert-info"</span>
            </div>
            <div class="alert alert-success">
              <button type="button" aria-hidden="true" class="close">
                <i class="now-ui-icons ui-1_simple-remove"></i>
              </button>
              <span><b> Success - </b> This is a regular notification made with ".alert-success"</span>
            </div>
            <div class="alert alert-warning">
              <button type="button" aria-hidden="true" class="close">
                <i class="now-ui-icons ui-1_simple-remove"></i>
              </button>
              <span><b> Warning - </b> This is a regular notification made with ".alert-warning"</span>
            </div>
            <div class="alert alert-danger">
              <button type="button" aria-hidden="true" class="close">
                <i class="now-ui-icons ui-1_simple-remove"></i>
              </button>
              <span><b> Danger - </b> This is a regular notification made with ".alert-danger"</span>
            </div>
                --}}
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection