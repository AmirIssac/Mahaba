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
      <form action="{{route('update.product',$product->id)}}" method="POST" enctype="multipart/form-data">
          @csrf
      <div id="new-product-form" class="card">
        <div class="card-header">
          <h4 class="card-title">Edit Product</h4>
            <p class="category">{{$product->code}}</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead class=" text-primary">
                <th>
                  Code
                </th>
                <th>
                  Name EN
                </th>
                <th>
                  Name AR
                </th>
                <th>
                  Description
                </th>
                <th>
                  Unit
                </th>
                <th>
                  Category
                </th>
              </thead>
              <tbody>
                <tr>
                    <td> <input type="text" name="code" value="{{$product->code}}" class="form-control"> </td>
                    <td> <input type="text" name="name_en" value="{{$product->name_en}}" class="form-control"> </td>
                    <td> <input type="text" name="name_ar" value="{{$product->name_ar}}" class="form-control"> </td>
                    <td> <input type="text" name="description" value="{{$product->description}}" class="form-control"> </td>
                    <td>
                        <select name="unit" class="form-control">
                            <option value="gram" {{$product->unit == 'gram' ? 'selected' : ''}}>Gram</option>
                            <option value="piece" {{$product->unit == 'piece' ? 'selected' : ''}}>Piece</option>
                        </select>
                    </td>
                    <td>
                        <select name="category_id" class="form-control">
                            @foreach($categories as $category)
                                @if($product->category_id == $category->id)
                                    <option value="{{$category->id}}" selected>{{$category->name_en}}</option>
                                @else
                                    <option value="{{$category->id}}">{{$category->name_en}}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                  <th>
                    Availability
                  </th>
                  <th>
                    Price
                  </th>
                  <th>
                    Min weight
                  </th>
                  <th>
                    Increase by
                  </th>
                </tr>
                <tr>
                  <td>
                    <select name="availability" class="form-control">
                        @if($product->availability)
                        <option value="yes" selected>available in stock</option>
                        <option value="no">not available</option>
                        @else
                        <option value="yes">available in stock</option>
                        <option value="no" selected>not available</option>
                        @endif
                    </select>
                </td>
                  <td> <input type="number" step="0.1" name="price" value="{{$product->price}}" class="form-control"> </td>
                    <td> <input type="number" step="100" name="min_weight" value="{{$product->min_weight}}" class="form-control"> </td>
                    <td> <input type="number" step="100" name="increase_by" value="{{$product->increase_by}}" class="form-control"> </td>
                </tr>
                <tr>
                    <th>
                        Primary image *
                    </th>
                    <?php $count = 1 ?>
                    @foreach($product->productImages as $additional_image)
                    <th>
                        Image{{$count}} 
                    </th>
                    <?php $count++ ?>
                    @endforeach
                    @for($k=$count;$k<=4;$k++)
                    <th>
                    </th>
                    @endfor
                </tr>
                <tr>
                    <td>
                        <img src="{{asset('storage/'.$product->image)}}">
                    </td>
                    @foreach($product->productImages as $additional_image)
                    <td>
                        <img src="{{asset('storage/'.$additional_image->image)}}">
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <td>
                        <label for="file-input">
                            click to update
                        </label>
                        <input id="file-input" type="file" name="primary_image" class="displaynone"/>
                    </td>
                    <td>
                        <label for="file-input1">
                            click to update
                        </label>
                        <input id="file-input1" type="file" name="image1" class="displaynone"/>
                    </td>
                    <td>
                        <label for="file-input2">
                            click to update
                        </label>
                        <input id="file-input2" type="file" name="image2" class="displaynone"/>
                    </td>
                    <td>
                        <label for="file-input3">
                            click to update
                        </label>
                        <input id="file-input3" type="file" name="image3" class="displaynone"/>
                    </td>
                    <td>
                        <label for="file-input4">
                            click to update
                        </label>
                        <input id="file-input4" type="file" name="image4" class="displaynone"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button class="btn btn-primary">update</button>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
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
@section('scripts')
<script>
    $('#new-product-btn').on('click',function(){
        $('#new-product-form').removeClass('displaynone');
        $(this).addClass('displaynone');
    })
</script>
@endsection