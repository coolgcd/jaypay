<form method="POST" action="{{ route('member.register.submit') }}">
    @csrf
    <input type="text" name="sponsorid" placeholder="Sponsor ID" required onblur="fetchSponsorName(this.value)">
    <input type="text" name="sponsor_name" id="sponsor_name" placeholder="Sponsor Name" readonly>

    <select name="position" required>
        <option value="">Select Position</option>
        <option value="left">Left</option>
        <option value="right">Right</option>
    </select>

    <input type="text" name="name" placeholder="Full Name" required>
    <input type="text" name="mobileno" placeholder="Mobile Number" required>
    <input type="email" name="emailid" placeholder="Email Address" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

    <button type="submit">Register</button>
</form>

<script>
function fetchSponsorName(id) {
    fetch('/get-sponsor-name', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ sponsorid: id })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('sponsor_name').value = data.name || 'Invalid ID';
    });
}
</script>
