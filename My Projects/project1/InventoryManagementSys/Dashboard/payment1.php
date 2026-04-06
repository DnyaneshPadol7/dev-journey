<?php

session_start();
if (isset($_GET['balance'])) {
    $remainingBalance = $_GET['balance']; // This will get the balance passed in the URL
} else {
    $remainingBalance = '0.00'; // Default if no balance is passed
}

// --- PHP code at the top ---
include('../dbconnect.php');

if (isset($_GET['invoice_id'])) {
    $invoice_id = $_GET['invoice_id'];
    $sql = "SELECT invoice_id,invoice_no,invoice_date,grand_total,customer_name,mobileNo
            FROM sales_invoices
            JOIN customers ON sales_invoices.customer_id=customers.customer_id
            WHERE invoice_id='$invoice_id'";
    $result = $conn->query($sql);
    $invoice = $result->fetch_assoc();

}
?>
 <!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="payment.css">
</head>

<body>
  <div class="">
    <form action="payment.php" method="post" id="paymentForm">
      <div class="container me-">
        <div class="row mb-3 pb-3 header">
          <div class="col-4">
            <div><label for="invoice_no" class="">Receipts </label><input id="invoice_no" value="<?php echo $invoice['invoice_no']; ?>" required readonly></div>
            <input type="hidden" id="invoice_pk" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">

            <div class=""><label for="customerName">Customer</label>
              <input type="text" id="customerName" value="<?php echo $invoice['customer_name']; ?>" required readonly>
            </div>
            <div>
              <label for="customerPhone">Phone</label>
              <input id="customerPhone" value="<?php echo $invoice['mobileNo'];?>" required readonly>
            </div>
          </div>
          <div class="col-4 ">
            <div class="mt-5"><label for="totalAmount">Total Amount</label>
              <input id="totalAmount" value="<?php echo $invoice['grand_total']?>" readonly>
            </div>
            <div>
              <label for="invoiceDate" class="idate">Date</label>
              <input type="date" id="invoiceDate">
            </div>
          </div>
          <div class="col-md-4 ">
            <div class="form-check mt-5">
              <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
              <label class="form-check-label " for="flexRadioDefault1">
                Customer Payment
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
              <label class="form-check-label" for="flexRadioDefault2">
                Seller Payment
              </label>
            </div>
          </div>
        </div>
        <div class="row input-box">
          <div class="col-6">
            <div>
              <label for="paid_amount" id="lp">Paid Amount</label>
              <input type="text" class=" pamount" name="payment" id="paid_amount" placeholder="00.00" required><br>
              <label for="payment_method">Payment Method</label>
              <select name="method" id="payment_method" class="pay-method">
                <option value="cash">Cash</option>
                <option value="check">Cheque</option>
                <option value="credit-card">Credit Card</option>
                <option value="debit-card">Debit Card</option>
                <option value="bank">Bank Transfer</option>
                <option value="upi">UPI</option>
                </option>
              </select>
            </div>
          </div>
          <div class="col-6">
            <div>
              <label class="ref" for="reference_no ">Reference No</label>
              <input type="text" class="reference" name="reference" id="reference_no" placeholder="ref"><br>
              <label for="note" id="pnote">Note</label>
              <textarea name="note" class="note" id="note" placeholder=""></textarea>
            </div>
            <!-- <div>
               <label for="date">Payment Date</label>
                <input type="date" name="date" id="date">
            </div> -->
          </div>
           <div class="col-4">
                
            </div> 
        </div>
        <div class="submit-btn"><button type="submit">Save</button></div>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <script>
    const today = new Date().toISOString().split('T')[0];
    document.getElementById("invoiceDate").value = today;

    document.getElementById("paymentForm").addEventListener("submit", function (e) {
      e.preventDefault();

      const paymentData = {
        invoice_id: document.getElementById("invoice_pk").value,
        date: document.getElementById("invoiceDate").value,
        paid_amount: document.getElementById("paid_amount").value,
        payment_method: document.getElementById("payment_method").value,
        reference_no: document.getElementById("reference_no").value,
        note: document.getElementById('note').value
      };
      console.log(paymentData)
      fetch("payment.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(paymentData)
      })
        .then(res => res.json())
        .then(data => {
          alert(data.message);

        })
        .catch(err => console.error(err));
    });

  </script>
</body>

</html>