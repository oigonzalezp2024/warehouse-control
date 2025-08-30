function bodegaAgregarMaterial(proveedor_id, servicio_id, producto_id, cantidad, fecha_creacion, fecha_modificacion, usuario_id){
    cadena = "proveedor_id=" + proveedor_id +
    "&servicio_id=" + servicio_id +
    "&producto_id=" + producto_id +
    "&cantidad=" + cantidad +
    "&fecha_creacion=" + fecha_creacion +
    "&fecha_modificacion=" + fecha_modificacion +
    "&usuario_id=" + usuario_id;

    accion = "insertar";
    mensaje_si = "Se agregó material a bodega.";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function agregaform(datos) {
    d = datos.split('||');
    $('#id_itemu').val(d[0]);
    $('#proveedor_idu').val(d[1]);
    $('#servicio_idu').val(d[2]);
    $('#producto_idu').val(d[3]);
    $('#cantidadu').val(d[4]);
    $('#fecha_creacionu').val(d[5]);
    $('#fecha_modificacionu').val(d[6]);
    $('#usuario_idu').val(d[7]);
}


function agregaformAdicion(datos) {
    d = datos.split('||');
    $('#id_itema').val(d[0]);
    $('#proveedor_ida').val(d[1]);
    $('#servicio_ida').val(d[2]);
    $('#producto_ida').val(d[3]);
    $('#cantidada').val(d[4]);
    $('#fecha_creaciona').val(d[5]);
    $('#fecha_modificaciona').val(d[6]);
    $('#usuario_ida').val(d[7]);
}

function agregaformSustraccion(datos) {
    d = datos.split('||');
    $('#id_itemr').val(d[0]);
    $('#proveedor_idr').val(d[1]);
    $('#servicio_idr').val(d[2]);
    $('#producto_idr').val(d[3]);
    $('#cantidadr').val(d[4]);
    $('#fecha_creacionr').val(d[5]);
    $('#fecha_modificacionr').val(d[6]);
    $('#usuario_idr').val(d[7]);
}

function bodegaModificarMaterial(){
    id_item = $('#id_itemu').val();
    proveedor_id = $('#proveedor_idu').val();
    servicio_id = $('#servicio_idu').val();
    producto_id = $('#producto_idu').val();
    cantidad = $('#cantidadu').val();
    fecha_creacion = $('#fecha_creacionu').val();
    fecha_modificacion = $('#fecha_modificacionu').val();
    usuario_id = $('#usuario_idu').val();
    cadena = "id_item=" + id_item +
    "&proveedor_id=" + proveedor_id +
    "&servicio_id=" + servicio_id +
    "&producto_id=" + producto_id +
    "&cantidad=" + cantidad +
    "&fecha_creacion=" + fecha_creacion +
    "&fecha_modificacion=" + fecha_modificacion +
    "&usuario_id=" + usuario_id;

    accion = "modificar";
    mensaje_si = "Se modificó material a bodega.";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function bodegaAdicionarMaterial(){
    id_item = $('#id_itema').val();
    proveedor_id = $('#proveedor_ida').val();
    servicio_id = $('#servicio_ida').val();
    producto_id = $('#producto_ida').val();
    cantidad = $('#cantidada').val();
    fecha_creacion = $('#fecha_creaciona').val();
    fecha_modificacion = $('#fecha_modificaciona').val();
    usuario_id = $('#usuario_ida').val();
    cadena = "id_item=" + id_item +
    "&proveedor_id=" + proveedor_id +
    "&servicio_id=" + servicio_id +
    "&producto_id=" + producto_id +
    "&cantidad=" + cantidad +
    "&fecha_creacion=" + fecha_creacion +
    "&fecha_modificacion=" + fecha_modificacion +
    "&usuario_id=" + usuario_id;

    accion = "adicionar";
    mensaje_si = "Se modificó material a bodega.";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function bodegaSustraer(){
    id_item = $('#id_itemr').val();
    proveedor_id = $('#proveedor_idr').val();
    servicio_id = $('#servicio_idr').val();
    producto_id = $('#producto_idr').val();
    cantidad = $('#cantidadr').val();
    fecha_creacion = $('#fecha_creacionr').val();
    fecha_modificacion = $('#fecha_modificacionr').val();
    usuario_id = $('#usuario_ida').val();
    cadena = "id_item=" + id_item +
    "&proveedor_id=" + proveedor_id +
    "&servicio_id=" + servicio_id +
    "&producto_id=" + producto_id +
    "&cantidad=" + cantidad +
    "&fecha_creacion=" + fecha_creacion +
    "&fecha_modificacion=" + fecha_modificacion +
    "&usuario_id=" + usuario_id;

    accion = "sustraer";
    mensaje_si = "Se modificó material a bodega aglutinado.";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_item) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_item);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_item) {
    cadena = "id_item=" + id_item;

    accion = "borrar";
    mensaje_si = "Material eliminado en bodega.";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/bodega_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r > 0) {
            $('#tabla').load('../vista/componentes/vista_bodega.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
