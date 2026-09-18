@extends('admin.layouts.layout')
@section('title', 'Admin - Drinking Habit')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Drinking Habit Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Add Drinking Habit</h5>

                                    <form method="post" action="{{ route('drinking-habit.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="drinking-input">Drinking Habit</label>
                                            <input type="text" class="form-control" name="name" id="drinking-input" placeholder="Enter Drinking Habit..." required>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Drinking Habit Form -->

                        <!-- Drinking Habit Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Drinking Habit</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Drinking Habit</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($drinkingHabits as $index => $drinkingHabit)
                                                <tr>
                                                    <th scope="row">{{ method_exists($drinkingHabits, 'firstItem') ? $drinkingHabits->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $drinkingHabit->name }}</td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $drinkingHabit->id }}">Edit</button>
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $drinkingHabit->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $drinkingHabit->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $drinkingHabit->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $drinkingHabit->id }}">Edit Drinking Habit</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('drinking-habit.update', $drinkingHabit->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-drinking">Drinking Habit</label>
                                                                        <input type="text" class="form-control" id="edit-drinking" name="name" value="{{ $drinkingHabit->name }}">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Edit Modal -->

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal-{{ $drinkingHabit->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $drinkingHabit->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $drinkingHabit->id }}">Delete Drinking Habit</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Drinking Habit "{{ $drinkingHabit->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('drinking-habit.destroy', $drinkingHabit->id) }}" method="POST" style="display: inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Delete Modal -->
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Pagination Links -->
                                    <div class="d-flex justify-content-end mt-2">
                                        {{ $drinkingHabits->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Drinking Habit Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection

