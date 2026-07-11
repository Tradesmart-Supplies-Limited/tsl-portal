@php($client = $client ?? null)
@csrf

<div class="row g-4">
  <!-- Logo -->
  <div class="col-12">
    <label class="form-label d-block">Client logo</label>
    <div class="d-flex align-items-center gap-3">
      <img
        id="logoPreview"
        src="{{ optional($client)->logo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($client->name ?? 'New+Client') . '&background=696cff&color=fff' }}"
        alt="Client logo"
        class="rounded"
        style="width: 72px; height: 72px; object-fit: cover;"
      />
      <div>
        <input type="file" name="logo" id="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
        <small class="text-muted">PNG or JPG, up to 2MB.</small>
        @error('logo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
      </div>
    </div>
  </div>

  <div class="col-12"><hr class="my-0"></div>

  <!-- Basic info -->
  <div class="col-md-6">
    <label class="form-label" for="name">Client / contact name <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $client->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="client_type">Client type <span class="text-danger">*</span></label>
    <select id="client_type" name="client_type" class="form-select @error('client_type') is-invalid @enderror" required>
      <option value="company" @selected(old('client_type', $client->client_type ?? 'company') === 'company')>Company</option>
      <option value="individual" @selected(old('client_type', $client->client_type ?? '') === 'individual')>Individual</option>
    </select>
    @error('client_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="company">Company name</label>
    <input type="text" id="company" name="company" class="form-control @error('company') is-invalid @enderror"
           value="{{ old('company', $client->company ?? '') }}">
    @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="industry">Industry</label>
    <input type="text" id="industry" name="industry" class="form-control @error('industry') is-invalid @enderror"
           value="{{ old('industry', $client->industry ?? '') }}">
    @error('industry') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="website">Website</label>
    <input type="url" id="website" name="website" class="form-control @error('website') is-invalid @enderror"
           placeholder="https://" value="{{ old('website', $client->website ?? '') }}">
    @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="tax_id">Tax / registration ID</label>
    <input type="text" id="tax_id" name="tax_id" class="form-control @error('tax_id') is-invalid @enderror"
           value="{{ old('tax_id', $client->tax_id ?? '') }}">
    @error('tax_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12"><hr class="my-0"></div>

  <!-- Contact details -->
  <div class="col-md-6">
    <label class="form-label" for="email">Primary email <span class="text-danger">*</span></label>
    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $client->email ?? '') }}" required>
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="secondary_email">Secondary email</label>
    <input type="email" id="secondary_email" name="secondary_email" class="form-control @error('secondary_email') is-invalid @enderror"
           value="{{ old('secondary_email', $client->secondary_email ?? '') }}">
    @error('secondary_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="phone">Primary phone</label>
    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
           value="{{ old('phone', $client->phone ?? '') }}">
    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="secondary_phone">Secondary phone</label>
    <input type="text" id="secondary_phone" name="secondary_phone" class="form-control @error('secondary_phone') is-invalid @enderror"
           value="{{ old('secondary_phone', $client->secondary_phone ?? '') }}">
    @error('secondary_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12"><hr class="my-0"></div>

  <!-- Address -->
  <div class="col-md-6">
    <label class="form-label" for="address">Address</label>
    <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror"
           value="{{ old('address', $client->address ?? '') }}">
    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label" for="city">City</label>
    <input type="text" id="city" name="city" class="form-control @error('city') is-invalid @enderror"
           value="{{ old('city', $client->city ?? '') }}">
    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label" for="postal_code">Postal code</label>
    <input type="text" id="postal_code" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror"
           value="{{ old('postal_code', $client->postal_code ?? '') }}">
    @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label" for="country">Country</label>
    <input type="text" id="country" name="country" class="form-control @error('country') is-invalid @enderror"
           value="{{ old('country', $client->country ?? '') }}">
    @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12"><hr class="my-0"></div>

  <!-- Account handling -->
  <div class="col-md-4">
    <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
      <option value="active" @selected(old('status', $client->status ?? 'active') === 'active')>Active</option>
      <option value="inactive" @selected(old('status', $client->status ?? '') === 'inactive')>Inactive</option>
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label" for="account_manager_id">Account manager</label>
    <select id="account_manager_id" name="account_manager_id" class="form-select @error('account_manager_id') is-invalid @enderror">
      <option value="">Unassigned</option>
      @foreach ($accountManagers as $manager)
        <option value="{{ $manager->id }}" @selected(old('account_manager_id', $client->account_manager_id ?? '') == $manager->id)>
          {{ $manager->name }}
        </option>
      @endforeach
    </select>
    @error('account_manager_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label" for="source">Source</label>
    <input type="text" id="source" name="source" class="form-control @error('source') is-invalid @enderror"
           placeholder="e.g. Referral, Website" value="{{ old('source', $client->source ?? '') }}">
    @error('source') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label" for="notes">Notes</label>
    <textarea id="notes" name="notes" rows="3"
              class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $client->notes ?? '') }}</textarea>
    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12"><hr class="my-0"></div>

  <!-- Contact persons repeater -->
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <label class="form-label mb-0">Contact persons / team members</label>
      <button type="button" id="addContactRow" class="btn btn-sm btn-outline-primary">
        <i class="bx bx-plus me-1"></i> Add contact
      </button>
    </div>
    <small class="text-muted d-block mb-2">Mark one as primary — that's who we'll reach out to by default.</small>

    <div id="contactRows">
      @php
        $existingContacts = old('contacts.name', (optional($client)->contacts ?? collect())->pluck('name')->all());
        $oldPrimary = old('contacts.primary_index');
      @endphp

      @forelse ((optional($client)->contacts ?? collect()) as $i => $contact)
        <div class="row g-2 align-items-center mb-2 contact-row">
          <div class="col-md-3">
            <input type="text" name="contacts[name][]" class="form-control form-control-sm" placeholder="Name" value="{{ old("contacts.name.$i", $contact->name) }}">
          </div>
          <div class="col-md-3">
            <input type="text" name="contacts[job_title][]" class="form-control form-control-sm" placeholder="Job title" value="{{ old("contacts.job_title.$i", $contact->job_title) }}">
          </div>
          <div class="col-md-3">
            <input type="email" name="contacts[email][]" class="form-control form-control-sm" placeholder="Email" value="{{ old("contacts.email.$i", $contact->email) }}">
          </div>
          <div class="col-md-2">
            <input type="text" name="contacts[phone][]" class="form-control form-control-sm" placeholder="Phone" value="{{ old("contacts.phone.$i", $contact->phone) }}">
          </div>
          <div class="col-md-1 d-flex align-items-center gap-1">
            <input type="radio" name="contacts[primary_index]" value="{{ $i }}" class="form-check-input" @checked($contact->is_primary) title="Primary contact">
            <button type="button" class="btn btn-sm btn-text-danger remove-contact-row"><i class="bx bx-trash"></i></button>
          </div>
        </div>
      @empty
        <div class="row g-2 align-items-center mb-2 contact-row">
          <div class="col-md-3">
            <input type="text" name="contacts[name][]" class="form-control form-control-sm" placeholder="Name">
          </div>
          <div class="col-md-3">
            <input type="text" name="contacts[job_title][]" class="form-control form-control-sm" placeholder="Job title">
          </div>
          <div class="col-md-3">
            <input type="email" name="contacts[email][]" class="form-control form-control-sm" placeholder="Email">
          </div>
          <div class="col-md-2">
            <input type="text" name="contacts[phone][]" class="form-control form-control-sm" placeholder="Phone">
          </div>
          <div class="col-md-1 d-flex align-items-center gap-1">
            <input type="radio" name="contacts[primary_index]" value="0" class="form-check-input" checked title="Primary contact">
            <button type="button" class="btn btn-sm btn-text-danger remove-contact-row"><i class="bx bx-trash"></i></button>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</div>

