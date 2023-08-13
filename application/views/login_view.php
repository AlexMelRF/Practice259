<form method="POST">
  <div class="mb-3">
    <label class="form-label">Login</label>
    <input name="login" type="login" class="form-control" id="exampleInputLogin"  required>
    <div id="LoginHelp" class="form-text">We'll never share your email with anyone else.</div>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input name="password" type="password" class="form-control" id="exampleInputPassword1" required>
  </div>
  <input name="submit" type="submit" value="Submit" class="btn btn-primary">
</form>
<a class="nav-link" href="index.php?url=register">Sign up</a>
<a class="nav-link" href="index.php?url=gallery">Go to the gallery</a>

<?php
////////////////////////////////////////////////////////////////////////
echo $_SESSION['auth'] ? "auth" : "no auth";
?>