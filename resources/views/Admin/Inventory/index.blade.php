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
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Products</h4>
          <a href="{{ route('products.filter','most_wanted') }}" class="btn btn-info">most wanted</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead class=" text-primary">
                <th>
                  Code
                </th>
                <th>
                  Name
                </th>
                <th>
                  Unit
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
                <th class="text-right">
                  Action
                </th>
              </thead>
              <tbody>
                @foreach($products as $product)
                <tr>
                  <td>
                    {{$product->code}}
                  </td>
                  <td>
                    {{$product->name_en}}
                  </td>
                  <td>
                    {{$product->unit}}
                  </td>
                  <td>
                    {{$product->price}}
                  </td>
                  <td>
                    {{$product->min_weight}} {{$product->unit}}
                  </td>
                  <td>
                    {{$product->increase_by}}
                  </td>
                  <td class="text-right">
                    {{--
                    <a href="{{route('edit.product.form',$product->id)}}" class="btn btn-info">Edit</a>
                    <a class="btn btn-danger">Delete</a>
                    --}}
                      <a href="{{route('edit.product.form',$product->id)}}"><i class="fas fa-tools"></i></a>
                      .
                      <a><i class="fas fa-trash-alt"></i></a>
                  </td>
                </tr>
                @endforeach
                <tr>
                    <td>
                        <button id="new-product-btn" class="btn btn-success">New</button>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td class="text-right">
                    </td>
                </tr>
              </tbody>
            </table>
            {{ $products->links() }}
          </div>
        </div>
      </div>

      {{-- new product Form --}}
      <form action="{{route('store.product')}}" method="POST" enctype="multipart/form-data">
          @csrf
      <div id="new-product-form" class="card displaynone">
        <div class="card-header">
          <h4 class="card-title">New Product</h4>
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
                    <td> <input type="text" name="code" class="form-control"> </td>
                    <td> <input type="text" name="name_en" class="form-control"> </td>
                    <td> <input type="text" name="name_ar" class="form-control"> </td>
                    <td> <input type="text" name="description" class="form-control"> </td>
                    <td>
                        <select name="unit" class="form-control">
                            <option value="gram">Gram</option>
                            <option value="piece">Piece</option>
                        </select>
                    </td>
                    <td>
                        <select name="category_id" class="form-control">
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name_en}}</option>
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
                  <th>
                    Options
                  </th>
                </tr>
                <tr>
                  <td>
                    <select name="availability" class="form-control">
                        <option value="yes">available in stock</option>
                        <option value="no">not available</option>
                    </select>
                </td>
                  <td> <input type="number" step="0.1" name="price" class="form-control"> </td>
                  <td> <input type="number" name="min_weight" class="form-control"> </td>
                  <td> <input type="number" name="increase_by" class="form-control"> </td>
                  <td>
                    {{--
                    <select name="options[]" class="form-control" multiple>
                      @foreach($options as $option)
                        <option value="{{$option->id}}">{{$option->name_en}}</option>
                      @endforeach
                    </select>
                    --}}
                  </td>
                </tr>
                <tr>
                    @foreach($attributes as $attribute)
                        <th>
                            {{ $attribute->name_ar }}
                            <select class="form-control" name="attribute_values[]" multiple>
                                @foreach($attribute->attributeValues as $attribute_value)
                                            <option value="{{ $attribute_value->id }}">
                                                {{ $attribute_value->value }}  +
                                                @if($attribute_value->isValue())
                                                    {{ $attribute_value->price }}
                                                @else
                                                    {{ $attribute_value->price }} %  ???? ????????????
                                                @endif
                                            </option>
                                @endforeach
                            </select>
                        </th>
                    @endforeach
                </tr>
                <tr>
                    <th>
                        Primary image *
                    </th>
                    <th>
                        Image 1
                    </th>
                    <th>
                        Image 2
                    </th>
                    <th>
                        Image 3
                    </th>
                    <th>
                        Image 4
                    </th>
                    <th>
                    </th>
                </tr>
                <tr>
                    <td>
                        <label for="file-input">
                            click to upload
                        </label>
                        <input id="file-input" type="file" name="primary_image" class="displaynone"/>
                    </td>
                    <td>
                        <label for="file-input1">
                            click to upload
                        </label>
                        <input id="file-input1" type="file" name="image1" class="displaynone"/>
                    </td>
                    <td>
                        <label for="file-input2">
                            click to upload
                        </label>
                        <input id="file-input2" type="file" name="image2" class="displaynone"/>
                    </td>
                    <td>
                        <label for="file-input3">
                            click to upload
                        </label>
                        <input id="file-input3" type="file" name="image3" class="displaynone"/>
                    </td>
                    <td>
                        <label for="file-input4">
                            click to upload
                        </label>
                        <input id="file-input4" type="file" name="image4" class="displaynone"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button class="btn btn-primary">create</button>
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

    <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"> Attributes </h4>
            <p class="category"></p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead class=" text-primary">
                  <th>
                    Name EN
                  </th>
                  <th>
                    Name AR
                  </th>
                  <th>
                    Collection type
                  </th>
                  <th class="text-right">
                    Actions
                  </th>
                </thead>
                <tbody>
                  @foreach($attributes as $attribute)
                  <tr>
                    <td>
                      {{$attribute->name_en}}
                    </td>
                    <td>
                      {{$attribute->name_ar}}
                    </td>
                    <td>
                        {{($attribute->isCheckbox() ? 'Multi' : 'Just one')}}
                    </td>
                    <td class="text-right">
                      <a><i class="fas fa-tools"></i></a>
                    </td>
                  </tr>
                  @endforeach
                  <tr>
                      <td>
                      <button id="new-attribute-btn" class="btn btn-success">New</button>
                      </td>
                      <td></td><td></td><td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>


            {{-- new Attribute Form --}}
            <div class="col-md-12">
            <form action="{{route('store.attribute')}}" method="POST">
                @csrf
            <div id="new-attribute-form" class="card displaynone">
              <div class="card-header">
                <h4 class="card-title">New Attribute</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                      <th>
                        Name EN
                      </th>
                      <th>
                        Name AR
                      </th>
                      <th>
                        Collection type
                      </th>
                    </thead>
                    <tbody>
                      <tr>
                          <td> <input type="text" name="name_en" class="form-control" required> </td>
                          <td> <input type="text" name="name_ar" class="form-control" required> </td>
                          <td>
                             <select name="type" class="form-control" required>
                                <option value="checkbox">Multi</option>
                                <option value="radio">just one</option>
                             </select>
                          </td>
                      </tr>
                      <tr>
                        <td><button class="btn btn-primary">Confirm</button></td>
                        <td></td><td></td>
                      </tr>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </form>
            </div>

      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"> Product Attributes </h4>
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
                    Price
                  </th>
                  <th class="text-right">
                    Actions
                  </th>
                </thead>
                <tbody>
                  @foreach($attribute_values as $attr_val)
                  <tr>
                    <td>
                      {{$attr_val->attribute->name_ar}}
                    </td>
                    <td>
                      {{$attr_val->value}}
                    </td>
                    <td>
                        {{$attr_val->value_en}}
                    </td>
                    <td>
                        @if($attr_val->value_type == 'value')
                        {{$attr_val->price}}
                        @else
                        {{$attr_val->price}}%
                        @endif
                    </td>
                    <td class="text-right">
                      <a href="{{ route('edit.attribute.value',$attr_val->id) }}"><i class="fas fa-tools"></i></a>
                    </td>
                  </tr>
                  @endforeach
                  <tr>
                      <td>
                      <button id="new-attribute-value-btn" class="btn btn-success">New</button>
                      </td>
                      <td></td><td></td><td></td><td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- new product Attribute Form --}}
      <div class="col-md-12">
      <form action="{{route('store.attribute.value')}}" method="POST">
        @csrf
    <div id="new-attribute-value-form" class="card displaynone">
      <div class="card-header">
        <h4 class="card-title">New Product Attribute</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead class=" text-primary">
              <th>
                Name EN
              </th>
              <th>
                Name AR
              </th>
              <th>
                Class
              </th>
              <th>
                Price
              </th>
              <th>
                Type
              </th>
            </thead>
            <tbody>
              <tr>
                  <td> <input type="text" name="name_en" class="form-control" required> </td>
                  <td> <input type="text" name="name_ar" class="form-control" required> </td>
                  <td>
                    <select name="attribute" class="form-control">
                        @foreach($attributes as $attribute)
                            <option value="{{$attribute->id}}">{{ $attribute->name_ar }}</option>
                        @endforeach
                    </select>
                  </td>
                  <td> <input type="number" min="0" step="0.1" name="price" class="form-control" required> </td>
                  <td>
                    <select name="type" class="form-control">
                        <option value="value">Value</option>
                        <option value="percent">Percent</option>
                    </select>
                  </td>
              </tr>
              <tr>
                <td><button class="btn btn-primary">Confirm</button></td>
                <td></td>
                <td></td><td></td><td></td>
              </tr>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</form>
