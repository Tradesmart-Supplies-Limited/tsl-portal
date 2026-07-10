@csrf

<div class="row g-4">
  <div class="col-md-6">
    <label class="form-label" for="name">Full name <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $client->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="company">Company</label>
    <input type="text" id="company" name="company" class="form-control @error('company') is-invalid @enderror"
           value="{{ old('company', $client->company ?? '') }}">
    @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $client->email ?? '') }}" required>
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="phone">Phone</label>
    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
           value="{{ old('phone', $client->phone ?? '') }}">
    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label" for="address">Address</label>
    <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror"
           value="{{ old('address', $client->address ?? '') }}">
    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label" for="city">City</label>
    <input type="text" id="city" name="city" class="form-control @error('city') is-invalid @enderror"
           value="{{ old('city', $client->city ?? '') }}">
    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label" for="country">Country</label>
    <input type="text" id="country" name="country" class="form-control @error('country') is-invalid @enderror"
           value="{{ old('country', $client->country ?? '') }}">
    @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
      <option value="active" @selected(old('status', $client->status ?? 'active') === 'active')>Active</option>
      <option value="inactive" @selected(old('status', $client->status ?? '') === 'inactive')>Inactive</option>
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label" for="notes">Notes</label>
    <textarea id="notes" name="notes" rows="4"
              class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $client->notes ?? '') }}</textarea>
    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-4 d-flex gap-2">
  <button type="submit" class="btn btn-primary">
    <i class="bx bx-save me-1"></i> {{ isset($client) ? 'Save changes' : 'Create client' }}
  </button>
  <a href="{{ isset($client) ? route('clients.show', $client) : route('clients.index') }}"
     class="btn btn-outline-secondary">Cancel</a>
</div>
