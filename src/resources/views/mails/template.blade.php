<div 
	style="
		background:white;
		width:100%;
		color:black;
		min-height:400px;
		max-width:450px;
		margin:auto;
		text-align:center;
		font-family: Arial, Helvetica, sans-serif;
		padding:35px 0px 35px 0px;
		box-shadow: 9px 8px 4px -2px #c1c1c1;
		border:3px solid #00993c;
	"
>
	<img 
		src="{{ env('APP_URL').'/'.env('LOGO_APP') }}"
		alt="{{ env('GET_NAME_APP') }}"
		width="200"
	>
	<h3 style="padding:15px 0px 0px 0px;">
		<small>Hola!</small> <small style="font-weight: 100; color: #00993c;">{!!$user->fullName!!}</small>
		<br>
		{!!$data->title!!}
	</h3>
	<br>
	<p style="text-align:left; padding: 15px 15px; font-size:13px; margin: auto;">
		{!!$data->body!!}
	</p>
	@if(isset($data->link)&&isset($data->textBtn))
	<p style="text-align:center;">
		<a
			href="{{$data->link}}"
			style="
				font-size:16px;
				text-decoration:none;
				color:#00993c;
				font-weight:bold;
				padding:14px 32px;
				border:3px solid #00993c;
				border-radius:5px;
			" 
			target="_blank"
		>
			{{$data->textBtn}}
		</a>
	</p>
	@endif
	@isset ($data->order)
		@include('TecnolawAuth::mails.list-cart', ['data' => $data])
	@endisset
	<br>
	<br>
	<br>
	<br>
	<p style="width:80%; margin:auto; color:#9c9aa0; font-size: 12px;">
		© {{env('GET_NAME_APP')}}
		<br>
		Todos los derecho reservados 2021
		<br>
		Powered by 
		<a style="color: #dc8a1b;" href="https://devmarcoestrada.com" target="_blank">Tecnolaw</a>
		<br>
	</p>
</div>