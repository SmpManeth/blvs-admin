<x-app-layout>
    <h1 class="text-2xl font-semibold mb-4">Add New User</h1>

    <form method="POST" action="{{ route('users.store') }}" class="space-y-6 max-w-lg">
        @csrf
        @include('pages.users._form', ['submit' => 'Create User'])
    </form>
</x-app-layout>
