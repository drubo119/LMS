<?php
include 'db_connect.php';

// Fetch books and availability
$sql = "
  SELECT 
    b.Book_ID,
    b.Title, 
    b.Image_URL,
    CASE 
      WHEN COUNT(bc.Copy_ID) > 0 THEN 'Available'
      ELSE 'Not Available'
    END AS Availability
  FROM Book b
  LEFT JOIN Book_Copy bc 
    ON b.Book_ID = bc.Book_ID AND bc.Availability_Status = 'Available'
  GROUP BY b.Book_ID
";

$result = mysqli_query($conn, $sql);
$books = [];
while ($row = mysqli_fetch_assoc($result)) {
    $books[] = [
        'title' => $row['Title'],
        'image' => $row['Image_URL'] ?: 'photos/no-image.jpg',
        'available' => $row['Availability'] === 'Available',
        'book_id' => $row['Book_ID']
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Books</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-light p-4">
  <h3 class="mb-4">All Books</h3>
  <div class="row">
    <?php foreach ($books as $book): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow">
          <img src="<?= htmlspecialchars($book['image']) ?>" class="card-img-top" style="height: 300px; object-fit: fill;" alt="<?= htmlspecialchars($book['title']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
            <p>
              <?php if ($book['available']): ?>
                <span class="badge bg-success">Available</span>
                <button class="btn btn-sm btn-primary mt-2" onclick="reserveBook(<?= $book['book_id'] ?>, this)">Reserve</button>

              <?php else: ?>
                <span class="badge bg-danger">Not Available</span>
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

<script>
function reserveBook(bookId, btn) {
    fetch("reserve_book.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "book_id=" + bookId
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        btn.disabled = true;  // Disable button after successful reservation
        btn.innerText = "Reserved";
    })
    .catch(error => {
        alert("Error reserving book: " + error);
    });
}
</script>

</body>
</html>

