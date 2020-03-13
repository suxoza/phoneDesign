@extends('base')

@section('content')

    

    <div class="w-full h-full flex justify-center items-center  bg-gray-800">
        <div class="bg-white w-full sm:w-128 sm:mt-24 h-full flex sm:shadow-2xl flex-col sm:border relative overflow-hidden">
            {{-- modal --}}
            <div id="modal" class="animMargin w-full overflow-hidden absolute z-20 top-0 flex justify-center mt-8 rounded-lg -mt-74">
                {{-- wall --}}
                <div class="closeHintWindow w-full h-full absolute"></div>
                {{-- close --}}
                <div class="closeHintWindow w-10 h-10 absolute top-0 right-0 bg-gray-800 rounded-full flex justify-center items-center mr-6 z-20">
                    <svg viewBox="0 0 23 23" fill="none" class="cursor-pointer w-6 h-6"><rect x="3.72177" y="0.186279" width="27" height="5" rx="2" transform="rotate(45 3.72177 0.186279)" fill="#EAEAEA"></rect><rect width="27" height="5" rx="2" transform="matrix(-0.707107 0.707107 0.707107 0.707107 19.0919 0)" fill="#EAEAEA"></rect></svg>
                </div>
                <div class="w-11/12 bg-gray-700 overflow-y-auto h-70 modalInner relative z-10 p-4"></div>
            </div>


            {{-- header --}}
            <div class="w-full bg-blue-600 h-20 py-10 flex justify-between items-center px-8">
                <div class="text-white text-3xl font-light">
                    Phonebook
                </div>
                <div class="flex flex-col h-12 py-1 justify-around">
                    @foreach(range(0,2) as $v)
                        <div class="rounded-full w-1.5 h-1.5 bg-white"></div>
                    @endforeach
                </div>
            </div>

            {{-- avatar --}}
            <div class="relative bg-gray-900 flex justify-center overflow-y-hidden">
                <svg class="w-1/3 h-70 -mb-20" fill="#5c5c5c" viewBox="0 0 24 24"><path d="M20.822 18.096c-3.439-.794-6.641-1.49-5.09-4.418 4.719-8.912 1.251-13.678-3.732-13.678-5.081 0-8.464 4.949-3.732 13.678 1.597 2.945-1.725 3.641-5.09 4.418-2.979.688-3.178 2.143-3.178 4.663l.005 1.241h10.483l.704-3h1.615l.704 3h10.483l.005-1.241c.001-2.52-.198-3.975-3.177-4.663zm-8.231 1.904h-1.164l-.91-2h2.994l-.92 2z"/>
                </svg>

                {{-- matchNumber --}}
                <div id="hintResultDiv" class="animMargin absolute bottom-0 w-full h-24 -mb-32 overflow-hidden">
                    {{-- wall --}}
                    <div class="absolute w-full h-full bg-black opacity-50"></div>
                    <div class="px-2 h-full flex justify-between content-center relative z-10 items-center">
                        {{-- number --}}
                        <div class="w-full flex mx-2 flex-col">
                            <div id="hintHtmls" class="flex flex-col"></div>
                            <div class="showAll hover:text-blue-500 cursor-pointer text-white w-full h-8 text-xs flex justify-center hidden">show all</div>
                        </div>
                        {{-- some logo --}}
                        <div class="py-2 px-5 border-l border-gray-700 h-12 flex items-center">
                            <img src="/img/kazam.png" class="h-8 w-12">
                        </div>
                    </div>
                </div>
            </div>

            {{-- table --}}
            <div class="bg-white border-1 border-gray-800 h-full w-full">
                {{-- header table --}}
                <div class="flex py-4 px-2 border-l border-r border-gray-400">
                    {{-- matches --}}
                    <div class="matches cursor-pointer w-1/4 px-2 flex flex-col items-center text-blue-600 font-bold border-r border-gray-400 hover:text-blue-800 invisible">
                        <span class="text-2xl font-thin matchCount">0</span>
                        <span class="uppercase tracking-tighter font-thin">matches</span>
                    </div>
                    <div id="input" class="overflow-hidden  w-full h-12 flex items-center justify-end text-6xl px-2 font-light">
                        
                    </div>
                    <div class="clearButton cursor-pointer w-1/5 px-3 border-l border-gray-400 flex items-center justify-center hover:bg-gray-300">
                        {{-- clear icon --}}
                        <svg viewBox="0 0 24 24" fill="gray" class="w-8 h-10" transform="rotate(180)">
                            <path d="M12 8v-5l9 9-9 9v-5h-12v-8h12zm12-4h-3v16h3v-16z"/>
                        </svg>
                    </div>
                </div>
            </div>
            {{-- numbers --}}
            <div class="flex flex-wrap w-full">
                @foreach($numbers as $key => $value)
                    <div data-value="{{$key}}" class="numbers w-1/3 h-20 border border-gray-400 flex justify-center items-center text-4xl cursor-pointer hover:text-gray-600 text-gray-800">
                        @if(is_array($value) || $key == 1)
                            <div class="text-5xl font-thin font-serif">{{$key}}</div>
                            <div class="text-base text-gray-700 ml-2 w-1/6 font-sans">
                                @if($value){{ join('', $value) }}
                                @else <img src="/img/voicemail.svg" class="w-12 h-8" /> 
                                @endif
                            </div>
                        @else
                            <div class="text-5xl font-light pt-3 text-gray-800 w-1/4">
                                {!! wordwrap($value, 0, '</div><div class="text-3xl -ml-2 mt-2 text-gray-700">') !!}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            {{-- bottom line --}}
            <div class="w-full flex justify-center">
                <div class="w-2/3 h-1 bg-green-600 rounded"></div>
            </div>

        </div>
    </div>




@endsection
