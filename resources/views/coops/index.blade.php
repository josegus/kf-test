<x-app-layout>
    @foreach ($coops as $coop)
        <div class="border border-gray-300 rounded p-3 mb-2 bg-white flex justify-between">
            <a href="{{ route('coops.show', $coop) }}">{{ $coop->name }}</a>
            <a class="hover:underline text-blue-500" href="{{ route('coops.show', $coop) }}">Fund</a>
        </div>
    @endforeach

    {!! $coops->links() !!}
</x-app-layout>
