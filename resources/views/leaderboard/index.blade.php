@extends('layouts.app')

@section('content')
    <div class="container table-container mt-5">
        <div class="text-center">
            <form method="POST" action="{{ route('leaderboard.recalculate') }}">
                @csrf
                <button type="submit" class="btn btn-custom mb-3">Recalculate</button>
            </form>
            <form method="POST" action="{{ route('leaderboard.search') }}">
                @csrf
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">User ID</span>
                    <input type="text" class="form-control" name="user_id" placeholder="Search by User ID" aria-label="User ID" aria-describedby="basic-addon1">
                    <button class="btn btn-custom filter-btn" type="submit">Search</button>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <form method="GET" action="{{ route('leaderboard.index') }}">
                <label for="filter">Filter:</label>
                <select class="form-select w-auto ms-2" name="filter" id="filter" onchange="this.form.submit()">
                    <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="day" {{ request('filter') == 'day' ? 'selected' : '' }}>Today</option>
                    <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
            </form>
        </div>

        <table class="table table-dark table-bordered text-center">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Total Points</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaderboard as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->total_points }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
