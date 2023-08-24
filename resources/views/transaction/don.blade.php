<x-app-layout class="">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Faire un don aux déplacés internes') }}
        </h2>
    </x-slot>

    <div class="py-12  ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Faites un don pour sauver des vies au Burkina Faso.Le montant du don est compris entre 100 FCFA et 1 000 000 FCFA!") }}
                </div>
            </div>
        </div>
        @php
            //la route vers la transaction avec la methode payin
        @endphp
        <div class=" py-12 flex justify-center items-center">

            <form method="POST" action="{{ route('transaction.store') }}">
                @csrf

                 <!-- Email Address -->
                <div>
                    <x-input-label for="montant" :value="__('Montant du don')" />
                    <x-text-input id="montant" class="block mt-1 w-full" type="number" name="montant" :value="old('montant')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('montant')" class="mt-2" />
                </div>


                <x-primary-button class="my-5">
                    {{ __('Je fais mon don') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
