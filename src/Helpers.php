<?php

function validaRut($rut){
	return true;
}

function hashPass($value='')
{
    return password_hash(
        $value, PASSWORD_BCRYPT, [
            'cost' => 10
        ]
    );
}
function sendMail($data,$offTemplate=false)
{
    try {
        Illuminate\Support\Facades\Mail::to($data['user']->email)->send(
            new \Tecnolaw\Authorization\Mail\MailShop(
                $data,
                $offTemplate
            )
        );
    } catch (Exception $e) {
        var_dump($e);
    }

}
function sampleMail()
{
    return (object)[
        'title' => 'Tu pedido fue relizado exitosamente!',
        'subject'=>'Love & Taste pedido exitoso!',
        'body' => 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen.',
        'link' => env('APP_URL_SITE'),
        'textBtn' => 'ir al sitio',
        'order' => (object)[
            'code_order'=>'SIN_CODIGO',
            'phone'=>'9 999999',
            'shipping_address'=>'Es una direccion de ejemplo',
            'payment_method'=>'MERCADO PAGO',
            'items'=> [
                (object)[
                    'name'=>'Producto 1',
                    'quantity'=>'5',
                    'price'=>'13000',
                    'img_url'=>env('APP_URL_SITE').'/app/CAMOTES/Acelga.png'
                ],
            ],
            'total'=>0,
            'tax'=>0,
            'shipping'=>0,
            'amount_payable'=>0,
        ],
    ];
}
