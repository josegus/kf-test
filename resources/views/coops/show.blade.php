<x-app-layout>
    <h2 class="text-lg">{{ $coop->name }}</h2>

    <div class="mt-4">Expires at: {{ $coop->expiration_date }}</div>
    <div>Goal: $ {{ $coop->goal }}</div>

    @auth
        <form class="mt-6" action="{{ route('coops.fund', $coop) }}" method="post">
            @csrf

            <div>
                <x-label class="w-full lg:w-1/4 inline-block" for="amount" value="Amount" />
                <x-input class="w-full lg:w-1/2" id="amount" type="number" name="amount" min="1" :value="old('amount', 1)" />
            </div>

            <div class="mt-4">
                <x-label class="w-full lg:w-1/4 inline-block" for="package_quantity" value="Package quantity" />
                <x-input class="w-full lg:w-1/2" id="package_quantity" type="number" name="package_quantity" min="1" :value="old('package_quantity', 1)" />
            </div>

            <div class="mt-4">
                <x-label class="w-full lg:w-1/4 inline-block" for="package_quantity" value="Package id" />
                <x-input class="w-full lg:w-1/2" id="package_id" type="number" name="package_id" min="1" :value="old('package_id', 1)" />
            </div>

            <x-button class="mt-4">Purchase</x-button>
        </form>
    @else
        <div class="mt-4">
            You need to be logged in to purchase.
            Please <x-link :href="route('register')">register</x-link>
            or <x-link :href="route('login')">log in</x-link>
        </div>
    @endauth
</x-app-layout>
