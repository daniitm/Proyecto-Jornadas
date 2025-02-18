<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" oninput="checkEmailDomain()" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Tipo de Inscripción -->
        <div class="mt-4">
            <x-input-label for="tipo_inscripcion" :value="__('Tipo de Inscripción')" />
            <select id="tipo_inscripcion" name="tipo_inscripcion" class="block mt-1 w-full" required>
                <option value="presencial">Presencial - 10€</option>
                <option value="virtual">Virtual - 5€</option>
                <option value="gratuita">Gratuita (para estudiantes) - 0€</option>
            </select>
            <x-input-error :messages="$errors->get('tipo_inscripcion')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Pagar y Registrar') }}
            </x-primary-button>
        </div>
    </form>

    <!-- JavaScript para controlar el tipo de inscripción dinámicamente -->
    <script>
        function checkEmailDomain() {
            var email = document.getElementById('email').value;
            var tipoInscripcion = document.getElementById('tipo_inscripcion');

            // Verificar si el correo electrónico contiene el dominio "@franciscoayala.es"
            if (email.endsWith('@franciscoayala.es')) {
                // Si es estudiante, solo mostrar la opción gratuita
                tipoInscripcion.innerHTML = '<option value="gratuita">Gratuita - 0€</option>';
            } else {
                // De lo contrario, mostrar presencial y virtual
                tipoInscripcion.innerHTML = '<option value="presencial">Presencial - 10€</option>' +
                                             '<option value="virtual">Virtual - 5€</option>';
            }
        }
    </script>
</x-guest-layout>