<div class="mt-4 d-flex gap-2">
  <button type="submit" class="btn btn-primary">
    <i class="bx bx-save me-1"></i> {{ $client ? 'Save changes' : 'Create client' }}
  </button>
  <a href="{{ $client ? route('clients.show', $client) : route('clients.index') }}"
     class="btn btn-outline-secondary">Cancel</a>
</div>

<script>
  (function () {
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logoPreview');
    if (logoInput) {
      logoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
          logoPreview.src = URL.createObjectURL(file);
        }
      });
    }

    const rows = document.getElementById('contactRows');
    let rowIndex = document.querySelectorAll('.contact-row').length;

    document.getElementById('addContactRow').addEventListener('click', function () {
      const row = document.createElement('div');
      row.className = 'row g-2 align-items-center mb-2 contact-row';
      row.innerHTML = `
        <div class="col-md-3"><input type="text" name="contacts[name][]" class="form-control form-control-sm" placeholder="Name"></div>
        <div class="col-md-3"><input type="text" name="contacts[job_title][]" class="form-control form-control-sm" placeholder="Job title"></div>
        <div class="col-md-3"><input type="email" name="contacts[email][]" class="form-control form-control-sm" placeholder="Email"></div>
        <div class="col-md-2"><input type="text" name="contacts[phone][]" class="form-control form-control-sm" placeholder="Phone"></div>
        <div class="col-md-1 d-flex align-items-center gap-1">
          <input type="radio" name="contacts[primary_index]" value="${rowIndex}" class="form-check-input" title="Primary contact">
          <button type="button" class="btn btn-sm btn-text-danger remove-contact-row"><i class="bx bx-trash"></i></button>
        </div>`;
      rows.appendChild(row);
      rowIndex++;
    });

    rows.addEventListener('click', function (e) {
      if (e.target.closest('.remove-contact-row')) {
        const row = e.target.closest('.contact-row');
        if (document.querySelectorAll('.contact-row').length > 1) {
          row.remove();
        } else {
          row.querySelectorAll('input').forEach(input => input.value = '');
        }
      }
    });
  })();
</script>
