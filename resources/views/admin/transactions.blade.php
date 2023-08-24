<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Liste des utilisateurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("La liste des utilisateur du site!") }}
                </div>
            </div>
        </div>


        <div class="flex flex-col justify-center py-7 mx-12">
            <table class="table-fixed dark:text-white my-5 space-x-24 ">
                <thead class="">
                  <tr>
                    <th class="px-4 text-xl">Id</th>
                    <th class="px-4 text-xl">Num Transaction</th>
                    <th class="px-4 text-xl">Montant Transaction</th>
                    <th class="px-4 text-xl">Date de Transaction</th>
                  </tr>
                </thead>
                <tbody>
                    @php
                        $i=1;
                    @endphp
                    @foreach ($transactions as $transaction)
                        <tr class="border-t-4 border-white-600">
                            <td class="px-4 text-center text-lg">{{ $i }}</td>
                            <td class="px-4 text-center text-lg">{{$transaction['numtrans'] }}</td>
                            <td class="px-4 text-center text-lg">{{$transaction['montant'] }}</td>
                            <td class="px-4 text-center text-lg">{{$transaction['created_at'] }}</td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach


                </tbody>
            </table>

            <div class=" align-center">
                {!! $transactions->links() !!}
            </div>
        </div>
        </div>


    </x-app-layout>
