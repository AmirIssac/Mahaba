@extends('Layouts.dashboard_main')
@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
  <div class="row">
    <div class="col-md-12 ml-auto mr-auto">
      <div class="card card-upgrade">
        <div class="card-header text-center">
          <h4 class="card-title"><b style="color: #fa7a50">Employees  {{$employees->count()}} </h3>
            <p class="card-category"></p>
        </div>
        <div class="card-body">
          <div class="table-responsive table-upgrade">
            <table class="table">
              <thead>
                <th style="font-weight: bold">Name</th>
                <th style="font-weight: bold">Email</th>
                <th style="font-weight: bold" class="text-center">Phone</th>
                <th style="font-weight: bold" class="text-center">Address</th>
                <th style="font-weight: bold" class="text-center">Store</th>
                <th style="font-weight: bold" class="text-center">Received orders</th>
                <th style="font-weight: bold" class="text-center">Action</th>
              </thead>
              <tbody>
                @foreach($employees as $employee)
                    <tr style="font-weight: bold">
                        <td>{{$employee->name}}</td>
                        <td>{{$employee->email}}</td>
                        <td class="text-center">{{$employee->profile->phone}}</td>
                        <td class="text-center">
                            @if($employee->profile->address_address)
                                {{$employee->profile->address_address}}
                            @else
                                /
                            @endif
                        </td>
                        <td class="text-center">
                            @if($employee->stores->count() > 1)
                            <span><b> Multi stores </b> </span>
                            @elseif($employee->stores->count() == 1)
                                <span> <b> {{$employee->stores->first()->name_en}} </b> </span>
                            @else
                                <span><b> no </b> </span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- order_systems for this employee  --}}
                            <?php $order_prepared_by_employee_count = App\Models\OrderSystem::where('user_id',$employee->id)->where('status','preparing')->count(); ?>
                            {{$order_prepared_by_employee_count}}
                        </td>
                        <td class="text-center">
                            <a href="{{route('view.user',$employee->id)}}"><i class="fa fa-eye"></i></a>
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
