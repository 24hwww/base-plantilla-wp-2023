<?php global $lctheme; $distros_lima = rt_ubigeo_get_distrito_by_idProv(127);

$get_buscar = get_query_var( 'buscar', '' );
$buscar = str_replace('+', ' ', $get_buscar);
$buscar = rawurldecode($buscar);
#$buscar = preg_replace('+', '/\s+/', $buscar);
$get_distrito = get_query_var( 'distrito', '' );
$distrito = str_replace('-', ' ', $get_distrito);

if($lctheme->is_request_ajax()){
$form = isset($_POST['form']) ? esc_attr($_POST['form']) : '';
?>

<div class="modal-dialog" role="document">
<form class="modal-content busqueda-form" method="POST">
<div class="modal-header">
<h4 class="modal-title"><strong>Subir busqueda <?php echo $page; ?></strong></h4>
</div>
<div class="modal-body">
<div class="row">

  <div class="col-sm-12 col-xs-12">
    <div class="form-group">
      <label class="require">Su Correo Eléctronico</label>
      <input type="email" class="form-control" autocomplete="off" name="emailAddress" required />
    </div>
  </div>

<div class="col-sm-12 col-xs-12">
  <div class="form-group">
    <label class="require">Nombre / Razón Social</label>
    <input type="text" class="form-control" autocomplete="off" name="entry.67260255" required />
  </div>
</div>

<div class="col-sm-12 col-xs-12">
  <div class="form-group">
    <label class="require">Distrito</label>
    <span class="bg-select-distritos"><select name="entry.610786875" title="distrito" class="form-control select-distritos" required><option value="">Distrito</option><?php if(function_exists('rt_ubigeo_get_distrito_by_idProv')): ?><?php foreach($distros_lima as $kd => $vd):$option_name = mb_convert_case($vd['distrito'], MB_CASE_TITLE, "UTF-8"); $option_value = sanitize_title($option_name); ?><option value="<?php echo $option_value; ?>" <?php echo  selected($option_value,$get_distrito); ?>><?php echo $option_name; ?></option><?php endforeach; ?><?php endif; ?></select></span>
  </div>
</div>

<div class="col-sm-12 col-xs-12">
  <div class="form-group">
    <label class="require">Descripción/Contactos</label>
    <textarea name="entry.1449886081" rows="2" autocomplete="off" class="form-control" required></textarea>
  </div>
</div>

<div class="col-sm-12 col-xs-12">
  <div class="form-group">
    <label class="require">Como calificas este lugar?</label>
    <ul class="estrellas-rate">
      <?php for($i=1;$i <= 5;$i++): ?>
        <li>
          <input id="star-<?php echo $i; ?>" type="radio" value="<?php echo $i; ?>" name="entry.862653896" />
          <label for="star-<?php echo $i; ?>"></label>
        </li>
      <?php endfor; ?>
    </ul>
  </div>
</div>

<div class="col-sm-12 col-xs-12">
  <div class="form-group">
    <label class="require">Buscar dirección</label>
    <select class="form-control search-address" autocomplete="off" name="search-address"><option value=""></option></select>
    <small class="desc">Escriba su dirección para obtener sus coordenadas.</small>
  </div>
</div>

<div class="col-sm-12 col-xs-12">
  <div class="form-group">
    <label>Coordenadas (Opcional)</label>
    <div class="row">
      <div class="col-md-6 col-sm-6">
        <input type="text" name="entry.442274695" autocomplete="off" class="form-control input-sm" placeholder="Latitud" />
      </div>
      <div class="col-md-6 col-sm-6">
        <input type="text" name="entry.608066928" autocomplete="off" class="form-control input-sm" placeholder="longitud" />
      </div>
    </div>
  </div>
</div>

<div class="hidden">

</div>

</div>
</div>
<div class="modal-footer">
<div class="vertical-align">
  <button type="submit" class="btn btn-primary">Enviar <ion-icon name="add-outline"></ion-icon></button>
</div>
</div>
</form>
</div>

<?php }else{ ?>
<?php get_header(); ?>

<main class="main-busqueda row-offcanvas row-offcanvas-right height-100">
  <aside id="sidebar" class="sidebar-aside col-lg-7 col-md-7 col-sm-12 col-xs-12 height-100 sidebar-offcanvas cero-padding">
    <div id="map" style="width:100%; height: 100%;"></div>
  </aside>
  <section class="main-section col-lg-5 col-md-5 col-sm-12 col-xs-12 height-100 cero-padding">
    <div class="col-md-12 col-sm-12 col-xs-12 padding-block">

      <div class="block-busqueda">
        <form id="lcbusqueda" method="post">
          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <input type="search" value="<?php echo $buscar; ?>" title="busqueda" name="buscar" class="form-control input-search" placeholder="Estoy buscando..." />
          </div>
          <div class="col-md-8 col-sm-12 col-xs-12 form-group">
            <span class="bg-select-distritos"><select name="distrito" title="distrito" class="form-control select-distritos"><option value="">Distrito</option><?php if(function_exists('rt_ubigeo_get_distrito_by_idProv')): ?><?php foreach($distros_lima as $kd => $vd):
              $option_name = mb_convert_case($vd['distrito'], MB_CASE_TITLE, "UTF-8");
              $option_value = sanitize_title($option_name); ?><option value="<?php echo $option_value; ?>" <?php echo  selected($option_value,$get_distrito); ?>><?php echo $option_name; ?></option><?php endforeach; ?><?php endif; ?></select></span>
          </div>
          <div class="col-md-4 col-sm-12 col-xs-12 form-group">
            <button id="demo" type="submit" class="btn btn-primary btn-block btn-lc"><ion-icon name="search-outline" data-loading-text="Loading..." autocomplete="off"></ion-icon><span>Buscar</span></button>
          </div>
        </form>
      </div>

      <div id="lc_resultados" class="block-busqueda block-busqueda-resultados">
        <div class="lc_resultados-404 col-md-12 col-sm-12 col-xs-12">
          <h3 class="lc_resultados-404-title">Aun no tenemos resultados para: <b><?php echo $buscar; ?></b> en <b><?php echo $distrito; ?></b></h3>
          <p class="lc_resultados-404-desc">Ayúdanos a tener resultados.</p> <a href="#subir-busqueda" id="lc_resultados_subir_busqueda" class="btn btn-primary btn-subir-busqueda-404">Subir busqueda</a>
        </div>
      </div>

    </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>

<?php } ?>
