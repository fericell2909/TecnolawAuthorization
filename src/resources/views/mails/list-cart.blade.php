<?php
	$margen='padding: 0px 15px 0px 15px;'
?>
<table width="100%">
	<tbody>
		<tr>
			<td colspan="2" style="{{$margen}}">
				<hr style="height:1px;color:#d9d9d9;background-color:#d9d9d9;border:none;margin:30px 0px 0px 0px">
				<h3 style="color: #8300f9;">
					Detalles de orden
					<br/>
					{{$data->order->code_order}}
				</h3>

				<hr style="height:1px;color:#d9d9d9;background-color:#d9d9d9;border:none;">
			</td>
		</tr>
		<tr>
			<td style="{{$margen}}" width="50%">
				<strong>DIRECCIÓN DE ENVÍO</strong>
				<br>
				{{$data->order->shipping_address}}
			</td>
			<td style="{{$margen}}" width="50%">
				<strong>MÉTODO DE PAGO</strong>
				<br>
				{{$data->order->payment_method}}
			</td>
		</tr>
		<tr>
			<td colspan="2" style="{{$margen}}">
				<hr style="height:1px;color:#d9d9d9;background-color:#d9d9d9;border:none;">
			</td>
		</tr>
	</tbody>
</table>
<table width="100%">
	<tbody>
		@foreach ($data->order->items as $item)
			<tr>
				<td style="{{$margen}}text-align:left;" width="100">
					<img src="{{ env('APP_URL').'/'.$item->img_url }}" alt="{{env('GET_NAME_APP')}}" style="object-fit:cover;border: 1px solid #8300f9;" width="100px" height="100px">
				</td>
				<td style="{{$margen}}">
					<p style="text-align:left;">
						<strong style="font-size: 12px">{{$item->name}}</strong>
						<br>
						x<span>{{$item->quantity}}</span>
						<br>
						<h5 style="text-align: right;"><strong>{{$item->price}}</strong></h5>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="{{$margen}}">
					<hr style="height:1px;color:#d9d9d9;background-color:#d9d9d9;border:none;">
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
<table width="100%">
	<tbody>
		<tr>
			<td style="{{$margen}}text-align:left;" width="50%">
				<p>
					<span>TOTAL COMPRA</span>
					<br>
					{{-- <span>IIMPUESTTO (excl)</span> --}}
					<br>
					<span>COSTO DE ENVÍO</span>
				</p>
			</td>
			<td style="{{$margen}}" width="50%">
				<p style="text-align: right;">
					<span>{{$data->order->total}}</span>
					<br>
					<span>{{$data->order->tax}}</span>
					<br>
					<span>{{$data->order->shipping}}</span>
				</p>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="{{$margen}}">
				<hr style="height:1px;color:#d9d9d9;background-color:#d9d9d9;border:none;">
			</td>
		</tr>
		<tr>
			<td style="{{$margen}}text-align:left;" width="100">
				<p>
					<span>TOTAL</span>
				</p>
			</td>
			<td style="{{$margen}}">
				<p style="text-align: right;">
					<span>{{$data->order->amount_payable}}</span>
				</p>
			</td>
		<tr>
			<td colspan="2" style="{{$margen}}">
				<hr style="height:1px;color:#d9d9d9;background-color:#d9d9d9;border:none;">
			</td>
		</tr>
	</tbody>
</table>