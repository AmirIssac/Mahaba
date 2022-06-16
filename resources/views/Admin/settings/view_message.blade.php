@extends('Layouts.dashboard_main')
@section('links')
<style>
    .pending-row{
        background-color: #cfccca;
    }
    .preparing-row{
        background-color: #04558b;
        color: white;
    }
    .shipping-row{
        background-color: #409ad6;
    }
    .delivered-row{
        background-color: #38b818;
        color: white;
    }
    .rejected-row{
        background-color: #c00202;
        color: white;
    }
    .displaynone{
      display: none;
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
          <h4 class="card-title"> <span class="badge badge-primary"> {{$message->subject}} </span> </h3>
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
              </thead>
              <tbody>
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
                    </tr>
                    <tr>
                    <th>
                      Message
                    </th>
                    <th style="font-weight: bold" class="text-center">
                      {{ $message->message }}
                    </th>
                    <th style="font-weight: bold" class="text-center">
                    </th>
                    <th style="font-weight: bold" class="text-center">
                    </th>
                    <th style="font-weight: bold" class="text-center">
                    </th>
                    <th>
                    </th>
                    <th>
                    </th>
                 </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@section('scripts')
@endsection
@endsection