</div>

    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">   Categories </h4>
          <p class="category"></p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead class=" text-primary">
                <th>
                  Name EN
                </th>
                <th>
                  Name AR
                </th>
                <th class="text-right">
                  Actions
                </th>
              </thead>
              <tbody>
                @foreach($categories as $category)
                <tr>
                  <td>
                    {{$category->name_en}}
                  </td>
                  <td>
                    {{$category->name_ar}}
                  </td>
                  <td class="text-right">
                    <a><i class="fas fa-tools"></i></a>
                  </td>
                </tr>
                @endforeach
                <tr>
                    <td>
                    <button id="new-category-btn" class="btn btn-success">New</button>
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- new Category Form --}}
    <div class="col-md-12">
        <form action="{{route('store.category')}}" method="POST" enctype="multipart/form-data">
          @csrf
      <div id="new-category-form" class="card displaynone">
        <div class="card-header">
          <h4 class="card-title">New Category</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead class=" text-primary">
                <th>
                  Name EN
                </th>
                <th>
                  Name AR
                </th>
                <th>
                  Image
                </th>
              </thead>
              <tbody>
                <tr>
                    <td> <input type="text" name="name_en" class="form-control" required> </td>
                    <td> <input type="text" name="name_ar" class="form-control" required> </td>

                    <td>{{--<label for="file-input-cat">
                        click to upload
                    </label>--}}
                    <input type="file" name="image" required/></td>
                </tr>
                <tr>
                  <td><button class="btn btn-success">Create</button></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
          </table>
        </div>
      </div>
    </div>
  </form>
  </div>


    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Discounts</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead class=" text-primary">
                <th>
                  ID
                </th>
                <th>
                  Type
                </th>
                <th>
                  Value
                </th>
                <th>
                  Products
                </th>
                <th>
                  Expired at
                </th>
                <th>
                  Status
                </th>
                <th>
                  Actions
                </th>
              </thead>
              <tbody>
              @foreach($discounts as $discount)
               <tr>
                 <td>
                   {{$discount->id}}
                 </td>
                 <td>
                    {{$discount->type}}
                 </td>
                 <td>
                    {{$discount->value}}
                </td>
                <td>
                    @if($discount->products()->count() > 0)
                    <select class="form-control">
                        @foreach($discount->products as $product)
                          <option>#{{$product->code}} / {{$product->name_en}}</option>
                        @endforeach
                    </select>
                    @else
                    <span class="badge badge-danger">none</span>
                    @endif
                </td>
                <td>
                  {{$discount->expired_at}}
                </td>
                <td>
                  @if($discount->active)
                  <span class="badge badge-success">active</span>
                  @else
                  <span class="badge badge-danger">disabled</span>
                  @endif
                </td>
                <td>
                  <a href="{{route('edit.discount.form',$discount->id)}}"><i class="fas fa-tools"></i></a>
                  .
                  <a><i class="fas fa-trash-alt"></i></a>
                </td>
               </tr>
               @endforeach
                <tr>
                    <td>
                        <button id="new-discount-btn" class="btn btn-success">New</button>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td class="text-right">
                    </td>
                </tr>
              </tbody>
            </table>

          </div>
        </div>
      </div>

      {{-- new discount Form --}}
      <form action="{{route('store.discount')}}" method="POST">
          @csrf
      <div id="new-discount-form" class="card displaynone">
        <div class="card-header">
          <h4 class="card-title">New Discount</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead class=" text-primary">
                <th>
                  Type
                </th>
                <th>
                  Value
                </th>
                <th>
                  Products
                </th>
                <th>
                  Expired at
                </th>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <select name="discount_type" class="form-control">
                        <option value="percent">percent</option>
                        <option value="value">value</option>
                    </select>
                 </td>
                 <td>
                    <input type="number" step="0.01" name="discount_value" class="form-control">
                </td>
                <td>
                    <select name="apply_discount_on_products[]" class="form-control" multiple>
                        @foreach($products as $product)
                          <option value="{{$product->id}}">#{{$product->code}} / {{$product->name_en}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="date" name="expired_at" class="form-control">
                </td>
                </tr>
                <tr>
                    <td>
                        <button class="btn btn-primary">create</button>
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
        $('html, body').animate({
          scrollTop: $("#new-product-form").offset().top
        }, 1000);
    });

    $('#new-attribute-btn').on('click',function(){
        $('#new-attribute-form').removeClass('displaynone');
        $(this).addClass('displaynone');
        $('html, body').animate({
          scrollTop: $("#new-attribute-form").offset().top
        }, 1000);
    });

    $('#new-attribute-value-btn').on('click',function(){
        $('#new-attribute-value-form').removeClass('displaynone');
        $(this).addClass('displaynone');
        $('html, body').animate({
          scrollTop: $("#new-attribute-value-form").offset().top
        }, 1000);
    });

    $('#new-category-btn').on('click',function(){
        $('#new-category-form').removeClass('displaynone');
        $(this).addClass('displaynone');
        $('html, body').animate({
          scrollTop: $("#new-category-form").offset().top
        }, 1000);
    });

    $('#new-discount-btn').on('click',function(){
        $('#new-discount-form').removeClass('displaynone');
        $(this).addClass('displaynone');
        $('html, body').animate({
          scrollTop: $("#new-discount-form").offset().top
        }, 1000);
    })
</script>
@endsection
