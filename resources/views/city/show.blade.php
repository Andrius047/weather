 <head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ajax Example</title>
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
     function setSelected(str){
     $.ajaxSetup({
       headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
     });
        $.ajax({
           type:'POST',
           url:'/weather/' + str,
           data:{},
           dataType: 'json',
            encode  : true,
           success:function(response) {
                $("#msg").html(response);
      }
        });
     }
    </script>
</head>

{!! Form::open(['route' => 'weather.store']) !!}
    <div class="form-group">
        {!! Form::label('new email', 'email') !!}
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::submit('Submit', ['class' => 'btn btn-info']) !!}
{!! Form::close() !!}

<div class="form-group">
    <select class="form-control" name="city" onchange="setSelected(this.value)">
        @foreach($cities as $city)
            <option value="{{$city->public_id}}" @if($city->selected == 1) selected @endif>{{$city->city}}</option>
        @endforeach
    </select>
</div>
<div id="msg">
    <div>{{$city_info->main->temp}}<br>{{$city_info->wind->deg}}<br>{{$city_info->wind->speed}}</div>
</div>


