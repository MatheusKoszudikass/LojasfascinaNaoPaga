<? 
  include("lib/verificaSessao.php");
  include_once 'vendor/autoload.php';

  $codigoDeBarra = new leitorClasses\CodigoDeBarra();
  $totalDoDia = $codigoDeBarra->TotalDoDia();
?>
<html>

<head>
  <title>Leitura</title>
  <? include("template/head.php") ?>
  <link href="css/leitura.css" rel="stylesheet">

  <script>
    $(document).ready(function() {
      $("#cod_barra").focus()
    });

    // READY, INSERTING
    var state = 'READY'

    function cadastraCodBarras() {

      // evita duplicidade enquanto está enviando.
      if(state != 'READY') {
        console.log('Abortando cadastro pra evitar duplicidade. cadastraCodBarras()')
        return false
      }

      // evita código fora do formato válido
      var codigo = $('#cod_barra').val()
      if(!codigoValido(codigo)){
        alert('Código inválido.')
        return false
      }

      // trata código flex
      if(ehTipoCodigoFlex(codigo)){
        codigo = corrigeCaracteresEstranhos(codigo)
        flexObj = JSON.parse(codigo)
        codigo = flexObj.id
      }

      state = 'INSERTING'
      data = {
        "codigo": codigo
      }

      $.ajax({
          type: 'POST',
          url: 'api/leitura.php',
          data: JSON.stringify(data),
          contentType: "application/json",
          dataType: 'json'
        })
        .done(function(data) {
          $("#total").text(data.totalDoDia);
          sucesso()
          state = 'READY'
        })
        .fail(function(req) {
          if(req.status == 401){
            sessaoExpirou()
            return
          }

          $("#erro").text(req.responseText);
          falha()
          state = 'READY'
        })
    }

    function codigoValido(codigo) {
      var formatoMercadoPago = /^[0-9]{11}$/
      var formatoCorreios = /^[A-Z]{2}[0-9]{9}[A-Z]{2}$/
      
      mercadoPagoValido = codigo.match(formatoMercadoPago)
      correiosValido = codigo.match(formatoCorreios)
      flexValido = ehTipoCodigoFlex(codigo)

      return mercadoPagoValido || correiosValido || flexValido
    }

    function ehTipoCodigoFlex(codigo) {
      try {
        codigo = corrigeCaracteresEstranhos(codigo)
        obj = JSON.parse(codigo)
        return obj.id ? true : false
      }
      catch(e){
        return false
      }
    }

    function corrigeCaracteresEstranhos(codigo){
      var caracteresEstranhosAspasDuplas = ['^','Â','Ê','Î','Ô','Û','â','ê','î','ô','û']
      caracteresEstranhosAspasDuplas.forEach((caracterEstranho)=>{
        codigo = codigo.replaceAll(caracterEstranho, '"')
      })
      codigo = codigo.replaceAll('{', '}')
      codigo = codigo.replaceAll('`', '{')
      codigo = codigo.replaceAll('Ç', ':')
      return codigo
    }

    function sucesso() {
      $('#success_toast').toast('show');
      playSound('success_sound')
      $('#cod_barra').val('')
    }

    function sessaoExpirou() {
      alert('Sua sessão expirou. Por favor faça o login novamente.')
      window.location = 'index.php'
    }

    function falha() {
      $('#modal').modal('show')
      playSound('error_sound')
      $('#cod_barra').val('')
    }

    function playSound(soundObj) {
      var sound = document.getElementById(soundObj);
      sound.play();
    }
  </script>
</head>

<body>
  <? include("template/menu.php") ?>

  <div style="position: absolute; top: 20; right: 20;">
    <div class="toast ml-auto" role="alert" data-delay="1500" data-autohide="true" id="success_toast">
        <div class="toast-header">
            <strong class="mr-auto text-success">Sucesso</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="toast-body"> Cadastro efetuado com sucesso. </div>
    </div>
  </div>

  <form class="col-lg-6 offset-lg-3" onsubmit="cadastraCodBarras();return false">
    <div class="vertical-center">
      <div class="row justify-content-center" style="padding-top: 100px">
        <div class="total"> Total: <span id="total"><?=$totalDoDia?></span></div>
        <!-- atenção. O leitor de código de barras "digita" um ENTER no final do código 
        e por conta disso, dispara o SUBMIT do form. -->
        <input type="text" placeholder="Código de barras" id="cod_barra">
      </div>
    </div>
  </form>

  <div style="display:none">
    <audio id='success_sound' src="sound/success.wav" type="audio/wav" preload="auto" autobuffer controls></audio>
    <audio id='error_sound' src="sound/error.wav" type="audio/wav" preload="auto" autobuffer controls></audio>
  </div>

  <div class="modal" id="modal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Erro</h5>
        </div>
        <div class="modal-body">
          <p>Ocorreu um erro ao cadastrar: <span id="erro"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#cod_barra').focus()">Entendi</button>
        </div>
      </div>
    </div>
  </div>

</body>

</html>