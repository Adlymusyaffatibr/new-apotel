@extends('layout.template')

@section('content')
    <h1>Kelola Akun</h1>
    <a href="{{ route('kelola.create') }}" class="btn btn-primary float-end">Tambah Akun</a>

    <!-- Table for displaying user data -->
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user) 
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <a href="{{ route('kelola.edit', $user->id) }}" class="btn btn-primary">Edit</a>

                    <!-- Trigger the modal with a button -->
                    <button class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">Hapus</button>

                    <!-- Delete form (hidden) -->
                    <form id="delete-form-{{ $user->id }}" action="{{ route('kelola.delete', $user->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Hapus</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
<script>
    let formToSubmit;

    // Function to trigger the modal and pass the form ID
    function confirmDelete(userId) {
        formToSubmit = document.getElementById('delete-form-' + userId);
        $('#deleteModal').modal('show'); // Show the modal
    }

    // When the confirm button in the modal is clicked
    document.getElementById('confirmDeleteButton').addEventListener('click', function () {
        if (formToSubmit) {
            formToSubmit.submit(); // Submit the form if the modal is confirmed
        }
    });
</script>
@endpush