<h4>Firma Digital</h4>
<div style="margin-top: 50px;">
	<div class="firmas" >

		<img src="{{url('/upload/users/'.$user->firma)}}" style="width:200px"><br>

		________________________<br>
		<b>{{$user->first_name}} {{$user->last_name}}</b><br>
		{{$user->cargo->cargo}}<br>
	</div>
</div>
