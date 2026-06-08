<?php
// api/library.php
require_once '../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Return dummy library data
    $books = [
        ['title' => 'Advanced Physics', 'category' => 'Science Dept.', 'author' => 'H.C. Verma', 'isbn' => '978-8177091878', 'status' => 'Available'],
        ['title' => 'A Brief History of Time', 'category' => 'General Sci.', 'author' => 'Stephen Hawking', 'isbn' => '978-0553380163', 'status' => 'Checked Out'],
        ['title' => 'Modern World History', 'category' => 'History Dept.', 'author' => 'Norman Lowe', 'isbn' => '978-0230206223', 'status' => 'Available'],
        ['title' => 'Calculus: Early Transcendentals', 'category' => 'Math Dept.', 'author' => 'James Stewart', 'isbn' => '978-1285741550', 'status' => 'Checked Out'],
        ['title' => 'To Kill a Mockingbird', 'category' => 'Literature', 'author' => 'Harper Lee', 'isbn' => '978-0060935467', 'status' => 'Available']
    ];

    $transactions = [
        ['type' => 'issue', 'student' => 'Sarah Jenkins', 'time' => 'Today, 09:41', 'book' => 'Advanced Physics', 'due' => 'Oct 24, 2023', 'status' => 'normal'],
        ['type' => 'return', 'student' => 'Michael Chang', 'time' => 'Today, 08:15', 'book' => 'Biology 101', 'due' => 'Returned on time', 'status' => 'success'],
        ['type' => 'overdue', 'student' => 'David Miller', 'time' => 'Overdue', 'book' => 'A Brief History of Time', 'due' => 'Oct 10, 2023 (2 days late)', 'status' => 'error'],
        ['type' => 'issue', 'student' => 'Emma Wilson', 'time' => 'Yesterday', 'book' => 'World Atlas', 'due' => 'Oct 25, 2023', 'status' => 'normal']
    ];

    echo json_encode([
        'status' => 'success',
        'data' => [
            'books' => $books,
            'transactions' => $transactions,
            'stats' => [
                'total_books' => 14285,
                'issued' => 1842,
                'overdue' => 47
            ]
        ]
    ]);
}
?>
