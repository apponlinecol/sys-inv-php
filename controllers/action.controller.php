<?php

class ControllerAction
{
    static public function customer_data()
    {
        if( isset( $_POST['Primer_Nombre'] ) ){
            switch ( $_POST['id'] ){
                case 0: $response = ControllerGeneral::ctrInsertRow('customer', header_active('customer'), insert_row('customer')); $success='creado'; break;
                default: $response = ControllerGeneral::ctrUpdateRow('customer', header_active('customer'), update_row('customer')); $success='actualizado';
                    if( isset( $_POST['dto'] ) ){ ControllerGeneral::ctrUpdateField('customer','dto', ['id'=>$_POST['id'], 'set'=> $_POST['dto'] ]); }
                    break;
            }
            switch ( $response ){
                case 'ok': success('Cliente '.$success.' con éxito'); break;
                case 'repeated': repeated('Algún dato del cliente que quiere crear ya está guardado en el sistema, verifique los datos antes de intentar crearlo de nuevo, si vuelve a salir este mensaje es porque el cliente ya existe y no necesita crearlo. '); break;
                default: error('Intente de nuevo o comuníquese con el administrador ('.$response.')'); break;
            }
        }
    }

    static public function new_reference(){
        if ( isset( $_POST['product'] ) ){
            switch ( $_POST['id_ref'] ){
                case 0: $response = ControllerGeneral::ctrInsertRow('`references`', header_active('`references`'), insert_row('`references`')); $success = 'creado'; break;
                default: $response = ControllerGeneral::ctrUpdateRow('`references`', header_active('`references`'), update_row('`references`', $_POST['id_ref'] )); $success = 'actualizado'; break;
            }
            switch ( $response ){
                case 'ok': success('Producto '.$success.' con éxito'); break;
                case 'repeated': repeated('Algún dato del producto que quiere crear ya está guardado en el sistema, verifique los datos antes de intentar crearlo de nuevo, si vuelve a salir este mensaje es porque el producto ya existe y no necesita crearlo. '); break;
                default: error('Intente de nuevo o comuníquese con el administrador'); break;
            }
        }
    }

    static public function save_sale(){
        if( isset( $_POST['orders'] ) ){

            switch ( $_POST['typ'] ){ case 1: $msg = 'actualizado'; break; case 2: $msg = 'entregado'; break; }
            $data = insert_row('sale'); $invoice = $data['sale'];
            switch ( $_POST['id'] ){
                case 0: $typ = 1; $response = ControllerGeneral::ctrInsertRow('sale', header_active('sale'), $data ); $success='creado'; break;
                default: $invoice = $_POST['id'];
                    $sale = ControllerGeneral::ctrRecord('single','sale','where id='.$_POST['id'] );
                    $pay = [ [ 'date'=> current_date('date_time'),'cash'=>$sale['cash'], 'card'=>$sale['card'], 'other'=>$sale['other'] ] ] ;
                    if( $sale['payment_track'] != '' ){ $tracker = array_merge( $pay , json_decode($sale['payment_track'], true) ) ; }
                    else{ $tracker = $pay; }
                    ControllerGeneral::ctrUpdateField('sale','payment_track', [ 'id'=>$sale['id'], 'set'=> json_encode( $tracker ) ]);
                    ControllerGeneral::ctrUpdateRow('sale', header_active('sale'), update_row('sale', $_POST['id']));
                    $response = ControllerGeneral::ctrUpdateField( 'sale','status', [ 'id'=>$_POST['id'], 'set'=> $_POST['status'] ] );
                    $success = $msg; break;
            }

            switch ( $response ){
                case 'ok':
                    switch ( $_POST['typ'] ){
                        case 1: $msg = 'actualizado'; $typ = 1;
                            $orders = json_decode( $_POST['orders'] , true );
                            foreach ( $orders as $row ){
                                $inv = ControllerGeneral::ctrRecord('single','`references`','where id='.$row['idr'] );
                                $update = ControllerGeneral::ctrUpdateField( '`references`','available_sale', [ 'id'=>$inv['id'], 'set'=> $inv['available_sale'] - $row['cant'] ] );
                            }
                            break;
                        case 2: $msg = 'entregado'; $typ = 2;

                            $orders = json_decode( $_POST['orders'] , true );
                            foreach ( $orders as $row ){
                                $inv = ControllerGeneral::ctrRecord('single','`references`','where id='.$row['idr'] );
                                $update = ControllerGeneral::ctrUpdateField( '`references`','available_sale', [ 'id'=>$inv['id'], 'set'=> $inv['available_sale'] + $row['cant'] ] );
                            }
                            $delivered = json_decode( $_POST['delivered'] , true );
                            foreach ( $delivered as $row ){
                                $inv = ControllerGeneral::ctrRecord('single','`references`','where id='.$row['idr'] );
                                ControllerGeneral::ctrUpdateField( '`references`','available_sale', [ 'id'=>$inv['id'], 'set'=> $inv['available_sale'] - $row['cant'] ] );
                                $del = ControllerGeneral::ctrRecord('single','courier_inventory','where ref='.$row['ref'] );
                                ControllerGeneral::ctrUpdateField( 'courier_inventory','delivery', [ 'id'=>$del['id'], 'set'=> $del['delivery'] + $row['cant'] ] );
                                $update = ControllerGeneral::ctrUpdateField( 'courier_inventory','stock', [ 'id'=>$del['id'], 'set'=> $del['stock'] - $row['cant'] ] );
                            }
                            $sale = ControllerGeneral::ctrRecord('single','sale','where id='.$_POST['id'] );
                            $storage = ControllerGeneral::ctrRecord('single','storage','where status=2 and sales='.$sale['sale'] );
                            ControllerGeneral::ctrUpdateField( 'storage','status', [ 'id'=>$storage['id'], 'set'=> 3 ] );
                            break; }
                    break;
                default: error('Se creo la venta, pero el inventario no se actualizo de forma correcta, indíquele a su jefe dicho error para que lo ajuste deforma manual y así evitar problemas con los clientes por incumplimiento.'); break;
            }

            switch ( $update ){
                case 'ok': print_pos( 'commercialization/print/'.$typ.'/'.$invoice ); break;
                default: error('Intente de nuevo o comuníquese con el administrador'); break;
            }
        }
    }

