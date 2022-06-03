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
          <h5 class="title">Profile</h5>
        </div>
        <div class="card-body">
            <div class="row">
              @if(!$person->isCustomer($person))
              <div class="col-md-5 pr-1">
                <div class="form-group">
                  <label>Stores</label>
                  <ol>
                  @foreach($user_stores as $store)
                  {{--<input type="text" class="form-control" disabled="" value="{{$store->name_en}}">--}}
                  <li>{{$store->name_en}}</li>
                  @endforeach
                  </ol>
                </div>
              </div>
              @endif
              <div class="col-md-3 px-1">
                <div class="form-group">
                  <label>Role</label>
                  <ul>
                      {{--
                      <input type="text" class="form-control" disabled="" placeholder="Username" value="{{$person->getRoleNames()->first()}}">
                      --}}
                      <li>{{$person->getRoleNames()->first()}}</li>
                  </ul>
                </div>
              </div>
              <div class="col-md-4 pl-1">
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" class="form-control" value="{{$person->email}}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 pr-1">
                <div class="form-group">
                  <label>First Name</label>
                  <input type="text" class="form-control" placeholder="Company" value="{{$person->profile->first_name}}">
                </div>
              </div>
              <div class="col-md-6 pl-1">
                <div class="form-group">
                  <label>Last Name</label>
                  <input type="text" class="form-control" placeholder="Last Name" value="{{$person->profile->last_name}}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Address</label>
                  <input type="text" class="form-control" placeholder="Home Address" value="{{$person->profile->address_address}}">
                </div>
              </div>
            </div>
            <button id="edit-user-btn" class="btn btn-primary">Edit</button>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card card-user">
        <div style="padding: 10px;" class="image">
            @if($person->isSuperAdmin($person))
            <img src="{{asset('dashboard_asset/img/crown.png')}}" height="30px">
            @elseif($person->isAdmin($person))
            <img src="{{asset('dashboard_asset/img/crown.png')}}" height="30px">
            @elseif($person->isEmployee($person))
            {{--
            <img src="{{asset('dashboard_asset/img/crown.png')}}" height="30px">
            --}}
            @elseif($person->isCustomer($person))
            @endif
        </div>
        <div class="card-body">
          <div class="author">
              <img class="avatar border-gray" src="{{asset('dashboard_asset/img/default-avatar.png')}}" alt="...">
              <h5 class="title">{{$person->name}}</h5>
          </div>

          <div style="display: flex; justify-content: space-around">
            <img src="{{asset('dashboard_asset/img/orders.png')}}" alt="..." height="50px">
            <img src="{{asset('dashboard_asset/img/points.png')}}" alt="..." height="50px">
          </div>

          <div style="display: flex; justify-content: space-around; margin-top: 10px;">
            <h3>{{$orders_count}}</h3>
            <h3>{{$person->profile->points}}</h3>
          </div>

        </div>
        <hr>
        
      </div>
    </div>

    
      <div id="update-user-form" class="col-md-8 displaynone">
        <div class="card">
          <div class="card-header">
            <h5 class="title">Edit Information</h5>
          </div>
          <div class="card-body">
            <form action="{{route('update.user',$person->id)}}" method="POST">
              @csrf
              <div class="row">
                @if(!$person->isCustomer($person))
                <div class="col-md-6 px-1">
                  <div class="form-group">
                    <label>Stores</label>
                    <ol>
                    <select name="stores[]" class="form-control" multiple>
                      @foreach($all_stores as $store)
                          @if($user_stores->contains('id', $store->id))
                          <option value="{{$store->id}}" selected>{{$store->name_en}}</option>
                          @else
                          <option value="{{$store->id}}">{{$store->name_en}}</option>
                          @endif
                      @endforeach
                    </select>
                    </ol>
                  </div>
                </div>
                @endif
                <div class="col-md-6 ml-1">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" name="email" value="{{$person->email}}">
                  </div>
                </div>
                <div class="col-md-6 ml-1">
                  <div class="form-group">
                    <label for="exampleInputEmail1">New Password</label>
                    <input type="password" name="new_password" class="form-control">
                  </div>
                </div>
                <div class="col-md-6 ml-1">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 pr-1">
                  <div class="form-group">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="first_name" value="{{$person->profile->first_name}}">
                  </div>
                </div>
                <div class="col-md-6 pl-1">
                  <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="{{$person->profile->last_name}}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Address</label>
                    <input type="text" class="form-control" name="address" value="{{$person->profile->address_address}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{$person->profile->phone}}">
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">update</button>
            </form>
          </div>
        </div>    
  </div>
</div>
@if(Session::has('success'))
<input type="text" id="session-notification" value="{{Session::get('success')}}">
@endif
@section('scripts')
<script>
    $('#edit-user-btn').on('click',function(){
        $('#update-user-form').removeClass('displaynone');
        $(this).addClass('displaynone');
        $('html, body').animate({
          scrollTop: $("#update-user-form").offset().top
        }, 1000);
    })
</script>
@if(Session::has('success'))
  <script>
      nowuiDashboard.showNotification('top','center',$('#session-notification').val(),'success');
     // alert('ok');
  </script>
@endif
@endsection

@endsection
