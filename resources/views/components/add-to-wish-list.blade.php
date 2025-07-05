<form class="wishlistForm" action="{{ route('add.wishlist', $productId) }}" method="post">
    @csrf
    <input type="hidden" name="product_id" value="{{ $productId }}">
    {{-- <button class="like" data-product-id="{{ $productId }}"
            style="color: white; background:{{Auth::guard('web')->check() && Auth::guard('web')->user()->wishlistProducts()->where('product_id', $productId)->exists() ? '#F55157': '#ffd5d5'}}">
        <i class="fa fa-heart"></i>
    </button> --}}
    <button class="like" data-product-id="{{ $productId }}"
        style="color: white;    width: 40px;
    height: 40px;
    text-align: center;
    line-height: 40px;
    border-radius: 8px;
    margin-bottom: 6px;
    transition: all .6s ease-in-out;
    border: none;
     background:{{ Auth::guard('web')->check() && Auth::guard('web')->user()->wishlistProducts()->where('product_id', $productId)->exists() ? '#F55157' : '#999dff' }}">
        <i class="fa fa-heart"></i>
    </button>
</form>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Unbind any previously bound click events to prevent multiple bindings
        $(document).off('click', '.wishlistForm .like');

        // Bind the click event to the like buttons
        $(document).on('click', '.wishlistForm .like', function(e) {
            e.preventDefault(); // Prevent the default action of the button

            var productId = $(this).data('product-id');
            var form = $(this).closest('.wishlistForm'); // Find the closest form
            $.ajax({
                url: form.attr('action'), // Get the form action URL
                type: 'POST',
                data: form.serialize(), // Serialize form data
                success: function(response) {
                    if (response.success) {
                        // Update button color based on response
                        form.find('.like').css('background', response.background);
                        // Show success message (if any)
                    } else if (response.error) {
                        window.location.href = "{{ route('login') }}";
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
