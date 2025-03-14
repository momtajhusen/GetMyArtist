{{-- resources/views/ArtistPanel/complete-profile.blade.php --}}
@extends('ArtistPanel.artist-layout')

@section('title', 'Complete Your Artist Profile')

@section('styles')
  <!-- BS Stepper CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
  <!-- Bootstrap Select CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
  <!-- Select2 CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
  <!-- Form Validation CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}" />
  
  <style>
    .bs-stepper-header {
      overflow-x: auto;
      white-space: nowrap;
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .bs-stepper-header::-webkit-scrollbar {
      display: none;
    }
  </style>
@endsection

@section('content')
<div class="container">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Wizard Stepper -->
  <div id="wizard-validation" class="bs-stepper mt-2">
    <div class="bs-stepper-header">
      <!-- Step 1: Stage Details -->
      <div class="step" data-target="#step-1">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-circle">1</span>
          <span class="bs-stepper-label mt-1">
            <span class="bs-stepper-title">Stage Details</span>
            <span class="bs-stepper-subtitle">Public Profile</span>
          </span>
        </button>
      </div>
      <div class="line"><i class="ti ti-chevron-right"></i></div>
      <!-- Step 2: Account Details -->
      <div class="step" data-target="#step-2">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-circle">2</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Account Details</span>
            <span class="bs-stepper-subtitle">Contact Info</span>
          </span>
        </button>
      </div>
      <div class="line"><i class="ti ti-chevron-right"></i></div>
      <!-- Step 3: Category -->
      <div class="step" data-target="#step-3">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-circle">3</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Category</span>
            <span class="bs-stepper-subtitle">Select Category</span>
          </span>
        </button>
      </div>
      <div class="line"><i class="ti ti-chevron-right"></i></div>
      <!-- Step 4: Genre -->
      {{-- <div class="step" data-target="#step-4">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-circle">4</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Genre</span>
            <span class="bs-stepper-subtitle">Select Genre(s)</span>
          </span>
        </button>
      </div>
      <div class="line"><i class="ti ti-chevron-right"></i></div> --}}
      <!-- Step 5: Events -->
      <div class="step" data-target="#step-5">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-circle">5</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Events</span>
            <span class="bs-stepper-subtitle">Select Event(s)</span>
          </span>
        </button>
      </div>
      <!-- Step 6: OTP (optional) -->
      {{-- <div class="line"><i class="ti ti-chevron-right"></i></div>
      <div class="step" data-target="#step-6">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-circle">6</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">OTP</span>
            <span class="bs-stepper-subtitle">Enter OTP</span>
          </span>
        </button>
      </div> --}}
    </div>

    <form class="needs-validation" novalidate action="{{ route('artist.storeProfile') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="bs-stepper-content">
        <!-- ===================== STEP 1 CONTENT ===================== -->
        <div id="step-1"
             class="bs-stepper-pane content"
             role="tabpanel"
             aria-labelledby="step-1-trigger"
        >
          <div class="row">
            <div class="mb-3 col-md-8">
              <label class="form-label" for="bs-validation-name">Stage Name</label>
                <input 
                  type="text" 
                  name="stage_name" 
                  id="bs-validation-name"
                  class="form-control" 
                  placeholder="Enter your stage name" 
                  required
                  value="{{ old('stage_name', $artist->stage_name ?? '') }}"
                >
                <div class="invalid-feedback"> Please enter your name. </div>
            </div>

            <div class="mb-3 col-md-4">
              <label class="form-label" for="profile_photo">Profile Photo</label>
              <input
                type="file"
                name="profile_photo"
                id="profile_photo"
                class="form-control"
                {{-- required --}}
              >
              <div class="invalid-feedback"> Please enter your name. </div>
            </div>
          </div>



          <div class="form-check mb-3 pl-3">
            <input
              type="checkbox"
              name="is_premium"
              id="is_premium"
              class="form-check-input"
              value="1"
              {{ old('is_premium', $artist->is_premium ?? false) ? 'checked' : '' }}
              required
            >
            <label class="form-check-label" for="is_premium">Premium Artist</label>
          </div>

          <!-- NEW FIELDS (Booking Rate, Location, Awards, is_verified) -->
          <div class="mb-3 row">
            <div class="col-md-6">
              <label class="form-label" for="booking_rate">Booking Rate</label>
                <input
                  type="number"
                  step="0.01"
                  name="booking_rate"
                  id="booking_rate"
                  class="form-control"
                  placeholder="e.g., 5000.00"
                  value="{{ old('booking_rate', $artist->booking_rate ?? '') }}"
                  required
                >
                <div class="invalid-feedback"> Please enter your booking rate. </div>
            </div>

            <div class="col-md-6">
              <label class="form-label" for="location">Location</label>
              <input
                type="text"
                name="location"
                id="location"
                class="form-control"
                placeholder="Your city or address"
                value="{{ old('location', $artist->location ?? '') }}"
                required
              >
              <div class="invalid-feedback"> Please enter your location. </div>
            </div>
          </div>
          <!-- End New Fields -->

          <div class="mb-3">
            <label class="form-label" for="bio">Bio</label>
            <textarea
              name="bio"
              id="bio"
              class="form-control"
              rows="3"
              placeholder="Enter your bio"
              required
            >{{ old('bio', $artist->bio ?? '') }}</textarea>
            <div class="invalid-feedback"> Please enter your bio. </div>
          </div>

          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary btn-next">
              Next <i class="ti ti-arrow-right"></i>
            </button>
          </div>
        </div>
        <!-- End Step 1 -->


        <!-- ===================== STEP 2 CONTENT ===================== -->
        <div id="step-2"
             class="bs-stepper-pane content"
             role="tabpanel"
             aria-labelledby="step-2-trigger"
        >
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label" for="profile_managed_by">Profile Managed By</label>
              <select name="profile_managed_by" id="profile_managed_by" class="form-select">
                <option value="artist"
                  {{ old('profile_managed_by', $artist->profile_managed_by ?? 'artist') == 'artist' ? 'selected' : '' }}
                >
                  Artist
                </option>
                <option value="manager"
                  {{ old('profile_managed_by', $artist->profile_managed_by ?? '') == 'manager' ? 'selected' : '' }}
                >
                  Manager
                </option>
                <option value="agency"
                  {{ old('profile_managed_by', $artist->profile_managed_by ?? '') == 'agency' ? 'selected' : '' }}
                >
                  Agency
                </option>
                <option value="family"
                  {{ old('profile_managed_by', $artist->profile_managed_by ?? '') == 'family' ? 'selected' : '' }}
                >
                  Family
                </option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label" for="contact_first_name">Contact First Name</label>
              <input
                type="text"
                name="contact_first_name"
                id="contact_first_name"
                class="form-control"
                value="{{ old('contact_first_name', $artist->contact_first_name ?? '') }}"
                required
              >
              <div class="invalid-feedback"> Please enter your first name. </div>
            </div>

            <div class="col-md-4">
              <label class="form-label" for="contact_last_name">Contact Last Name</label>
              <input
                type="text"
                name="contact_last_name"
                id="contact_last_name"
                class="form-control"
                value="{{ old('contact_last_name', $artist->contact_last_name ?? '') }}"
                required
              >
              <div class="invalid-feedback"> Please enter your last name. </div>
            </div>

            <div class="col-md-6">
              <label class="form-label" for="phone">Phone Number</label>
                <input
                  type="text"
                  name="phone"
                  id="phone"
                  class="form-control"
                  placeholder="Enter Phone Number"
                  value="{{ old('phone') }}"
                  required
                >
                <div class="invalid-feedback"> Please enter your phone number. </div>
            </div>

            <div class="col-md-6">
              <label class="form-label" for="bs-validation-email">Email</label>
                <input
                  type="email"
                  name="email"
                  id="bs-validation-email"
                  class="form-control"
                  placeholder="yourname@example.com"
                  value="{{ old('email') }}"
                  required
                >
                <div class="invalid-feedback"> Please enter a valid email </div>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-label-secondary btn-prev">
              <i class="ti ti-arrow-left"></i> Previous
            </button>
            <button type="button" class="btn btn-primary btn-next">
              Next <i class="ti ti-arrow-right"></i>
            </button>
          </div>
        </div>
        <!-- End Step 2 -->


        <!-- ===================== STEP 3 CONTENT ===================== -->
        <div id="step-3"
             class="bs-stepper-pane content"
             role="tabpanel"
             aria-labelledby="step-3-trigger"
        >
          <div class="row">
            <div class="mb-3 col-md-6">
              <label class="form-label" for="category_id">Select Your Category</label>
              <select name="category_id" id="category_id" class="form-select">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                  <option
                    value="{{ $category->id }}"
                    required
                    {{ old('category_id', $artist->category_id ?? '') == $category->id ? 'selected' : '' }}
                  >
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
              <small class="form-text text-muted">An artist can be registered with only one category.</small>
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="experience_years">Experience (Years)</label>
              <input
                type="number"
                name="experience_years"
                id="experience_years"
                class="form-control"
                placeholder="Enter years of experience"
                value="{{ old('experience_years', $artist->experience_years ?? '') }}"
                required
              >
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Select Your Genre</label>
            {{-- <p class="form-text text-muted">You can change these later from your dashboard.</p> --}}
            <!-- Example checkboxes; loop over DB genres if you have dynamic data -->
            <div class="form-check">
              <input
                type="checkbox"
                name="genre[]"
                class="form-check-input"
                id="genre1"
                value="Rock"
                @if(is_array(old('genre', $artist->genre ?? [])) && in_array('Rock', old('genre', $artist->genre ?? []))) checked @endif
                required
              >
              <label class="form-check-label" for="genre1">Rock</label>
            </div>

            <div class="form-check">
              <input
                type="checkbox"
                name="genre[]"
                class="form-check-input"
                id="genre2"
                value="Pop"
                required
                @if(is_array(old('genre', $artist->genre ?? [])) && in_array('Pop', old('genre', $artist->genre ?? []))) checked @endif
              >
              <label class="form-check-label" for="genre2">Pop</label>
            </div>
            <!-- ... add more as needed ... -->
          </div>

          <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-label-secondary btn-prev">
              <i class="ti ti-arrow-left"></i> Previous
            </button>
            <button type="button" class="btn btn-primary btn-next">
              Next <i class="ti ti-arrow-right"></i>
            </button>
          </div>
        </div>
        <!-- End Step 3 -->


        <!-- ===================== STEP 4 CONTENT ===================== -->
        <div id="step-4"
             class="bs-stepper-pane content"
             role="tabpanel"
             aria-labelledby="step-4-trigger"
        >
          
          <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-label-secondary btn-prev">
              <i class="ti ti-arrow-left"></i> Previous
            </button>
            <button type="button" class="btn btn-primary btn-next">
              Next <i class="ti ti-arrow-right"></i>
            </button>
          </div>
        </div>
        <!-- End Step 4 -->


        <!-- ===================== STEP 5 CONTENT ===================== -->
        <div id="step-5"
             class="bs-stepper-pane content"
             role="tabpanel"
             aria-labelledby="step-5-trigger"
        >
          <div class="mb-3">
            <label class="form-label">Select Your Events</label>
            <p class="form-text text-muted">You can change these later from your dashboard.</p>

            <div class="form-check">
              <input
                type="checkbox"
                name="events[]"
                class="form-check-input"
                id="event1"
                value="Concert"
                @if(is_array(old('events', $artist->events ?? [])) && in_array('Concert', old('events', $artist->events ?? []))) checked @endif
              >
              <label class="form-check-label" for="event1">Concert</label>
            </div>

            <div class="form-check">
              <input
                type="checkbox"
                name="events[]"
                class="form-check-input"
                id="event2"
                value="Festival"
                @if(is_array(old('events', $artist->events ?? [])) && in_array('Festival', old('events', $artist->events ?? []))) checked @endif
              >
              <label class="form-check-label" for="event2">Festival</label>
            </div>
            <!-- ... add more as needed ... -->
          </div>

          <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-label-secondary btn-prev">
              <i class="ti ti-arrow-left"></i> Previous
            </button>
            <button type="submit" class="btn btn-success">
              Submit Profile <i class="ti ti-check"></i>
            </button>
          </div>
        </div>
        <!-- End Step 5 -->

      </div>
    </form>
  </div>
</div>

<!-- Initialize the Stepper and Button Handlers -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var stepper = new Stepper(document.querySelector('#wizard-validation'));

    document.querySelectorAll('.btn-next').forEach(button => {
      button.addEventListener('click', function(event) {
        let currentStep = button.closest('.bs-stepper-pane'); 
        let inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
          if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
          } else {
            input.classList.remove('is-invalid');
          }
        });

        if (!isValid) {
          event.preventDefault();
        } else {
          stepper.next(); 
        }
      });
    });

    document.querySelectorAll('.btn-prev').forEach(button => {
      button.addEventListener('click', function() {
        stepper.previous();
      });
    });
  });
</script>


@endsection

@section('scripts')
  <!-- BS-Stepper JS -->
  <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
  <!-- Bootstrap Select JS -->
  <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
  <!-- Select2 JS -->
  <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
  <!-- Form Validation JS -->
  <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
  <!-- Custom Wizard Scripts (if any) -->
  <script src="{{ asset('assets/js/form-wizard-numbered.js') }}"></script>
  <script src="{{ asset('assets/js/form-validation.js') }}"></script>
  
  
@endsection
