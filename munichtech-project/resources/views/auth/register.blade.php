@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">Register</div>
            <div class="card-body">

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row g-3">

                        {{--name--}}
                        <div class="col-md-6">
                            <label class="form-label">Full name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{--email--}}
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{--role--}}
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                                @endforeach
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{--company--}}
                        <div class="col-md-6">
                            <label class="form-label">Enterprise / Startup</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control @error('company_name') is-invalid @enderror">
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{--phone with country prefix--}}
                        <div class="col-md-6">
                            <label class="form-label">Phone number</label>
                            <div class="input-group">
                                <select name="phone_prefix" class="form-select" style="max-width: 130px;">
                                    @php
                                    $prefixes = [
                                        ['code' => '+49',  'flag' => '🇩🇪', 'name' => 'DE'],
                                        ['code' => '+1',   'flag' => '🇺🇸', 'name' => 'US'],
                                        ['code' => '+44',  'flag' => '🇬🇧', 'name' => 'GB'],
                                        ['code' => '+33',  'flag' => '🇫🇷', 'name' => 'FR'],
                                        ['code' => '+34',  'flag' => '🇪🇸', 'name' => 'ES'],
                                        ['code' => '+39',  'flag' => '🇮🇹', 'name' => 'IT'],
                                        ['code' => '+31',  'flag' => '🇳🇱', 'name' => 'NL'],
                                        ['code' => '+41',  'flag' => '🇨🇭', 'name' => 'CH'],
                                        ['code' => '+43',  'flag' => '🇦🇹', 'name' => 'AT'],
                                        ['code' => '+32',  'flag' => '🇧🇪', 'name' => 'BE'],
                                        ['code' => '+351', 'flag' => '🇵🇹', 'name' => 'PT'],
                                        ['code' => '+48',  'flag' => '🇵🇱', 'name' => 'PL'],
                                        ['code' => '+46',  'flag' => '🇸🇪', 'name' => 'SE'],
                                        ['code' => '+47',  'flag' => '🇳🇴', 'name' => 'NO'],
                                        ['code' => '+45',  'flag' => '🇩🇰', 'name' => 'DK'],
                                        ['code' => '+358', 'flag' => '🇫🇮', 'name' => 'FI'],
                                        ['code' => '+420', 'flag' => '🇨🇿', 'name' => 'CZ'],
                                        ['code' => '+36',  'flag' => '🇭🇺', 'name' => 'HU'],
                                        ['code' => '+40',  'flag' => '🇷🇴', 'name' => 'RO'],
                                        ['code' => '+30',  'flag' => '🇬🇷', 'name' => 'GR'],
                                        ['code' => '+380', 'flag' => '🇺🇦', 'name' => 'UA'],
                                        ['code' => '+7',   'flag' => '🇷🇺', 'name' => 'RU'],
                                        ['code' => '+90',  'flag' => '🇹🇷', 'name' => 'TR'],
                                        ['code' => '+972', 'flag' => '🇮🇱', 'name' => 'IL'],
                                        ['code' => '+971', 'flag' => '🇦🇪', 'name' => 'AE'],
                                        ['code' => '+966', 'flag' => '🇸🇦', 'name' => 'SA'],
                                        ['code' => '+91',  'flag' => '🇮🇳', 'name' => 'IN'],
                                        ['code' => '+86',  'flag' => '🇨🇳', 'name' => 'CN'],
                                        ['code' => '+81',  'flag' => '🇯🇵', 'name' => 'JP'],
                                        ['code' => '+82',  'flag' => '🇰🇷', 'name' => 'KR'],
                                        ['code' => '+65',  'flag' => '🇸🇬', 'name' => 'SG'],
                                        ['code' => '+61',  'flag' => '🇦🇺', 'name' => 'AU'],
                                        ['code' => '+55',  'flag' => '🇧🇷', 'name' => 'BR'],
                                        ['code' => '+52',  'flag' => '🇲🇽', 'name' => 'MX'],
                                        ['code' => '+54',  'flag' => '🇦🇷', 'name' => 'AR'],
                                        ['code' => '+56',  'flag' => '🇨🇱', 'name' => 'CL'],
                                        ['code' => '+57',  'flag' => '🇨🇴', 'name' => 'CO'],
                                        ['code' => '+27',  'flag' => '🇿🇦', 'name' => 'ZA'],
                                        ['code' => '+20',  'flag' => '🇪🇬', 'name' => 'EG'],
                                        ['code' => '+212', 'flag' => '🇲🇦', 'name' => 'MA'],
                                        ['code' => '+234', 'flag' => '🇳🇬', 'name' => 'NG'],
                                    ];
                                    $oldPrefix = old('phone_prefix', '+49');
                                    @endphp
                                    @foreach($prefixes as $p)
                                        <option value="{{ $p['code'] }}" @selected($oldPrefix === $p['code'])>
                                            {{ $p['flag'] }} {{ $p['name'] }} {{ $p['code'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="123 456 789">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{--interests--}}
                        <div class="col-md-6">
                            <label class="form-label">Interests</label>
                            <input type="text" name="interests" value="{{ old('interests') }}"
                                class="form-control @error('interests') is-invalid @enderror"
                                placeholder="AI, IoT, Fintech, Health...">
                            @error('interests')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{--password--}}
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{--confirm password--}}
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        {{--bio--}}
                        <div class="col-12">
                            <label class="form-label">Professional Description</label>
                            <textarea name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror">{{ old('bio') }}</textarea>
                            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>
                    <button class="btn btn-success mt-4 w-100">Create account</button>
                </form>

                <div class="d-flex align-items-center my-3">
                    <hr class="flex-grow-1"><span class="mx-3 text-muted small">or continue with</span><hr class="flex-grow-1">
                </div>

                <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    Sign in with Google
                </a>

                <p class="text-center text-muted small mt-3 mb-0">
                    Already have an account? <a href="{{ route('login') }}">Log in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
