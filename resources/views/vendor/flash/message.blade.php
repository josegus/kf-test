@if (session()->has('flash_notification'))
    <div class="flex rounded bg-blue-500 p-3 text-white {{ session('flash_notification.type') }} {{ config('flash.class') }}" role="alert">
         @if (session('flash_notification.dismissible'))
            <button type="button"
                    onclick="this.parentElement.remove()"
                    data-dismiss="alert"
                    aria-hidden="true"
            >&times;</button>
        @endif

        <div class="ml-4">
            {!! session('flash_notification.message') !!}
        </div>
    </div>
@endif

@if (config('flash.validations.enabled'))
    @include(config('flash.validations.view'))
@endif
