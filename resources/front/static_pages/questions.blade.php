@extends('front.index')

@section('page_title', 'تواصل معنا')

@section('front-section')
    <div class="question-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="all-question">
                        @forelse ($questions as $question)
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="accordion-item">
                                    {{--                                    <div class="accordion-title"> {{$question->title}}</div>--}}
                                    <div class="accordion-title"> {!! translateWithHTMLTags($question->title) !!}</div>
                                    <div class="accordion-content">
                                        <p>
                                            {!! translateWithHTMLTags($question->description) !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>no question exist</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
