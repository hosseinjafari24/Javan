@extends('layouts.app')

@section('content')
	<main class="container main">
		@include('partials.carousel')
		<article>
			<div class="col-md-8">
				<a href="{{ route('menu') }}" class="btn btn-success btn-raised btn-round btn-lg btn-block">
					Order Food Online
				</a>
				<a href="{{ auth()->check() ? route('member.bookings') : route('create.reservation') }}"
				   class="btn btn-danger btn-raised btn-round btn-lg btn-block">
					Reserve Table Online
				</a>
				<a href="{{ auth()->check() ? route('member.bookings') : route('create.reservation') }}">
					<img src="{{ url('/images/Xmas.jpg') }}" alt="Xmas Booking" width="100%" class="img-rounded">
				</a>
				<h2 class="hidden-xs">Javan's Opening Hours</h2>
				<h3 class="visible-xs">Javan's Opening Hours</h3>
				<dl class="dl-horizontal">
					<dt>Monday</dt><dd>12:00 - 23:00</dd>
					<dt>Tuesday</dt><dd>12:00 - 23:00</dd>
					<dt>Wednesday</dt><dd>12:00 - 23:00</dd>
					<dt>Thursday</dt><dd>12:00 - 23:00</dd>
					<dt>Friday</dt><dd>12:00 - 23:30</dd>
					<dt>Saturday</dt><dd>12:00 - 23:30</dd>
					<dt>Sunday</dt><dd>12:00 - 23:00</dd>
				</dl>
				{{--
				<h2 class="hidden-xs">Coco Cokctail's Opening Hours</h2>
				<h3 class="visible-xs">Coco Cokctail's Opening Hours</h3>
				<dl class="dl-horizontal">
					<dt>Friday</dt><dd>19:00 - 00:00</dd>
					<dt>Saturday</dt><dd>19:00 - 00:00</dd>
					<dt>Sunday</dt><dd>19:00 - 23:00</dd>
				</dl>
				--}}
				<h2 class="hidden-xs">Contact Number</h2>
				<h3 class="visible-xs">Contact Number</h3>
				<p class="text-justify">
					020 8563 8553
				</p>
			</div>
		</article>
		<aside>
			<div class="col-md-4">
				<p class="bbcnassim pull-right text-right text-danger large-text">حلال</p>

				<div class="card card-nav-tabs card-plain">
					<div class="header header-primary">
						<div class="nav-tabs-navigation">
							<div class="nav-tabs-wrapper">
								<ul class="nav nav-tabs" data-tabs="tabs">
									<li class="active">
										<a href="#instagram" data-toggle="tab">
											<i class="fa fa-instagram fa-2x fa-fw"></i>
										</a>
									</li>
									<li>
										<a href="#tweets" data-toggle="tab">
											<i class="fa fa-twitter fa-2x fa-fw"></i>
										</a>
									</li>
									<li>
										<a href="#facebook" data-toggle="tab">
											<i class="fa fa-facebook fa-2x fa-fw"></i>
										</a>
									</li>
									<li>
										<a href="#google" data-toggle="tab">
											<i class="fa fa-google-plus fa-2x fa-fw"></i>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="content">
						<div class="tab-content text-center">
							<div class="tab-pane active" id="instagram">
								@foreach (array_chunk($images, 2) as $row)
									<div class="row">
										@foreach ($row as $image)
											<div class="col-sm-6 col-xs-12">
												<a href="{{ $image['images']['standard_resolution']['url'] }}" data-lity>
													<img class=" img-space img-rounded img-raised"
													     src="{{ $image['images']['thumbnail']['url'] }}"
													     alt="{{ $image['link'] }}">
												</a>
											</div>
										@endforeach
									</div>
								@endforeach
							</div>
							<div class="tab-pane" id="tweets">
								@include('partials.twitter')
							</div>
							<div class="tab-pane" id="facebook">
								@include('partials.facebook')
							</div>
							<div class="tab-pane" id="google">
								@include('partials.google+')
							</div>
						</div>
					</div>
				</div>
			</div>
		</aside>
	</main>
@endsection

