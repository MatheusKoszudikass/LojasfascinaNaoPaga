<!DOCTYPE html>

<head>
  <title>Login</title>
  <link href="css/index.css" rel="stylesheet">
  <? include("template/head.php") ?>

  <script>
    function validaLogin() {
      var email = $('#email').val()
      var senha = $('#senha').val()

      data = {
        "email": email,
        "senha": senha
      }

      $.ajax({
        type: 'POST',
        url: 'api/login.php',
        data: JSON.stringify(data),
        contentType: "application/json",
        dataType: 'json',
        success: function(data) {
          var jsonData = JSON.parse(data);

          if (jsonData == true) {
            location.href = 'leitura.php';
          } else {
            alert('Dados estão errados!');
          }

        },
        error: function(req) {
          console.error(req)
          console.log(data)
          alert("Ocorreu um erro: " + req.responseText);
        },
      })
    }
  </script>
</head>

<body>
  <div class="wrapper fadeInDown">
    <div id="formContent">
      <form class="login">
        <input type="text" id="email" class="fadeIn second" name="email" placeholder="Email">
        <i class="fa fa-envelope"></i>

        <input type="password" id="senha" class="fadeIn third" name="senha" placeholder="Senha">
        <i class="fa fa-key"></i>

        <input type="button" class="fadeIn fourth" value="Log In" onclick="validaLogin();">
      </form>
    </div>
  </div>
</body>