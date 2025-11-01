@extends('agent.layout')

@section('content')
  <div class="text-left">
    <h2 class="modal-title">Property Details</h2>
  </div>
  <div class="property-box" id="load_wings">

  </div>
  <div  id="load_property_details" class="mt-4">
  </div>
@endsection

@section('script')
  <script>

    var property_id = "{{$id}}";
    setTimeout(function(){
      loadWings()
    },100)
    function loadWings()
    {
      $.get("{{url('agent/property-inventory/wings')}}/"+property_id, function(res){
        $('#load_wings').html(res.view);
      });
    }
  </script>
@endsection

