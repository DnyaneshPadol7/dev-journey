<?php

include('../dbconnect.php');

$getin="select * from purchase_invoices order by invoice_no desc";
$result=mysqli_query($conn,$getin);
if($result){
$row=mysqli_fetch_assoc($result);
$lastin=$row['invoice_no'];
if($lastin==null){
    $newin="IN00000";
}
else{
    $newin=str_replace("IN","",$lastin);
    $newin=str_pad($newin+1,5,0,STR_PAD_LEFT);
    $newin="IN".$newin;
//echo $newin;

}
}
else{
 die('data not inserted'.mysqli_error($conn));
}
?>
<!-- fully working code -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Invoice Entry</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="purchase.css">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>
<body>
  <h2>Purchase Entry</h2>
  <div class="container-fluid">
    <form id="invoiceForm" action="./purchase.php" method="post">
      <div class="row">
        <!-- Invoice header  -->
        <div class="col-2  ">
          <label for="sellerSelect">Seller:</label>
          <select class="seller" id="sellerSelect" name="seller_id" required>
            <option value=""> Select Seller</option>
          </select>
        </div>
        <div class="col-2 d-flex align-items-center w-auto">
          <label for="invoiceid" class="mb-4">Invoice No:</label>
          <input type="text" id="invoiceid" name="invoice_no" class="invoice" value="<?php echo $newin; ?>" readonly/>
          
        </div>
        <div class="col-2">
          <label for="date">Date:</label>
          <input type="date" id="date" name="invoice_date" required />
        </div>
        <div class="col-2 d-flex align-items-center w-auto">
          <label for="paymentTerm" class="mb-4">Payment Term:</label>
          <select class="payment" id="paymentTerm" name="payment_term" required>
            <option value="credit">Credit</option>
            <option value="cash">Cash</option>
          </select>
        </div>
        <div class="col-2 d-flex align-items-center w-auto">
          <label class="mb-4 gt text-success"><strong>Grand Total: </strong></label>
          <input type="text" id="grandTotal" name="grand_total" class="readonly grandTotl" readonly value="0.00" />
        </div>
        <hr id="hrline" />
      </div>
      <!-- Products table -->
      <table class="table table-bordered mx-auto border-secondary" id="productsTable">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Unit Price</th>
            <th>Discount %</th>
            <th>Tax %</th>
            <th>Total</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <input type="text" name="product_name" class="productSearch productSelect"id="selectProduct" list="productList"  placeholder="Select Product" required>
              <datalist class="" id="productList"></datalist>
              <div id="selectedProductInfo"></div>
            </td>
            <td><input type="number" class="quantityInput" min="1" value="1" required /></td>
            <td><input type="text" class="unitDisplay readonly" readonly /></td>
            <td><input type="number" class="unitPriceInput" min="0" step="0.01" required /></td>
            <td><input type="number" class="discountInput" min="0" max="100" step="0.01" value="0" /></td>
            <td><input type="number" class="taxInput" min="0" max="100" step="0.01" value="0" /></td>
            <td><input type="text" class="totalDisplay readonly" readonly value="0.00" /></td>
            <td><button type="button" class="removeBtn btn btn-danger my-1">X</button></td>
          </tr>
        </tbody>
      </table>
      <div>
        <button id="addProductBtn" type="button" class="btn btn-primary">Add Product</button>
      </div>
      <hr />
      <br /><br><br>
    </form>
  </div>
  <div class="justify-content-center saveb">
    <button type="submit" form="invoiceForm" class="position-fixed bottom-0 end-0  mb-0 save-btn">Save Purchase</button>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
  <script>
    
    const today = new Date().toISOString().split('T')[0];
    document.getElementById("date").value = today;
const sellerMap = new Map(); // Optional: to map names to IDs if needed

async function loadSeller() {
      try {
        let response = await fetch("loadSeller.php"); // call backend
        let data = await response.json();

        let dropdown = document.getElementById("sellerSelect");
        dropdown.innerHTML = `<option value="">Select Seller</option>`;

        console.log(data);
        data.forEach(seller => {
          let option = document.createElement("option");
          option.value = seller.seller_id;
          option.textContent = seller.seller_Name;
          dropdown.appendChild(option);
        });
      } catch (error) {
        console.error("Error loading sellers:", error);
      }
    }
    window.addEventListener("load", loadSeller);

    

   async function loadProduct() {
  try {
    let response = await fetch('loadProduct.php');
    let data = await response.json();

    let dropdown = document.getElementById('productList');
    dropdown.innerHTML = ''; // reset

    data.forEach(product => {
      let option = document.createElement('option');
      option.value = product.product_name;   // what user sees
      option.dataset.id = product.product_id; // real ID
      option.dataset.unit = product.measure_unit;
      option.dataset.price = product.unit_price;
      dropdown.appendChild(option);
    });

    // Attach listener to product search inputs
    document.querySelectorAll('.productSelect').forEach(input => {
      input.addEventListener('input', () => {
        let val = input.value;
        let option = Array.from(dropdown.options).find(opt => opt.value === val);

        if (option) {
          // store product id in dataset
          input.dataset.id = option.dataset.id;

          // auto-fill unit & price in same row
          let row = input.closest('tr');
          row.querySelector('.unitDisplay').value = option.dataset.unit;
          row.querySelector('.unitPriceInput').value = option.dataset.price;

          calculateRowTotal(row);
        } else {
          input.dataset.id = ''; // reset if not valid product
        }
      });
    });

  } catch (error) {
    console.error('Error loading products:', error);
  }
}

window.addEventListener("load", loadProduct);

