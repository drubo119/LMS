<?php
include 'db_connect.php';

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
                <a href="reserve_book.php?book_id=<?= $book['book_id'] ?>" class="btn btn-sm btn-primary mt-2">Reserve</a>
              <?php else: ?>
                <span class="badge bg-danger">Not Available</span>
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
