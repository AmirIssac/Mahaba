@extends('Layouts.dashboard_main')
@section('links')
<style>
.radius-span{
  border-radius:50%;
  margin: 0 10;
}
</style>
@endsection
@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
  <div class="row">
    <div class="col-md-12 ml-auto mr-auto">
      <div class="card card-upgrade">
        <div class="card-header text-center">
          <h3 class="card-title"><b style="color: #fa7a50">Messages</b>
          </h3>
            <p class="card-category"></p>
        </div>
        <div class="card-body">
          <div class="table-responsive table-upgrade">
            <table class="table">
              <thead>
                <th style="font-weight: bold">Name</th>
                <th style="font-weight: bold" class="text-center">Email</th>
                <th style="font-weight: bold" class="text-center">Phone</th>
                <th style="font-weight: bold" class="text-center">Subject</th>
                <th style="font-weight: bold" class="text-center">Date</th>
                <th style="font-weight: bold" class="text-center">Action</th>
              </thead>
              <tbody>
                @foreach($messages as $message)
                <tr style="font-weight: bold">
                  <td>{{$message->name}}</td>
                  <td class="text-center">{{$message->email}}</td>
                  <td class="text-center">
                     {{ $message->phone }}
                  </td>
                  <td class="text-center">
                    {{ $message->subject }}
                  </td>
                  <td class="text-center">{{$message->created_at}}</td>
                  <td class="text-center"><a href="{{ route('dashboard.view.message',$message->id) }}"><i class="fas fa-eye"></i></a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {!! $messages->links() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
