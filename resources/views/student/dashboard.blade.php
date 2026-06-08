<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - School Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body>
    <nav class="bg-green-600 text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-xl font-bold">Student Portal</h1>
        <div class="flex items-center gap-4">
            <span>Welcome, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600 transition">Logout</button>
            </form>
        </div>
    </nav>
    <div class="container mx-auto mt-8 p-4">
        <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-2 text-gray-800">My Attendance</h3>
                <p class="text-gray-600">Check your daily attendance records.</p>
                <a href="#" class="mt-4 inline-block text-green-600 hover:underline">View Attendance &rarr;</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-2 text-gray-800">My Grades</h3>
                <p class="text-gray-600">View your exam results and report cards.</p>
                <a href="#" class="mt-4 inline-block text-green-600 hover:underline">View Grades &rarr;</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-2 text-gray-800">My Timetable</h3>
                <p class="text-gray-600">Check your weekly class schedule.</p>
                <a href="#" class="mt-4 inline-block text-green-600 hover:underline">View Timetable &rarr;</a>
            </div>
        </div>
    </div>
</body>
</html>