    static public function save_payment(){
        if( isset( $_POST['sale'] ) ){
            $new_pay = ControllerGeneral::ctrInsertRow('payment', header_active('payment'), insert_row('payment') );
            $payment = ControllerGeneral::ctrRecord('single','payment','order by id desc limit 1'); $idr = $payment['id'];
            switch ( $new_pay ){
                case 'ok':
                    $pay = $_POST['cash'] + $_POST['card'] + $_POST['other'];
                    $sale = ControllerGeneral::ctrRecord('single','sale','where id='.$_POST['idr'] );
                    ControllerGeneral::ctrUpdateField('sale','balance', [ 'id'=>$sale['id'], 'set'=>$sale['balance'] - $pay ] );
                    $response = ControllerGeneral::ctrUpdateField('sale','pay', [ 'id'=>$sale['id'], 'set'=>$sale['pay'] + $pay ] );

            }

            switch ( $response ){
                case 'ok': print_pos( 'commercialization/print/3/'.$idr ); break;
                default: error('Intente de nuevo o comuníquese con el administrador'); break;
            }
        }
    }

    static public function save_storage(){
        if( isset( $_POST['receives'] ) ){
            $insert = ControllerGeneral::ctrInsertRow('storage',header_active('storage'),insert_row('storage') );
            switch ( $insert ){
                case 'ok':
                    $sale = ControllerGeneral::ctrRecord('single','sale','where sale="'.$_POST['sales'].'"');
                    ControllerGeneral::ctrUpdateField('sale','status', ['id'=> $sale['id'], 'set'=>2 ]);

                    $products = json_decode( $_POST['products'] , true );
                    foreach ( $products as $row ){
                        $inv = ControllerGeneral::ctrRecord('single','`references`','where id='.$row['idr'] );
                        $response = ControllerGeneral::ctrUpdateField( '`references`','available_packing', [ 'id'=>$inv['id'], 'set'=> $inv['available_packing'] - $row['cant'] ] );
                    }
                default: error('El pedido no fue asignado al repartidor, intente de nuevo o comuníquese con el administrador ('.$response.')'); break;
            }
            switch ( $response ){
                case 'ok': success('Pedido asignado al repartidor con éxito'); break;
                default: error('El pedido se asignó, pero no se ajustó el inventario, comuníquese con el administrador ('.$response.')'); break;
            }
        }
    }

