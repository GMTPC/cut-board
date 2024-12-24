@extends('dashboard')

@section('title', 'Edit User Settings')

@section('content')
    <style>
        /* Input field styling */
        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1e7dd; /* Light green border */
            border-radius: 25px; /* Rounded corners */
            background-color: #f8faff; /* Light blue background */
            outline: none;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .form-input:focus {
            border-color: #198754; /* Green border on focus */
            box-shadow: 0 0 5px rgba(25, 135, 84, 0.5); /* Green glow */
        }

        /* Button styling */
        .form-button {
            background-color: #198754; /* Green background */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px; /* Rounded button */
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .form-button:hover {
            background-color: #145d39; /* Darker green */
        }

        .delete-button {
            background-color: #dc3545; /* Red background */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            margin-top: 20px;
        }

        .delete-button:hover {
            background-color: #a71d2a; /* Darker red */
        }

        /* Form container styling */
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
        }

        .form-label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #000;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }
    </style>



<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('swal'))
            const swalData = @json(session('swal'));
            Swal.fire({
                title: swalData.title,
                text: swalData.text,
                icon: swalData.icon, // warning จะทำให้ SweetAlert แสดงสีเหลือง
            }).then(() => {
                if (swalData.logout) {
                    // ใช้ฟอร์ม POST สำหรับ logout
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('logout') }}";
                    form.innerHTML = `
                        @csrf
                    `;
                    document.body.appendChild(form);
                    form.submit();
                } else if (swalData.redirect) {
                    window.location.href = swalData.redirect;
                }
            });
        @endif
    });
</script>







    <div class="form-container">
        <h1 class="mb-4 text-center">Edit User Settings</h1>

     

        <!-- Update Form -->
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf

            <!-- Name Input -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" 
                       class="form-input @error('name') is-invalid @enderror" 
                       value="{{ old('name', Auth::user()->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Input -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" 
                       class="form-input @error('email') is-invalid @enderror" 
                       value="{{ old('email', Auth::user()->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Input -->
            <div class="mb-3">
                <label for="password" class="form-label">New Password (Optional)</label>
                <input type="password" name="password" id="password" class="form-input @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password Input -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="form-button">Save Changes</button>
        </form>

        <!-- Delete Account Button -->
        <button class="delete-button" id="delete-account-button">Delete Account</button>

        <!-- Hidden Form for Deleting Account -->
        <form id="delete-account-form" action="{{ route('settings.delete') }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script>
        // Handle delete account confirmation
        document.getElementById('delete-account-button').addEventListener('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-account-form').submit();
                }
            });
        });
    </script>
@endsection
