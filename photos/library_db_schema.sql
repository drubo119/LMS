-- Create Database and Use It
CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- CATEGORY TABLE
CREATE TABLE Category (
  Category_ID INT AUTO_INCREMENT PRIMARY KEY,
  Category_Name VARCHAR(100) NOT NULL
);

-- AUTHOR TABLE
CREATE TABLE Author (
  Author_ID INT AUTO_INCREMENT PRIMARY KEY,
  Author_Name VARCHAR(100) NOT NULL,
  Nationality VARCHAR(50)
);

-- BOOK TABLE
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

-- BOOK COPY TABLE
CREATE TABLE Book_Copy (
  Copy_ID INT AUTO_INCREMENT PRIMARY KEY,
  Book_ID INT,
  Condition_Status ENUM('New', 'Damaged', 'Lost') DEFAULT 'New',
  Availability_Status ENUM('Available', 'Reserved', 'Loaned') DEFAULT 'Available',
  FOREIGN KEY (Book_ID) REFERENCES Book(Book_ID)
);

-- MEMBER TABLE
CREATE TABLE Member (
  Member_ID INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100),
  Membership_Type ENUM('Student', 'Faculty', 'Staff'),
  Email VARCHAR(100),
  Street VARCHAR(100),
  City VARCHAR(100),
  Postal_Code VARCHAR(20)
);

-- LOAN TABLE
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

-- RESERVATION TABLE
CREATE TABLE Reservation (
  Reservation_ID INT AUTO_INCREMENT PRIMARY KEY,
  Book_ID INT,
  Member_ID INT,
  Reservation_Date DATE,
  Status ENUM('Pending', 'Fulfilled', 'Cancelled') DEFAULT 'Pending',
  FOREIGN KEY (Book_ID) REFERENCES Book(Book_ID),
  FOREIGN KEY (Member_ID) REFERENCES Member(Member_ID)
);

-- STAFF TABLE
CREATE TABLE Staff (
  Staff_ID INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100),
  Role ENUM('Admin', 'Librarian') DEFAULT 'Admin',
  Username VARCHAR(100) UNIQUE,
  Password VARCHAR(255)
);

-- USER ACCOUNT TABLE
CREATE TABLE UserAccount (
  User_ID INT AUTO_INCREMENT PRIMARY KEY,
  Member_ID INT,
  Username VARCHAR(100) UNIQUE,
  Password VARCHAR(255),
  Access_Level ENUM('Member', 'Admin'),
  FOREIGN KEY (Member_ID) REFERENCES Member(Member_ID)
);

-- FINE POLICY TABLE
CREATE TABLE Fine_Policy (
  Policy_ID INT AUTO_INCREMENT PRIMARY KEY,
  Membership_Type ENUM('Student', 'Faculty', 'Staff'),
  Fine_Per_Day DECIMAL(5,2),
  Max_Fine DECIMAL(10,2)
);
