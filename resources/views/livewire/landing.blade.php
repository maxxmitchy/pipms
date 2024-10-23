<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <header class="bg-white shadow-md">
        <div class="container px-4 py-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-indigo-700 sm:text-3xl">PIPMS</h1>
                <nav>
                    <ul class="flex space-x-4 sm:space-x-6">
                        <li><a href="#features" class="text-sm text-gray-600 transition duration-150 ease-in-out sm:text-base hover:text-indigo-600">Features</a></li>
                        <li><a href="#about" class="text-sm text-gray-600 transition duration-150 ease-in-out sm:text-base hover:text-indigo-600">About</a></li>
                        <li><a href="{{ route('login') }}" class="text-sm text-gray-600 transition duration-150 ease-in-out sm:text-base hover:text-indigo-600">Login</a></li>
                        <li><a href="{{ route('register') }}" class="px-2 py-1 text-sm text-white transition duration-150 ease-in-out bg-indigo-600 rounded sm:text-base hover:bg-indigo-50 hover:text-indigo-600">Register</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="container px-4 py-12 mx-auto sm:px-6 lg:px-8">
        <section class="mb-16 text-center">
            <h2 class="mb-4 text-3xl font-extrabold text-indigo-800 sm:text-4xl md:text-5xl">Pharmaceutical Inventory and Prescription Management System</h2>
            <p class="max-w-3xl mx-auto text-lg text-gray-600 sm:text-xl">Streamline your pharmaceutical operations with our comprehensive management solution.</p>
        </section>

        <section id="features" class="mb-16">
            <h3 class="mb-6 text-2xl font-bold text-indigo-700 sm:text-3xl">Key Features</h3>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach(['Manage Medications', 'Create Prescriptions', 'Track Inventory', 'Generate Reports'] as $feature)
                    <div class="p-6 transition duration-300 ease-in-out bg-white rounded-lg shadow-md hover:shadow-lg">
                        <h4 class="mb-2 text-lg font-semibold text-indigo-600">{{ $feature }}</h4>
                        <p class="text-gray-600">Efficiently {{ strtolower($feature) }} with our intuitive interface.</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section id="about" class="mb-16">
            <h3 class="mb-6 text-2xl font-bold text-indigo-700 sm:text-3xl">About PIPMS</h3>
            <div class="p-6 bg-white rounded-lg shadow-md sm:p-8">
                <p class="mb-4 text-gray-600">The Pharmaceutical Inventory and Prescription Management System (PIPMS) is designed to revolutionize how medical professionals manage their pharmaceutical operations. Our system provides a complete solution for pharmacies and medical organizations to efficiently handle their inventory and prescription needs.</p>
                <p class="text-gray-600">PIPMS is built to seamlessly integrate into existing workflows and can be easily extended to include additional features as your organization grows.</p>
            </div>
        </section>

        <section id="contact">
            <h3 class="mb-6 text-2xl font-bold text-indigo-700 sm:text-3xl">Contact Us</h3>
            <div class="p-6 bg-white rounded-lg shadow-md sm:p-8">
                <form wire:submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" wire:model="name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" wire:model="email" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea id="message" wire:model="message" rows="4" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        @error('message') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <button type="submit" class="w-full px-6 py-3 font-medium text-white transition duration-150 ease-in-out bg-indigo-600 rounded-md sm:w-auto hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Send Message</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <footer class="py-8 text-white bg-indigo-800">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between sm:flex-row">
                <p class="mb-4 text-sm sm:mb-0">&copy; {{ date('Y') }} PIPMS. All rights reserved.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-sm transition duration-150 ease-in-out hover:text-indigo-200">Privacy Policy</a>
                    <a href="#" class="text-sm transition duration-150 ease-in-out hover:text-indigo-200">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</div>
