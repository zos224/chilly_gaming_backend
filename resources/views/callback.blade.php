<html>
<head>
  <meta charset="utf-8">
  <title>ALoooo</title>
  <script>
    window.opener.postMessage({ token: "{{ $token }}", user: "{{ $user }}"}, "http://127.0.0.1:5173/")
    window.close()
  </script>
</head>
<body>
</body>
</html>