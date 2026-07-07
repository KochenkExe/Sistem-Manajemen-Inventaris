<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kategori Inventaris') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
                    <span class="font-medium">Sukses!</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Add Category Form Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700 h-fit">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Tambah Kategori Baru') }}</h3>
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kategori</label>
                            <input type="text" name="name" id="name" required placeholder="Contoh: Elektronik" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm">
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 dark:hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Simpan Kategori') }}
                        </button>
                    </form>
                </div>

                <!-- Categories List Card -->
                <div class="md:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Daftar Kategori') }}</h3>
                    
                    @if($categories->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            Belum ada kategori yang ditambahkan.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Nama Kategori</th>
                                        <th scope="col" class="px-6 py-3">Jumlah Barang</th>
                                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600/50">
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                                <!-- Inline Edit Form (Optional / Toggleable) -->
                                                <form id="edit-form-{{ $category->id }}" action="{{ route('categories.update', $category) }}" method="POST" class="hidden flex gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="name" value="{{ $category->name }}" required 
                                                        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-xs py-1 px-2">
                                                    <button type="submit" class="bg-green-600 text-white rounded px-2 py-1 text-xs">Simpan</button>
                                                    <button type="button" onclick="toggleEdit({{ $category->id }})" class="bg-gray-500 text-white rounded px-2 py-1 text-xs">Batal</button>
                                                </form>
                                                
                                                <span id="name-display-{{ $category->id }}">
                                                    {{ $category->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                    {{ $category->products_count }} Barang
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end gap-2" id="action-buttons-{{ $category->id }}">
                                                    <button type="button" onclick="toggleEdit({{ $category->id }})" class="text-blue-600 dark:text-blue-400 hover:underline text-xs">
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-xs">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleEdit(id) {
            const form = document.getElementById(`edit-form-${id}`);
            const display = document.getElementById(`name-display-${id}`);
            const actions = document.getElementById(`action-buttons-${id}`);

            if(form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                display.classList.add('hidden');
                actions.classList.add('hidden');
            } else {
                form.classList.add('hidden');
                display.classList.remove('hidden');
                actions.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