// 🔥 function to attach listener (reusable for new rows)
function attachProductListener(input, dropdown) {
  input.addEventListener('input', () => {
    let val = input.value;
    let option = Array.from(dropdown.options).find(opt => opt.value === val);

    if (option) {
      input.dataset.id = option.dataset.id;

      let row = input.closest('tr');
      row.querySelector('.unitDisplay').value = option.dataset.unit;
      row.querySelector('.unitPriceInput').value = option.dataset.price;

      calculateRowTotal(row);
    } else {
      input.dataset.id = '';
    }
  });
}


    const productsTable = document.getElementById('productsTable').getElementsByTagName('tbody')[0];
    const addProductBtn = document.getElementById('addProductBtn');
    const grandTotalInput = document.getElementById('grandTotal');

    // Add event listeners for existing row
    function addRowListeners(row) {
      const productSelect = row.querySelector('.productSelect');
      const quantityInput = row.querySelector('.quantityInput');
      const unitPriceInput = row.querySelector('.unitPriceInput');
      const discountInput = row.querySelector('.discountInput');
      const taxInput = row.querySelector('.taxInput');
      const unitDisplay = row.querySelector('.unitDisplay');
      const totalDisplay = row.querySelector('.totalDisplay');
      const removeBtn = row.querySelector('.removeBtn');


      // On inputs change recalc total
      [quantityInput, unitPriceInput, discountInput, taxInput].forEach(input => {
        input.addEventListener('input', () => {
          calculateRowTotal(row);
        });
      });

      // Remove product row
      removeBtn.addEventListener('click', () => {
        if (productsTable.rows.length > 1) {
          row.remove();
          calculateGrandTotal();
        } else {
          alert('At least one product is required.');
        }
      });
    }

    // Calculate total for a single row
    function calculateRowTotal(row) {
      const qty = parseFloat(row.querySelector('.quantityInput').value) || 0;
      const price = parseFloat(row.querySelector('.unitPriceInput').value) || 0;
      const discount = parseFloat(row.querySelector('.discountInput').value) || 0;
      const tax = parseFloat(row.querySelector('.taxInput').value) || 0;

      let subtotal = qty * price;
      let discountAmount = subtotal * (discount / 100);
      let taxableAmount = subtotal - discountAmount;
      let taxAmount = taxableAmount * (tax / 100);
      let total = taxableAmount + taxAmount;

      row.querySelector('.totalDisplay').value = total.toFixed(2);
      calculateGrandTotal();
    }

    // Calculate grand total for all rows
    function calculateGrandTotal() {
      let grandTotal = 0;
      productsTable.querySelectorAll('tr').forEach(row => {
        const total = parseFloat(row.querySelector('.totalDisplay').value) || 0;
        grandTotal += total;
      });
      grandTotalInput.value = grandTotal.toFixed(2);
    }

    // Only add row when Enter is pressed in the tax input
    productsTable.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        if (event.target.classList.contains('taxInput')) {
          event.preventDefault();

          const newRow = productsTable.rows[0].cloneNode(true);

          // Reset inputs in cloned row
          newRow.querySelector('.productSelect').value = '';
          newRow.querySelector('.unitDisplay').value = '';
          newRow.querySelector('.quantityInput').value = 1;
          newRow.querySelector('.unitPriceInput').value = '';
          newRow.querySelector('.discountInput').value = 0;
          newRow.querySelector('.taxInput').value = 0;
          newRow.querySelector('.totalDisplay').value = '0.00';

          productsTable.appendChild(newRow);
          addRowListeners(newRow);

                    // 🔥 attach product listener for new row
          let dropdown = document.getElementById('productList');
          attachProductListener(newRow.querySelector('.productSelect'), dropdown);
        }
      }
    });
    // Initialize listeners for default row
    addRowListeners(productsTable.rows[0]);

    // Handle form submit (example: collect data and log)
    document.getElementById('invoiceForm').addEventListener('submit', (e) => {
      e.preventDefault();

      const sellerId = document.getElementById('sellerSelect').value;
      const invoiceNo = e.target.invoice_no.value;
      const invoiceDate = e.target.invoice_date.value;
      const paymentTerm = e.target.payment_term.value;
      const grandTotal = grandTotalInput.value;

      // Gather product data
      const products = [];
      productsTable.querySelectorAll('tbody tr').forEach(row => {
        const productSelect = row.querySelector('.productSelect');
        const productId = productSelect.dataset.id;
        const quantity = row.querySelector('.quantityInput').value;
        const unit = row.querySelector('.unitDisplay').value;
        const unitPrice = row.querySelector('.unitPriceInput').value;
        const discount = row.querySelector('.discountInput').value;
        const tax = row.querySelector('.taxInput').value;
        const total = row.querySelector('.totalDisplay').value;

        if (productId) {
          products.push({
            product_id: productId,
            quantity: parseFloat(quantity),
            unit: unit,
            unit_price: parseFloat(unitPrice),
            discount: parseFloat(discount),
            tax: parseFloat(tax),
            total: parseFloat(total)
          });
        }
      });

      // Example: Log data, replace with AJAX or form submit
      const data = {
        seller_id: sellerId,
        invoice_no: invoiceNo,
        invoice_date: invoiceDate,
        payment_term: paymentTerm,
        grand_total: parseFloat(grandTotal),
        products: products
      };
     console.log("Sending data:", data);
      // Send data to backend
      fetch("purchase.php", {
        method: "POST",
        headers: { "Content-Type": "application/json"},
        body: JSON.stringify(data)
      })
        .then(res => res.json())
        .then(response => {
          if (response.success) {
            // redirect to printable invoice page
            window.location.href = `../Bill/purchaseDetail.php?invoice_no=${response.invoice_no}&invoice_id=${response.invoice_id}`;
          } else {
            alert(response.message);
          }
        })
        .catch(error => {
          console.error("Error saving purchase:", error);
        });
    });
   
  </script>

</body>

</html>

