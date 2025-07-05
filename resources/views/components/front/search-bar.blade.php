    <div class="searchFilter">
        <div class="search">
            <form action="{{ route('get.product.search') }}" method="post">
                <div>
                    @csrf
                    <input type="search" name="product_name" class="search_product" placeholder="{{__('general.SEARCH')}}">

                    <button><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    {{-- <div class="search">
        <form action="{{ route('get.product.search') }}" method="post">
            @csrf
            <input type="search" name="product_name" class="search_product" placeholder="البحث">
        </form>
    </div> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('front/js/jquery-search.js') }}"></script>

    <script>
        var availableTags = [];

        $.ajax({
            method: "GET",
            url: "/all-products",
            success: function(response) {
                startAutoComplete(response);
            }
        });

        function startAutoComplete(availableTags) {
            $(".search_product").autocomplete({
                source: availableTags
            });
        }
    </script>
