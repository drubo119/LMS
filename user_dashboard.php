<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: user_login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: url('photos/user.jpg') center center / cover no-repeat; height: 100vh;">

<nav class="navbar navbar-dark bg-dark px-4">
  <a class="navbar-brand fw-bold" href="#">📚 User Dashboard</a>
  <span class="text-white">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
  <a href="logout.php" class="btn btn-outline-light ms-3">Logout</a>
</nav>

<div class="container my-5">
  <h2 class="mb-4 text-white">User Dashboard</h2>
  <div class="row g-4">

    <!-- Book Browsing
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title">📘 Browse Books</h5>
          <ul>
            <li><a href="browse_books.php">View Available Books</a></li>
            <li><a href="reserve_book.php">Reserve Book</a></li>
          </ul>
        </div>
      </div>
    </div> -->
    <!-- <div class="col-md-4">
  <div class="card shadow h-100">
    <div class="card-body text-center">
      <h5 class="card-title">📘 Browse Books</h5>
       Book Browsing
      <?php
      include 'db_connect.php';
      $sql = "SELECT DISTINCT b.Book_ID, b.Title, b.Image_URL
      FROM Book b
      JOIN Book_Copy bc ON bc.Book_ID = b.Book_ID
      WHERE bc.Availability_Status = 'Available'";

      $result = mysqli_query($conn, $sql);
      $books = [];
      while ($row = mysqli_fetch_assoc($result)) {
        $books[] = [
          'book_id' => $row['Book_ID'],  // Add this line
          'title' => $row['Title'],
          'image' => $row['Image_URL'] ?: 'photos/no-image.jpg',
          'available' => true
      ];
      
      }
      ?>

      

      
      <img id="bookImage" src="" alt="Book Cover" class="img-fluid mb-2" style="max-height: 200px; object-fit: contain;">
      <h6 id="bookTitle" class="mb-1"></h6>
      <span id="bookStatus" class="badge mb-3"></span>
      <div>
        <button id="prevBtn" class="btn btn-sm btn-outline-secondary me-1">Prev</button>
        <button id="nextBtn" class="btn btn-sm btn-outline-secondary">Next</button>
      </div>

      <script>
        const books = 
        let currentIndex = 0;

        function updateBook() {
          if (books.length === 0) return;
          const book = books[currentIndex];
          document.getElementById('bookImage').src = book.image;
          document.getElementById('bookTitle').textContent = book.title;
          const statusEl = document.getElementById('bookStatus');
          statusEl.textContent = 'Available';
          statusEl.className = 'badge bg-success';
        }

        function nextBook() {
          currentIndex = (currentIndex + 1) % books.length;
          updateBook();
        }

        function prevBook() {
          currentIndex = (currentIndex - 1 + books.length) % books.length;
          updateBook();
        }

        document.getElementById('nextBtn').addEventListener('click', nextBook);
        document.getElementById('prevBtn').addEventListener('click', prevBook);

        setInterval(nextBook, 5000); // Auto change every 5 seconds

        updateBook(); // Initial display
      </script>

    </div>
  </div>
</div> -->

<div class="col-md-4">
  <div class="card shadow h-100">
    <div class="card-body text-center">
      <h5 class="card-title">📘 Browse Books</h5>

      <!-- Book Slideshow Area -->
      <img id="bookImage" src="" alt="Book Cover" class="img-fluid mb-2" style="max-height: 200px; object-fit: contain;">
      <h6 id="bookTitle" class="mb-1"></h6>
      <span id="bookStatus" class="badge mb-2"></span>
      <div id="reserveBtnContainer" class="mb-3"></div>

      <!-- Buttons -->
      <div>
        <button id="prevBtn" class="btn btn-sm btn-outline-secondary me-1">Prev</button>
        <button id="nextBtn" class="btn btn-sm btn-outline-secondary">Next</button>
        <a href="browse_all_books.php" class="btn btn-outline-primary btn-sm ">Browse All</a>
      </div>

      <!-- Script to handle book browsing -->
      <script>
        const books = <?php echo json_encode($books); ?>;
        let currentIndex = 0;

        function updateBook() {
          const book = books[currentIndex];
          document.getElementById('bookImage').src = book.image;
          document.getElementById('bookTitle').textContent = book.title;

          const statusEl = document.getElementById('bookStatus');
          const reserveContainer = document.getElementById('reserveBtnContainer');
          
          if (book.available) {
            statusEl.textContent = "Available";
            statusEl.className = "badge bg-success";

            reserveContainer.innerHTML = `
  <form action="reserve_book.php" method="POST">
    <input type="hidden" name="book_id" value="${book.book_id}">
    <button type="submit" class="btn btn-primary btn-sm">Reserve</button>
  </form>
`;

          } else {
            statusEl.textContent = "Not Available";
            statusEl.className = "badge bg-danger";
            reserveContainer.innerHTML = '';
          }
        }

        function nextBook() {
          currentIndex = (currentIndex + 1) % books.length;
          updateBook();
        }

        function prevBook() {
          currentIndex = (currentIndex - 1 + books.length) % books.length;
          updateBook();
        }

        document.getElementById('nextBtn').addEventListener('click', nextBook);
        document.getElementById('prevBtn').addEventListener('click', prevBook);
        setInterval(nextBook, 5000); // Auto change

        updateBook(); // Show first book
      </script>
    </div>
  </div>
</div>


    
    <!-- <div class="col-md-6">
  <div class="card shadow h-100">
    <div class="card-body">
      <h5 class="card-title">📘 Browse Books</h5>

      <div id="bookCarousel" class="text-center">
        <img id="bookImage" src="" style="height: 200px;" class="img-fluid rounded mb-2" alt="Book Cover">
        <h6 id="bookTitle" class="mb-1">Loading...</h6>
        <span id="bookStatus" class="badge bg-secondary">Checking availability...</span>

        <div class="mt-3">
          <button class="btn btn-sm btn-outline-primary me-2" onclick="prevBook()">⬅️ Prev</button>
          <button class="btn btn-sm btn-outline-primary" onclick="nextBook()">Next ➡️</button>
        </div>
      </div>

      <a href="browse_books.php" class="btn btn-primary mt-4">Browse All</a>
    </div>
  </div>
</div> -->


    <!-- Borrowing Status -->
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title">📖 My Borrowings</h5>
          <ul>
            <li><a href="my_borrowings.php">View Borrowed Books</a></li>
            <li><a href="my_fines.php">Check Fines</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Profile -->
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title">👤 Profile</h5>
          <ul>
            <li><a href="edit_profile.php">Edit Profile</a></li>
            <li><a href="borrowing_history.php">Borrowing History</a></li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>
<script src="book-carousel.js"></script>

</body>
</html>
