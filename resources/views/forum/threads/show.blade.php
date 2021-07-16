@title($thread->subject())

@extends('layouts.default', ['hasShadow' => true])

@section('subnav')            
    <section class="bg-white pb-10">
        <div class="container mx-auto">
            <h1 class="flex items-center gap-x-3.5 text-3xl font-semibold">
                <a href="{{ route('forum') }}" class="text-gray-400 hover:underline">Forum</a>
                <x-heroicon-o-chevron-right class="w-6 h-6" />
                {{ $title }}
            </h1>
        </div>
    </section>
@endsection

@section('content')
    <section class="pt-5 pb-10 container mx-auto flex gap-x-12 lg:pt-10 lg:pb-0">
        <div class="w-3/4">
            <div class="relative">
                <div class="relative flex flex-col gap-y-6 z-50">
                    <x-threads.thread :thread="$thread" />

                    @foreach ($thread->replies() as $reply)
                        <x-threads.reply :thread="$thread" :reply="$reply" />
                    @endforeach
                </div>
                <div class="absolute h-full border-l border-lio-500 ml-16 z-10 inset-y-0 left-0"></div>
            </div>

            @can(App\Policies\ReplyPolicy::CREATE, App\Models\Reply::class)
                @if ($thread->isConversationOld())
                    <x-info-panel class="flex justify-between gap-x-16">
                        <p>The last reply to this thread was more than six months ago. Please consider opening a new thread if you have a similar question.</p>
                        <x-buttons.arrow-button href="{{ route('threads.create') }}" class="flex-shrink-0">
                            Create thread
                        </x-buttons.arrow-button>
                    </x-info-panel>
                @else
                    <div class="my-8">
                        <form action="{{ route('replies.store') }}" method="POST">
                            @csrf

                            @formGroup('body')
                                <label for="body">Write a reply</label>

                                @include('_partials._editor', ['content' => old('body')])

                                @error('body')
                            @endFormGroup

                            <input type="hidden" name="replyable_id" value="{{ $thread->id() }}" />
                            <input type="hidden" name="replyable_type" value="threads" />

                            <div class="flex justify-between items-center mt-4">
                                <p>
                                    Please make sure you've read our <a href="{{ route('rules') }}" class="text-lio-500 border-b-2 pb-0.5 border-lio-100 hover:text-lio-600">Forum Rules</a> before replying to this thread.
                                </p>

                                <button type="submit" class="button button-primary">Reply</button>
                            </div>
                        </form>
                    </div>
                @endif
            @else
                @if (Auth::guest())
                    <p class="text-center py-8">
                        <a href="{{ route('login') }}" class="text-lio-500 border-b-2 pb-0.5 border-lio-100 hover:text-lio-600">Sign in</a> to participate in this thread!
                    </p>
                @else
                    <x-info-panel class="flex justify-between gap-x-16">
                        <p>You'll need to verify your account before participating in this thread.</p>
                        <form action="{{ route('verification.resend') }}" method="POST" class="block">
                            @csrf
                            <x-buttons.arrow-button type="submit" class="flex-shrink-0">
                                Click here to resend the verification link.
                            </x-buttons.arrow-button>
                        </form>
                    </x-info-panel>
                @endif
            @endcan
        </div>

        <div class="w-1/4">
            @include('layouts._ads._forum_sidebar')

            <x-users.profile-block :user="$thread->author()" />

            <x-moderators :moderators="$moderators" />
        </div>
    </section>
@endsection
