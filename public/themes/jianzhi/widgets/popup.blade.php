
<script>
    var error = 0;
    var success = 0;
    var message = '';
    @if(Session::has('error'))
         error = 1;
        var message = '{{ Session::get('error') }}';
    @endif

    @if(Session::has('message'))
        success = 1;
        var message = '{{ Session::get('message') }}';
    @endif

</script>
{!! Theme::asset()->container('custom-css')->usepath()->add('layer-style', 'style/layer.css') !!}
{!! Theme::asset()->container('custom-js')->usepath()->add('layer-js', 'js/layer.js') !!}