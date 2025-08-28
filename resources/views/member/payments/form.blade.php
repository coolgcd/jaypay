@extends('member.layout')
@section('content')
<style>
    .payment-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .payment-wrapper {
        display: flex;
        gap: 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        min-height: 600px;
    }

    .form-section {
        flex: 1;
        padding: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .form-section h2 {
        margin: 0 0 30px 0;
        font-size: 28px;
        font-weight: 600;
        text-align: center;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        font-size: 16px;
    }

    .form-group select,
    .form-group input[type="number"],
    .form-group input[type="file"] {
        width: 100%;
        padding: 12px 15px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        background: rgba(255, 255, 255, 0.95);
        color: #333;
        transition: all 0.3s ease;
    }

    .form-group select:focus,
    .form-group input:focus {
        outline: none;
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }

    .form-group select option {
        color: #333;
        background: white;
    }

    .submit-btn {
        width: 100%;
        padding: 15px;
        background: #ff6b6b;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
    }

    .submit-btn:hover {
        background: #ee5a52;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    }

    .info-section {
        flex: 1;
        padding: 40px;
        background: #f8f9fc;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .qr-container {
        text-align: center;
        margin-bottom: 40px;
    }

    .qr-container h3 {
        color: #333;
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: 600;
    }

    .qr-code {
        width: 240px;
        height: 240px;
        border: 3px solid #667eea;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .bank-info {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 350px;
    }

    .bank-info h3 {
        color: #333;
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
        border-bottom: 2px solid #667eea;
        padding-bottom: 10px;
    }

    .bank-detail {
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
    }

    .bank-detail strong {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .bank-detail span {
        color: #555;
        font-size: 14px;
        word-break: break-all;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .payment-container {
            padding: 10px;
        }

        .payment-wrapper {
            flex-direction: column;
            gap: 0;
        }

        .form-section,
        .info-section {
            padding: 30px 20px;
        }

        .form-section h2 {
            font-size: 24px;
        }

        .qr-code {
            width: 190px;
            height: 190px;
        }

        .bank-info {
            padding: 20px;
        }

        .bank-info h3 {
            font-size: 18px;
        }
    }

    @media (max-width: 480px) {

        .form-section,
        .info-section {
            padding: 20px 15px;
        }

        .form-section h2 {
            font-size: 20px;
        }

        .form-group label {
            font-size: 14px;
        }

        .form-group select,
        .form-group input {
            padding: 10px 12px;
            font-size: 14px;
        }

        .submit-btn {
            font-size: 16px;
            padding: 12px;
        }

        .qr-code {
            width: 170px;
            height: 170px;
        }

        .qr-container h3 {
            font-size: 18px;
        }

        .bank-detail span {
            font-size: 13px;
        }
    }

    /* Animation for form loading */
    .payment-wrapper {
        animation: slideIn 0.6s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* File input custom styling */
    .form-group input[type="file"] {
        padding: 8px;
        background: rgba(255, 255, 255, 0.95);
        cursor: pointer;
    }

    .form-group input[type="file"]::-webkit-file-upload-button {
        background: #667eea;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
        font-size: 14px;
    }

    .form-group input[type="file"]::-webkit-file-upload-button:hover {
        background: #5a67d8;
    }
</style>

<div class="payment-container">
    <div class="payment-wrapper">
        <!-- Form Section -->
        <div class="form-section">
            <h2>Payment Details</h2>
            <form method="POST" action="{{ route('member.payment.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="package_amount">Package Amount:</label>
                    <select name="package_amount" id="package_amount" required>
                        <option value="">Select Package</option>
                        <option value="1000">₹ 1,000</option>
                        <option value="5000">₹ 5,000</option>
                        <option value="10000">₹ 10,000</option>
                        <option value="50000">₹ 50,000</option>
                        <option value="100000">₹ 100,000</option>

                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" min="1" max="10" required placeholder="Enter quantity">
                </div>
                <div class="form-group">
                    <label for="final_amount">Total with GST (18%):</label>
                    <input type="text" id="final_amount" readonly placeholder="₹0">
                </div>


                <div class="form-group">
                    <label for="screenshot">Payment Screenshot:</label>
                    <input type="file" name="screenshot" id="screenshot" accept="image/*" required>
                </div>

                <button type="submit" class="submit-btn">Submit Payment</button>
            </form>
        </div>

        <!-- QR Code and Bank Info Section -->
        <div class="info-section">
            <div class="qr-container">
                <h3>Scan QR Code</h3>
                <img src="{{ asset('assets/images/qr_code.jpeg') }}" alt="Payment QR Code" class="qr-code">
            </div>

            <div class="bank-info">
                <h3>Bank Information for Deposit</h3>

                <div class="bank-detail">
                    <strong>Bank Name:</strong>
                    <span>Axis Bank Ltd</span>
                </div>

                <div class="bank-detail">
                    <strong>Branch Location:</strong>
                    <span>Mirzapur (UP), Mirzapur, 231001</span>
                </div>

                <div class="bank-detail">
                    <strong>IFSC Code:</strong>
                    <span>UTIB0000506</span>
                </div>

                <div class="bank-detail">
                    <strong>Account Number:</strong>
                    <span>923020051181891</span>
                </div>

                <div class="bank-detail">
                    <strong>Account Holder Name:</strong>
                    <span>JAI HO INFRA PRIVATE LIMITED</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function calculateFinalAmount() {
        const packageAmount = parseInt(document.getElementById('package_amount').value) || 0;
        const quantity = parseInt(document.getElementById('quantity').value) || 0;

        const baseTotal = packageAmount * quantity;
        const gst = baseTotal * 0.18;
        const finalAmount = baseTotal + gst;

        document.getElementById('final_amount').value = '₹' + finalAmount.toFixed(2);
    }

    document.getElementById('package_amount').addEventListener('change', calculateFinalAmount);
    document.getElementById('quantity').addEventListener('input', calculateFinalAmount);
</script>

@endsection