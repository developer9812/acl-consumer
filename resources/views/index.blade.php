<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resource Server</title>
    <link rel="stylesheet" href="/css/main.css">
  </head>
  <body>
    <div id="app">
      <router-view></router-view>
    </div>
    <script type="text/javascript" src="{{ URL::asset('js/index.js') }}"></script>
  </body>
</html>
