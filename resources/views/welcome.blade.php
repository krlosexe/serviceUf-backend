<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Signin Template · Bootstrap v5.1</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">

    

    <!-- Bootstrap core CSS -->
<link href="/assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }


      html,
body {
  height: 100%;
}

body {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}

.form-signin .checkbox {
  font-weight: 400;
}

.form-signin .form-floating:focus-within {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}



    </style>

    
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
    
    <main class="form-signin">
    <form id="store" method="POST">
       
        <img class="mb-4" src="/img/logo.png" alt="" width="72" height="57">
        <h3 class="h7 mb-3 fw-normal">Hola {{$name}}, vamos a cambiar tu contraseña</h3>


        <div class="alert" style="display:none" role="alert" id="alert"></div>


        <div class="form-floating">
        <input type="password" name="password" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Nueva Contraseña</label>
        </div>
        <div class="form-floating">
        <input type="password" name="repeat_password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Repetir Contraseña</label>
        </div>  

        <input type="hidden" class="form-control" name="id_client" value={{$id_client}} placeholder="name@example.com">

    
        <button class="w-100 btn btn-lg btn-primary" type="submit">Cambiar</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
    </form>
    </main>
    <input type="hidden" id="route" value="<?= url('/api') ?>">

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    

    <script>
        $(document).ready(function() {
            Store();
        });

        const Store = () => {
            SendForm("#store", 'change/password');
        }

         const SendForm = (form, route) => {
            $(form).submit(function(e) {
                e.preventDefault(); 

                var url      = document.getElementById('route').value;
                var formData = new FormData($(form)[0]); 
                var method   = $(this).attr('method');

                $('#submit').attr('disabled', 'disabled'); 

                $.ajax({
                    url: `${url}/${route}`,
                    type: method,
                    dataType: 'JSON',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        displayError("primary", "Please Wait...")
                    },
                    error: function(error) {
                        $('#submit').removeAttr('disabled');
                        displayError("danger", "Las contraseñas no coinciden")
                                
                    },
                    success: function(response) {
                        $('#submit').removeAttr('disabled');
                        $(form)[0].reset()
                        displayError("success", response)
                    }
                    });

                });
            }

            const displayError = (type, message)=>{
                $(".alert")
                    .css("display", "block")
                    .text(message)
                    .removeClass("alert-success")
                    .removeClass("alert-danger")
                    .removeClass("alert-primary")
                    .addClass(`alert-${type}`)
            }



    </script>

  </body>
</html>
