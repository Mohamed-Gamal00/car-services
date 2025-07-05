@if(session()->has($type))
    <div class="new-alert text-center alert-{{$type}} alert_class">
        {{ session($type) }}
    </div>
@endif


<script>
    setTimeout(function () {
        $('.alert_class').fadeOut('slow');
    }, 5000);
</script>

<style>
    .new-alert {
        color: white;
        padding: 16px;
        font-size: 19px;
        background-color: #6cc942;
    }
</style>
