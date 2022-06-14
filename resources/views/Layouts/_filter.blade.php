<style>
    @media only screen and (max-width: 600px) {
                .input-search {
                    width:135px;
                }
            }
</style>
<form action="{{ route('filter') }}" method="GET">
    @csrf
<div class="col mb-5">
    <div class="card h-100 product-container">
            <div class="badge sale-badge position-absolute" style="top: 0.5rem; right: 0.5rem">Filter</div>
        <div class="card-body p-4">
            <div class="text-center">
                <label>Search</label>
                <input type="search" name="search" class="input-search">
                <hr>
                <div style="display: flex; flex-direction: column">
                    <label>Category</label>
                    @foreach($categories as $category)
                    <div>
                        <label class="custom-control custom-checkbox">
                            <div class="custom-control-label">{{ $category->name_en }}
                                <input type="checkbox" checked="" name="categories[]" value="{{ $category->id }}" class="custom-control-input">
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
                <hr>
                <label>Price Range</label>
                <div style="display:flex; justify-content: space-around">
                    from
                    <input type="number" name="price_min" step="1" min="0" placeholder="MIN" value="0" style="width:50px;" required>
                    to
                    <input type="number" name="price_max" step="1" min="1" placeholder="MAX" value="200" style="width:50px;" required>
                </div>
                <hr>
                <button class="btn view-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>
    </div>
</div>
</form>
