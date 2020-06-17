<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Page Analizator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="css/album.css" rel="stylesheet">
  </head>
  <body class="d-flex flex-column h-100">
    <header>
      @include("layouts.header")
    </header>
    <main role="main" class="flex-shrink-0">
      <div class="container">
        @yield("content")
      </div>
    </main>
    @include("layouts.footer")
  </body>
</html>