    static public function accept(){
        if( isset( $_POST['accept'] ) ){

            switch ( $_POST['accept'] ){
                case 4:
                    $courier_inventory = ControllerGeneral::ctrRecord('all','courier_inventory','where `return`>0 and courier_id='.$_POST['idr'] );
                    $data = [ 'courier_id' => $_POST['idr'], 'product' => json_encode($courier_inventory) ];
                    ControllerGeneral::ctrInsertRow('courier_return',header_active('courier_return'), $data );

                    foreach ( $courier_inventory as $row ){
                        $response = ControllerGeneral::ctrUpdateField('courier_inventory','stock', ['id'=>$row['id'], 'set'=> $row['stock'] - $row['return'] ] );
                        $ref = ControllerGeneral::ctrRecord('single','`references`','where ref="'.$row['ref'].'"');
                        ControllerGeneral::ctrUpdateField('`references`','available_packing', ['id'=>$ref['id'], 'set'=> $ref['available_packing'] + $row['return'] ] );
                        $response = ControllerGeneral::ctrUpdateField('`references`','available_sale', ['id'=>$ref['id'], 'set'=> $ref['available_sale'] + $row['return'] ] );
                    }

                    $courier_inv = ControllerGeneral::ctrRecord('all','courier_inventory','where stock=0 and courier_id='.$_POST['idr'] );
                    foreach ( $courier_inv as $row ){ $response = ControllerGeneral::ctrRemoveRow( 'courier_inventory',$row['id'] ); } break;
                case 5: $storage = ControllerGeneral::ctrRecord('single','storage','where id='.$_POST['idr'] );
                    $products = json_decode( $storage['products'] , true );
                    foreach ( $products as $row ){
                        $ref = ControllerGeneral::ctrRecord('single','courier_inventory','where courier_id='.$_POST['receives_id'].' and ref="'.$row['ref'].'"' );
                        if( !empty($ref) ){
                            ControllerGeneral::ctrUpdateField( 'courier_inventory','`load`', [ 'id'=>$ref['id'], 'set'=> $ref['load'] + $row['cant'] ] );
                            $update = ControllerGeneral::ctrUpdateField( 'courier_inventory','stock', [ 'id'=>$ref['id'], 'set'=> $ref['stock'] + $row['cant'] ] );
                        } else {
                            $items = [
                                'courier_id' => $_POST['receives_id'],
                                'ref' => $row['ref'],
                                'product' => $row['product'],
                                'load' => $row['cant'],
                                'delivery' => 0,
                                'return' => 0,
                                'stock' => $row['cant']
                            ];
                            $update = ControllerGeneral::ctrInsertRow('courier_inventory', header_active('courier_inventory'), $items);
                        }
                    }
                    switch ( $update ){
                        case 'ok':
                            $sale = ControllerGeneral::ctrRecord('single','sale','where sale='.$storage['sales']);
                            ControllerGeneral::ctrUpdateField('sale','courier_id', ['id'=>$sale['id'], 'set'=>$_POST['receives_id'] ]);
                            ControllerGeneral::ctrUpdateField('storage','receives_id', ['id'=>$_POST['idr'], 'set'=>$_POST['receives_id'] ]);
                            $response = ControllerGeneral::ctrUpdateField('storage','status', ['id'=>$_POST['idr'], 'set'=>2 ]);  break;
                        default: error('No se asignaron de forma correcta los productos de esta entrega, intente de nuevo o comuníquese con el administrador. ('.$update.')'); break;
                    }
                    break;
            }
            switch ( $response ){
                case 'ok': success('Aceptación realizada con éxito'); break;
                default: error('Se actualizaron los inventarios pero no se asigno la entrega de forma correcta, comuníquese con el administrador para ajustarlo. ('.$update.')'); break;
            }
        }
    }

    static public function cancel(){
        if( isset( $_POST['cancel'] ) ){

            switch ( $_POST['cancel'] ){
                case 4: $storage = ControllerGeneral::ctrRecord('single','storage','where id='.$_POST['idr'] );
                    $products = json_decode( $storage['products'] , true );
                    foreach ( $products as $row ){
                        $inv = ControllerGeneral::ctrRecord('single','`references`','where id='.$row['idr'] );
                        $update = ControllerGeneral::ctrUpdateField( '`references`','available_packing', [ 'id'=>$inv['id'], 'set'=> $inv['available_packing'] + $row['cant'] ] );
                    }
                    switch ( $update ){
                        case 'ok':
                            $sale = ControllerGeneral::ctrRecord('single','sale','where sale="'.$storage['sales'].'"');
                            ControllerGeneral::ctrUpdateField('sale','status', ['id'=> $sale['id'], 'set'=>1 ]);
                            $response = ControllerGeneral::ctrUpdateField('storage','status', ['id'=>$_POST['idr'], 'set'=>0 ]);  break;
                        default: error('No se cancelo de forma correcta esta entrega, intente de nuevo o comuníquese con el administrador. ('.$update.')'); break;
                    }
                    break;

            }

            switch ( $response ){
                case 'ok': success('Cancelación realizada con éxito'); break;
                default: error('Se actualizaron los inventarios pero no se canceloo la entrega de forma correcta, comuníquese con el administrador para ajustarlo. ('.$update.')'); break;
            }
        }
    }

    static public function returnDelivery(){
        if( isset( $_POST['returnDelivery'] ) ){

            $ref = json_decode( $_POST['returnDelivery'], true );
            foreach ( $ref as $row ){
                $response = ControllerGeneral::ctrUpdateField('courier_inventory','`return`', ['id'=>$row['idr'], 'set'=>$row['can'] ] );
            }
            switch ( $response ){
                case 'ok': success('Se relaciono su devolución, ahora debe hacer la entrega física de los productos para que bodega de su aceptación y ya no sean su responsabilidad'); break;
                default: error('No se relacionó de forma correcta su devolución, intente de nuevo o comuníquese con el administrador. ('.$response.')'); break;
            }

        }
    }


}