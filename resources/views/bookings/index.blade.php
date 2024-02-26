<!DOCTYPE html>
<html>
<head>
    <title>Bookings</title>
    <style>
        body {
            background-color: #e9f5ec;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            flex-direction: column; /* Ensures vertical centering and allows content to define width */
        }
        div {
            display: inline-flex; /* Allows the div to only be as wide as its contents but still be flex */
            flex-direction: column; /* Aligns children (like ul) in a column */
            align-items: center; /* Centers the items horizontally */
        }
        ul {
            list-style-type: none;
            padding: 0;
            display: inline-block; /* Makes the ul only as wide as its contents */
        }
        li {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #cdecd9;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        input[type="text"], input[type="hidden"] {
            padding: 5px;
            border: 1px solid #a4b3a6; /* Slightly darker border for inputs */
            border-radius: 3px;
        }
        button {
            background-color: #4a8f5c; /* Vibrant green for buttons */
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #3a6f4b; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <div>
        <h1>Course Booking Times</h1>
        <div>
            <button onclick="changeDate(-1)">←</button>
            <span id="currentDate">{{ $selectedDate }}</span>
            <button onclick="changeDate(1)">→</button>
        </div>
        <script>
            function changeDate(change) {
                let currentDate = new Date(document.getElementById("currentDate").textContent);
                currentDate.setDate(currentDate.getDate() + change);
                let formattedDate = currentDate.toISOString().split('T')[0];
                window.location.href = `?date=${formattedDate}`;
            }
        </script>
        <ul>
            @foreach($times as $time)
                <li>
                    {{ $time }} :
                    @if(array_key_exists($time, $bookings))
                        <span>{{ $bookings[$time] }}</span>
                    @else
                        <form action="{{ route('bookings.book') }}" method="POST">
                            @csrf
                            <input type="hidden" name="time" value="{{ $time }}">
                            <input type="hidden" name="date" value="{{ $selectedDate }}"> <!-- Include the date -->
                            <input type="text" name="booked_by" required placeholder="Your Name">
                            <button type="submit">Book</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>
