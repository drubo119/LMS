<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">📖 LMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="user_login.php">User Login</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_login.php">Admin Login</a></li>
        <li class="nav-item"><a class="nav-link active" href="signup.php">Signup</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Background & Signup Card -->
<div class="bg-dark text-light d-flex align-items-center justify-content-center" style="background: url('photos/cover.png') center center / cover no-repeat; height: 100vh;">
  <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px;">
    <h4 class="text-center mb-4 text-success">Create New Account</h4>
    <form action="signup_action.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Membership Type</label>
        <select name="tier" class="form-select" required>
          <option value="student">Student</option>
          <option value="faculty">Faculty</option>
          <option value="staff">Staff</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Create Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Register</button>
    </form>
  </div>
</div>

</body>
</html>

