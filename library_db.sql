CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- Users table (for user login/signup)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    tier ENUM('student', 'faculty', 'staff') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admins table (optional: or use static admin login)
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- CATEGORY
CREATE TABLE Category (
  Category_ID INT AUTO_INCREMENT PRIMARY KEY,
  Category_Name VARCHAR(100) NOT NULL
);

-- AUTHOR
CREATE TABLE Author (
  Author_ID INT AUTO_INCREMENT PRIMARY KEY,
  Author_Name VARCHAR(100) NOT NULL,
  Nationality VARCHAR(50)
);

-- BOOK
CREATE TABLE Book (
  Book_ID INT AUTO_INCREMENT PRIMARY KEY,
  Title VARCHAR(255) NOT NULL,
  Author_ID INT,
  Category_ID INT,
  Publisher VARCHAR(100),
  ISBN VARCHAR(50),
  Language VARCHAR(50),
  FOREIGN KEY (Author_ID) REFERENCES Author(Author_ID),
  FOREIGN KEY (Category_ID) REFERENCES Category(Category_ID)
);

-- BOOK COPY (weak entity)
CREATE TABLE Book_Copy (
  Copy_ID INT AUTO_INCREMENT PRIMARY KEY,
  Book_ID INT,
  Condition_Status ENUM('New', 'Damaged', 'Lost') DEFAULT 'New',
  Availability_Status ENUM('Available', 'Reserved', 'Loaned') DEFAULT 'Available',
  FOREIGN KEY (Book_ID) REFERENCES Book(Book_ID)
);

-- MEMBER
CREATE TABLE Member (
  Member_ID INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100),
  Membership_Type ENUM('Student', 'Faculty', 'Staff'),
  Email VARCHAR(100),
  Street VARCHAR(100),
  City VARCHAR(100),
  Postal_Code VARCHAR(20)
);

-- LOAN
CREATE TABLE Loan (
  Loan_ID INT AUTO_INCREMENT PRIMARY KEY,
  Book_Copy_ID INT,
  Member_ID INT,
  Loan_Date DATE,
  Due_Date DATE,
  Return_Date DATE,
  Fine_Amount DECIMAL(10,2),
  FOREIGN KEY (Book_Copy_ID) REFERENCES Book_Copy(Copy_ID),
  FOREIGN KEY (Member_ID) REFERENCES Member(Member_ID)
);

-- RESERVATION
CREATE TABLE Reservation (
  Reservation_ID INT AUTO_INCREMENT PRIMARY KEY,
  Book_ID INT,
  Member_ID INT,
  Reservation_Date DATE,
  Status ENUM('Pending', 'Fulfilled', 'Cancelled') DEFAULT 'Pending',
  FOREIGN KEY (Book_ID) REFERENCES Book(Book_ID),
  FOREIGN KEY (Member_ID) REFERENCES Member(Member_ID)
);

-- STAFF / ADMIN
CREATE TABLE Staff (
  Staff_ID INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100),
  Role ENUM('Admin', 'Librarian') DEFAULT 'Admin',
  Username VARCHAR(100) UNIQUE,
  Password VARCHAR(255)
);

-- USER ACCOUNT (for login control)
CREATE TABLE UserAccount (
  User_ID INT AUTO_INCREMENT PRIMARY KEY,
  Member_ID INT,
  Username VARCHAR(100) UNIQUE,
  Password VARCHAR(255),
  Access_Level ENUM('Member', 'Admin'),
  FOREIGN KEY (Member_ID) REFERENCES Member(Member_ID)
);

-- FINE POLICY
CREATE TABLE Fine_Policy (
  Policy_ID INT AUTO_INCREMENT PRIMARY KEY,
  Membership_Type ENUM('Student', 'Faculty', 'Staff'),
  Fine_Per_Day DECIMAL(5,2),
  Max_Fine DECIMAL(10,2)
);
INSERT INTO Book_Copy (Book_ID, Condition_Status, Availability_Status) VALUES
(1, 'New', 'Available'),
(3, 'New', 'Available'),
(4, 'Damaged', 'Loaned'),
(5, 'New', 'Available'),
(6, 'Damaged', 'Loaned'),
(7, 'New', 'Available'),
(8, 'New', 'Reserved'),
(9, 'New', 'Available'),
(10, 'New', 'Loaned'),
(11, 'New', 'Available'),
(12, 'Lost', 'Lost'),
(13, 'New', 'Available'),
(14, 'New', 'Reserved'),
(15, 'New', 'Available');
INSERT INTO Book (Title, Author_ID, Category_ID, Publisher, ISBN, Language) VALUES
('Harry Potter and the Sorcerer\'s Stone', 1, 1, 'Bloomsbury', '9780747532743', 'English'),
('1984', 2, 2, 'Secker & Warburg', '9780451524935', 'English'),
('Pride and Prejudice', 3, 3, 'T. Egerton', '9780141040349', 'English'),
('The Adventures of Tom Sawyer', 4, 6, 'American Publishing Company', '9780486400778', 'English'),
('Murder on the Orient Express', 5, 5, 'Collins Crime Club', '9780062693662', 'English'),
('The Old Man and the Sea', 6, 8, 'Charles Scribner\'s Sons', '9780684801223', 'English'),
('A Tale of Two Cities', 7, 7, 'Chapman & Hall', '9781853262647', 'English'),
('The Great Gatsby', 8, 4, 'Charles Scribner\'s Sons', '9780743273565', 'English'),
('War and Peace', 9, 7, 'The Russian Messenger', '9780199232765', 'English'),
('To Kill a Mockingbird', 10, 4, 'J.B. Lippincott & Co.', '9780061120084', 'English');
INSERT INTO Category (Category_Name) VALUES
('Fantasy'),
('Science Fiction'),
('Romance'),
('Classic'),
('Mystery'),
('Adventure'),
('Historical'),
('Drama'),
('Philosophy'),
('Biography');
INSERT INTO Author (Author_Name, Nationality) VALUES
('J.K. Rowling', 'British'),
('George Orwell', 'British'),
('Jane Austen', 'British'),
('Mark Twain', 'American'),
('Agatha Christie', 'British'),
('Ernest Hemingway', 'American'),
('Charles Dickens', 'British'),
('F. Scott Fitzgerald', 'American'),
('Leo Tolstoy', 'Russian'),
('Harper Lee', 'American');
