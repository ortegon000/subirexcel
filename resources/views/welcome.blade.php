<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">

                @if ($queueRunning)
                    <div class="alert alert-info">
                        <p>Actualmente se estan procesando datos</p>
                    </div>
                @else
                    @if (session('response'))
                        <div class="alert alert-{{session('response')['type']}}">
                            {{ session('response')['message'] }}
                        </div>
                    @else
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('upload_excel') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="file">Selecciona el excel para subir</label> <br>
                                <input name="file" type="file" class="form-control-file" id="file" required> <br>
                                <button type="submit" class="btn btn-success">Subir archivo</button>
                            </div>
                        </form>
                    @endif

                    <div>
                        <a href="{{ route('download_excel.template') }}" class="btn btn-primary btn-sm mt-5">Descargar plantilla de excel</a>
                        <a href="{{ route('delete_duplicates') }}" class="btn btn-danger btn-sm mt-5">Borrar duplicados</a>
                    </div>
                @endif


            </div>
        </div>
    </body>
</html>
