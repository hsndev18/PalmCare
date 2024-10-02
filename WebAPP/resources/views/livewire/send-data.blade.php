<div>
    <div wire:poll.2000ms="checkResponse"></div>
    @if ($file_uploaded)
        <div class="lds-default">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    @else
        <div class="form-group">
            <input type="file" class="form-control" id="file" name="file" wire:model='file'>
        </div>

        <div class="form-group mt-1">
            <button type="submit" wire:click='save()' class="btn btn-primary">{{ __('إرسال') }}</button>
        </div>


        @if ($apiResponse)
            <div>
                <p class="mb-5 my-sm-5">
                    Diagnosis: {{ $apiResponse->diagnosis }}
                </p>
                <p class="mb-5 my-sm-5">
                    Disease: {{ $apiResponse->disease }}
                </p>
            </div>
        @endif
    @endif
    <style>
        .lds-default,
        .lds-default div {
            box-sizing: border-box;
        }

        .lds-default {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }

        .lds-default div {
            position: absolute;
            width: 6.4px;
            height: 6.4px;
            background: currentColor;
            border-radius: 50%;
            animation: lds-default 1.2s linear infinite;
        }

        .lds-default div:nth-child(1) {
            animation-delay: 0s;
            top: 36.8px;
            left: 66.24px;
        }

        .lds-default div:nth-child(2) {
            animation-delay: -0.1s;
            top: 22.08px;
            left: 62.29579px;
        }

        .lds-default div:nth-child(3) {
            animation-delay: -0.2s;
            top: 11.30421px;
            left: 51.52px;
        }

        .lds-default div:nth-child(4) {
            animation-delay: -0.3s;
            top: 7.36px;
            left: 36.8px;
        }

        .lds-default div:nth-child(5) {
            animation-delay: -0.4s;
            top: 11.30421px;
            left: 22.08px;
        }

        .lds-default div:nth-child(6) {
            animation-delay: -0.5s;
            top: 22.08px;
            left: 11.30421px;
        }

        .lds-default div:nth-child(7) {
            animation-delay: -0.6s;
            top: 36.8px;
            left: 7.36px;
        }

        .lds-default div:nth-child(8) {
            animation-delay: -0.7s;
            top: 51.52px;
            left: 11.30421px;
        }

        .lds-default div:nth-child(9) {
            animation-delay: -0.8s;
            top: 62.29579px;
            left: 22.08px;
        }

        .lds-default div:nth-child(10) {
            animation-delay: -0.9s;
            top: 66.24px;
            left: 36.8px;
        }

        .lds-default div:nth-child(11) {
            animation-delay: -1s;
            top: 62.29579px;
            left: 51.52px;
        }

        .lds-default div:nth-child(12) {
            animation-delay: -1.1s;
            top: 51.52px;
            left: 62.29579px;
        }

        @keyframes lds-default {

            0%,
            20%,
            80%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.5);
            }
        }
    </style>
</div>
